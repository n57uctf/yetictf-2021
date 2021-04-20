import { disallow } from 'feathers-hooks-common';
import validate from '../../hooks/validate';
import { create } from '../../schemas/verification.schema';
import { hooks } from '@feathersjs/authentication-local';

const { protect } = hooks;

export default {
  before: {
    all: [],
    find: [disallow('external')],
    get: [disallow('external')],
    create: [validate(create)],
    update: [disallow('external')],
    patch: [disallow('external')],
    remove: [disallow('external')],
  },

  after: {
    all: [protect('verificationCode')],
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
