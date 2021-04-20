// orders-model.ts - A mongoose model
//
// See http://mongoosejs.com/docs/models.html
// for more of what you can do here.
import { Application } from '../declarations';
import { Model, Mongoose } from 'mongoose';
import { ModelNames, OrderStatuses } from '../misc/enums';
import { REQUIRED_NUMBER, REQUIRED_OBJECT_ID, REQUIRED_STRING } from '../misc/mongoose-helpers';

export default function(app: Application): Model<any> {
  const modelName = ModelNames.Orders;
  const mongooseClient: Mongoose = app.get('mongooseClient');
  const { Schema } = mongooseClient;
  const schema = new Schema({
    altId: REQUIRED_NUMBER,
    userId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Users,
    },
    customerId: {
      ...REQUIRED_OBJECT_ID,
      ref: ModelNames.Users,
    },
    status: {
      ...REQUIRED_STRING,
      enum: Object.values(OrderStatuses),
      default: OrderStatuses.New,
    },
    items: {}, // TODO: Add Array<OrderItem>
    totalPrice: REQUIRED_NUMBER,
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
