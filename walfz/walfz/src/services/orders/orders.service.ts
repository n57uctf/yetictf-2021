// Initializes the `orders` service on path `/orders`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { Orders } from './orders.class';
import createModel from '../../models/orders.model';
import hooks from './orders.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'orders': Orders & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/orders', new Orders(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('orders');

  service.hooks(hooks);
}
