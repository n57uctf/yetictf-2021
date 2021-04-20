// Use this hook to manipulate incoming or outgoing data.
// For more information on hooks see: http://docs.feathersjs.com/api/hooks.html
import { Hook, HookContext } from '@feathersjs/feathers';
import { UserRoles } from '../misc/enums';
import { Forbidden } from '@feathersjs/errors';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
export default (options = {}): Hook => {
  return async (context: HookContext): Promise<HookContext> => {
    if (context.params.user?.role !== UserRoles.Admin &&
      context.params.provider) {
      throw new Forbidden();
    }
    return context;
  };
};
