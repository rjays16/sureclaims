import _get from 'lodash/get';
import _isArray from 'lodash/isArray';

export const fill = (o, fields, data) => {
  /* eslint-disable no-param-reassign */
  fields.forEach((field) => {
    o[field] = _get(data, field, '');
  });
  return o;
};

export const fillList = (data, factory) => {
  const result = _isArray(data) ? data : [];
  data.forEach((d) => {
    result.push(factory(d));
  });
  return result;
};
