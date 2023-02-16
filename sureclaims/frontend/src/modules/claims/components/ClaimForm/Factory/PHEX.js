import { fill } from './utilities';

const fields = [
  'pBP',
  'pCR',
  'pRR',
  'pTemp',
  'pHEENT',
  'pChestLungs',
  'pCVS',
  'pAbdomen',
  'pGUIE',
  'pSkinExtremities',
  'pNeuroExam',
];

export default data => fill({}, fields, data);
