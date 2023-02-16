//
import DefaultLayout from '@/modules/core/layouts/DefaultLayout';

import CheckEligibilityPage from './pages/CheckEligibilityPage';
import VerifyPinPage from './pages/VerifyPinPage';
/*
import CheckSpc from './views/CheckSpc';
import DoctorPan from './views/DoctorPan';
import SearchEmployer from './views/SearchEmployer';
*/

export default [

  // Check Eligibility
  {
    path: '/eligibility',
    name: 'eligibility-check',
    component: CheckEligibilityPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'Check Claim Eligibility',
        description: 'Checks the eligibility of a member and registered dependents',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Check Eligibility',
    },
  },

  // Verify PIN
  {
    path: '/eligibility/verify-pin',
    name: 'eligibility-verify-pin',
    component: VerifyPinPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'PIN Verification Utility',
        description: 'Checks the PIN for a given patient',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Verify PIN',
    },
  },
];


/*
export default [
  {
    path: '/eligibility',
    component: Layout,
    meta: {
      requiresAuth: true,
      breadcrumb: 'Eligibility',
    },
    children: [
      {
        path: '/',
        name: 'eligibility-main',
        components: { default: Page },
        props: {
          default: {
            pageMeta: {
              title: 'Check Claim Eligibility',
              description: 'Checks the eligibility of a member and registered dependents',
            },
            content: CheckEligibilityForm,
            contentProps: {},
          },
        },
        meta: {
          requiresAuth: true,
          breadcrumb: 'CEWS',
        },
      },
      {
        path: 'check',
        name: 'eligibility-check',
        components: { default: Page },
        props: {
          default: {
            pageMeta: {
              title: 'Check Claims Eligibility',
              description: 'Checks the eligibility of a member and valid dependents',
            },
            content: CheckEligibilityForm,
            contentProps: {},
          },
        },
        meta: {
          requiresAuth: true,
          breadcrumb: 'Claim Eligibility',
        },
      },

      {
        path: 'verify-pin',
        name: 'eligibility-verify-pin',
        components: { default: Page },
        props: {
          default: {
            pageMeta: {
              title: 'PIN Verification Utility',
              description: 'Checks the PIN for a given patient',
            },
            content: VerifyPinForm,
            contentProps: {},
          },
        },
        meta: {
          requiresAuth: true,
          breadcrumb: 'Verify PIN',
        },
      },

      {
        path: 'check-spc',
        name: 'eligibility-check-spc',
        component: CheckSpc,
        meta: {
          requiresAuth: true,
          breadcrumb: 'Check SPC',
        },
      },

      {
        path: 'doctor-pan',
        name: 'eligibility-doctor-pan',
        component: DoctorPan,
        meta: {
          requiresAuth: true,
          breadcrumb: 'Check SPC',
        },
      },

      {
        path: 'search-employer',
        name: 'eligibility-search-employer',
        component: SearchEmployer,
        meta: {
          requiresAuth: true,
          breadcrumb: 'Search Employers',
        },
      },
    ],
  },
];

*/
