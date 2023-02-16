<template>
  <preloader
    v-if="this.pageLoading"
    message="Loading ..."
  >
    <cube-grid-spinner slot="icon" :size="60"/>
  </preloader>
</template>

<script>
  import { mapActions } from 'vuex';
  import authService from '../services/AuthService';
  // eslint-disable-next-line import/first
  import CubeGridSpinner from '@/components/spinners/CubeGridSpinner';
  // eslint-disable-next-line import/first
  import Preloader from '@/components/Preloader';

  export default {

    components: {
      CubeGridSpinner,
      Preloader
    },
    data() {
      return {
        pageLoading: true
      };
    },

    methods: {
      ...mapActions('Auth', [
        'login',
      ]),
    },

    created() {
      if (authService.auth0) {
        authService.loginAuth();
      }
    },
  };
</script>


<style>

</style>
