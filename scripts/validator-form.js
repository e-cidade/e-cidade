function initializeValidation(formSelector) {
  const form = document.querySelector(formSelector);
  if (!form) return;

  const inputs = form.querySelectorAll('input, select, textarea');
  const messages = {};

  inputs.forEach(input => {
    const name = input.name;
    const messagesObj = {};

    // Captura mensagens de erro personalizadas
    [
      "required", "minlength", "maxlength", "email",
      "min", "max", "filetype", "maxsize",
      "date", "time", "year", "no-special-chars", "integer"
    ].forEach(attr => {
      const key = `data-validate-${attr}-message`;
      if (input.hasAttribute(key)) {
        messagesObj[attr] = input.getAttribute(key);
      }
    });

    if (Object.keys(messagesObj).length > 0) {
      messages[name] = messagesObj;
    }

    // Adiciona um elemento de erro se ainda não existir
    if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
      const errorElement = document.createElement('div');
      errorElement.classList.add('error-message');
      input.insertAdjacentElement('afterend', errorElement);
    }
  });

  function clearErrors() {
    inputs.forEach(input => {
      input.classList.remove('error');
      const errorElement = input.nextElementSibling;
      if (errorElement && errorElement.classList.contains('error-message')) {
        errorElement.textContent = '';
      }
    });

    form.querySelectorAll('.accordion-item').forEach(accordionItem => {
      accordionItem.classList.remove('accordion-error');
    });
  }

  function mountRules() {
    const rules = {};
    inputs.forEach(input => {
      const name = input.name;
      const rulesObj = {};

      [
        "required", "minlength", "maxlength", "email",
        "min", "max", "filetype", "maxsize",
        "date", "time", "year", "no-special-chars", "integer"
      ].forEach(attr => {
        const key = `data-validate-${attr}`;
        if (input.hasAttribute(key)) {
          const value = input.getAttribute(key);
          rulesObj[attr] = attr === "filetype" ? value.split(',') :
                          ["minlength", "maxlength", "min", "max", "maxsize"].includes(attr) ? parseInt(value, 10) :
                          value === "true";
        }
      });

      if (Object.keys(rulesObj).length > 0) {
        rules[name] = rulesObj;
      }
    });

    return rules;
  }

  return {
    validate() {
      clearErrors();
      let isValid = true;
      const rules = mountRules();

      inputs.forEach(input => {
        const name = input.name;
        const rulesObj = rules[name] || {};
        const messagesObj = messages[name] || {};
        const errorElement = input.nextElementSibling;

        let errorMessage = "";

        if (rulesObj.required && !input.value.trim()) {
          errorMessage = messagesObj.required || "Este campo é obrigatório";
        } else if (rulesObj.minlength && input.value.length < rulesObj.minlength) {
          errorMessage = messagesObj.minlength || `O campo deve ter pelo menos ${rulesObj.minlength} caracteres`;
        } else if (rulesObj.maxlength && input.value.length > rulesObj.maxlength) {
          errorMessage = messagesObj.maxlength || `O campo não pode ter mais de ${rulesObj.maxlength} caracteres`;
        } else if (rulesObj.email && !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(input.value)) {
          errorMessage = messagesObj.email || "Digite um email válido";
        } else if (rulesObj.min !== undefined && parseFloat(input.value) < rulesObj.min) {
          errorMessage = messagesObj.min || `O valor deve ser maior ou igual a ${rulesObj.min}`;
        } else if (rulesObj.max !== undefined && parseFloat(input.value) > rulesObj.max) {
          errorMessage = messagesObj.max || `O valor deve ser menor ou igual a ${rulesObj.max}`;
        } else if (input.type === 'file' && rulesObj.filetype) {
          const file = input.files[0];
          if (file && !rulesObj.filetype.includes(file.name.split('.').pop().toLowerCase())) {
            errorMessage = messagesObj.filetype || "Tipo de arquivo inválido";
          }
        }

        if (errorMessage) {
          isValid = false;
          input.classList.add("error");
          errorElement.textContent = errorMessage;
        } else {
          input.classList.remove("error");
          errorElement.textContent = "";
        }
      });

      return isValid;
    }
  };
}


function initializeValidationInput(input) {
  if (!(input instanceof HTMLInputElement)) {
    return false;
  }

  const rules = {};
  const messages = {};

  // Regras de valida??o
  if (input.hasAttribute('data-validate-required')) {
    rules.required = input.getAttribute('data-validate-required') === 'true';
  }
  if (input.hasAttribute('data-validate-minlength')) {
    rules.minLength = parseInt(input.getAttribute('data-validate-minlength'), 10);
  }
  if (input.hasAttribute('data-validate-maxlength')) {
    rules.maxLength = parseInt(input.getAttribute('data-validate-maxlength'), 10);
  }
  if (input.hasAttribute('data-validate-email')) {
    rules.email = input.getAttribute('data-validate-email') === 'true';
  }
  if (input.hasAttribute('data-validate-min')) {
    rules.min = parseFloat(input.getAttribute('data-validate-min'));
  }
  if (input.hasAttribute('data-validate-max')) {
    rules.max = parseFloat(input.getAttribute('data-validate-max'));
  }
  if (input.hasAttribute('data-validate-integer')) {
    rules.integer = input.getAttribute('data-validate-integer') === 'true';
  }
  if (input.hasAttribute('data-validate-no-special-chars')) {
    rules.noSpecialChars = input.getAttribute('data-validate-no-special-chars') === 'true';
  }
  if (input.hasAttribute('data-validate-date')) {
    rules.date = input.getAttribute('data-validate-date') === 'true';
  }
  if (input.hasAttribute('data-validate-time')) {
    rules.time = input.getAttribute('data-validate-time') === 'true';
  }
  if (input.hasAttribute('data-validate-year')) {
    rules.year = input.getAttribute('data-validate-year') === 'true';
  }

  // Mensagens de valida??o
  if (input.hasAttribute('data-validate-required-message')) {
    messages.required = input.getAttribute('data-validate-required-message');
  }
  if (input.hasAttribute('data-validate-minlength-message')) {
    messages.minLength = input.getAttribute('data-validate-minlength-message');
  }
  if (input.hasAttribute('data-validate-maxlength-message')) {
    messages.maxLength = input.getAttribute('data-validate-maxlength-message');
  }
  if (input.hasAttribute('data-validate-email-message')) {
    messages.email = input.getAttribute('data-validate-email-message');
  }
  if (input.hasAttribute('data-validate-min-message')) {
    messages.min = input.getAttribute('data-validate-min-message');
  }
  if (input.hasAttribute('data-validate-max-message')) {
    messages.max = input.getAttribute('data-validate-max-message');
  }
  if (input.hasAttribute('data-validate-integer-message')) {
    messages.integer = input.getAttribute('data-validate-integer-message');
  }
  if (input.hasAttribute('data-validate-no-special-chars-message')) {
    messages.noSpecialChars = input.getAttribute('data-validate-no-special-chars-message');
  }
  if (input.hasAttribute('data-validate-date-message')) {
    messages.date = input.getAttribute('data-validate-date-message');
  }
  if (input.hasAttribute('data-validate-time-message')) {
    messages.time = input.getAttribute('data-validate-time-message');
  }
  if (input.hasAttribute('data-validate-year-message')) {
    messages.year = input.getAttribute('data-validate-year-message');
  }

  // Cria ou seleciona um elemento para exibir mensagens de erro
  let errorElement = input.nextElementSibling;
  if (!errorElement || !errorElement.classList.contains('error-message')) {
    errorElement = document.createElement('div');
    errorElement.classList.add('error-message');
    input.insertAdjacentElement('afterend', errorElement);
  }

  function clearErrors() {
    input.classList.remove('error');
    errorElement.textContent = ''; // Limpa o texto da mensagem de erro
  }

  return {
    validate() {
      clearErrors();
      let isValid = true;
      if (rules.required && !input.value.trim()) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.required || 'Este campo é obrigatório';
      } else if(!rules.required && (typeof input.value == 'string' && input.value.trim() === '')){
        clearErrors();
      } else if (rules.minLength && input.value.length < rules.minLength) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.minLength || `O campo deve ter pelo menos ${rules.minLength} caracteres`;
      } else if (rules.maxLength && input.value.length > rules.maxLength) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.maxLength || `O campo não pode ter mais de ${rules.maxLength} caracteres`;
      } else if (rules.email && !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(input.value)) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.email || 'Digite um email válido';
      } else if (rules.integer && !/^\d+$/.test(input.value) && (typeof input.value == 'string' && input.value.trim() !== '')) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.integer || 'O campo deve conter apenas números';
      } else if (rules.noSpecialChars && containsSpecialChars(input.value)) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.noSpecialChars || 'Este campo não deve conter caracteres especiais';
      } else if (rules.date && !isValidDate(input.value)) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.date || 'Data inválida';
      } else if (rules.year && !isValidYear(input.value) && (typeof input.value == 'string' && input.value.trim() !== '')) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.year || 'Ano inválido';
      } else if (rules.time && !isValidTime(input.value)) {
        isValid = false;
        input.classList.add('error');
        errorElement.textContent = messages.time || 'Hora inválida';
      }

      return isValid;
    }
  };

  function isValidDate(dateString) {
    if (!dateString) return false;

    const regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(dateString)) return false;

    const date = new Date(dateString);
    if (date.toString() === 'Invalid Date') return false;

    const [year, month, day] = dateString.split('-');
    if (date.getUTCFullYear() !== parseInt(year) ||
        date.getUTCMonth() + 1 !== parseInt(month) ||
        date.getUTCDate() !== parseInt(day)) {
      return false;
    }

    if (year < 1900 || year > 2099) return false;

    return true;
  }

  function isValidYear(yearString) {
    if (!yearString) return false;

    const regex = /^\d{4}$/; // Verifica se o ano est? no formato de 4 d?gitos
    if (!regex.test(yearString)) return false;

    const year = parseInt(yearString, 10);

    // Verifica se o ano est? no intervalo desejado
    if (year < 1900 || year > 2099) return false;

    return true;
  }

  function isValidTime(timeString) {
    if (!timeString) return false;

    const regex = /^([01]\d|2[0-3]):([0-5]\d)$/;
    return regex.test(timeString);
  }

  function containsSpecialChars(value) {
    const regex = /["';%]/;
    return regex.test(value);
  }
}
