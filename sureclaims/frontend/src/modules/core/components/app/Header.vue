<template>
  <div
    id="app-header"
    :class="[
      'm-grid__item',
      'm-header',
    ]"
    data-minimize="minimize"
    data-minimize-offset="200"
    data-minimize-mobile-offset="200"
  >
    <div class="m-header__top">
      <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
        <div class="m-stack m-stack--ver m-stack--desktop">
          <!-- begin::Brand -->
          <div class="m-stack__item m-brand">
            <div class="m-stack m-stack--ver m-stack--general m-stack--inline">
              <div class="m-stack__item m-stack__item--middle m-brand__logo">
                <router-link to="/" class="m-brand__logo-wrapper">
                  <img alt="" :src="logo">
                </router-link>
              </div>
              <div class="m-stack__item m-stack__item--middle m-brand__tools">

                <!-- begin::Responsive Header Menu Toggler-->
                <a id="m_aside_header_menu_mobile_toggle" href="javascript:;"
                   class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                  <span></span>
                </a>
                <!-- end::Responsive Header Menu Toggler-->
                <!-- begin::Topbar Toggler-->
                <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;"
                   class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                  <i class="flaticon-more"></i>
                </a>
                <!--end::Topbar Toggler-->
              </div>
            </div>
          </div>
          <!-- end::Brand -->

          <!-- begin::Topbar -->
          <app-top-bar></app-top-bar>
          <!-- end::Topbar -->

        </div>
      </div>
    </div>

    <div class="m-header__bottom">
      <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">

        <!-- begin::Bottom Bar Menu -->
        <!-- <app-bottom-bar></app-bottom-bar> -->
        <!-- end::Bottom Bar Menu -->

      </div>
    </div>
  </div>
</template>

<script>
  import logo from '@/assets/media/img/logos/logo.png';

  import TopBar from './TopBar';
  import BottomBar from './BottomBar';

  export default {
    name: 'app-header',
    components: {
      'app-top-bar': TopBar,
      'app-bottom-bar': BottomBar,
    },
    data() {
      return {
        logo,
      };
    },
  };
</script>
