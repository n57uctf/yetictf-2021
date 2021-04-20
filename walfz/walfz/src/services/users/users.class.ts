import { MongooseServiceOptions, Service } from 'feathers-mongoose';
import { Application } from '../../declarations';
import createApplication, { Paginated } from '@feathersjs/feathers';
// import createApplication from '@feathersjs/feathers';
// import { UserRoles } from '../../misc/enums';
// import { Forbidden } from '@feathersjs/errors';

export class Users extends Service {
  app: Application;

  //eslint-disable-next-line @typescript-eslint/no-unused-vars
  constructor(options: Partial<MongooseServiceOptions>, app: Application) {
    super(options);
    this.app = app;
  }
}
