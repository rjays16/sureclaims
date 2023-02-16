export default {
  props: [
    'name',
    'disabled',
  ],

  inject: ['schema', 'model', '$validator'],

  computed: {
    $_schema() {
      if (!this.schema[this.name]) {
        throw new Error(`[ActsAsFormControl] Schema "${this.name}" is not defined`);
      }
      return this.schema[this.name];
    },

    $_model: {
      cache: false,
      get() {
        return this.model[this.name];
      },
      set(value) {
        this.model[this.name] = value;
      },
    },
  },

  methods: {
    schemaProps(schema) {
      return {
        class: [
          schema.fieldClasses,
        ],

        // Default input props
        name: this.name,
        disabled: this.disabled,

        // Props set by schema
        readonly: schema.readonly,
        required: schema.required,
        autocomplete: schema.autocomplete,

      };
    },
  },

};
