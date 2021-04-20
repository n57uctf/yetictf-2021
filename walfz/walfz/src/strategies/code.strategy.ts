import { LocalStrategy } from '@feathersjs/authentication-local';
import { VerificationInitiators } from '../misc/enums';

export default class CodeStrategy extends LocalStrategy {
  // eslint-disable-next-line @typescript-eslint/explicit-module-boundary-types
  get configuration() {
    const authConfig = this.authentication?.configuration;
    const config = super.configuration || {};

    return {
      hashSize: 10,
      service: authConfig.service,
      verificationService: authConfig.verificationService,
      entity: authConfig.entity,
      entityId: authConfig.entityId,
      errorMessage: 'Invalid login',
      entityPasswordField: config.passwordField,
      entityUsernameField: config.usernameField,
      ...config,
    };
  }

  async comparePassword(entity: { email: string }, password: string): Promise<any> {
    const verifier = this.app?.get('verifier');
    await verifier.check(entity.email, password, VerificationInitiators.LogIn);
  }
}
