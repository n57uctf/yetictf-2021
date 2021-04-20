module.exports = {
  preset: 'ts-jest',
  testEnvironment: 'node',
  setupFiles: ['dotenv/config'],
  globals: {
    'ts-jest': {
      diagnostics: false
    }
  }
};
