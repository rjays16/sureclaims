import _get from 'lodash/get';
// import PROFILE from './PROFILE';
import CHIEFCOMPLAINT from './CHIEFCOMPLAINT';
import PRESENTILLNESS from './PRESENTILLNESS';
import PERTINENTPASTMEDICAL from './PERTINENTPASTMEDICAL';
import PERTINENTSYMTOMS from './PERTINENTSYMPTOMS';
import OBGYNE from './OBGYNE';
import PHYSICALEXAMINATION from './PHYSICALEXAMINATION';
import COURSEWARD from './COURSEWARD';
import MEDICINE from './MEDICINE';

export default (data) => {
  const result = {};

  // result.PROFILE = PROFILE(_get(data, 'PROFILE', {}));
  result.CHIEFCOMPLAINT = CHIEFCOMPLAINT(_get(data, 'CHIEFCOMPLAINT', {}));
  result.PRESENTILLNESS = PRESENTILLNESS(_get(data, 'PRESENTILLNESS', {}));
  result.PERTINENTPASTMEDICAL = PERTINENTPASTMEDICAL(_get(data, 'PERTINENTPASTMEDICAL', {}));
  result.PERTINENTSYMTOMS = PERTINENTSYMTOMS(_get(data, 'PERTINENTSYMTOMS', {}));
  result.OBGYNE = OBGYNE(_get(data, 'OBGYNE', {}));
  result.PHYSICALEXAMINATION = PHYSICALEXAMINATION(_get(data, 'PHYSICALEXAMINATION', {}));
  result.COURSEWARD = COURSEWARD(_get(data, 'COURSEWARD', {}));
  result.MEDICINE = MEDICINE(_get(data, 'MEDICINE', {}));

  return result;
};
