import validate from 'validate.js';
import { watch } from 'vue';

export default (data, rules) => {
  const validationRules = {
    required(data) {
      return {
        presence: {
          allowEmpty: false,
          message: "is required",
        },
      };
    },
    email(data) {
      return {
        presence: {
          message: "email is invalid",
        },
      };
    },
    min(data) {
      return {
        presence: {
          message: "email is invalid",
        },
      };
    },
    same(data) {
      return {
        equality: data.params[0],
        presence: {
          message: "email is invalid",
        },
      };
    },
  };

  const r = ref({
    message: '',
    errors: {},
    constraints: {},
    mergeErrors(errors={}) {
      // this.errors = { ...this.errors, ...errors };
    },
    get(field) {
      return this.errors[field] || false;
    },
    setData(data) {
      this.message = data.message || '';
      this.errors = data.fields || [];
    },
    setMessage(message) {
      this.message = message;
    },
    setFields(errors) {
      this.errors = errors;
    },
    clear() {
      this.message = '';
      this.errors = {};
    },
    valid() {
      return 0==Object.values(this.errors).length
    },
    invalid() {
      return !this.valid();
    },
  });

  watch([ data ], ([ dataNew ]) => {
    const attributes = { ...data };

    let constraints = {};

    Object.entries(rules).forEach(([ ruleField, ruleRules ]) => {
      ruleRules.forEach((rule) => {
        let [ ruleName, ruleParams='' ] = rule.split(':');
        ruleParams = (ruleParams||'').split(',');
        if (validationRules[ruleName]) {
          constraints[ruleField] = validationRules[ruleName]({
            rule: ruleName,
            field: ruleField,
            value: dataNew[ruleField]||false,
            values: dataNew,
            params: ruleParams,
          });
        }
      });
    });

    r.value.errors = validate(attributes, constraints) || {};
    r.value.constraints = constraints;
  });

  return r;
};