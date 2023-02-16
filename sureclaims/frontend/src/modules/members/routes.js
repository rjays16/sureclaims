//
import Layout from '@/modules/core/layouts/DefaultLayout';

import CreateMemberPage from './pages/CreateMemberPage';
import ListMembersPage from './pages/ListMembersPage';

export default [
  // Members
  {
    path: '/members',
    name: 'members-list',
    component: ListMembersPage,
    props: {
      useLayout: Layout,
      pageMeta: {
        title: 'List of Members & Patients',
        description: 'List of registered members/patients',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Members',
    },
  },

  // Create Member
  {
    path: '/members/create',
    name: 'members-create',
    component: CreateMemberPage,
    props: {
      useLayout: Layout,
      pageMeta: {
        title: 'Register Member/Dependent',
        description: 'Register a member/dependent record',
      },
    },
    meta: {
      requiresAuth: true,
      breadcrumb: 'Create Member',
    },
  },
];
