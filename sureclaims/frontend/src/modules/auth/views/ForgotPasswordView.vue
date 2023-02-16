<template>
  <div class="m-login__forget-password">
    <div class="m-login__head">
      <h3 class="m-login__title">
        Forgotten Password ?
      </h3>
      <div class="m-login__desc">
        Enter your email to reset your password:
      </div>
    </div>
    <form class="m-login__form m-form" action="">
      <div class="form-group m-form__group">
        <input class="form-control m-input"
               placeholder="Email"
               name="email"
               id="m_email"
               autocomplete="off">
      </div>
      <div class="m-login__form-action">
        <button id="m_login_forget_password_submit"
                class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary"
        >
          Request
        </button>
        &nbsp;&nbsp;
        <router-link :to="{name: 'auth-main'}"
                     tag="button"
                     class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">
          Cancel
        </router-link>
      </div>
    </form>
  </div>
</template>

<script>
  export default {
    name: 'auth-forgot-password-view',
  };
</script>
