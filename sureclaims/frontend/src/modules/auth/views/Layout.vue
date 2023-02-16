<template>
  <div class="m-grid m-grid--hor m-grid--root m-page">
    <div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-1"
         id="m_login"
         style="background-image: url(static/img//bg/bg-1.jpg);">
      <div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
        <div class="m-login__container">
          <div class="m-login__logo"></div>
          <router-view></router-view>
          <div class="m-login__account">
            <span class="m-login__account-msg">
                Don't have an account yet ?
            </span>
            &nbsp;&nbsp;
            <router-link :to="{name: 'auth-signup'}"
                         class="m-link m-link--light m-login__account-link">
              Sign Up
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'auth-layout',
  };
</script>

<style lang="scss">
  .m-page {
    height: 100vh;
  }
</style>
