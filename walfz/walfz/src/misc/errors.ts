interface ICustomError {
  readonly code: string;
  readonly msg: string;
}

function e(code: string, msg: string): ICustomError {
  return {
    code,
    msg,
  };
}

export const VerificationErrors = {
  MissingCode: e('MISSING_VERIFICATION_CODE', 'Missing verification code'),
  InvalidCode: e('INVALID_VERIFICATION_CODE', 'Invalid verification code'),
  VerificationNotFound: e('VERIFICATION_NOT_FOUND', 'Create verification first'),
  OutOfAttempts: e('OUT_OF_ATTEMPTS', 'Too many bad requests. Request a new code'),
  Expired: e('VERIFICATION_CODE_EXPIRED', 'Verification code expired'),
  NoVerificationNeeded: e('NO_VERIFICATION_NEEDED', 'No verification needed'),
};
