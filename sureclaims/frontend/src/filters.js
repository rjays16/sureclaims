import Vue from 'vue';
import { formatCurrency } from './helpers/number';

Vue.filter('currency', formatCurrency);
