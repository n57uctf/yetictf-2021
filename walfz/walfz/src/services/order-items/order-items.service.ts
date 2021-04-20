// Initializes the `orderItems` service on path `/order-items`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { OrderItems } from './order-items.class';
import createModel from '../../models/order-items.model';
import hooks from './order-items.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'order-items': OrderItems & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/order-items', new OrderItems(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('order-items');

  service.hooks(hooks);
}
