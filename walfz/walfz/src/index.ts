// eslint-disable-next-line
/// <reference path='./declarations/smtp-tester.d.ts' />

// eslint-disable-next-line
require('dotenv').config();

import logger from './logger';
import app from './app';
// import ms from 'smtp-tester';

const port = app.get('port');
const server = app.listen(port);

process.on('unhandledRejection', (reason, p) =>
  logger.error('Unhandled Rejection at: Promise ', p, reason),
);

server.on('listening', () =>
  logger.info('Feathers application started on http://%s:%d', app.get('host'), port),
);

// if (process.env.NODE_ENV !== 'production') {
//   const smtpPort = app.get('smtpPort');
//   const smtpServer = ms.init(smtpPort);
//   smtpServer.bind((addr: string, id: string, email: any) => {
//     logger.info(`Email from ${email.sender} to ${Object.keys(email.receivers)}: ${email.body}`);
//   });
// }
