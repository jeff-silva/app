export default (inputs, rules=[]) => {

  const validationRules = {
    required(params) {
      if (!!params.value) return;
      return `${params.field} is required`;
    },
    email(params) {
      if (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(params.value||'')) return;
      return 'Invalid email';
    },
    min(params, number) {
      if (params.value >= number) return;
      return `${params.field} lower than ${number}`;
    },
    max(params, number) {
      if (params.value <= number) return;
      return `${params.field} greather than ${number}`;
    },
  };

  let errorsRaw = {};

  const r = ref({
    message: '',
    errors: {},
    get(field) {
      return this.errors[field] || false;
    },
    bind(field, fieldRules=null) {

      if (Array.isArray(fieldRules)) {
        rules[field] = rules[field] || [];
        fieldRules.map((fieldRule) => {
          rules[field].push(fieldRule);
          rules[field] = [...new Set(rules[field])];
        });
      }

      const elemHandler = (ev) => {
        errorsRaw = this.validateData(inputs.value);
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
    validateData(values={}) {
      const _forceNumber = (n) => isNaN(n) ? n : parseFloat(n);
      let error = {};
      Object.entries(rules).map(([ field, validRules ]) => {
        error[field] = error[field] || [];
        validRules.map((validRule) => {
          const value = _forceNumber(values[field] || '') || '';
          let [ methodName, methodParams='' ] = validRule.split(':');
          if (typeof validationRules[methodName] != 'function') return;
          methodParams = (methodParams||'').split(',').filter(v => !!v);
          methodParams.unshift({ value, field, values });
          methodParams = methodParams.map(_forceNumber);
          const errorMsg = validationRules[methodName].apply(this, methodParams) || false;
          error[field].push(errorMsg);
        });
        error[field] = error[field].filter(v => !!v);
      });
      return error;
    },
    setData(data) {
      console.log(data);
      this.message = data.message || '';
      this.errors = data.fields || [];
      errorsRaw = data.fields || [];
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
      errorsRaw = {};
    },
    valid() {
      return !this.message && this.errorsList().length==0;
    },
    invalid() {
      return !this.valid();
    },
  });

  errorsRaw = r.value.validateData(inputs.value);
  return r;
};