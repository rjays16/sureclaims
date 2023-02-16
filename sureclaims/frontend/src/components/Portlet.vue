<template>
  <div :class="[
    'm-portlet',
    {
      [`m-portlet--${contextType}`]: !solid && contextType,
      'm-portlet--head-solid-bg': solidHeader,
      'm-portlet--head-sm': smallHeader,
      'm-portlet--rounded': rounded,
      'm-portlet--bordered': bordered,
      'm-portlet--bordered-semi': semi,
      'm-portlet--skin-dark': dark,
      'm-portlet--unair': !air,
      'm-portlet--air': air,
      [`m--bg-${contextType}`]: solid && contextType,
    }
  ]">
    <div class="m-portlet__head" v-if="!noHeader">
      <div class="m-portlet__head-caption">
        <div class="m-portlet__head-title">
          <span class="m-portlet__head-icon" v-if="$slots.icon">
            <slot name="icon" />
          </span>
          <h3 :class="{
            'm-portlet__head-text': true,
            [`m--font-${titleContext}`]: !!titleContext
          }">
            {{ title }}
            <small>
              {{ subtitle }}
            </small>
          </h3>
        </div>
      </div>
    </div>
    <div :class="[
      'm-portlet__body',
      {
        'm-portlet__body--no-padding': noPadding,
        'm-portlet__body--no-left-padding m-portlet__body--no-right-padding': noHorizontalPadding
      }
    ]">
      <slot />
    </div>
    <div class="m-portlet__foot" v-if="this.$slots.footer">
      <slot name="footer" />
    </div>
  </div>
</template>

<script>
  export default {
    name: 'portlet',
    props: {
      title: {
        type: String,
      },
      subtitle: {
        type: String,
      },
      titleContext: {
        type: String,
        default: undefined,
      },
      contextType: {
        type: String,
        default: undefined,
      },
      solidHeader: {
        type: Boolean,
        default: undefined,
      },
      solid: {
        type: Boolean,
        default: undefined,
      },
      dark: {
        type: Boolean,
        default: undefined,
      },
      air: {
        type: Boolean,
        default: undefined,
      },
      bordered: {
        type: Boolean,
        default: undefined,
      },
      rounded: {
        type: Boolean,
        default: undefined,
      },
      semi: {
        type: Boolean,
        default: undefined,
      },
      noHeader: {
        type: Boolean,
        default: undefined,
      },
      smallHeader: {
        type: Boolean,
        default: undefined,
      },
      noPadding: {
        type: Boolean,
        default: undefined,
      },
      noHorizontalPadding: {
        type: Boolean,
        default: undefined,
      },
    },
  };
</script>
