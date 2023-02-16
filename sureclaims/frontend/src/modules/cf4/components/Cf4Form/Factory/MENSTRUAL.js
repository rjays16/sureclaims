import { fill } from './utilities';

const fields = [
  'pLastMensPeriod',
  'pIsApplicable',
];

export default (data) => {
  const result = fill({}, fields, {
    pIsApplicable: 'N',
  }, data);

  return result;
};
