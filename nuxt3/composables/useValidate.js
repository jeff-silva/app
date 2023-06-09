import validate from 'validate.js';
// import { watch } from 'vue';

export default (inputs, constraints) => {

  const _log = (data) => {
    console.clear(); console.log(JSON.stringify(data, null, 2));
  };

  let errorsRaw = validate(inputs.value, constraints) || {};

  return ref({
    message: '',
    errors: {},
    constraints,
    inputs,
    errorsRaw,
    get(field) {
      return this.errors[field] || false;
    },
    bind(field, fieldConstraint=null) {

      if (fieldConstraint && typeof fieldConstraint=='object' && !Array.isArray(fieldConstraint)) {
        constraints[field] = fieldConstraint;
      }

      const elemHandler = (ev) => {
        errorsRaw = validate(inputs.value, constraints) || {};
        this.errors[field] = errorsRaw[field] || [];
      };

      return {
        errorMessages: (this.errors[field] || []),
        onKeyup: elemHandler,
        onKeydown: elemHandler,
        onFocus: elemHandler,
        onBlur: elemHandler,
      };
    },
    setData(data) {
      this.message = data.message || '';
      this.errors = data.fields || [];
    },
    errorsList() {
      let errors = [];
      Object.entries(errorsRaw).map(([ errorField, errorList ]) => {
        (errorList || []).map(errorItem => {
          errors.push(errorItem);
        });
      });
      return errors;
    },
    clear() {
      this.message = '';
      this.errors = {};
    },
    valid() {
      return !this.message && this.errorsList().length==0;
    },
    invalid() {
      return !this.valid();
    },
  });
};