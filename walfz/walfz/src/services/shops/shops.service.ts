// Initializes the `shops` service on path `/shops`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { Shops } from './shops.class';
import createModel from '../../models/shops.model';
import hooks from './shops.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'shops': Shops & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/shops', new Shops(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('shops');

  service.hooks(hooks);
}
