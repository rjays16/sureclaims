<template>
  <div>
    <!-- {{ formData }} -->
    <el-form
      :disabled="loading"
      :model="formData"
      :rules="rules"
      inline-message
      status-icon
      ref="form"
      label-width="220px"
      label-position="left"
    >
    <el-row :gutter="20">
      <el-col :span="24">
        <el-alert
          v-show="error"
          title="Error Message "
          type="error"
          @close="MessageClose">
          {{ message }}
        </el-alert>
      </el-col>
    </el-row>
      <el-tabs class="" v-model="active">
        <el-tab-pane name="basic">
          <span slot="label">
            Basic Details
            <!--<font-awesome-icon :icon="['far', 'info-circle']" />-->
          </span>
            <el-row :gutter="0">
                <el-col :span="12" :xs="12">
                  <basic-information
                    class="m--padding-20 m--padding-top-30"
                    :form-data="formData"
                  />
                </el-col>
                <el-col :span="12" :xs="12">
                  <additional-information
                    class="m--padding-20 m--padding-top-30"
                    :form-data="formData"
                  />
                </el-col>
            </el-row>
        </el-tab-pane>

        <!--  <el-tab-pane name="advanced">
          <span slot="label">
            Additional Info
            <font-awesome-icon :icon="['far', 'list-ul']" />
       </span>
          <el-row :gutter="0">
            <el-col :span="16" :xs="24">
              <additional-information
                class="m--padding-20 m--padding-top-30"
                :form-data="formData"
              />
            </el-col>
          </el-row>
        </el-tab-pane>   -->

        <el-tab-pane name="membership">
          <span slot="label">
            Membership
            <!--<font-awesome-icon :icon="['far', 'id-card']" />-->
          </span>

          <el-row :gutter="0">
            <el-col :span="24" :xs="24">
              <membership
                class="m--padding-20 m--padding-top-30"
                :form-data="formData"
              />
            </el-col>
          </el-row>
        </el-tab-pane>
      </el-tabs>

    </el-form>
  </div>
</template>

<script>
  import _pick from 'lodash/pick';
  import { Row, Col, Form, Tabs, TabPane } from 'element-ui';

  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  // import { invalidateFields, ROOT } from 'apollo-cache-invalidation';

  import CREATE_PERSON_MUTATION from '@/modules/core/graphql/mutations/CreatePersonMutation.gql';
  import UPDATE_PERSON_MUTATION from '@/modules/core/graphql/mutations/UpdatePersonMutation.gql';

  import TransformsPersonData from '@/modules/core/mixins/TransformsPersonData';
  import MessageAlert from '@/modules/core/mixins/MessageAlert';
  import BasicInformation from './BasicInformation';
  import AdditionalInformation from './AdditionalInformation';
  import Membership from './Membership';

  export default {

    props: {
      formData: {
        type: Object,
        default() {
          return {
            lastName: '',
            firstName: '',
            middleName: '',
            birthDate: '',
            suffix: '',
            sex: '',
            member: {
              pin: '',
              pen: '',
              membershipType: '',
              relation: '',
              principal: {},
            },
          };
        },
      },
      bus: {
        type: Object,
      },
    },

    mixins: [TransformsPersonData, MessageAlert],

    components: {
      FontAwesomeIcon,
      'el-row': Row,
      'el-col': Col,
      'el-form': Form,
      'el-tabs': Tabs,
      'el-tab-pane': TabPane,
      'basic-information': BasicInformation,
      'additional-information': AdditionalInformation,
      membership: Membership,
    },

    data() {
      return {
        active: 'basic',
        loading: false,
        rules: {
          lastName: [
            { required: true, message: 'Please provide last name', trigger: 'blur' },
          ],
          firstName: [
            { required: true, message: 'Please provide first name', trigger: 'blur' },
          ],
          mailingAddress: [
            { required: true, message: 'Please provide mailing address', trigger: 'blur' },
          ],
          zipCode: [
            { required: true, message: 'Please provide zip code', trigger: 'blur' },
          ],
          sex: [
            { required: true, message: 'Please provide sex', trigger: 'blur' },
          ],
          birthDate: [
            { required: true, message: 'Please provide birth date', trigger: 'blur' },
          ],
          mobileNo: [
            { max: 30, message: 'Should not be more than 30 characters', trigger: 'blur' },
          ],
        },
      };
    },

    methods: {
      savePerson() {
        const id = this.formData.id;
        const person = _pick(this.formData, [
          'lastName',
          'firstName',
          'middleName',
          'suffix',
          'birthDate',
          'sex',
          'emailAddress',
          'mailingAddress',
          'zipCode',
          'landLineNo',
          'mobileNo',
        ]);
        const member = _pick(this.formData.member || {}, [
          'pin',
          'membershipType',
          'pen',
          'employerName',
          'principalId',
          'relation',
        ]);

        let mutation;
        // let resultName;
        const variables = {
          person,
          member,
        };
        if (id) {
          // resultName = 'updatePerson';
          mutation = UPDATE_PERSON_MUTATION;
          variables.id = id;
        } else {
          // resultName = 'createPerson';
          mutation = CREATE_PERSON_MUTATION;
        }

        this.bus.$emit('form.submitting');

        this.$apollo.mutate({
          mutation,
          variables,
          refetchQueries: [
            'PersonsPage',
            'MembersPage',
          ],
        }).then(({ data: { updatePerson, createPerson } }) => {
          if (createPerson) {
            this.$snotify.success(`Record for ${this.fullname(createPerson)} successfully created!`, 'Success!');
          } else if (updatePerson) {
            this.$snotify.success(`Record for ${this.fullname(updatePerson)} successfully updated!`, 'Success!');
          }
          this.bus.$emit('form.submitted');
        }).catch((error) => {
          this.$snotify.error(`Saving failed - ${error.message}`, 'Oops!');
          this.message = `Saving failed - ${error.message}`;
          this.bus.$emit('form.failed');
          this.messageNotify();
        });
      },

      submit() {
        const save = () => {
          this.$refs.form.validate((valid, errors) => {
            if (valid) {
              this.savePerson();
              return true;
            }
            this.$snotify.error('Please fill up all required information');
            this.message = ': Please fill up all required information';
            this.messageNotify();
            return false;
          });
        };
        if (this.formData.member.pin) {
          save();
          return;
        }
        this.$confirm('You did not provide a PIN for this Member, are you sure to continue?', 'Warning', {
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          type: 'warning'
        }).then(() => {
          save();
        });
      },

      submitting() {
        this.loading = true;
      },

      submitted() {
        this.loading = false;
      },

      failed() {
        this.loading = false;
      },

      tab() {
        this.active = 'basic';
      },

      reset() {
        this.$refs.form.resetFields();
        Object.assign(this.$data, this.tab());
      },
    },

    mounted() {
      if (this.bus) {
        this.bus.$on('form.submit', this.submit);
        this.bus.$on('form.submitting', this.submitting);
        this.bus.$on('form.submitted', this.submitted);
        this.bus.$on('form.failed', this.failed);
        this.bus.$on('form.reset', this.reset);
        this.bus.$on('form.closed', this.reset);
      }
    },
  };
</script>
