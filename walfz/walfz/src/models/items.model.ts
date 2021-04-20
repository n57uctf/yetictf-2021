// items-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, modelNames, Mongoose } from 'mongoose';
import {
  NUMBER,
  OBJECT_ID,
  REQUIRED_NUMBER,
  REQUIRED_OBJECT_ID,
  REQUIRED_STRING,
} from '../misc/mongoose-helpers';
import { ModelNames } from '../misc/enums';

export default function (app: Application): Model<any> {
  const modelName = 'items';
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema(
    {
      shopId: {
        ...REQUIRED_OBJECT_ID,
        ref: ModelNames.Shops,
      },
      title: REQUIRED_STRING,
      innerId: NUMBER,
      price: REQUIRED_NUMBER,
      bonus: REQUIRED_NUMBER,
      variations: [
        {
          ...OBJECT_ID,
          ref: ModelNames.Variations,
        },
      ],
      default: {
        ...OBJECT_ID,
        ref: ModelNames.Variations,
      },
    },
    {
      timestamps: true,
    }
  );

  schema.virtual('shop', {
    ref: ModelNames.Shops, // The model to use
    localField: 'shopId', // Find people where `localField`
    foreignField: '_id', // is equal to `foreignField`
    justOne: true,
  });

  // This is necessary to avoid model compilation errors in watch mode
  // see https://mongoosejs.com/docs/api/connection.html#connection_Connection-deleteModel
  if (mongooseClient.modelNames().includes(modelName)) {
    (mongooseClient as any).deleteModel(modelName);
  }
  return mongooseClient.model<any>(modelName, schema);
}
