class DynamicLoader {
  constructor(navElement, contentElement, encodingType = 'utf-8') {
    this.navElement = document.getElementById(navElement);
    this.contentElement = document.getElementById(contentElement);
    this.encodingType = encodingType;
    this.loadedScripts = new Set(); // Para garantir que scripts externos não sejam carregados várias vezes
  }

  initialize(menus) {
    this.generateNavPills(menus);
    this.addEventListeners(menus);
  }

  generateNavPills(menus) {
    menus.forEach((menu, index) => {
      const activeClass = index === 0 && !menu.disabled ? 'active' : '';
      this.addNavItem(menu.label, menu.url, menu.disabled, activeClass);
    });
  }

  addNavItem(label, url, disabled = false, activeClass = '') {
    const navItem = document.createElement('li');
    navItem.classList.add('nav-item');

    if (disabled) {
      navItem.classList.add('disabled');
    }

    const link = document.createElement('a');
    link.classList.add('nav-link');
    if (activeClass) link.classList.add(activeClass);
    link.href = '#';
    link.setAttribute('data-url', url);
    link.textContent = label;

    if (disabled) {
      link.setAttribute('aria-disabled', 'true');
      link.setAttribute('tabindex', '-1');
      link.classList.add('link-disabled');
    }

    navItem.appendChild(link);
    this.navElement.appendChild(navItem);
  }

  addEventListeners(menus) {
    const links = this.navElement.querySelectorAll('.nav-link');

    links.forEach(link => {
      const navItem = link.parentElement;

      if (!navItem.classList.contains('disabled')) {
        link.addEventListener('click', (event) => {
          event.preventDefault();

          links.forEach(l => l.classList.remove('active'));
          link.classList.add('active');

          const url = link.getAttribute('data-url');
          this.loadContent(url);
        });
      }
    });

    const firstEnabledMenu = menus.find(menu => !menu.disabled);
    if (firstEnabledMenu) {
      this.loadContent(firstEnabledMenu.url);
    }
  }

  loadContent(url) {
    fetch(url)
      .then(response => {
        if (!response.ok) {
          throw new Error('Erro na requisição');
        }
        return response.arrayBuffer().then(buffer => {
          const decoder = new TextDecoder(this.encodingType);
          return decoder.decode(buffer);
        });
      })
      .then(data => {
        this.contentElement.innerHTML = data;

        this.executeScripts(); // Executa os scripts carregados
        this.triggerCustomEvent(url, data); // Dispara o evento personalizado
      })
      .catch(error => {
        this.contentElement.innerHTML = `<p class="text-danger">Erro ao carregar conteúdo: ${error.message}</p>`;
      });
  }

  executeScripts() {
    const scripts = this.contentElement.querySelectorAll('script');

    scripts.forEach(script => {
      if (script.src) {
        // Evita o carregamento duplicado de scripts
        if (!this.loadedScripts.has(script.src)) {
          const newScript = document.createElement('script');
          newScript.src = script.src;
          this.loadedScripts.add(script.src); // Adiciona o script ao conjunto carregado
          document.body.appendChild(newScript);
          document.body.removeChild(newScript);
        }
      } else {
        // Para scripts inline, execute diretamente
        const inlineScript = new Function(script.textContent); // Garante que execute no escopo global
        try {
          inlineScript();  // Executa o conteúdo do script inline
        } catch (error) {
          console.error('Erro ao executar script inline:', error);
        }
      }
    });
  }

  // Função para disparar um evento personalizado
  triggerCustomEvent(url, data) {
    const customEvent = new CustomEvent('contentLoaded', {
      detail: {
        url: url,
        content: data
      }
    });
    this.contentElement.dispatchEvent(customEvent);
  }

  // Função para bloquear dinamicamente um menu
  disableMenu(label) {
    const link = [...this.navElement.querySelectorAll('.nav-link')]
      .find(el => el.textContent.trim() === label);

    if (link) {
      const navItem = link.parentElement;
      navItem.classList.add('disabled');
      link.setAttribute('aria-disabled', 'true');
      link.setAttribute('tabindex', '-1');
      link.classList.add('link-disabled');
    }
  }

  // Função para desbloquear dinamicamente um menu
  enableMenu(label) {
    const link = [...this.navElement.querySelectorAll('.nav-link')]
      .find(el => el.textContent.trim() === label);

    if (link) {
      const navItem = link.parentElement;
      navItem.classList.remove('disabled');
      link.removeAttribute('aria-disabled');
      link.removeAttribute('tabindex');
      link.classList.remove('link-disabled');
    }
  }

  // Função para bloquear todos os menus após inicialização
  blockAllMenus() {
    const links = this.navElement.querySelectorAll('.nav-link');

    links.forEach(link => {
      const navItem = link.parentElement;
      navItem.classList.add('disabled');
      link.setAttribute('aria-disabled', 'true');
      link.setAttribute('tabindex', '-1');
      link.classList.add('link-disabled');
    });
  }

  addMenu(label, url, disabled = false) {
    // Verifica se já existe um item com o mesmo rótulo
    const existingItem = [...this.navElement.querySelectorAll('.nav-link')]
      .find(el => el.textContent.trim() === label);

    if (existingItem) {
      return; // Sai da função se o item já existir
    }

    // Se não existir, adiciona o novo item
    this.addNavItem(label, url, disabled);

    // Reatribui eventos de clique para todos os links
    const links = this.navElement.querySelectorAll('.nav-link');
    links.forEach(link => {
      link.addEventListener('click', (event) => {
        event.preventDefault();

        // Remove a classe 'active' de todos os links antes de ativar o novo link
        links.forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        // Carrega o conteúdo do link clicado
        const url = link.getAttribute('data-url');
        this.loadContent(url);
      });
    });
  }

  // Remover um menu dinamicamente
  removeMenu(label) {
    const link = [...this.navElement.querySelectorAll('.nav-link')]
      .find(el => el.textContent.trim() === label);

    if (link) {
      const navItem = link.parentElement;
      navItem.remove();
    }
  }
}

// Exemplo de uso
//document.addEventListener('DOMContentLoaded', () => {
//  const menus = [
//    { label: 'Home', url: '/home' },
//    { label: 'Profile', url: '/profile', disabled: true },
//    { label: 'Messages', url: '/messages' }
//  ];
//
//  const dynamicLoader = new DynamicLoader('dynamicNav', 'contentArea', 'iso-8859-1');
//  dynamicLoader.initialize(menus);
//
//  // Exemplo de bloqueio e desbloqueio dinâmico
//  setTimeout(() => dynamicLoader.disableMenu('Messages'), 3000); // Desabilita após 3 segundos
//  setTimeout(() => dynamicLoader.enableMenu('Profile'), 5000);   // Habilita após 5 segundos
//
//  // Adicionando e removendo dinamicamente
//  setTimeout(() => dynamicLoader.addMenu('Settings', '/settings'), 4000); // Adiciona nova aba após 4 segundos
//  setTimeout(() => dynamicLoader.removeMenu('Profile'), 6000);   // Remove aba "Profile" após 6 segundos
//
//  document.getElementById('contentArea').addEventListener('contentLoaded', (event) => {
//    console.log('Conteúdo carregado da URL:', event.detail.url);
//    initializeMyTabs();
//  });
//});
//
//function initializeMyTabs() {
//  console.log('Tabs foram inicializadas.');
//}
//setTimeout(() => dynamicLoader.addMenu('Settings', '/settings'), 4000); // Adiciona nova aba após 4 segundos
//setTimeout(() => dynamicLoader.addMenu('Settings', '/settings'), 6000); // Tentativa de adicionar "Settings" novamente (não será adicionado)
