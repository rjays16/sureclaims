<template>
  <div class="m-grid__item m-footer ">
    <div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
      <div class="m-footer__wrapper">
        <div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
          <div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
            <span class="m-footer__copyright">
              2017 © Sureclaims by
              <a href="http://www.segworks.com" class="m-link">
                Segworks Technologies Corporation
              </a>
            </span>
          </div>
          <div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
            <ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
              <li class="m-nav__item">
                <a href="#" class="m-nav__link">
                  <span class="m-nav__link-text">
                    About
                  </span>
                </a>
              </li>
              <li class="m-nav__item">
                <a href="#" class="m-nav__link">
                  <span class="m-nav__link-text">
                    Privacy
                  </span>
                </a>
              </li>
              <li class="m-nav__item">
                <a href="#" class="m-nav__link">
                  <span class="m-nav__link-text">
                    T&amp;C
                  </span>
                </a>
              </li>
              <li class="m-nav__item">
                <a href="#" class="m-nav__link">
                  <span class="m-nav__link-text">
                    Purchase
                  </span>
                </a>
              </li>
              <li class="m-nav__item m-nav__item--last">
                <a href="#" class="m-nav__link" data-toggle="m-tooltip" title="" data-placement="left" data-original-title="Support Center">
                  <i class="m-nav__link-icon flaticon-info m--icon-font-size-lg3"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'app-footer',
  };

</script>

<style>
  .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-footer .m-footer__wrapper {
    margin-left: 0;
  }
</style>
