import nodemailer from 'nodemailer';
import app from '../app';

interface IEmail {
  to: string;
  subject: string;
  text: string;
  html: string;
}

interface IStyles {
  [key: string]: string;
}

const makeStyles = (styles: IStyles) => Object.entries(styles).map(v => `${v[0]}: ${v[1]}`).join('; ');

async function sendEmail(email: IEmail): Promise<nodemailer.SentMessageInfo> {
  return app.get('nodemailerClient').sendMail({
    from: `"${app.get('emailName')}"<${app.get('smtpAccount')}>`,
    ...email,
  });
}

export async function sendVerificationCode(to: string, code: string): Promise<nodemailer.SentMessageInfo> {
  const redText = makeStyles({
    color: 'red',
  });
  return sendEmail({
    to,
    subject: 'Код верификации',
    text: `Ваш код верификации ${code}. Walfz`,
    html: `<div style="${redText}">${code}</div>`,
  });
}
