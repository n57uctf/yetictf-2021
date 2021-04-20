// orderItems-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { ModelNames } from '../misc/enums';
import { REQUIRED_DATE, REQUIRED_OBJECT_ID } from '../misc/mongoose-helpers';

export default function(app: Application): Model<any> {
  const modelName = ModelNames.OrderItems;
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema({
    orderId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Orders,
    },
    itemId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Items,
    },
    variationId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Variations,
    },
    shipDate: {
      ...REQUIRED_DATE,
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
