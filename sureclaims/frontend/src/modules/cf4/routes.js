//
import DefaultLayout from '@/modules/core/layouts/DefaultLayout';
import ReportPage from './pages/Cf4Page';

export default [
  {
    path: '/cf4',
    name: 'cf4-page',
    component: ReportPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'CF4',
        description: 'Cf4 Generator',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'CF4',
    },
  },

];
