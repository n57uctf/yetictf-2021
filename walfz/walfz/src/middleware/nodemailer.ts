import nodemailer from 'nodemailer';
import { Application } from '../declarations';

export default function(app: Application): void {
  const transporter = nodemailer.createTransport({
    host: app.get('smtpHost'),
    port: app.get('smtpPort'),
    secure: app.get('smtpSecure') === 'true',
    auth: {
      user: app.get('smtpAccount'),
      pass: app.get('smtpPassword'),
    },
  });
  app.set('nodemailerClient', transporter);
}
