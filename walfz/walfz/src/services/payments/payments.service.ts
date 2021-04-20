// Initializes the `payments` service on path `/payments`
import { ServiceAddons } from '@feathersjs/feathers';
import { Application } from '../../declarations';
import { Payments } from './payments.class';
import createModel from '../../models/payments.model';
import hooks from './payments.hooks';

// Add this service to the service type index
declare module '../../declarations' {
  interface ServiceTypes {
    'payments': Payments & ServiceAddons<any>;
  }
}

export default function(app: Application): void {
  const options = {
    Model: createModel(app),
    paginate: app.get('paginate'),
  };

  // Initialize our service with any options it requires
  app.use('/payments', new Payments(options, app));

  // Get our initialized service so that we can register hooks
  const service = app.service('payments');

  service.hooks(hooks);
}
