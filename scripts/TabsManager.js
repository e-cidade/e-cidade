class TabManager {
  constructor(container) {
    this.container = container;
    this.tabs = container.querySelectorAll('.tab');
    this.contents = container.querySelectorAll('.tab-content');

    this.init();
  }

  init() {
    this.tabs.forEach(tab => {
      tab.addEventListener('click', () => this.switchTab(tab));
    });
  }

  switchTab(tab) {
    if (tab.dataset.locked === "true") {
      return;
    }

    const previousTab = this.container.querySelector('.tab.active');
    const previousTabId = previousTab ? previousTab.dataset.tab : null;

    this.tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');

    this.contents.forEach(content => {
      content.style.display = 'none';
    });

    const tabId = tab.dataset.tab;
    const content = this.container.querySelector(`#tab-${tabId}`);
    content.style.display = 'block';

    // Dispara o evento personalizado 'tabSwitched'
    const event = new CustomEvent('tabSwitched', {
      detail: {
        previousTabId: previousTabId,
        currentTabId: tabId
      }
    });
    this.container.dispatchEvent(event);
  }

  lockTab(tabIndex) {
    const tab = this.container.querySelector(`.tab[data-tab="${tabIndex}"]`);
    if (tab) {
      tab.dataset.locked = "true";
    }
  }

  unlockTab(tabIndex) {
    const tab = this.container.querySelector(`.tab[data-tab="${tabIndex}"]`);
    if (tab) {
      delete tab.dataset.locked;
    }
  }
}

// Inicializando o gerenciador de abas. Ex: utilização
//const tabContainer = document.querySelector('.tab-container');
//const tabManager = new TabManager(tabContainer);

//tabContainer.addEventListener('tabSwitched', (e) => {
//    if(e.detail.currentTabId == 2){
//        loadingPorLotes();
//    } else {
//        loadingPorItens();
//    }
//});
