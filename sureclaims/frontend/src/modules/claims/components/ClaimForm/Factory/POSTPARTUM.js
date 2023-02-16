import { fill } from './utilities';

const fields = [
  'pPerinealWoundCare',
  'pPerinealRemarks',
  'pMaternalComplications',
  'pMaternalRemarks',
  'pBreastFeeding',
  'pBreastFeedingRemarks',
  'pFamilyPlanning',
  'pFamilyPlanningRemarks',
  'pPlanningService',
  'pPlanningServiceRemarks',
  'pSurgicalSterilization',
  'pSterilizationRemarks',
  'pFollowupSchedule',
  'pFollowupScheduleRemarks',
];

export default data => fill({}, fields, data);
