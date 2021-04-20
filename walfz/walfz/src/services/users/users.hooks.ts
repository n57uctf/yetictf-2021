import * as feathersAuthentication from '@feathersjs/authentication';
import * as local from '@feathersjs/authentication-local';
import validate from '../../hooks/validate';
import { create } from '../../schemas/users.schema';
import createSettings from '../../hooks/create-settings';
import { disallow } from 'feathers-hooks-common';
import checkAdmin from '../../hooks/check-admin';
import limitToUser from '../../hooks/limit-to-user';
import checkVerification from '../../hooks/check-verification';
import { VerificationInitiators } from '../../misc/enums';
// Don't remove this comment. It's needed to format import lines nicely.

const { authenticate } = feathersAuthentication.hooks;
const { hashPassword, protect } = local.hooks;

export default {
  before: {
    all: [],
    find: [authenticate('jwt')],
    get: [authenticate('jwt'), limitToUser({ allowOthers: true })],
    create: [
      validate(create),
      checkVerification(VerificationInitiators.SignUp),
      hashPassword('password'),
    ],
    update: [disallow()],
    patch: [authenticate('jwt'), limitToUser(), hashPassword('password')],
    remove: [authenticate('jwt'), checkAdmin()],
  },

  after: {
    all: [],
    find: [protect('phoneNumber', 'password')],
    get: [],
    create: [createSettings(), protect('password')],
    update: [],
    patch: [],
    remove: [protect('password')],
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
