<template>
  <div class="m-stack m-stack--ver m-stack--desktop">
    <div class="m-stack__item m-stack__item--middle m-stack__item--fluid">
      <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-light "
              id="m_aside_header_menu_mobile_close_btn">
        <i class="la la-close"></i>
      </button>
    </div>
  </div>
</template>

<script>
  import Store from '@/store';
  import AppMenu from './Menu';

  export default {
    name: 'app-bottom-bar',
    components: {
      AppMenu,
    },
    data() {
      return {
        items: Store.getters['Core/navigation']('top'),
      };
    },
  };
</script>
