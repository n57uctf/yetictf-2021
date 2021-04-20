// verification-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { REQUIRED_DATE, REQUIRED_NUMBER, REQUIRED_STRING } from '../misc/mongoose-helpers';
import { VerificationInitiators } from '../misc/enums';

export default function(app: Application): Model<any> {
  const modelName = 'verification';
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema({
    email: REQUIRED_STRING,
    verificationCode: REQUIRED_STRING,
    expires: REQUIRED_DATE,
    initiator: {
      ...REQUIRED_STRING,
      enum: Object.values(VerificationInitiators),
    },
    attempts: {
      ...REQUIRED_NUMBER,
      default: 0,
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
