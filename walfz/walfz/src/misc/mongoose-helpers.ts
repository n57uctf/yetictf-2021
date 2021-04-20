import { Schema } from 'mongoose';

export const STRING = {
  type: String,
};

export const BOOLEAN = {
  type: Boolean,
};

export const NUMBER = {
  type: Number,
};

export const DATE = {
  type: Date,
};

export const OBJECT_ID = {
  type: Schema.Types.ObjectId,
};

export const REQUIRED_STRING = {
  ...STRING,
  required: true,
};

export const REQUIRED_BOOLEAN = {
  ...BOOLEAN,
  required: true,
};

export const REQUIRED_NUMBER = {
  ...NUMBER,
  required: true,
};

export const REQUIRED_DATE = {
  ...DATE,
  required: true,
};

export const REQUIRED_OBJECT_ID = {
  ...OBJECT_ID,
  required: true,
};
