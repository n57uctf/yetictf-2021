// shops-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { ModelNames } from '../misc/enums';
import {
  NUMBER,
  OBJECT_ID,
  REQUIRED_OBJECT_ID,
  REQUIRED_STRING,
} from '../misc/mongoose-helpers';

export default function (app: Application): Model<any> {
  const modelName = ModelNames.Shops;
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema(
    {
      currentPeriodId: {
        ...OBJECT_ID,
        ref: ModelNames.Periods,
      },
      periodLength: NUMBER,
      userId: {
        ...REQUIRED_OBJECT_ID,
        ref: ModelNames.Users,
        unique: true,
      },
      title: {
        ...REQUIRED_STRING,
      },
    },
    {
      timestamps: true,
    }
  );

  schema.virtual('settings', {
    ref: ModelNames.Settings, // The model to use
    localField: 'userId', // Find people where `localField`
    foreignField: 'userId', // is equal to `foreignField`
    justOne: true,
  });

  // This is necessary to avoid model compilation errors in watch mode
  // see https://mongoosejs.com/docs/api/connection.html#connection_Connection-deleteModel
  if (mongooseClient.modelNames().includes(modelName)) {
    (mongooseClient as any).deleteModel(modelName);
  }
  return mongooseClient.model<any>(modelName, schema);
}
