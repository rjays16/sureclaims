<template>
  <div :class="mainClasses()">
    <span
      class="m-menu__arrow m-menu__arrow--adjust"
      v-if="arrowPosition !== 'none'"
      :style="arrowPosition === 'left' ? 'left: 25%' : arrowPosition === 'right' ? 'right: 25%' : ''"
    >
    </span>
    <ul :class="ulClasses()">
      <template v-for="item in sortedItems">
        <app-menu-item
          :type="item.type"
          :label="item.label"
          :icon="item.icon"
          :link="item.link"
          :items="item.items"
          :active="item.active"
          :toggle="toggle"
          :menu-position="type === 'submenu' ? 'right' : 'left'"
          :arrow-position="type === 'submenu' ? '' : 'left'"
          :horizontal-arrow="horizontalArrow">
        </app-menu-item>
      </template>
    </ul>
  </div>
</template>

<script>
  import _orderBy from 'lodash/orderBy';
  import MenuItem from './MenuItem';

  export default {
    name: 'app-menu',
    components: {
      'app-menu-item': MenuItem,
    },
    props: {
      type: {
        type: String,
        default: '',
      },
      items: {
        type: Array,
        default: () => [],
      },
      menuPosition: {
        type: String,
        default: 'right',
      },
      toggle: {
        type: String,
        default: 'click',
      },
      arrowPosition: {
        type: String,
        default: '',
      },
      horizontalArrow: {
        type: String,
        default: 'angle-down',
      },
    },
    computed: {
      sortedItems() {
        return _orderBy(this.items, ['order']);
      },
    },
    methods: {
      /**
       *
       */
      mainClasses() {
        switch (this.type) {
          case 'submenu': {
            return [
              'm-menu__submenu',
              'm-menu__submenu--classic',
              `m-menu__submenu--${this.menuPosition}`,
            ];
          }
          default: {
            return [
              'm-header-menu',
              'm-aside-header-menu-mobile',
              'm-aside-header-menu-mobile--offcanvas',
              'm-header-menu--skin-light',
              'm-header-menu--submenu-skin-light',
              'm-aside-header-menu-mobile--skin-light',
              'm-aside-header-menu-mobile--submenu-skin-light',
            ];
          }
        }
      },

      /**
       *
       */
      ulClasses() {
        switch (this.type) {
          case 'submenu': {
            return [
              'm-menu__subnav',
              'm-menu__nav--submenu-arrow',
            ];
          }
          default: {
            return [
              'm-menu__nav',
              'm-menu__nav--submenu-arrow',
            ];
          }
        }
      },
    },

  };
</script>
