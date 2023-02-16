<template>
  <router-link
    tag="li"
    :event="hasItems ? '' : 'click'"
    :to="link || ''"
    :class="[
      'm-menu__item',
      'm-menu__item--rel',
      {
        'm-menu__item--submenu': hasItems,
        'm-menu__item--open-dropdown': hasItems && open,
        'm-menu__item--hover': hasItems && open,
      }
    ]"
    active-class="m-menu__item--active"
    @mouseover="toggleByHover(true)"
    @mouseleave="toggleByHover(false)"
    v-click-outside="() => toggleByClick(false)"
    :aria-haspopup="hasItems"
  >
    <template v-if="type === 'header'">
      <h3 class="m-menu__heading m-menu__toggle" style="padding: 20px 30px 10px">
        <span
          class="m-menu__link-text"
          style="font-size: 0.85rem; text-transform: uppercase; color: #898b96"
        >
          {{ label }}
        </span>
        <i class="m-menu__ver-arrow la la-angle-right"></i>
      </h3>
    </template>
    <template v-else>
      <a
        v-if="!!link && !hasItems"
        class="m-menu__link"
        @click="toggleByClick()"
      >
        <span class="m-menu__item-here"></span>
        <template v-if="isFontAwesomeIcon(icon)">
          <span class="m-menu__link-icon">
            <font-awesome-icon :icon="icon" />
          </span>
        </template>
        <template v-else-if="!!icon">
          <i :class="['m-menu__link-icon', icon]"></i>
        </template>
        <span v-if="" class="m-menu__link-text">
          {{ label }}
        </span>
        <template v-if="hasItems">
          <i :class="['m-menu__hor-arrow', 'la', 'la-' + horizontalArrow]"></i>
          <i class="m-menu__ver-arrow la la-angle-right"></i>
        </template>
      </a>
      <span
        v-else
        class="m-menu__link"
        @click="toggleByClick()"
      >
        <!-- <span class="m-menu__item-here"></span> -->
        <i v-if="!!icon" :class="['m-menu__link-icon', icon]"></i>
        <span class="m-menu__link-title">
          <span v-if="" class="m-menu__link-text">
            {{ label }}
          </span>
        </span>
        <template v-if="hasItems">
          <i :class="['m-menu__hor-arrow', 'la', 'la-' + horizontalArrow]"></i>
          <i class="m-menu__ver-arrow la la-angle-right"></i>
        </template>
      </span>

      <template v-if="hasItems">
        <app-menu
          type="submenu"
          :items="items"
          :menu-position="menuPosition"
          :arrow-position="arrowPosition"
          horizontal-arrow="angle-right"
          toggle="hover"
          @click.native="toggleByClick()"
        >
        </app-menu>
      </template>
    </template>
  </router-link>
</template>

<script>
  import _isArray from 'lodash/isArray';
  import ClickOutside from 'vue-click-outside';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import Menu from './Menu';

  export default {
    name: 'app-menu-item',

    directives: {
      ClickOutside,
    },

    components: {
      FontAwesomeIcon,
    },

    props: {
      type: {
        type: String,
        default: 'link',
      },
      label: {
        type: String,
      },
      icon: {},
      order: {
        type: Number,
      },
      link: {
        type: [String, Object],
        default: null,
      },
      active: {
        type: Boolean,
        default: false,
      },
      toggle: {
        type: String,
        default: 'click',
      },
      horizontalArrow: {
        type: String,
        default: 'angle-down',
      },
      arrowPosition: {
        type: String,
        default: '',
      },
      menuPosition: {
        type: String,
        default: '',
      },
      items: {
        type: Array,
        default: () => [],
      },
    },

    data() {
      return {
        open: false,
        hasItems: this.items.length > 0,
      };
    },

    methods: {
      toggleByClick(open) {
        if (this.toggle === 'click' && this.hasItems) {
          this.open = (typeof open === 'undefined') ? !this.open : open;
        }
      },
      toggleByHover(open) {
        if (this.toggle === 'hover' && this.hasItems) {
          this.open = (typeof open === 'undefined') ? !this.open : open;
        }
      },

      isFontAwesomeIcon(icon) {
        return !!icon && _isArray(icon);
      },
    },

    beforeCreate() {
      // Avoid recursive referencing of components
      this.$options.components['app-menu'] = Menu;
    },
  };
</script>
