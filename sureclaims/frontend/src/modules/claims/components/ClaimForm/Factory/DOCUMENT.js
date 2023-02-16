import { fill } from './utilities';

const fields = [
  'pDocumentType',
  'pDocumentURL',
];

export default data => fill({}, fields, data);
