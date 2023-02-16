<template>
  <div></div>
</template>

<script>
  import Router from '@/router';
  import Store from '@/store';
  import authRoutes from './routes';
  import authStore from './store';
  import authService from './services/AuthService';

  export const AUTH_ACCESS_TOKEN = 'Auth/AUTH_ACCESS_TOKEN';
  export const AUTH_USER_INFO = 'Auth/AUTH_USER_INFO';
  export const AUTH_EXPIRES_AT = 'Auth/AUTH_EXPIRES_AT';

  Store.registerModule('Auth', authStore);
  Router.addRoutes(authRoutes);

  export default {
    name: 'auth-module',

    created() {
      authService.init();
    },

    mounted() {
    },
  };

</script>
