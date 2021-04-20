// Application hooks that run for every service
// Don't remove this comment. It's needed to format import lines nicely.

import { HookContext } from '@feathersjs/feathers';

export default {
  before: {
    all: [
      (context: HookContext): HookContext => {
        //console.log(context.data);
        return context;
      },
    ],
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
    create: [],
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
