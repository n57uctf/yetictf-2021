// Use this hook to manipulate incoming or outgoing data.
// For more information on hooks see: http://docs.feathersjs.com/api/hooks.html
import { Hook, HookContext } from '@feathersjs/feathers';
import { ModelNames } from '../misc/enums';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
export default (options = {}): Hook => {
  return async (context: HookContext): Promise<HookContext> => {
    const { _id } = context.result;
    const settings = await context.app.service(ModelNames.Settings).create({
      userId: _id,
    });
    await context.app.service(ModelNames.Users).patch(_id, {
      settings: settings._id,
    });
    return context;
  };
};
