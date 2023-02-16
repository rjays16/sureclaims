<template>
  <div class="m-stack__item m-stack__item--fluid m-header-head">
    <app-menu
      :items="items"
      arrow-position="left"
    >
    </app-menu>
    <div class="m-topbar  m-stack m-stack--ver m-stack--general">
      <div class="m-stack__item m-topbar__nav-wrapper">
        <ul class="m-topbar__nav m-nav m-nav--inline">
          <!-- <li
            class="m-nav__item m-topbar__notifications m-topbar__notifications--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-center  m-dropdown--mobile-full-width"
            data-dropdown-toggle="click" data-dropdown-persistent="true">
            <a
              class="m-nav__link m-dropdown__toggle"
              @click="() => setPageLoading(true)"
            >
              <span
                  class="m-nav__link-badge m-badge m-badge--dot m-badge--dot-small m-badge--danger m-animate-blink">
              </span>
              <span class="m-nav__link-icon m-animate-shake">
                <span class="m-nav__link-icon-wrapper">
                  <i class="flaticon-music-2"></i>
                </span>
              </span>
            </a>
          </li> -->
          <!--
          <li
            class="m-nav__item m-topbar__quick-actions m-topbar__quick-actions--img m-dropdown m-dropdown--large m-dropdown--header-bg-fill m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--mobile-full-width m-dropdown--skin-light"
            data-dropdown-toggle="click">
            <a href="#" class="m-nav__link m-dropdown__toggle" @click="renewToken()">
              <span class="m-nav__link-badge m-badge m-badge--dot m-badge--info m--hide"></span>
              <span class="m-nav__link-icon">
                <span class="m-nav__link-icon-wrapper">
                  <i class="flaticon-calendar"></i>
                </span>
              </span>
            </a>
          </li>
          -->
          <li
            class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light"
            data-dropdown-toggle="click">
            <a href="#" class="m-nav__link m-dropdown__toggle" @click="signOut">
              <span class="m-topbar__welcome">
                Hello,&nbsp;
              </span>
              <span class="m-topbar__username m--padding-right-10">
                {{ user && user.nickname || '' }}
              </span>
              <span class="m-topbar__userpic">
                <img :src="user && user.picture || ''"
                     class="m--img-rounded m--marginless m--img-centered" alt="">
              </span>
            </a>
          </li>
          <!-- <li id="m_quick_sidebar_toggle" class="m-nav__item">
            <a href="#" class="m-nav__link m-dropdown__toggle">
              <span class="m-nav__link-icon m-nav__link-icon--aside-toggle">
                <span class="m-nav__link-icon-wrapper">
                  <i class="flaticon-grid-menu"></i>
                </span>
              </span>
            </a>
          </li> -->
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapState, mapActions } from 'vuex';
  import authService from '@/modules/auth/services/AuthService';
  import Menu from './Menu';

  export default {
    components: {
      'app-menu': Menu,
    },

    data() {
      return {
        items: this.$store.getters['Core/navigation']('top'),
        loading: 0,
      };
    },

    computed: {
      ...mapState('Auth', {
        user: state => state.user,
      }),
    },

    methods: {
      ...mapActions('Core', [
        'setPageLoading',
      ]),

      ...mapActions('Auth', [
        'login',
        'logout',
      ]),

      renewToken() {
        authService.renewToken();
      },

      signOut() {
        this.$snotify.confirm('Log off your user account', 'Sign out?', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.$router.push('/logout');
              },
              bold: false,
            },
            {
              text: 'Close',
              action: (toast) => {
                this.$snotify.remove(toast.id);
              },
              bold: true,
            },
          ],
        });
      },
    },

    mounted() {
      if (!this.user) {
        authService.userInfo()
          .then((profile) => {
            this.login(profile);
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  };
</script>
