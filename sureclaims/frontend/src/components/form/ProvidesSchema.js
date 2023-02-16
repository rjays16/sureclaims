//

export default {
  // generate a new VeeValidate instance for every
  // schema provider
  $_veeValidate: {
    validator: 'new',
  },

  provide() {
    return {
      model: this.model,
      schema: this.schema,
    };
  },
};
