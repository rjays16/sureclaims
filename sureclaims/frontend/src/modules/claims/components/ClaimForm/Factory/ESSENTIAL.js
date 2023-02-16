import { fill } from './utilities';

const fields = [
  'pDrying',
  'pSkinToSkin',
  'pCordClamping',
  'pProphylaxis',
  'pWeighing',
  'pVitaminK',
  'pBCG',
  'pNonSeparation',
  'pHepatitisB',
];

export default (data, withDefault = false) => {
  let defaultData = {
    pDrying: 'Y',
    pSkinToSkin: 'Y',
    pCordClamping: 'Y',
    pProphylaxis: 'Y',
    pWeighing: 'Y',
    pVitaminK: 'Y',
    pBCG: 'Y',
    pNonSeparation: 'Y',
    pHepatitisB: 'Y',
  };

  if (!withDefault) {
    defaultData = {};
  }

  const result = fill({}, fields, defaultData, data);

  return result;
};
