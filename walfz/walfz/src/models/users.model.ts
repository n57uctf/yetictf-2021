// users-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { DATE, OBJECT_ID, REQUIRED_OBJECT_ID, REQUIRED_STRING, STRING } from '../misc/mongoose-helpers';
import { ModelNames, UserRoles } from '../misc/enums';

export default function(app: Application): Model<any> {
  const modelName = ModelNames.Users;
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const schema = new mongooseClient.Schema({
    email: {
      ...REQUIRED_STRING,
      unique: true,
      lowercase: true,
    },
    name: {
      ...REQUIRED_STRING,
    },
    phoneNumber: {
      ...REQUIRED_STRING,
    },
    password: {
      ...REQUIRED_STRING,
    },
    role: {
      ...REQUIRED_STRING,
      enum: Object.values(UserRoles),
      default: UserRoles.Customer,
    },
    settingsId: {
      ...OBJECT_ID,
      ref: ModelNames.Settings,
    },
    shopId: {
      ...OBJECT_ID,
      ref: ModelNames.Shops,
    },
    photo: STRING,
    lastLogin: DATE,
    lastPassChange: DATE,

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
