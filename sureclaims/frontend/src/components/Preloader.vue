<template>
  <div class="preloader-container">
    <div class="preloader-backdrop"></div>
    <div class="preloader">
      <div class="preloader-icon">
        <slot name="icon"></slot>
      </div>
      <div class="preloader-label">{{ message }}</div>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'preloader',

    props: [
      'message',
    ],

    methods: {
      toggleScrollable(toggle) {
        document.querySelector('body').style.overflowY = toggle ? '' : 'hidden';
      },
    },

    mounted() {
      this.toggleScrollable(false);
    },

    destroyed() {
      this.toggleScrollable(true);
    },

  };
</script>

<style lang="scss" scoped>
  .preloader-container {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    text-align: center;

    .preloader-backdrop {
      position: fixed;
      top: 0;
      left: 0;
      height: 100%;
      width: 100%;
      background-color: lightgray;
      opacity: 0.5;
      z-index: 9998;
    }

    .preloader {
      z-index: 9999;
      position: fixed;
      display: inline-block;
      padding: 20px 50px;
      left: 50%;
      top: 50%;
      -ms-transform: translate(-50%, -50%);
      -moz-transform: translate(-50%, -50%);
      -webkit-transform: translate(-50%, -50%);
      transform: translate(-50%, -50%);

      .preloader-icon {
        margin-bottom: 8px;
      }

      .preloader-label {
        font-size: 1.5rem;
        text-shadow: 0 0 4px rgba(0, 0, 0, 0.15);
      }
    }
  }
</style>
