import _get from 'lodash/get';
import _set from 'lodash/set';
import _has from 'lodash/has';
import _unset from 'lodash/unset';

export default {

  methods: {
    field(field, defaultValue = '') {
      return _get(this, field, defaultValue) || defaultValue;
    },

    setField(field, value) {
      _set(this, field, value);
    },

    removeField(field) {
      _unset(this, field);
    },

    initField(field, defaultValue = '') {
      if (!_has(this, field)) {
        this.setField(field, defaultValue);
      }
    }
  },
};
