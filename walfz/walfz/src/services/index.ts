import { Application } from '../declarations';
import users from './users/users.service';
import settings from './settings/settings.service';
import shops from './shops/shops.service';
import periods from './periods/periods.service';
import payments from './payments/payments.service';
import notifications from './notifications/notifications.service';
import orders from './orders/orders.service';
import items from './items/items.service';
import variations from './variations/variations.service';
import orderItems from './order-items/order-items.service';
import verification from './verification/verification.service';
// Don't remove this comment. It's needed to format import lines nicely.

export default function(app: Application): void {
  app.configure(users);
  app.configure(settings);
  app.configure(shops);
  app.configure(periods);
  app.configure(payments);
  app.configure(notifications);
  app.configure(orders);
  app.configure(items);
  app.configure(variations);
  app.configure(orderItems);
  app.configure(verification);
}
