//
import DefaultLayout from '@/modules/core/layouts/DefaultLayout';
import ListDoctorsPage from './pages/ListDoctorsPage';

export default [
  {
    path: '/doctors',
    name: 'doctors-list',
    component: ListDoctorsPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'List of Doctors',
        description: 'List of doctors registered in the system',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'List of Doctors',
    },
  },

];
