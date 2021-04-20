// settings-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { ModelNames } from '../misc/enums';
import { REQUIRED_BOOLEAN, REQUIRED_OBJECT_ID, STRING } from '../misc/mongoose-helpers';

export default function(app: Application): Model<any> {
  const modelName = ModelNames.Settings;
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema({
    userId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Users,
    },
    vendor: STRING,
    isActive: {
      ...REQUIRED_BOOLEAN,
      default: false,
    },
    allowCollectData: {
      ...REQUIRED_BOOLEAN,
      default: true,
    },
    allowSMSNotify: {
      ...REQUIRED_BOOLEAN,
      default: false,
    },
    allowMailNotify: {
      ...REQUIRED_BOOLEAN,
      default: true,
    },
  }, {
    timestamps: true,
  });

  // This is necessary to avoid model compilation errors in watch mode
  // see https://mongoosejs.com/docs/api/connection.html#connection_Connection-deleteModel
  if (mongooseClient.modelNames().includes(modelName)) {
    (mongooseClient as any).deleteModel(modelName);
  }
  return mongooseClient.model<any>(modelName, schema);
}
