function serializarFormulario(form) {
  const formData = new FormData(form);
  const objeto = {};

  formData.forEach((value, key) => {
    // Se o campo for um arquivo
    if (value instanceof File) {
      if (value.name) {
        objeto[key] = value.name; // Apenas o nome do arquivo
        // Alternativamente, você pode ler o arquivo como Base64 ou Blob conforme necessário
      }
    } else {
      const encodedValue = encodeURIComponent(value);
      if (objeto[key]) {
          if (!Array.isArray(objeto[key])) {
              objeto[key] = [objeto[key]];
          }
          objeto[key].push(encodedValue);
      } else {
          objeto[key] = encodedValue;
      }
    }
  });

  return JSON.stringify(objeto);
}

function isElementFullyVisible(element) {
  if (!element) return false;

  // Verifica se o elemento está visível na tela
  const rect = element.getBoundingClientRect();
  const isOnScreen = (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );

  if (!isOnScreen) return false;

  // Verifica a visibilidade dos elementos pais
  let currentElement = element;
  while (currentElement) {
    const style = window.getComputedStyle(currentElement);

    if (style.display === 'none' || style.visibility === 'hidden' || style.opacity === '0') {
      return false;
    }

    currentElement = currentElement.parentElement;
  }

  return true;
}
