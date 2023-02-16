const template = `
<ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
  <li class="m-nav__item m-nav__item--home">
    <router-link 
      to="/"
      class="m-nav__link m-nav__link--icon"
    >
      <i class="m-nav__link-icon la la-home"></i>
    </router-link>
  </li>
  <template
    v-for="(crumb, key) in $breadcrumbs" 
  >
    <li class="m-nav__separator">
      &#x025B8;
    </li>
    <li class="m-nav__item">
      <router-link
        :to="linkProp(crumb)"
        :key="key"
        class="m-nav__link"
      >
        <span class="m-nav__link-text">
          {{ crumb | crumbText }}
        </span>      
      </router-link>
    </li>
  </template> 
</ul>
`;

export default {
  template,
};
