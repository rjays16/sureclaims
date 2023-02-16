import { fill } from './utilities';

const fields = [
  'pCataractPreAuth',
  'pLeftEyeIOLStickerNumber',
  'pLeftEyeIOLExpiryDate',
  'pRightEyeIOLStickerNumber',
  'pRightEyeIOLExpiryDate',

];

export default data => fill({}, fields, data);
