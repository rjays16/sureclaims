<template>
  <!-- begin:: Page -->
  <div class="m-grid m-grid--hor m-grid--root m-page">
    <!-- Page preloader -->
    <preloader
      v-if="pageLoading"
      message="Loading ..."
    >
      <cube-grid-spinner slot="icon" :size="60" />
    </preloader>

    <app-header/>

    <main
      class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body"
    >
      <div
        class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver	m-container m-container--responsive m-container--xxl m-page__container">
        <button class="m-aside-left-close m-aside-left-close--skin-light">
          <i class="la la-close"></i>
        </button>
        <div class="m-grid__item m-grid__item--fluid m-wrapper">
          <!-- BEGIN: Subheader -->
          <div class="m-subheader ">
            <div class="d-flex align-items-center">
              <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">{{ pageTitle }}</h3>
                <breadcrumbs />
              </div>
            </div>
          </div>
          <!-- END: Subheader -->
          <div class="m-content">
            <transition>
              <div>
                <slot />
              </div>
            </transition>
          </div>
        </div>
      </div>
    </main>

    <footer slot="footer">
      <app-footer/>
    </footer>
  </div>
</template>

<script>
  import { mapActions, mapState } from 'vuex';
  import Preloader from '@/components/Preloader';
  import CubeGridSpinner from '@/components/spinners/CubeGridSpinner';
  import Header from '../components/app/Header';
  import Footer from '../components/app/Footer';

  export default {

    components: {
      'app-header': Header,
      'app-footer': Footer,
      Preloader,
      CubeGridSpinner,
    },

    computed: {
      ...mapState('Core', {
        pageLoading: state => state.pageLoading,
        pageTitle: state => state.pageTitle,
      }),
    },

    methods: {
      ...mapActions('Core', [
        'setPageLoading',
      ]),
    },

  };
</script>
