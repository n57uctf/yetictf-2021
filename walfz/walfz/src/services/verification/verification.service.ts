// Initializes the `verification` service on path `/verification`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { Verification } from './verification.class';
import createModel from '../../models/verification.model';
import hooks from './verification.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'verification': Verification & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/verification', new Verification(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('verification');

  service.hooks(hooks);
}
