// Initializes the `variations` service on path `/variations`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { Variations } from './variations.class';
import createModel from '../../models/variations.model';
import hooks from './variations.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'variations': Variations & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/variations', new Variations(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('variations');

  service.hooks(hooks);
}
