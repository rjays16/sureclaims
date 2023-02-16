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
  import router from '@/router';
  import authService from '../services/AuthService';
  // eslint-disable-next-line import/first
  import CubeGridSpinner from '@/components/spinners/CubeGridSpinner';
  // eslint-disable-next-line import/first
  import Preloader from '@/components/Preloader';

  export default {
    name: 'callback',
    data() {
      return {
        pageLoading: true
      };
    },
    components: {
      CubeGridSpinner,
      Preloader
    },
    methods: {
      ...mapActions('Auth', [
        'login',
      ]),

      loginSuccess(userInfo) {
        this.pageLoading = false;
        this.login(userInfo);
        this.$router.push({ path: '/' });
      },
    },

    created() {
      authService.checkAuth(
        this.loginSuccess
      );
    },

  };
</script>


<style scoped>

</style>
