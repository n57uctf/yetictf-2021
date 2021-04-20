import { MongooseServiceOptions, Service } from 'feathers-mongoose';
import { Application } from '../../declarations';
import createApplication, { Paginated } from '@feathersjs/feathers';
import { Types } from 'mongoose';
const ObjectId = Types.ObjectId;

export class Items extends Service {
  app: Application;

  //eslint-disable-next-line @typescript-eslint/no-unused-vars
  constructor(options: Partial<MongooseServiceOptions>, app: Application) {
    super(options);
    this.app = app;
  }

  async create(
    data: Partial<any> | Partial<any>[],
    params?: createApplication.Params
  ): Promise<any[] | any> {
    const shopId = ((await this.app
      .service('shops')
      .find({ query: { userId: params?.user?._id } })) as Paginated<any>)
      .data[0]._id;

    return super.create({ ...data, shopId }, params);
  }

  async find(
    params?: createApplication.Params
  ): Promise<any[] | createApplication.Paginated<any>> {
    const UserModel = this.app.service('users').Model;

    const info = await UserModel.findById(params?.user?._id)
      .populate({
        path: 'settings',
        select: 'isActive allowCollectData allowSMSNotify allowMailNotify -_id',
      })
      .populate({ path: 'shop', select: 'title _id' })
      .exec();

    //console.log(info);

    const res = await this._find({
      ...params,
      query: {
        ...params?.query,
        $populate: ['shop', 'shop.settings'],
      },
    });

    console.log(res.data[0]);

    return super.find({
      ...params,
      query: {
        ...params?.query,
        $populate: {
          path: 'shopId',
          $populate: {
            path: 'userId',
            $populate: {
              path: 'settingsId',
              select: 'isActive',
              field: 'isShopActive',
            },
            select: 'isShopActive',
            field: 'isShopActive',
          },
          select: 'isShopActive',
          field: 'isShopActive',
        },
      },
    });
  }

  async get(
    id: createApplication.Id,
    params?: createApplication.Params
  ): Promise<any> {
    return super.get(id, params);
  }
}
