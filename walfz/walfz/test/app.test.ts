// eslint-disable-next-line
/// <reference path='../src/declarations/smtp-tester.d.ts' />
import { Server } from 'http';
import url from 'url';
import axios from 'axios';
import ms from 'smtp-tester';

import app from '../src/app';
import { ModelNames } from '../src/misc/enums';

const port = app.get('port') || 8998;
const mailPort = app.get('mailPort') || 8999;
const getUrl = (pathname?: string): string => url.format({
  hostname: app.get('host') || 'localhost',
  protocol: 'http',
  port,
  pathname,
});

describe('Feathers application tests (with jest)', () => {
  let mailServer: any;
  let server: Server;
  const db = app.get('mongooseClient').connections[0];

  beforeAll(done => {
    mailServer = ms.init(mailPort);
    server = app.listen(port);
    db.dropDatabase();
    server.once('listening', () => done());
  });

  afterAll(done => {
    mailServer.stop();
    server.close(done);
  });

  it('starts and shows the index page', async () => {
    expect.assertions(1);

    const { data } = await axios.get(getUrl());

    expect(data.indexOf('<html lang="en">')).not.toBe(-1);
  });

  describe('404', () => {
    it('shows a 404 HTML page', async () => {
      expect.assertions(2);

      try {
        await axios.get(getUrl('path/to/nowhere'), {
          headers: {
            'Accept': 'text/html',
          },
        });
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(404);
        expect(response.data.indexOf('<html>')).not.toBe(-1);
      }
    });

    it('shows a 404 JSON error without stack trace', async () => {
      expect.assertions(4);

      try {
        await axios.get(getUrl('path/to/nowhere'));
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(404);
        expect(response.data.code).toBe(404);
        expect(response.data.message).toBe('Page not found');
        expect(response.data.name).toBe('NotFound');
      }
    });
  });

  const validUserData = {
    email: 'test@example.com',
    phoneNumber: '71234567890',
    name: 'Test',
    password: 'testPassword!123',
  };
  let logInVerificationCode: string,
    signUpVerificationCode: string;
  describe('email verification', () => {
    it('shows a 400 error on invalid email', async () => {
      expect.assertions(4);

      try {
        await axios.post(getUrl('verification'), {
          email: 'testInvalidEmail',
          initiator: 'signup',
        });
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(400);
        expect(response.data.code).toBe(400);
        expect(response.data.message).toContain('email');
        expect(response.data.name).toBe('BadRequest');
      }
    });

    it('sends a verification code to a given address with initiator signup', async () => {
      expect.assertions(2);

      const response = await axios.post(getUrl('verification'), {
        email: validUserData.email,
        initiator: 'signup',
      });

      const { email } = await mailServer.captureOne(validUserData.email, {
        wait: 5000,
      });

      expect(response.status).toBe(201);
      expect(email.body).toBeTruthy();

      signUpVerificationCode = email.body;
    });

    it('sends a verification code to a given address with initiator login', async () => {
      expect.assertions(2);

      const response = await axios.post(getUrl('verification'), {
        email: validUserData.email,
        initiator: 'login',
      });

      const { email } = await mailServer.captureOne(validUserData.email, {
        wait: 5000,
      });

      expect(response.status).toBe(201);
      expect(email.body).toBeTruthy();

      logInVerificationCode = email.body;
    });
  });

  describe('sign up', () => {
    it('shows a 400 error on invalid password', async () => {
      expect.assertions(2);

      try {
        await axios.post(getUrl('users'), {
          ...validUserData,
          password: 'invalidPassword',
        });
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(400);
        expect(response.data.message).toContain('password');
      }
    });

    it('shows a 400 error on invalid email', async () => {
      expect.assertions(2);

      try {
        await axios.post(getUrl('users'), {
          ...validUserData,
          email: 'test@example',
        });
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(400);
        expect(response.data.message).toContain('email');
      }
    });

    let registeredUserId: string;

    it('is able to create a user with valid data', async () => {
      expect.assertions(2);

      const response = await axios.post(getUrl('users'), {
        ...validUserData,
        verificationCode: signUpVerificationCode,
      });

      expect(response.status).toBe(201);
      expect(response.data._id).toBeTruthy();

      registeredUserId = response.data._id;
    });

    it('creates settings for a new user', async () => {
      const response: any = await app.service(ModelNames.Settings).find({ userId: registeredUserId });
      expect(response.data[0]._id).toBeTruthy();
    });
  });

  // will be used to test next services
  const headers = { 'Authorization': '' };
  describe('log in and authorization', () => {
    const validLoginData = {
      email: validUserData.email,
      password: validUserData.password,
      strategy: 'local',
    };

    it('shows a 401 error on invalid password', async () => {
      expect.assertions(1);

      try {
        await axios.post(getUrl('authentication'), {
          ...validLoginData,
          password: 'someInvalidPassword',
        });
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(401);
      }
    });

    it('returns a token on login with local strategy', async () => {
      expect.assertions(1);

      const { data: { accessToken } } = await axios.post(getUrl('authentication'), validLoginData);

      expect(accessToken).toBeTruthy();
      headers.Authorization = accessToken;
    });

    it('returns a token on login with code strategy', async () => {
      try {
        const { data: { accessToken } } = await axios.post(getUrl('authentication'), {
          email: validLoginData.email,
          verificationCode: logInVerificationCode,
          strategy: 'code',
        });

        expect(accessToken).toBeTruthy();
      } catch (e) {
        console.error(e);
      }
    });

    it('blocks a request without a token', async () => {
      expect.assertions(1);
      try {
        await axios.patch(getUrl('users'), {
          password: 'SomeOtherPassword123!!!',
        });
      } catch (error) {
        const { response } = error;

        expect(response.status).toBe(401);
      }
    });
  });

  describe('users service', () => {
    it('responds with current user info on users.get', async () => {
      expect.assertions(4);

      const { status, data } = await axios.get(getUrl('users'), { headers });
      const { _id, email, password } = data.data[0];

      expect(status).toBe(200);
      expect(_id).toBeTruthy();
      expect(email).toBeTruthy();
      expect(password).toBeUndefined();
    });
  });
});
