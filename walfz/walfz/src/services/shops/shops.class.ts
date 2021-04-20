import { MongooseServiceOptions, Service } from 'feathers-mongoose';
import { Application } from '../../declarations';
import createApplication, { Paginated } from '@feathersjs/feathers';
import { localeSet } from '../../localeset/ru_RU';

interface IShop {
  title?: string;
}

export class Shops extends Service {
  private app: Application;
  //eslint-disable-next-line @typescript-eslint/no-unused-vars
  constructor(options: Partial<MongooseServiceOptions>, app: Application) {
    super(options);
    this.app = app;
  }

  create(data: IShop, params?: createApplication.Params): Promise<any[] | any> {
    const userId = params?.user?._id;

    const title =
      data.title || `${localeSet.shops.createNewName} ${params?.user?.name}`;

    return super.create({ ...data, title, userId }, params);
  }

}
