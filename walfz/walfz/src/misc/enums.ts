export enum UserRoles {
  Admin = 'admin',
  Seller = 'seller',
  Customer = 'customer',
}

export enum NotificationLevels {
  Important = 'important',
  Default = 'default',
}

export enum Plans {
  Elite = 'elite',
  NotElite = 'not-elite',
}

export enum OrderStatuses {
  New = 'new',
  Paid = 'paid',
  Confirmed = 'confirmed',
  Closed = 'closed',
  Dispute = 'dispute',
}

export enum ModelNames {
  Users = 'users',
  Settings = 'settings',
  Shops = 'shops',
  Payments = 'payments',
  Periods = 'periods',
  Notifications = 'notifications',
  Orders = 'orders',
  Items = 'items',
  Variations = 'variations',
  OrderItems = 'orderItems',
}

export enum VerificationInitiators {
  SignUp = 'signup',
  LogIn = 'login',
}
