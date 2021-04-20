import { Application } from '../declarations';
import { Paginated } from '@feathersjs/feathers';
import { randomBytes } from 'crypto';
import { compare, hash } from 'bcryptjs';
import { sendVerificationCode } from '../misc/nodemailer-helpers';
import { VerificationInitiators } from '../misc/enums';
import { NotAuthenticated } from '@feathersjs/errors';
import { VerificationErrors } from '../misc/errors';

interface IVerification {
  _id: string;
  email: string;
  verificationCode: string;
  expires: Date;
  initiator: VerificationInitiators;
  attempts: number;
}

interface ICreatedVerification {
  verificationCode: string,
  expires: Date,
}

class Verifier {
  app: Application;

  constructor(app: Application) {
    this.app = app;
  }

  private get service() {
    return this.app.service('verification');
  }

  private async findVerifications(email: string, initiator: string) {
    const service = this.app.service('verification');
    const { data } = await service.find({ query: { email, initiator } }) as Paginated<IVerification>;
    return data;
  }

  async handleCreation(email: string, initiator: VerificationInitiators): Promise<ICreatedVerification> {
    const data = await this.findVerifications(email, initiator);

    if (data.length > 0) {
      await this.service.remove(data[0]._id);
    }

    const code = Array.from(randomBytes(6).values())
      .map(v => Math.floor(v * 10 / 256))
      .join('');

    const verificationCode = await hash(code, 8);
    const expires = new Date(Date.now() + 20 * 60 * 1000);

    await sendVerificationCode(email, code);

    return {
      verificationCode,
      expires,
    };
  }

  async check(email: string, code: string, initiator: VerificationInitiators) {
    const service = this.app.service('verification');

    if (!code) {
      throw new NotAuthenticated(VerificationErrors.MissingCode.msg, {
        code: VerificationErrors.MissingCode.code,
      });
    }

    const data = await this.findVerifications(email, initiator);

    if (data.length === 0) {
      throw new NotAuthenticated(VerificationErrors.VerificationNotFound.msg, {
        code: VerificationErrors.VerificationNotFound.code,
      });
    }

    const [{
      verificationCode: hash,
      _id,
      expires,
      attempts,
    }] = data;

    const removeVerification = async () => this.service.remove(_id);

    if (Date.now() > expires.getTime()) {
      await removeVerification();
      throw new NotAuthenticated(VerificationErrors.Expired.msg, {
        code: VerificationErrors.Expired.code,
      });
    }

    const passwordIsValid = await compare(code, hash);

    if (passwordIsValid) {
      await removeVerification();
    } else {
      if (attempts >= 2) {
        await removeVerification();
        throw new NotAuthenticated(VerificationErrors.OutOfAttempts.msg, {
          code: VerificationErrors.OutOfAttempts.code,
        });
      }
      await service.patch(_id, { attempts: attempts + 1 });
      throw new NotAuthenticated(VerificationErrors.InvalidCode.msg, {
        code: VerificationErrors.InvalidCode.code,
        attemptsLeft: 2 - attempts,
      });
    }
  }
}

export default function(app: Application): void {
  const instance = new Verifier(app);
  app.set('verifier', instance);
}
