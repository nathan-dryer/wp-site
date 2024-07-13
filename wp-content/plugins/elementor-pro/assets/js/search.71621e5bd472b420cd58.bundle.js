/*! elementor-pro - v3.23.0 - 26-06-2024 */
"use strict";
(self["webpackChunkelementor_pro"] = self["webpackChunkelementor_pro"] || []).push([["search"],{

/***/ "../modules/search/assets/js/frontend/handlers/search.js":
/*!***************************************************************!*\
  !*** ../modules/search/assets/js/frontend/handlers/search.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, exports, __webpack_require__) => {



var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");
Object.defineProperty(exports, "__esModule", ({
  value: true
}));
exports["default"] = void 0;
var _defineProperty2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "../node_modules/@babel/runtime/helpers/defineProperty.js"));
var _runElementHandlers = _interopRequireDefault(__webpack_require__(/*! elementor-pro/frontend/utils/run-element-handlers */ "../assets/dev/js/frontend/utils/run-element-handlers.js"));
class Search extends elementorModules.frontend.handlers.Base {
  constructor() {
    super(...arguments);
    (0, _defineProperty2.default)(this, "debounceTimeoutId", void 0);
  }
  getDefaultSettings() {
    return {
      selectors: {
        searchWrapper: '.e-search',
        searchField: '.e-search-input',
        submitButton: '.e-search-submit',
        clearIcon: '.e-search-input-wrapper > svg, .e-search-input-wrapper > i',
        searchIcon: '.e-search-label > svg, .e-search-label > i',
        resultsContainer: '.e-search-results'
      }
    };
  }
  getDefaultElements() {
    const selectors = this.getSettings('selectors');
    return {
      searchWidget: this.$element[0],
      searchWrapper: this.$element[0].querySelector(selectors.searchWrapper),
      searchField: this.$element[0].querySelector(selectors.searchField),
      submitButton: this.$element[0].querySelector(selectors.submitButton),
      clearIcon: this.$element[0].querySelector(selectors.clearIcon),
      searchIcon: this.$element[0].querySelector(selectors.searchIcon),
      resultsContainer: this.$element[0].querySelector(selectors.resultsContainer)
    };
  }
  onInit() {
    super.onInit();
    this.changeClearIconVisibility(true);
    this.updateInputStyle();
    this.toggleSearchResultsVisibility = this.toggleSearchResultsVisibility.bind(this);
    document.addEventListener('click', this.toggleSearchResultsVisibility);
    document.fonts.ready.then(() => this.updateInputStyle());
  }
  onDestroy() {
    document.removeEventListener('click', this.toggleSearchResultsVisibility);
  }
  bindEvents() {
    this.elements.submitButton.addEventListener('click', this.onSubmit.bind(this));
    this.elements.searchField.addEventListener('input', event => {
      this.changeClearIconVisibility(!event.target.value.length);
      this.debounce(this.onType)(event);
    });
    this.elements.searchField.addEventListener('keydown', this.onSearchFieldKeydown.bind(this));
    this.elements.searchWidget.addEventListener('click', this.onClick.bind(this));
    ['focusin', 'focusout'].forEach(eventType => {
      this.elements.searchField.addEventListener(eventType, this.toggleWidgetFocusClass.bind(this));
    });
    this.elements.clearIcon?.addEventListener('click', this.onClear.bind(this));
  }
  onClick() {
    this.elements.resultsContainer.classList.remove('hidden');
  }
  onType(event) {
    event.preventDefault();
    if (!this.elements.searchField.value.length) {
      this.clearResultsMarkup();
      return;
    }
    const minimumSearchLength = this.getMinimumSearchLength();
    const shouldShowLiveResults = this.shouldShowLiveResults();
    if (shouldShowLiveResults && this.elements.searchField.value.length >= minimumSearchLength) {
      this.renderLiveResults();
    }
  }
  toggleWidgetFocusClass(event) {
    const isFocusIn = 'focusin' === event.type;
    this.$element[0].classList.toggle('e-focus', isFocusIn);
  }
  onSubmit(event) {
    if (elementorFrontend.isEditMode() || !this.shouldAllowClick(event) && !this.shouldAllowEnter(event)) {
      event.preventDefault();
    }
  }
  onClear(event) {
    event.preventDefault();
    this.elements.searchField.value = '';
    this.clearResultsMarkup();
    this.elements.searchField.focus();
    this.changeClearIconVisibility(true);
  }
  onSearchFieldKeydown(event) {
    if ('Enter' === event.code) {
      this.onSubmit(event);
    }
  }
  fetchUpdatedSearchWidgetMarkup() {
    return fetch(`${elementorProFrontend.config.urls.rest}elementor-pro/v1/refresh-search`, this.getFetchArgumentsForSearchUpdate());
  }
  getMinimumSearchLength() {
    return this.getElementSettings().minimum_search_characters || 3;
  }
  shouldShowLiveResults() {
    return this.getElementSettings().live_results && this.getElementSettings().template_id;
  }
  renderLiveResults() {
    const widget = document.querySelector(`.elementor-element-${this.getID()}`);
    if (!widget) {
      return;
    }
    if (!this.elements.searchField.value) {
      this.clearResultsMarkup();
      return;
    }
    return this.fetchUpdatedSearchWidgetMarkup().then(response => {
      if (!(response instanceof Response) || !response?.ok || 400 <= response?.status) {
        return {};
      }
      return response.json();
    }).catch(() => {
      return {};
    }).then(response => {
      if (!response?.data) {
        return;
      }
      const resultNode = document.createElement('div');
      resultNode.innerHTML = response.data;
      this.elements.resultsContainer.replaceChildren(resultNode);
      this.maybeHandleNoResults(resultNode);
    }).finally(() => {
      const resultsElements = document.querySelectorAll(`[data-id="${this.getID()}"] .e-loop-item`);
      (0, _runElementHandlers.default)(resultsElements);
      if (elementorFrontend.config.experimentalFeatures.e_lazyload) {
        document.dispatchEvent(new Event('elementor/lazyload/observe'));
      }
    });
  }
  maybeHandleNoResults(resultsNode) {
    const isNoResultsMessage = !!resultsNode.querySelector('.e-search-nothing-found-message');
    this.elements.resultsContainer.classList[isNoResultsMessage ? 'add' : 'remove']('no-results');
  }
  clearResultsMarkup() {
    this.elements.resultsContainer.innerHTML = '';
  }
  getFetchArgumentsForSearchUpdate() {
    const data = this.prepareSearchUpdateRequestData();
    const args = {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(data)
    };
    if (elementorFrontend.isEditMode() && !!elementorPro.config.eSearch?.nonce) {
      args.headers['X-WP-Nonce'] = elementorPro.config.eSearch?.nonce;
    }
    return args;
  }
  prepareSearchUpdateRequestData() {
    const widgetId = '' + this.getID(),
      data = {
        post_id: this.getClosestDataElementorId(this.$element[0]),
        widget_id: widgetId,
        search_term: this.elements.searchField.value || ''
      };
    if (elementorFrontend.isEditMode()) {
      // In the editor, we have to support Search widgets that have been created but not saved to the database yet.
      const widgetContainer = window.top.$e.components.get('document').utils.findContainerById(widgetId);
      data.widget_model = widgetContainer.model.toJSON({
        remove: ['default', 'editSettings', 'defaultEditSettings']
      });
      data.is_edit_mode = true;
    }
    return data;
  }
  getClosestDataElementorId(element) {
    const closestParent = element.closest('[data-elementor-id]');
    return closestParent ? closestParent.getAttribute('data-elementor-id') : 0;
  }
  shouldAllowEnter(event) {
    return +event.detail <= 0 && this.getSubmitTrigger().enter;
  }
  shouldAllowClick(event) {
    return +event.detail > 0 && this.getSubmitTrigger().click;
  }
  getSubmitTrigger() {
    const trigger = this.getElementSettings('submit_trigger');
    return {
      click: 'key_enter' !== trigger,
      enter: 'click_submit' !== trigger
    };
  }
  debounce(callback) {
    var _this = this;
    let timeout = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 300;
    return function () {
      for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
        args[_key] = arguments[_key];
      }
      clearTimeout(_this.debounceTimeoutId);
      _this.debounceTimeoutId = setTimeout(() => callback.apply(_this, args), timeout);
    };
  }
  updateInputStyle() {
    let iconSlugs = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : ['searchIcon', 'clearIcon'];
    const cssVariableNamesMap = {
        searchIcon: 'icon-label',
        clearIcon: 'icon-clear'
      },
      widgetStyle = this.$element[0].style,
      hiddenRoots = this.getAllDisplayNoneParents(this.$element[0].parentNode);
    this.setElementsDisplay(hiddenRoots, 'block');
    for (const iconSlug of iconSlugs) {
      const {
          width
        } = this.elements[iconSlug]?.getBoundingClientRect() || {
          width: 0
        },
        cssVariableSlug = cssVariableNamesMap[iconSlug];
      widgetStyle.setProperty(`--e-search-${cssVariableSlug}-absolute-width`, width + 'px');
      this.elements.searchField.classList[width ? 'remove' : 'add'](`no-${cssVariableSlug}`);
    }
    this.setElementsDisplay(hiddenRoots, '');
    this.elements.searchWrapper.classList.remove('hidden');
  }

  /**
   * Sets the clear icon visibility.
   * @param { boolean } shouldHide true to hide or false to show.
   * @return { void } the width.
   */
  changeClearIconVisibility(shouldHide) {
    this.elements.clearIcon?.classList[shouldHide ? 'add' : 'remove']('hidden');
  }
  toggleSearchResultsVisibility(event) {
    const selectors = this.getSettings('selectors'),
      widgetWrapper = `.elementor-element-${this.getID()}`,
      isSearchContainerClicked = !!event?.target?.closest(`${widgetWrapper} ${selectors.searchWrapper}`),
      isSearchInputClicked = event?.target?.classList?.contains(selectors.searchField.replace('.', '')),
      isSearchResultsPresent = this.elements.resultsContainer?.children?.length;
    if (!isSearchResultsPresent) {
      return;
    }
    if (!isSearchInputClicked || !isSearchContainerClicked) {
      this.elements.resultsContainer.classList.add('hidden');
    }
  }
  getAllDisplayNoneParents(elementNode) {
    let foundElements = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
    if (!elementNode || elementNode === document.body) {
      return foundElements;
    }
    const style = window.getComputedStyle(elementNode),
      isNotDisplayed = 'none' === style.display;
    if (isNotDisplayed) {
      foundElements.push(elementNode);
    }
    return this.getAllDisplayNoneParents(elementNode.parentNode, foundElements);
  }
  setElementsDisplay(elements, displayValue) {
    elements.forEach(element => {
      element.style.display = displayValue;
    });
  }
  onElementChange(propertyName) {
    const propertyNameCallbackMap = {
      search_field_icon_label_size: () => this.updateInputStyle(['searchIcon']),
      icon_clear_size: () => this.updateInputStyle(['clearIcon'])
    };
    if (propertyNameCallbackMap[propertyName]) {
      propertyNameCallbackMap[propertyName]();
    }
  }
}
exports["default"] = Search;

/***/ })

}]);
//# sourceMappingURL=search.71621e5bd472b420cd58.bundle.js.map