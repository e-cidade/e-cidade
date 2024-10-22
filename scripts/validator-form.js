function initializeValidation(formSelector) {
  const form = document.querySelector(formSelector);
    if (!form) return;

    const inputs = form.querySelectorAll('input, select');
    const rules = {};
    const messages = {};

    inputs.forEach(input => {
      const name = input.name;
      const rulesObj = {};
      const messagesObj = {};

      // Regras de validaÃ§Ã£o
      if (input.hasAttribute('data-validate-required')) {
        rulesObj.required = input.getAttribute('data-validate-required') === 'true';
      }
      if (input.hasAttribute('data-validate-minlength')) {
        rulesObj.minLength = parseInt(input.getAttribute('data-validate-minlength'), 10);
      }
      if (input.hasAttribute('data-validate-maxlength')) {
        rulesObj.maxLength = parseInt(input.getAttribute('data-validate-maxlength'), 10);
      }
      if (input.hasAttribute('data-validate-email')) {
        rulesObj.email = input.getAttribute('data-validate-email') === 'true';
      }
      if (input.hasAttribute('data-validate-min')) {
        rulesObj.min = parseFloat(input.getAttribute('data-validate-min'));
      }
      if (input.hasAttribute('data-validate-max')) {
        rulesObj.max = parseFloat(input.getAttribute('data-validate-max'));
      }
      if (input.hasAttribute('data-validate-filetype')) {
        rulesObj.filetype = input.getAttribute('data-validate-filetype').split(',');
      }
      if (input.hasAttribute('data-validate-maxsize')) {
        rulesObj.maxsize = parseInt(input.getAttribute('data-validate-maxsize'), 10);
      }
      if (input.hasAttribute('data-validate-date')) {
        rulesObj.date = input.getAttribute('data-validate-date') === 'true';
      }

      if (Object.keys(rulesObj).length > 0) {
        rules[name] = rulesObj;
      }

      // Mensagens de validaÃ§Ã£o
      if (input.hasAttribute('data-validate-required-message')) {
        messagesObj.required = input.getAttribute('data-validate-required-message');
      }
      if (input.hasAttribute('data-validate-minlength-message')) {
        messagesObj.minLength = input.getAttribute('data-validate-minlength-message');
      }
      if (input.hasAttribute('data-validate-maxlength-message')) {
        messagesObj.maxLength = input.getAttribute('data-validate-maxlength-message');
      }
      if (input.hasAttribute('data-validate-email-message')) {
        messagesObj.email = input.getAttribute('data-validate-email-message');
      }
      if (input.hasAttribute('data-validate-min-message')) {
        messagesObj.min = input.getAttribute('data-validate-min-message');
      }
      if (input.hasAttribute('data-validate-max-message')) {
        messagesObj.max = input.getAttribute('data-validate-max-message');
      }
      if (input.hasAttribute('data-validate-filetype-message')) {
        messagesObj.filetype = input.getAttribute('data-validate-filetype-message');
      }
      if (input.hasAttribute('data-validate-maxsize-message')) {
        messagesObj.maxsize = input.getAttribute('data-validate-maxsize-message');
      }
      if (input.hasAttribute('data-validate-date-message')) {
        messagesObj.date = input.getAttribute('data-validate-date-message');
      }

      if (Object.keys(messagesObj).length > 0) {
        messages[name] = messagesObj;
      }

      // Cria um elemento para exibir mensagens de erro
      const errorElement = document.createElement('div');
      errorElement.classList.add('error-message');
      input.insertAdjacentElement('afterend', errorElement);
    });

    function clearErrors() {
      inputs.forEach(input => {
        input.classList.remove('error');
        const errorElement = input.nextElementSibling;
        if (errorElement && errorElement.classList.contains('error-message')) {
          errorElement.textContent = '';
        }
      });
    }

    return {
      validate() {
        clearErrors();
        let isValid = true;

        inputs.forEach(input => {
          const name = input.name;
          const rulesObj = rules[name] || {};
          const messagesObj = messages[name] || {};

          const errorElement = input.nextElementSibling;

          if (rulesObj.required && !input.value.trim()) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.required || 'Este campo é obrigatório';
          } else if (rulesObj.minLength && input.value.length < rulesObj.minLength) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.minLength || `O campo deve ter pelo menos ${rulesObj.minLength} caracteres`;
          } else if (rulesObj.maxLength && input.value.length > rulesObj.maxLength) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.maxLength || `O campo não pode ter mais de ${rulesObj.maxLength} caracteres`;
          } else if (rulesObj.email && !/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/.test(input.value)) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.email || 'Digite um email válido';
          } else if (input.type === 'number' && rulesObj.min !== undefined && parseFloat(input.value) < rulesObj.min) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.min || `O valor deve ser maior ou igual a ${rulesObj.min}`;
          } else if (input.type === 'number' && rulesObj.max !== undefined && parseFloat(input.value) > rulesObj.max) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.max || `O valor deve ser menor ou igual a ${rulesObj.max}`;
          } else if (input.type === 'file') {
            const files = input.files;
            if (rulesObj.filetype) {
              for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileExtension = file.name.split('.').pop().toLowerCase();
                if (!rulesObj.filetype.includes(fileExtension)) {
                  isValid = false;
                  input.classList.add('error');
                  errorElement.textContent = messagesObj.filetype || `Tipo de arquivo inválido. Tipos permitidos: ${rulesObj.filetype.join(', ')}`;
                  break;
                }
              }
            }
            if (rulesObj.maxsize) {
              for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.size > rulesObj.maxsize) {
                  isValid = false;
                  input.classList.add('error');
                  errorElement.textContent = messagesObj.maxsize || `O tamanho do arquivo não pode exceder ${rulesObj.maxsize / 1024 / 1024} MB`;
                  break;
                }
              }
            }
          } else if (rulesObj.date && !isValidDate(input.value)) {
            isValid = false;
            input.classList.add('error');
            errorElement.textContent = messagesObj.date || 'Data inválida';
          }
        });

        return isValid;
      }
    };

    function isValidDate(dateString) {
      // Verifica se o input está vazio
      if (!dateString) {
        return false;
      }

      // Verifica o formato da data: "YYYY-MM-DD"
      const regex = /^\d{4}-\d{2}-\d{2}$/;
      if (!regex.test(dateString)) {
        return false;
      }

      // Cria uma instância de Date a partir da string
      const date = new Date(dateString);

      // Verifica se a data é válida
      if (date.toString() === 'Invalid Date') {
        return false;
      }

      // Verifica se a data no formato original é a mesma que o resultado da conversão para evitar datas inválidas como "2024-02-30"
      const [year, month, day] = dateString.split('-');
      if (date.getUTCFullYear() !== parseInt(year) ||
          date.getUTCMonth() + 1 !== parseInt(month) ||
          date.getUTCDate() !== parseInt(day)) {
        return false;
      }

      // Verifica se o ano está dentro de um intervalo razoável (por exemplo, 1900-2099)
      if (year < 1900 || year > 2099) {
        return false;
      }

      return true;
    }

}
