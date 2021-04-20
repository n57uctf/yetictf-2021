import * as yup from 'yup';
import { VerificationInitiators } from '../misc/enums';

export const create = yup.object().shape({
  email: yup
    .string()
    .trim()
    .email()
    .required(),

  initiator: yup
    .string()
    .trim()
    .oneOf(Object.values(VerificationInitiators)),
});
