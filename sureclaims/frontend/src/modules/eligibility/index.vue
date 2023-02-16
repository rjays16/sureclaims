<template>
  <div></div>
</template>

<script>

  import Store from '@/store';
  import Router from '@/router';
  import coreRoutes from './routes';
  import coreStore from './store';
  import menu from './menu';

  Store.registerModule('Eligibility', coreStore);
  Router.addRoutes(coreRoutes);

  // Register menu items found in ./menu.js
  Object.keys(menu).forEach(key =>
    Store.dispatch('Core/registerMenu', {
      key,
      menu: menu[key],
    }),
  );

  export default {
    name: 'eligibility-module',

    created() {
    },

    mounted() {
    },
  };

</script>
