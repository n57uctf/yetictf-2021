// Initializes the `items` service on path `/items`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { Items } from './items.class';
import createModel from '../../models/items.model';
import hooks from './items.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'items': Items & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/items', new Items(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('items');

  service.hooks(hooks);
}
