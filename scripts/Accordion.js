class Accordion {
  constructor(selector, options = { singleOpen: true }) {
    this.selector = selector;
    this.options = options;
    this.initializeExistingAccordions();

    // Configura o MutationObserver
    this.observeDOM();
  }

  initializeExistingAccordions() {
    const accordions = document.querySelectorAll(this.selector);
    accordions.forEach(accordion => {
      if (!accordion.dataset.accordionInitialized) {
        accordion.dataset.accordionInitialized = true; // Marca o accordion como inicializado
        this.initAccordion(accordion);
      }
    });
  }

  initAccordion(accordion) {
    const items = accordion.querySelectorAll('.accordion-item');
    items.forEach(item => {
      const header = item.querySelector('.accordion-header');
      header.addEventListener('click', () => this.toggleAccordion(item, items));
    });
  }

  toggleAccordion(item, items) {
    const content = item.querySelector('.accordion-content');
    const header = item.querySelector('.accordion-header');

    // Fecha outros itens se `singleOpen` estiver ativo
    if (this.options.singleOpen) {
      items.forEach(otherItem => {
        const otherContent = otherItem.querySelector('.accordion-content');
        const otherHeader = otherItem.querySelector('.accordion-header');
        if (otherItem !== item) {
          otherContent.style.height = '0';
          otherContent.style.opacity = '0';
          otherContent.style.padding = '0';
          otherHeader.classList.remove('active');
        }
      });
    }

    // Verifica se o conteúdo está aberto
    if (content.style.height && content.style.height !== '0px') {
      // Fechar
      content.style.height = content.scrollHeight + 'px'; // Define altura atual para permitir a transição
      requestAnimationFrame(() => {
        content.style.height = '0';
        content.style.opacity = '0';
        content.style.padding = '0';
      });
      header.classList.remove('active');
    } else {
      // Abrir
      content.style.height = content.scrollHeight + 'px';
      content.style.opacity = '1';
      content.style.padding = '15px';

      // Transição suave para auto
      content.addEventListener('transitionend', () => {
        content.style.height = 'auto';
      }, { once: true });

      header.classList.add('active');

      // Dispara evento personalizado quando o accordion é aberto
      const accordionOpenedEvent = new CustomEvent('accordionOpened', {
        detail: {
          item: item,
          header: header.innerText
        }
      });
      item.dispatchEvent(accordionOpenedEvent);
    }
  }

  observeDOM() {
    const observer = new MutationObserver(() => {
      this.initializeExistingAccordions();
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }
}

// Exemplo de uso
//new Accordion('.accordion', { singleOpen: true });
