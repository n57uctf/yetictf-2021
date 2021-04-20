import { Application } from '../declarations';
import verifier from './verifier';
import mongoose from './mongoose';
// import nodemailer from './nodemailer';
import authentication from './authentication';
// Don't remove this comment. It's needed to format import lines nicely.

// eslint-disable-next-line @typescript-eslint/no-unused-vars, @typescript-eslint/no-empty-function
export default function(app: Application): void {
  app.configure(mongoose);
  // app.configure(nodemailer);
  app.configure(verifier);
  app.configure(authentication);
  // app.configure(channels); // we are not using sockets right now
}
