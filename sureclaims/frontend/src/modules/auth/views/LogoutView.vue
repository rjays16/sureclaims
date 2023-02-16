<template>
  <div><!-- Empty --></div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  // eslint-disable-next-line import/extensions
  import authService from '../services/AuthService';

  export default {
    name: 'auth-logout-view',

    computed: {
      ...mapState('Auth', {
        pending: state => state.pending,
        error: state => state.error,
      }),
    },

    methods: {
      ...mapActions('Auth', [
        'logout',
      ]),
    },

    created() {
      if (authService.auth0) {
        authService.logout()
          .then((result) => {
            if (result) {
              this.$router.push('/');
            }
          })
          .catch((error) => {
            console.log(error);
            this.$snotify.error(`Logout failed - ${error.message}`);
            this.$router.push('/');
          });
      }
    },

    mounted() {
      authService.logout()
        .then((result) => {
          if (result) {
            this.$router.push('/');
          }
        })
        .catch((error) => {
          console.log(error);
          this.$snotify.error(`Logout failed - ${error.message}`);
          this.$router.push('/');
        });
    },

  };
</script>
