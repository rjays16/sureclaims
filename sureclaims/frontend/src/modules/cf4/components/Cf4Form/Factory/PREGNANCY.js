import { fill } from './utilities';

const fields = [
  'pPregCnt',
  'pDeliveryCnt',
  'pFullTermCnt',
  'pPrematureCnt',
  'pAbortionCnt',
  'pLivChildrenCnt'
];

export default data => fill({}, fields, data);
