<template>
  <div class="supporting-documents--container">
    <!-- Dialog -->
    <el-dialog
      width="60%"
      top="5vh"
      :visible.sync="innerVisible"
    >
      <template slot="title">
        <h5 class="m--regular-font-size-lg2" style="margin-bottom: 0">
          Supporting Documents
        </h5>
      </template>

      <div class="m--padding-left-40 m--padding-right-40">
        <!-- Content -->

        <el-tabs class="" v-model="mode">
          <el-tab-pane name="upload">
            <span slot="label">
              Upload Document
            </span>
            <div class="m--padding-top-20">
              <el-form
                v-show="claim.status === 'RETURN' || (!claim.status && !claim.lhioSeriesNo)"
                ref="form"
                :model="formData"
                :disabled="!!loading"
                status-icon
                label-width="200px"
                label-position="left"
              >
                <div
                  class="m--margin-bottom-20"
                  v-show="showUpload || formData.uploads.length === 0"
                  style="text-align: center"
                >
                  <el-upload
                    name="document"
                    ref="upload"
                    accept=".xls, .pdf, .xml, .xlsx, .xlsm, .xlsb, .xlr, .xlw, .xla, .csv"
                    drag
                    multiple
                    :action="uploadUrl"
                    :headers="{ Authorization: `Bearer ${accessToken}` }"
                    :data="{ types: formData.uploadTypes }"
                    :on-preview="handlePreview"
                    :on-remove="handleRemove"
                    :on-change="handleUploadChange"
                    :on-success="handleUploadSuccess"
                    :on-error="handleUploadError"
                    :show-file-list="false"
                  >
                    <i class="el-icon-upload"></i>
                    <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
                    <div class="el-upload__tip" slot="tip">
                      <strong style="text-decoration: underline;">PDF, XML, EXCEL</strong> files with a size less than 30MB
                    </div>
                  </el-upload>
                </div>

                <div
                  class="m--margin-bottom-20"
                  style="text-align: right"
                  v-if="formData.uploads.length > 0"
                >
                  <el-button
                    plain
                    size="small"
                    v-if="!showUpload"
                    @click="showUpload = true"
                  >
                    Add more files ...
                  </el-button>


                  <el-popover
                    ref="clearuploadspopover"
                    placement="top"
                    width="200"
                    v-model="clearUploadsPopover">
                    <p>Clear all uploads?</p>
                    <div style="text-align: right; margin: 0">
                      <el-button size="mini" type="text" @click="clearUploadsPopover = false">cancel</el-button>
                      <el-button type="danger" size="mini" @click="clearUploads(); clearUploadsPopover = false;">confirm</el-button>
                    </div>
                  </el-popover>
                  <el-button
                    type="danger"
                    plain
                    size="small"
                    v-popover:clearuploadspopover
                  >
                    Remove all
                  </el-button>
                </div>

                <div class="m--margin-bottom-20">
                  <el-table
                    v-if="formData.uploads.length > 0"
                    :data="formData.uploads"
                    v-loading="!!loading"
                    stripe
                    empty-text="No uploads yet"
                    style="width: 100%"
                  >
                    <el-table-column
                      type="index"
                      width="50"
                    />

                    <el-table-column
                      label="File Name"
                      min-width="200px"
                    >
                      <template slot-scope="scope">
                        <span class="m--regular-font-size-sm1">{{ scope.row.name}}</span>
                        <br/>
                        <span v-if="isRowUploading(scope.row)" class="blinking m--regular-font-size-sm4 m--font-bolder text-info">
                          Uploading . . .
                        </span>
                        <template v-else>
                          <span v-if="!isRowSuccess(scope.row)" class="m--regular-font-size-sm4 m--font-bolder text-danger">
                            {{getRowErrorMessage(scope.row)}}
                          </span>
                          <span v-else class="m--regular-font-size-sm4 m--font-bolder text-success">
                            Ready to upload!
                          </span>
                        </template>
                      </template>
                    </el-table-column>

                    <el-table-column
                      label="Size"
                      width="100px"
                    >
                      <template slot-scope="scope">
                        {{ formatBytes(scope.row.size) }}
                      </template>
                    </el-table-column>

                    <el-table-column
                      label="Document Type"
                      min-width="220px"
                    >
                      <template slot-scope="scope">
                        <el-form-item
                          label=""
                          label-width="0px"
                          :rules="rules.documentType"
                          :prop="`uploadTypes.${scope.row.uid}`"
                        >
                          <el-select
                            class="m--margin-bottom-10"
                            v-model="formData.uploadTypes[scope.row.uid]"
                            placeholder="Select type"
                            style="width: 100%;"
                          >
                            <el-option
                              v-for="(item, key) in lookupTypes('claim.supportingDocumentType')"
                              :key="key"
                              :label="item"
                              :value="key"
                            />
                          </el-select>
                        </el-form-item>
                      </template>
                    </el-table-column>

                    <el-table-column
                      fixed="right"
                      label="Actions"
                      align="center"
                      width="100px"
                    >
                      <template slot-scope="scope">

                        <el-tooltip content="Remove this upload?" placement="top-start" transition="el-zoom-in-bottom">
                          <el-button
                            class="m--margin-left-10"
                            type="text"
                            @click="removeUpload(scope.row)"
                          >
                            <font-awesome-icon class="text-danger" :icon="['far', 'trash']" size="lg" transform="shrink-2" />
                          </el-button>
                        </el-tooltip>
                      </template>
                    </el-table-column>
                  </el-table>
                </div>
              </el-form>
              <div style="text-align: center" v-show="claim.status !== 'RETURN' && !(!claim.status && !claim.lhioSeriesNo)">
                Cannot upload document...
                <br>
                Status <b>{{ !this.claim.status && this.claim.lhioSeriesNo ? 'TRANSMITTED' : this.claim.status }}</b>
              </div>
            </div>
          </el-tab-pane>

          <el-tab-pane name="list">
            <span slot="label">
              <el-badge :value="supportingDocuments.length">
                Document List
              </el-badge>
            </span>
            <div class="m--padding-top-20">
              <!-- List of documents -->
              <el-table
                class=""
                :data="supportingDocuments"
                v-loading="!!loading"
                stripe
                empty-text="No supporting documents found ..."
                style="width: 100%"
              >
                <el-table-column
                  type="index"
                  width="50"
                />

                <el-table-column
                  label="Document"
                  min-width="200px"
                >
                  <template slot-scope="scope">
                    <span class="m--font-bolder">
                      {{ lookup('claim.supportingDocumentType', scope.row.documentType) }}
                      <!-- <a :href="scope.row.publicPath" target="_blank">
                        {{ lookup('claim.supportingDocumentType', scope.row.documentType) }}
                        <font-awesome-icon :icon="['far', 'external-link']" transform="shrink-2" />
                      </a> -->
                    </span>
                  </template>
                </el-table-column>

                <el-table-column
                  label="Upload File Name"
                  min-width="200px"
                >
                  <template slot-scope="scope">
                    {{ scope.row.fileName }}
                  </template>
                </el-table-column>

                <el-table-column
                  label="File Size"
                  width="120px"
                >
                  <template slot-scope="scope">
                    {{ formatBytes(scope.row.fileSize) }}
                  </template>
                </el-table-column>

                <el-table-column
                  fixed="right"
                  align="center"
                  label="Actions"
                  width="100px"
                >
                  <template slot-scope="scope">
                    <el-tooltip content="Remove this supporting document?" placement="top-start" transition="el-zoom-in-bottom">
                      <el-button type="text" @click="confirmDelete(scope.row)" class="m--margin-left-10">
                        <font-awesome-icon class="text-danger" :icon="['far', 'trash']" size="lg" transform="shrink-2" />
                      </el-button>
                    </el-tooltip>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </el-tab-pane>
          <el-tab-pane name="returnlist">
            <span slot="label">
              <el-badge :value="returnDocuments.length">
                RTH Document List
              </el-badge>
            </span>
            <div class="m--padding-top-20">
              <!-- List of documents -->
              <el-table
                class=""
                :data="returnDocuments"
                v-loading="!!loading"
                stripe
                empty-text="No RTH documents found ..."
                style="width: 100%"
              >
                <el-table-column
                  type="index"
                  width="50"
                />

                <el-table-column
                  label="Document"
                  min-width="200px"
                >
                  <template slot-scope="scope">
                    <span class="m--font-bolder">
                      {{ lookup('claim.supportingDocumentType', scope.row.supportingDocument.documentType) }}
                      <!-- <a :href="scope.row.publicPath" target="_blank">
                        {{ lookup('claim.supportingDocumentType', scope.row.documentType) }}
                        <font-awesome-icon :icon="['far', 'external-link']" transform="shrink-2" />
                      </a> -->
                    </span>
                  </template>
                </el-table-column>

                <el-table-column
                  label="Upload File Name"
                  min-width="200px"
                >
                  <template slot-scope="scope">
                    {{ scope.row.supportingDocument.fileName }}
                  </template>
                </el-table-column>

                <el-table-column
                  label="Uploaded"
                  min-width="100px"
                >
                  <template slot-scope="scope">
                    <b>{{ scope.row.isUploaded ? 'YES' : 'NO' }}</b>
                  </template>
                </el-table-column>

                <el-table-column
                  label="File Size"
                  width="120px"
                >
                  <template slot-scope="scope">
                    {{ formatBytes(scope.row.supportingDocument.fileSize) }}
                  </template>
                </el-table-column>

                <el-table-column
                  fixed="right"
                  align="center"
                  label="Actions"
                  width="100px"
                >
                  <template slot-scope="scope">
                    <el-tooltip content="Remove this return document?" placement="top-start" transition="el-zoom-in-bottom">
                      <el-button v-show="!scope.row.isUploaded" type="text" @click="confirmDelete(scope.row, true)" class="m--margin-left-10">
                        <font-awesome-icon class="text-danger" :icon="['far', 'trash']" size="lg" transform="shrink-2" />
                      </el-button>
                    </el-tooltip>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </el-tab-pane>
        </el-tabs>
      </div>

      <span slot="footer" class="dialog-footer">
        <el-row type="flex" justify="space-between">
          <el-col :span="24" align="right">
            <el-button
              type="text"
              @click="hide"
              class="btn btn-feature"
              style="min-width: 120px"
            >
              Close
            </el-button>

            <el-button
              type="primary"
              @click="upload()"
              class="btn btn-feature"
              style="min-width: 120px"
              :disabled="!hasSuccessFiles"
              v-if="mode === 'upload'"
            >
              Upload
            </el-button>
          </el-col>
        </el-row>
      </span>
    </el-dialog>
  </div>
</template>

<script>
  import url from 'url';
  import _get from 'lodash/get';
  import {
    Row,
    Col,
    Tabs,
    TabPane,
    Table,
    TableColumn,
    Form,
    FormItem,
    Select,
    Option,
    Tooltip,
    Upload,
    Dialog,
    Button,
    Badge,
    Popover,
    Progress,
  } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';
  import { formatBytes } from '@/helpers/number';

  import AuthService from '@/modules/auth/services/AuthService';
  import UsesLookups from '@/modules/core/mixins/UsesLookups';

  import ATTACH_SUPPORTING_DOCUMENTS_MUTATION
    from '@/modules/claims/graphql/mutations/AttachSupportingDocumentsMutation.gql';
  import ATTACH_RETURN_DOCUMENTS_MUTATION
    from '@/modules/claims/graphql/mutations/AttachReturnDocumentsMutation.gql';
  import DELETE_SUPPORTING_DOCUMENT_MUTATION
    from '@/modules/claims/graphql/mutations/DeleteSupportingDocumentMutation.gql';
  import DELETE_RETURN_DOCUMENT_MUTATION
    from '@/modules/claims/graphql/mutations/DeleteReturnDocumentMutation.gql';
  import SUPPORTING_DOCUMENTS_QUERY
    from '@/modules/claims/graphql/queries/SupportingDocumentsQuery.gql';
  import RETURN_DOCUMENTS_QUERY
    from '@/modules/claims/graphql/queries/ReturnDocumentsQuery.gql';

  /* eslint-disable */

  export default {
    name: 'supporting-documents',

    mixins: [UsesLookups],

    components: {
      FontAwesomeIcon,
      'el-dialog': Dialog,
      'el-button': Button,
      'el-tabs': Tabs,
      'el-tab-pane': TabPane,
      'el-row': Row,
      'el-col': Col,
      'el-upload': Upload,
      'el-form': Form,
      'el-form-item': FormItem,
      'el-select': Select,
      'el-option': Option,
      'el-tooltip': Tooltip,
      'el-table': Table,
      'el-table-column': TableColumn,
      'el-badge': Badge,
      'el-progress': Progress,
      'el-popover': Popover,
    },

    props: {
      claim: {
        claim: Object,
        required: true,
      },
      visible: {
        type: Boolean,
        default: false,
      },
      documents: {
        type: Array,
        default() {
          return [];
        },
      },
    },

    data() {
      const uploadUrl = process.env.NODE_ENV === 'production' ?
        url.resolve(window.location.href, '/api/v1/supporting-documents') :
        url.resolve(process.env.GRAPHQL_ENDPOINT, '/api/v1/supporting-documents');

      return {
        mode: 'upload',
        loading: 0,
        showUpload: true,

        formData: {
          uploads: [],
          data: {},
          uploadTypes: {},
        },

        clearUploadsPopover: false,

        supportingDocuments: [],
        returnDocuments: [],

        uploadUrl,

        mimiType: [
          '.pdf',
          '.xml'
        ],

        rules: {
          documentType: [
            { required: true, message: 'Document type required', trigger: 'blur,change' },
          ],
        },
      };
    },

    apollo: {
      supportingDocuments: {
        query: SUPPORTING_DOCUMENTS_QUERY,
        variables() {
          return {
            claim: this.claim.id,
          };
        },
        loadingKey: 'loading',
        skip() {
          return !this.claim;
        },
        // fetchPolicy: 'cache-first',
      },
      returnDocuments: {
        query: RETURN_DOCUMENTS_QUERY,
        variables() {
          return {
            claim: this.claim.id,
          };
        },
        loadingKey: 'loading',
        skip() {
          return !this.claim;
        },
      },
    },

    asyncComputed: {
      accessToken: {
        async get() {
          return await AuthService.accessToken();
        },
        watch() {
          AuthService.lastUpdated;
        }
      }
    },

    computed: {
      innerVisible: {
        get() {
          return this.visible;
        },

        set(value) {
          if (!value) {
            this.clearUploads()
          }

          this.show(value);
        },
      },
      hasSuccessFiles() {
        const uploads = this.formData.uploads;
        console.log(
          uploads.filter(a => {
            return _get(a, 'response.data.id', false);
          })
        );
        return !!uploads.filter(a => {
          return _get(a, 'response.data.id', false);
        }).length;
      }
    },

    methods: {

      isRowUploading(row) {
        return row.status==='uploading' || (row.percentage >= 0 && row.percentage < 100);
      },

      isRowSuccess(row) {
        return row.response.status === 'success';
      },

      getRowErrorMessage(row) {
        const {response} = row;
        if (response.status === 'validation') {
          this.$snotify.error((_get(response.data, 'document', [])).join(" "));
          this.clearUploads();
        }
        return 'Something went wrong, please try again.';
      },

      formatBytes(n) {
        return formatBytes(n);
      },

      upload() {
        this.$refs.form.validate((valid) => {
          if (!valid) {
            this.$snotify.error('Please re-check the details you entered');
            return false;
          }
          return this.attachDocuments();
        });
      },

      attachDocuments() {
        const uploads = this.formData.uploads || [];
        const documents = uploads.filter(upload => {
          return _get(this.formData.data[upload.uid], 'id', false);
        }).map(upload => ({
          id: _get(this.formData.data[upload.uid], 'id'),
          claimId: this.claim.id,
          patientId: this.claim.patientId,
          documentType: this.formData.uploadTypes[upload.uid],
          fileName: upload.name,
        }));

        const variables = {
          documents
        };

        this.$apollo.mutate({
          mutation: this.claim.status === 'RETURN' ?
            ATTACH_RETURN_DOCUMENTS_MUTATION :
            ATTACH_SUPPORTING_DOCUMENTS_MUTATION,
          variables,
          refetchQueries: [
            'Claims',
            'SupportingDocuments',
            'ReturnDocuments',
          ],
        }).then(() => {
          this.$snotify.success('Return documents successfully attached!', 'Success!');
          this.clearUploads();
          this.mode = this.claim.status === 'RETURN' ? 'returnlist' : 'list';
        }).catch((error) => {
          this.$snotify.error(`Return documents failed to attach - ${error.message}`, 'Oops!');
        });
      },

      deleteFile(document, rth) {
        let id = rth ? document.supportDocumentId : document.id;
        this.loading += 1;
        this.$apollo.mutate({
          mutation: DELETE_SUPPORTING_DOCUMENT_MUTATION,
          variables: {
              id: id,
          },
          refetchQueries: [
              'Claims',
              'SupportingDocuments',
              'ReturnDocuments'
          ],
        }).then(() => {
          this.$snotify.success('Document successfully deleted!', 'Success!');
        }).catch(() => {
          this.$snotify.error('Failed to delete document', 'Oops!');
        }).then(() => {
          this.loading -= 1;
        });
      },

      confirmDelete(document, rth = false) {
        this.$snotify.confirm('Remove this attached document from the claim?', 'Delete?', {
          timeout: 5000,
          showProgressBar: true,
          closeOnClick: false,
          pauseOnHover: true,
          buttons: [
            {
              text: 'Yes',
              action: (toast) => {
                this.$snotify.remove(toast.id);
                this.deleteFile(document, rth);
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

      show(toggle = true) {
        this.$emit('update:visible', toggle);
        this.clearUploads();
      },

      hide() {
        this.show(false);
      },

      removeUpload(upload) {
        this.$refs.upload.abort(upload);
        const index = this.formData.uploads.findIndex(o => o.uid === upload.uid);
        if (index > -1) {
          this.formData.uploads.splice(index, 1);
        }
        this.$delete(this.formData.uploadTypes, upload.uid);
      },

      clearUploads() {
        this.$refs.upload.clearFiles();
        this.formData.uploads = [];
        Object.keys(this.formData.uploadTypes)
          .forEach((key) => {
            this.$delete(this.formData.uploadTypes, key);
            this.$delete(this.formData.data, key);
          });
      },

      /**
       * Before and after upload.
       * Before:
       *   - Check filename if exist from supported claim document types.
       *     If true, select the document type related to the file name.
       * After: noop
       */
      handleUploadChange(file, fileList) {
        this.showUpload = false;
        this.formData.uploads = fileList;

        const filename = (() => {
          const regex = /(.*)\.[^.]+$/;
          const matches = file.name.match(regex);
          return (matches ? matches.pop() : '').toUpperCase();
        })();

        let documentType = '';
        if (this.lookup('claim.supportingDocumentType', filename)) {
          documentType = filename;
        }
        this.$set(this.formData.uploadTypes, file.uid, documentType);
      },

      /**
       *
       */
      handleUploadSuccess(response, file, fileList) {
        this.showUpload = false;
        this.formData.uploads = [].concat(fileList);
        this.$set(this.formData.data, file.uid, response.data);
        this.$set(this.formData.uploadTypes, file.uid, this.formData.uploadTypes[file.uid] || '');
      },

      handleUploadError(err, file, fileList) {
        this.$snotify.error('Something went wrong during upload. Please call your system provider.');
      },

      handlePreview(file) {
        // console.log(file);
      },

      handleRemove(file, fileList) {
        // console.log(file, fileList);
      },

      downloadFile(file) {
        // console.log(file);
      },

      downloadXFile(file) {
        // console.log(file);
      },
    },

    mounted() {
    },
  };
</script>

<style scoped></style>
