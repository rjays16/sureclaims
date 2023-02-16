<template>
    <div></div>
</template>
<script>
  import Store from '@/store';
  import Router from '@/router';
  import routes from './routes';

  import menus from './menu';

  Router.addRoutes(routes);

  // Register menu items found in ./menu.js
  Store.dispatch('Core/registerMenus', menus);

  export default {
    name: 'claims-module',
  };

</script>
