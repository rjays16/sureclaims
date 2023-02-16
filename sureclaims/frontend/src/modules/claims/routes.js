//
import DefaultLayout from '@/modules/core/layouts/DefaultLayout';
import ListClaimsPage from '@/modules/claims/pages/ListClaimsPage';
import TransmittalPage from '@/modules/claims/pages/TransmittalPage';

export default [
  {
    path: '/claims',
    name: 'claims-list',
    component: ListClaimsPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'List of Claims',
        description: 'List of encoded claims',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Claims',
    },
  },

  {
    path: '/transmittal',
    name: 'transmittal-list',
    component: TransmittalPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'Claims Transmittal',
        description: 'Transmit claims via the PHIC Web Service',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Transmittal',
    },
  },

];
