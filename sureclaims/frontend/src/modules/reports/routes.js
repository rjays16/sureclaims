//
import DefaultLayout from '@/modules/core/layouts/DefaultLayout';
import ReportPage from './pages/ReportPage';

export default [
  {
    path: '/reports',
    name: 'report-page',
    component: ReportPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'List of Reports',
        description: 'List of Reports',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Report page',
    },
  },

];
