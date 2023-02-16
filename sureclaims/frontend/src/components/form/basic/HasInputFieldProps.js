export default {
  props: [
    'required',
    'placeholder',
    'min',
    'max',
    'maxlength',
    'minlength',
    'step',
    'alt',
  ],

  methods: {
    inputFieldProps() {
      return {
        name: this.name,
        disabled: this.disabled,
        placeholder: this.placeholder,
        required: this.required,
        max: this.max,
        maxlength: this.maxlength,
        min: this.min,
        minlength: this.minlength,
        alt: this.alt,
        step: this.step,
      };
    },

  },
};
