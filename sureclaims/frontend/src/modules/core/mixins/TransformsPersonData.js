import _has from 'lodash/has';
import _get from 'lodash/get';
import _reduce from 'lodash/reduce';
import moment from 'moment';

const transformData = (data, format) =>
  _reduce(
    format.match(/{\w+}/g).map(match => match.replace(/[{,}]/g, '')),
    (result, token) => (
      _has(data, token) ?
        result.replace(`{${token}}`, _get(data, token)) :
        result
    ),
    format,
  );

export default {

  methods: {
    fullname(data, template = '{lastName}, {firstName} {middleNameInitial} {suffix}') {
      if (!data) {
        return null;
      }
      const mi = data.middleName ?
        `${data.middleName.substring(0, 1)}.` : '';
      const newFormat = template.replace('{middleNameInitial}', mi);
      return transformData(data, newFormat);
    },

    age(data, template = '%s years old', errorMessage = '-') {
      if (!data) {
        return null;
      }
      const date = moment(data.birthDate, 'MM-DD-YYYY');
      return date.isValid() ? template.replace('%s', moment().diff(moment(date), 'years')) : errorMessage;
    },

    principalName(person, defaultValue = null) {
      const principal = _get(person, 'member.principal');
      if (principal) {
        return this.fullname(principal);
      }
      return defaultValue;
    },

    isPrincipalMember(person) {
      const member = _get(person, 'member');
      if (!member) {
        return false;
      }
      return member.relation === 'M';
    },

    relationToMember(person, defaultValue = null) {
      const member = _get(person, 'member');
      if (!member) {
        return defaultValue;
      }
      // Assumes that component has UsesLookup mixin
      return this.lookup('dependent.relation', member.relation, defaultValue);
    },


  },

};
