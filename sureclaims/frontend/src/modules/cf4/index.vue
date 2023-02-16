<template>
  <div></div>
</template>

<script>
  import Store from '@/store';
  import Router from '@/router';
  import routes from './routes';

  import menu from './menu';

  Router.addRoutes(routes);

  // Register menu items found in ./menu.js
  Store.dispatch('Core/registerMenus', menu);

  export default {
    name: 'cf4-module',
  };

</script>
