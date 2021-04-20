import * as yup from 'yup';

export const create = yup.object().shape({
  email: yup
    .string()
    .trim()
    .email()
    .required(),
  phoneNumber: yup
    .string()
    .trim()
    .min(7)
    .max(20)
    .matches(
      /^[0-9]+/,
      'phoneNumber must contain only digits',
    )
    .required(),
  name: yup
    .string()
    .max(30)
    .required(),
  password: yup
    .string()
    .trim()
    .min(8)
    .max(80)
    .matches(
      /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*_-])/,
      'password must contain at least one lowercase letter, one uppercase letter and one special symbol',
    ),
});
