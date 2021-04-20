// Use this hook to manipulate incoming or outgoing data.
// For more information on hooks see: http://docs.feathersjs.com/api/hooks.html
import { Hook, HookContext } from '@feathersjs/feathers';
import { BadRequest } from '@feathersjs/errors';
import { AnySchema } from 'yup';

// eslint-disable-next-line @typescript-eslint/no-unused-vars
export default (schema: AnySchema): Hook => {
  return async (context: HookContext): Promise<HookContext> => {
    try {
      await schema.validate(context.data);
    } catch (e) {
      throw new BadRequest(e);
    }
    return context;
  };
};
