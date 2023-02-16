<template>
  <div></div>
</template>

<script>
  import Store from '@/store';
  import Router from '@/router';
  import coreRoutes from './routes';
  import coreStore from './store';
  // import corePages from './pages';
  import menu from './menu';

  Store.registerModule('Core', coreStore);
  Router.addRoutes(coreRoutes);

  // Register menu items found in ./menu.js
  Store.dispatch('Core/registerMenus', menu);

  export default {
    name: 'core-module',

    created() {
    },

    mounted() {
    },
  };

</script>
