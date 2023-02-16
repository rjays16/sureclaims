import Router from '@/router';

import Callback from './components/Callback';
import Layout from './views/Layout';
import LoginView from './views/Auth0LoginView';
import LogoutView from './views/LogoutView';
import SignupView from './views/SignupView';
import ForgotPasswordView from './views/ForgotPasswordView';

import authService from './services/AuthService';

Router.beforeEach(async (to, from, next) => {
  const authenticated = await authService.isAuthenticated();

  if (to.path === '/logout' && authenticated) {
    return next();
  }

  if (to.matched.some(route => route.path === '/auth')) {
    if (authenticated) {
      return next({
        path: '/'
      });
    }
  }

  if (to.meta.requiresAuth) {
    if (!authenticated) {
      return next({
        path: '/login'
      });
    }
  }
  return next();
});

export default [
  {
    path: '/access_token',
    name: 'auth-callback',
    component: Callback,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/login',
    component: LoginView,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/logout',
    component: LogoutView,
    meta: {
      requiresAuth: false
    }
  },
  {
    path: '/auth',
    component: Layout,
    children: [
      {
        name: 'auth-main',
        path: '',
        component: LoginView,
        meta: {
          requiresAuth: false
        }
      },
      {
        name: 'auth-signup',
        path: 'signup',
        component: SignupView,
        meta: {
          requiresAuth: false
        }
      },
      {
        name: 'auth-forgot-password',
        path: 'forgotPassword',
        component: ForgotPasswordView,
        meta: {
          requiresAuth: false
        }
      },
      {
        name: 'auth-logout',
        path: 'logout',
        component: LogoutView,
        meta: {
          requiresAuth: false
        }
      }
    ]
  }

];
