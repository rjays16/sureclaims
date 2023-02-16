<template>
  <div></div>
</template>

<script>
  import Store from '@/store';
  import Router from '@/router';
  import membersRoutes from './routes';
  import membersStore from './store';
  import menu from './menu';

  Store.registerModule('Member', membersStore);
  Router.addRoutes(membersRoutes);

  // Register menu items found in ./menu.js
  Object.keys(menu).forEach(key =>
    Store.dispatch('Core/registerMenu', {
      key,
      menu: menu[key],
    }),
  );

  export default {
    name: 'core-module',

    created() {
    },

    mounted() {
    },
  };

</script>
