//
import DefaultLayout from './layouts/DefaultLayout';
import DashboardPage from './pages/DashboardPage';

export default [
  {
    path: '/',
    redirect: 'dashboard',
    meta: {
      requiresAuth: true
    }
  },

  {
    path: '/dashboard',
    name: 'core-dashboard',
    component: DashboardPage,
    props: {
      useLayout: DefaultLayout,
      pageMeta: {
        title: 'Dashboard',
        description: 'Some data about your e-claims'
      }
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Dashboard'
    }
  }

  // Error routes
  /*
  {
    path: '/error',
    name: 'core-error',
    component: Layout,
  },
  */

];
