// Use this hook to manipulate incoming or outgoing data.
// For more information on hooks see: http://docs.feathersjs.com/api/hooks.html
import { Hook, HookContext } from '@feathersjs/feathers';
import { UserRoles } from '../misc/enums';
import { Forbidden } from '@feathersjs/errors';

export default (options = { allowOthers: false }): Hook => {
  return async (context: HookContext): Promise<HookContext> => {
    const { params, id } = context;
    const { provider, user } = params;
    if (!provider) {
      return context;
    }
    if (id === 'my') {
      context.id = user?._id;
      return context;
    }
    if (options.allowOthers) {
      return context;
    }
    if (user?.role === UserRoles.Admin) {
      return context;
    }
    if (user?._id === context.id) {
      return context;
    }

    throw new Forbidden();
  };
};
