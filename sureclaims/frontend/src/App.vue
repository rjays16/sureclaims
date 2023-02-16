<template>
  <div
    :class="[
    'm-page--wide',
    'm-header--fixed',
    'm-header--fixed-mobile',
    'm-footer--push',
    'm-aside--offcanvas-default',
    'm-footer--fixed',
    {
      'm-header--minimize-on': minimizeHeader,
      'm-header--minimize-off': !minimizeHeader,
    }
  ]">
    <vue-snotify/>
    <scroll-view :offset="0" tag="div">
      <template slot-scope="markers">
        <scroll-marker
          v-for="marker in markerNames"
          :name="marker"
          :key="marker"
          :visible="markers[marker]"
          :spacing="markerOffsets[marker]"
          @isVisible="markerVisible"
          @isNotVisible="markerNotVisible"
        />
      </template>
    </scroll-view>

    <router-view/>

    <!-- BEGIN modules go here -->
    <auth-module/>
    <core-module/>
    <members-module/>
    <eligibility-module/>
    <doctors-module/>
    <claims-module/>
    <!-- END modules -->
  </div>
</template>

<script>
  import EventEmitter from 'eventemitter3';
  import { mapActions } from 'vuex';
  import { $scrollview } from 'vue-scrollview';

  import LOOKUP_TYPES_QUERY from '@/modules/core/graphql/queries/LookupTypesQuery.gql';

  import AuthModule from '@/modules/auth';

  import CoreModule from '@/modules/core';
  import EligibilityModule from '@/modules/eligibility';
  import MembersModule from '@/modules/members';
  import DoctorsModule from '@/modules/doctors';
  import ClaimsModule from '@/modules/claims';
  import ReportModule from '@/modules/reports';
  import authService from '@/modules/auth/services/AuthService';

  export default {
    name: 'app',

    components: {
      CoreModule,
      AuthModule,
      EligibilityModule,
      MembersModule,
      DoctorsModule,
      ClaimsModule,
      ReportModule,
    },

    data() {
      return {
        loading: 0,
        minimizeHeader: false,
        markerNames: ['header-marker'],
        markerOffsets: {
          'header-marker': 0,
        },
        skipLookup: true,
        emitter: new EventEmitter(),
      };
    },

    methods: {
      ...mapActions('Core', [
        'registerLookupType',
        'setPageLoading',
      ]),

      markerVisible(name) {
        if (name === 'header-marker' && $scrollview.getScrollDirection() === 'UP') {
          this.minimizeHeader = false;
        }
      },
      markerNotVisible(name) {
        if (name === 'header-marker' && $scrollview.getScrollDirection() === 'DOWN') {
          this.minimizeHeader = true;
        }
      },
    },

    created() {
      this.$apollo.addSmartQuery('lookupTypes', {
        query: LOOKUP_TYPES_QUERY,
        loadingKey: 'loading',
        manual: true,
        result({ data, loading }) {
          // this.$snotify.info('Lookups loaded!');
          if (!loading) {
            data.lookupTypes.forEach(type => this.registerLookupType(type));
          }
        },
        error() {
          this.$snotify.error('Failed to load global lookups. You may need to reload the browser...', 'Error');
        },
        watchLoading(loading) {
          this.setPageLoading(loading);
        },
        skip() {
          return this.skipLookup;
        },
      });

      authService.onAuthChange((result) => {
        this.skipLookup = !result.authenticated;
      }, this);
    },

    mounted() {
      authService.scheduleRenewToken();
    },
  };

</script>

<style lang="scss">
  @import "~@/assets/sass/themes/default/style.scss";
  @import "~vue-snotify/styles/material";
  @import '~codemirror/lib/codemirror.css';
  @import '~@/assets/sass/themes/default/element-variables.scss';

  button:focus {
    outline: 0;
  }

  .uppercase {
    text-transform: uppercase;
  }

  .snotify-confirm {
    background-color: #607d8b;
  }

  .snotify-confirm .snotifyToast__progressBar {
    background-color: #4d616b;
  }

  .snotify-confirm .snotifyToast__progressBar__percentage {
    background-color: #94afbd;
  }

  .snotify-success .snotifyToast__body {
    color: #f8fdf8;
  }

  .snotify-error {
    background-color: #e91e63;
  }

  .snotifyToast__title {
    font-size: 1.1rem;
  }

  .snotifyToast__inner {
    padding-top: 10px;
    padding-bottom: 10px;
  }

  .snotifyToast__body {
    font-size: 0.9rem;
  }

  /*
  $snotify--confirm--base-color: get-state-color(metal);
  .snotify-confirm {
    background-color: $snotify--confirm--base-color;

    .snotifyToast__progressBar {
      background-color: lighten($snotify--confirm--base-color, 10%);
    }
    .snotifyToast__progressBar__percentage {
      background-color: #80cbc4;
    }
    .snotifyToast__body {
      color: #e0f2f1;
    }
  }
  */
</style>
