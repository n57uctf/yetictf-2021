import { MongooseServiceOptions, Service } from 'feathers-mongoose';
import { Application } from '../../declarations';
import createApplication, { Paginated } from '@feathersjs/feathers';

export class Settings extends Service {
  app: Application;

  //eslint-disable-next-line @typescript-eslint/no-unused-vars
  constructor(options: Partial<MongooseServiceOptions>, app: Application) {
    super(options);
    this.app = app;
  }

  async patch(
    id: createApplication.NullableId,
    data: Partial<any>,
    params?: createApplication.Params
  ): Promise<any[] | any> {
    let newId = id;
    if (['my', null, 0].includes(id)) {
      newId = ((await this.app
        .service('settings')
        .find({ query: { userId: params?.user?._id } })) as Paginated<any>)
        .data[0]._id;
    }
    return super.patch(newId, data, params);
  }

  async get(id: createApplication.Id, params?: createApplication.Params): Promise<any> {
    let newId = id;
    if (['my', null, 0].includes(id)) {
      newId = ((await this.app
        .service('settings')
        .find({ query: { userId: params?.user?._id } })) as Paginated<any>)
        .data[0]._id;
    }
    return super.get(newId, params);
  }
}
