// Use this hook to manipulate incoming or outgoing data.
// For more information on hooks see: http://docs.feathersjs.com/api/hooks.html
import { Hook, HookContext } from '@feathersjs/feathers';
import { VerificationInitiators } from '../misc/enums';

export default (initiator: VerificationInitiators): Hook => {
  return async (context: HookContext): Promise<HookContext> => {
    const { authStrategies } = context.app.get('authentication');

    if (!authStrategies.includes('code')) return context;

    const { verificationCode, email } = context.data;

    const verifier = context.app.get('verifier');
    await verifier.check(email, verificationCode, initiator);

    return context;
  };
};
