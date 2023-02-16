import Vue from 'vue';

// Vue plugins
import VueForm from 'vue-form';
import BootstrapVue from 'bootstrap-vue';
import VueBlockUI from 'vue-blockui';
import VueSVGIcon from 'vue-svgicon';
import VueBreadcrumbs from 'vue-breadcrumbs';
import VueMeta from 'vue-meta';
import VeeValidate from 'vee-validate';
import ScrollView from 'vue-scrollview';
import VueAffix from 'vue-affix';
import VueScrollTo from 'vue-scrollto';
import AsyncComputed from 'vue-async-computed';
import Datatable from 'vue2-datatable-component';
import Snotify from 'vue-snotify';
import Vuebar from 'vuebar';
import VueCodemirror from 'vue-codemirror';

// 3rd party libraries
import localforage from 'localforage';

import breadcrumbsOptions from './config/breadcrumbs';
import localForageOptions from './config/localforage';
import scrollToOptions from './config/scrollTo';
import snotifyOptions from './config/snotify';
import './config/fontAwesome';
import './config/elementUi';

// Vue plugins
Vue.use(VueForm);
Vue.use(VeeValidate, { inject: false });
Vue.use(VueBlockUI);
Vue.use(BootstrapVue);
Vue.use(VueSVGIcon);
Vue.use(VueMeta);
Vue.use(VueAffix);
Vue.use(ScrollView);
Vue.use(AsyncComputed);
Vue.use(Datatable);
Vue.use(Vuebar);
Vue.use(VueCodemirror);

// Vue plugins with configurable options
Vue.use(VueBreadcrumbs, breadcrumbsOptions);
Vue.use(VueScrollTo, scrollToOptions);
Vue.use(Snotify, snotifyOptions);

// Other third-party plugins
localforage.config(...localForageOptions);
