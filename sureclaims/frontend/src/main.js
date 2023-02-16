import Vue from 'vue';
import { sync } from 'vuex-router-sync';
import 'line-awesome/dist/css/line-awesome.css';
import 'roboto-fontface/css/roboto/roboto-fontface.css';
import 'typeface-poppins/index.css';
import './assets/vendors/flaticon/css/flaticon.css';
import './assets/vendors/metronic/css/styles.css';
import './plugins';
import './filters';
// import './directives';

import App from './App';
import apolloProvider from './apollo';
import router from './router';
import store from './store';

Vue.config.productionTip = false;
sync(store, router);

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  provide: apolloProvider.provide(),
  template: '<App/>',
  components: { App },
  metaInfo: {
    title: 'Welcome',
    titleTemplate: '%s | sureclaims',
  },
});
