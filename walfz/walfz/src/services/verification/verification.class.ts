import { MongooseServiceOptions, Service } from 'feathers-mongoose';
import { Application } from '../../declarations';
import createApplication from '@feathersjs/feathers';
import { VerificationInitiators } from '../../misc/enums';
import { BadRequest } from '@feathersjs/errors';
import { VerificationErrors } from '../../misc/errors';

interface IVerification {
  _id: string;
  email: string;
  verificationCode: string;
  expires: Date;
  initiator: VerificationInitiators;
}

export class Verification extends Service {
  app: Application;

  //eslint-disable-next-line @typescript-eslint/no-unused-vars
  constructor(options: Partial<MongooseServiceOptions>, app: Application) {
    super(options);
    this.app = app;
  }

  async create(data: IVerification, params?: createApplication.Params): Promise<any[] | any> {
    const { authStrategies } = this.app.get('authentication');

    if (!authStrategies.includes('code')) throw new BadRequest(VerificationErrors.NoVerificationNeeded.msg, {
      code: VerificationErrors.NoVerificationNeeded.code,
    });

    const { email, initiator } = data;

    const verifier = this.app.get('verifier');
    const result = await verifier.handleCreation(email, initiator);

    return super.create({
      ...data,
      ...result,
    }, params);
  }
}
