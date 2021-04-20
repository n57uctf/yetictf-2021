// notifications-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { REQUIRED_BOOLEAN, REQUIRED_OBJECT_ID, REQUIRED_STRING } from '../misc/mongoose-helpers';
import { ModelNames, NotificationLevels } from '../misc/enums';

export default function(app: Application): Model<any> {
  const modelName = ModelNames.Notifications;
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema({
    userId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Users,
    },
    isRead: {
      ...REQUIRED_BOOLEAN,
      default: false,
    },
    level: {
      ...REQUIRED_STRING,
      enum: Object.values(NotificationLevels),
      default: NotificationLevels.Default,
    },
    isArchived: {
      ...REQUIRED_BOOLEAN,
      default: false,
    },
    text: REQUIRED_STRING,
    link: REQUIRED_STRING,
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
