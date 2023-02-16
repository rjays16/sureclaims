<script>
  import { Menu, Submenu, MenuItemGroup, MenuItem } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  // import navigation from './navigation';

  export default {

    components: {
      'font-awesome-icon': FontAwesomeIcon,
      'el-menu': Menu,
      'el-submenu': Submenu,
      'el-menu-item-group': MenuItemGroup,
      'el-menu-item': MenuItem,
    },

    props: ['formData', 'bus', 'currentActive'],

    data() {
      return {
        navigation: [
          // {
          //   id: 'CF4.PATIENTPROFILE',
          //   label: 'Patient Profile',
          // },

          {
            id: 'CF4.CHIEFCOMPLAINT',
            label: 'Chief Complaint',
          },

          {
            id: 'CF4.PRESENTILNESS',
            label: 'History of Present Illness',
          },

          {
            id: 'CF4.PERTINENTPASTMEDICAL',
            label: 'Past Medical History',
          },

          {
            id: 'CF4.OBGYNE',
            label: 'OB-Gyne History',
          },

          {
            id: 'CF4.PERTINENTSIGNSYMPTOMS',
            label: 'Pertinent Signs & Symptoms',
          },

          {
            id: 'CF4.PHYSICALEXAMINATION',
            label: 'Physical Examination',
          },

          {
            id: 'CF4.COURSEWARD',
            label: 'Course in the ward',
          },

          {
            id: 'CF4.MEDICINE',
            label: 'Medicine',
          },
        ],
      };
    },

    methods: {
      renderMenu(vm, createElement, schema) {
        return schema.map((schemoid) => {
          if (schemoid.id) {
            if (schemoid.items) {
              // Submenu item
              const template = createElement('template', {
                slot: 'title',
              }, schemoid.label || '');
              return createElement(
                'el-submenu', {
                  props: {
                    index: schemoid.id,
                    showTimeout: 100,
                    hideTimeout: 100,
                  },
                },
                [ template ].concat(
                  this.renderMenu(vm, createElement, schemoid.items),
                ),
              );
            }

            // Menu item
            return createElement(
              'el-menu-item', {
                props: {
                  index: schemoid.id,
                },
                on: {
                  click(item) {
                    vm.bus.$emit('navigation.activated', item);
                  },
                },
              },
              schemoid.label || '',
            );
          }

          // Menu Group
          return createElement(
            'el-menu-item-group', {
              props: {
                title: schemoid.label || '',
              },
            },
            this.renderMenu(vm, createElement, schemoid.items || []),
          );
        });
      },

      activate(active) {
        this.currentActive = active;
      },
    },

    mounted() {
      if (this.bus) {
        this.bus.$on('navigation.activate', this.activate);
      }
    },

    render(createElement) {
      return createElement(
        'el-menu', {
          class: {
            'claim-form--navigation': true,
          },
          props: {
            defaultActive: this.currentActive,
          },
          on: {
            open() {
            },

            close() {
            },
          },
        },
        this.renderMenu(this, createElement, this.navigation),
      );
    },
  };
</script>

<style scoped>
  .claim-form--navigation {
    border-right: 0;
  }

  .claim-form--navigation >>> .el-menu-item.is-active {

  }
</style>
