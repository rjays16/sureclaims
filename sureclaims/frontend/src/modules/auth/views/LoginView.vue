<template>
  <div class="m-login__signin">
    <div class="m-login__head">
      <h3 class="m-login__title">
        Secure sign-in
      </h3>
    </div>
    <div class="m-login__form m-form">
      <div class="form-group m-form__group">
        <input class="form-control m-input"
               placeholder="Email"
               autocomplete="off"
               v-model="username">
      </div>
      <div class="form-group m-form__group">
        <input class="form-control m-input m-login__form-input--last"
               type="password"
               placeholder="Password"
               v-model="password">
      </div>
      <div class="row m-login__form-sub">
        <div class="col m--align-left m-login__form-left">
          <label class="m-checkbox  m-checkbox--light">
            <input type="checkbox" name="remember">
            Remember me
            <span></span>
          </label>
        </div>
        <div class="col m--align-right m-login__form-right">
          <router-link :to="{name: 'auth-forgot-password'}"
                       class="m-link">
            Forget Password ?
          </router-link>
        </div>
      </div>
      <div class="m-login__form-action">
        <button id="m_login_signin_submit"
                class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air  m-login__btn m-login__btn--primary"
                :disabled="!!pending"
                v-on:click="signIn()">
                  <span v-if="pending">
                    Signing in
                    <font-awesome-icon icon="spinner" v-if="pending" spin/>
                  </span>
          <span v-if="!pending"> Sign-in </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';

  import fontawesome from '@fortawesome/fontawesome';
  import faSpinner from '@fortawesome/fontawesome-free-solid/faSpinner';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  import authService from '@/modules/auth/services/AuthService';

  fontawesome.library.add(faSpinner);

  export default {
    name: 'auth-login-view',

    data() {
      return {
        currentUser: null,
        username: 'ygoldner@example.com',
        password: 'secret',
      };
    },

    components: {
      FontAwesomeIcon,
    },

    computed: {
      ...mapState('Auth', {
        pending: state => state.pending,
        error: state => state.error,
      }),
    },

    methods: {
      ...mapActions('Auth', [
        'authenticate',
      ]),

      signIn() {
        /* eslint-disable */

        authService.login();
      },
    },
  };
</script>

