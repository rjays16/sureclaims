//
import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

const router = new Router({
  mode: 'hash',
  scrollBehavior() {
    return {
      x: 0,
      y: 0,
    };
  },
});

export default router;
