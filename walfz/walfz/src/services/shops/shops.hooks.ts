import * as authentication from '@feathersjs/authentication';
import { HookContext } from '@feathersjs/feathers';
import { ModelNames } from '../../misc/enums';
// Don't remove this comment. It's needed to format import lines nicely.

const { authenticate } = authentication.hooks;

export default {
  before: {
    all: [authenticate('jwt')],
    find: [],
    get: [],
    create: [],
    update: [],
    patch: [],
    remove: [],
  },

  after: {
    all: [],
    find: [],
    get: [],
    create: [
      async (context: HookContext): Promise<HookContext> => {
        const shopId = context.result._id;
        const { _id } = context.params.user as { _id: any };
        await context.app.service(ModelNames.Users).patch(_id, {
          shop: shopId,
        });
        return context;
      },
    ],
    update: [],
    patch: [],
    remove: [],
  },

  error: {
    all: [],
    find: [],
    get: [],
    create: [],
    update: [],
    patch: [],
    remove: [],
  },
};
