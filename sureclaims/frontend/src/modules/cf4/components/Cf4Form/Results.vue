<template>
  <div
    class="check-eligibility-result m--padding-20 m--padding-left-40"
  >
    <div class="results--container">

      <div class="results--image">
        <font-awesome-icon
          class="text-danger"
          :icon="['far', 'exclamation-circle']"
          size="4x"
          v-if="!eligibilityResult.isOk"
        />

        <font-awesome-icon
          class="text-success"
          :icon="['far', 'check-circle']"
          size="4x"
          v-if="eligibilityResult.isOk"
        />
      </div>

      <div class="results--message text-danger" v-if="!eligibilityResult.isOk">
        Sorry! This patient is <b>NOT ELIGIBLE</b>
      </div>

      <div class="results--message text-success" v-if="eligibilityResult.isOk">
        Great! This patient is <b>ELIGIBLE</b>
      </div>

      <div class="documents-required" v-if="eligibilityResult.requiredDocuments.length > 0">
        <div class="documents-required--header">
          Documents Required
          <div class="documents-required--subheader">
            Submit the ff. documents to the hospital for document scanning
          </div>
        </div>

        <ol>
          <li v-for="document in eligibilityResult.requiredDocuments">
            <span class="document--name">
              {{ document.name || '' }}
              <span v-if="document.code">({{ document.code }})</span>
            </span>
            <span class="document--reason">
              Reason:
              {{ document.reason || 'Reason not indicated' }}
            </span>
          </li>
        </ol>
      </div>

      <div class="m--align-center">
        <hr/>
        <el-button
          class="btn btn-sm btn-secondary m-btn--wide"  v-on:click="print(eligibilityResult.url)"
        >
          <font-awesome-icon :icon="['far', 'file-pdf']" />
          View printout <br/>
        </el-button>
      </div>
    </div>

  </div>
</template>

<script>
  import { Button } from 'element-ui';
  import FontAwesomeIcon from '@fortawesome/vue-fontawesome';

  export default {

    components: {
      'font-awesome-icon': FontAwesomeIcon,
      'el-button': Button,
    },

    methods: {
      print(url) {
        window.open(url, 'mywindow', 'status=1');
      },
    },

    props: {
      eligibilityResult: {
        type: Object,
        default: () => {},
      },
    },
  };
</script>

<style scoped>
  .check-eligibility-result {
    background-color: #fff;
    position: relative;
  }

  .check-eligibility-result:before {
    content: '';
    position: absolute;
    top: 10%;
    left: 0;
    width: 1px;
    border-left: 2px solid #f4f4f4;
    height: 80%;
  }

  .check-eligibility-result > h5 {
    font-size: 1.1rem;
  }

  .check-eligibility-result .results--container {

  }

  .check-eligibility-result .results--container > .results--image {
    margin: 0 0 1rem;
    text-align: center;
  }

  .check-eligibility-result .results--container > .results--message {
    font-size: 1.2rem;
    color: #bbb;
    font-weight: 400;
    text-align: center;
  }

  .check-eligibility-result .results--container > .documents-required {
    margin: 2rem 0;
  }

  .check-eligibility-result .results--container > .documents-required > .documents-required--header {
    color: #858EA0;
    font-size: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    padding-bottom: 1rem;
  }

  .check-eligibility-result > .results--container > .documents-required > .documents-required--header > .documents-required--subheader {
    text-transform: none;
    font-size: 0.85rem;
    font-weight: 400;
    line-height: 1rem;
  }

  .check-eligibility-result .results--container > .documents-required > ol {
    padding-left: 1rem;
  }

  .check-eligibility-result .results--container > .documents-required > ol > li {
    margin-bottom: 1rem;
  }

  .check-eligibility-result .results--container > .documents-required > ol > li > .document--name {
    display: block;
    padding-left: 4px;
    line-height: 1rem;
  }

  .check-eligibility-result .results--container > .documents-required > ol > li > .document--reason {
    display: block;
    padding-left: 4px;
    font-size: 0.85rem;
    line-height: 1rem;
    color: #96acbd;
    margin-top: 0.5rem;
  }
</style>
