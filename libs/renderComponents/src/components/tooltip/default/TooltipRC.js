export class TooltipRC {
    constructor(targetEl, triggerEl, options = {}) {
        if (!targetEl || !triggerEl) {
            throw new Error("targetEl e triggerEl são obrigatórios.");
        }
        
        this.targetEl = targetEl;
        this.triggerEl = triggerEl;
        this.options = Object.assign({
            placement: 'top',
            triggerType: 'hover',
            onHide: null,
            onShow: null,
            onToggle: null,
        }, options);
        
        this.init();
    }

    init() {
        if (this.options.triggerType === 'hover') {
            this.triggerEl.addEventListener('mouseenter', () => this.show());
            this.triggerEl.addEventListener('mouseleave', () => this.hide());
        } else if (this.options.triggerType === 'click') {
            this.triggerEl.addEventListener('click', () => this.toggle());
        }
    }

    show() {
        this.targetEl.style.display = 'block';
        this.setPosition();

        if (typeof eval(this.options.onShow) === 'function') {
            this.options.onShow();
        }
    }

    hide() {
        this.targetEl.style.display = 'none';

        if (typeof eval(this.options.onHide) === 'function') {
            this.options.onHide();
        }
    }

    toggle() {
        if (this.targetEl.style.display === 'none' || this.targetEl.style.display === '') {
            this.show();
        } else {
            this.hide();
        }
        if (typeof this.options.onToggle === 'function') {
            this.options.onToggle();
        }
    }

    setPosition() {
        const rect = this.triggerEl.getBoundingClientRect();
        const tooltipRect = this.targetEl.getBoundingClientRect();
        
        let top, left;
        switch (this.options.placement) {
            case 'top':
                top = rect.top - tooltipRect.height - 8;
                left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                break;
            case 'right':
                top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                left = rect.right + 8;
                break;
            case 'bottom':
                top = rect.bottom + 8;
                left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                break;
            case 'left':
                top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                left = rect.left - tooltipRect.width - 8;
                break;
        }
        this.targetEl.style.position = 'fixed';
        this.targetEl.style.top = `${top}px`;
        this.targetEl.style.left = `${left}px`;
    }

    updateOnShow(callback) {
        this.options.onShow = callback;
    }

    updateOnHide(callback) {
        this.options.onHide = callback;
    }

    updateOnToggle(callback) {
        this.options.onToggle = callback;
    }
}
