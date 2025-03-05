const tw = (...classes) => classes.filter(Boolean).join(' ');

class Components {
  /**
   * Get the value of an object key if it exists, otherwise return a default value.
   *
   * @param {Object} obj - The object to search for the key.
   * @param {string} key - The key to search for in the object.
   * @param {string} defaultValue - The default value to return if the key does not exist.
   * @param {string} ifTrue - The value to return if the key exists. If not set, the value of the key is returned.
   *
   * @return {string} The value of the key if it exists, otherwise the default value.
   */
  static merge(obj, key, defaultValue = '', ifTrue = false) {
    return obj.hasOwnProperty(key) && obj[key] !== ''
      ? ifTrue
        ? ifTrue
        : obj[key]
      : defaultValue;
  }

  /**
   * Button constructor.
   *
   * @param {Object} params - An object of parameters to customize the button.
   *   - 'label' (string): The text to display on the button.
   *   - 'variant' (string): The style variant of the button. Default is 'primary'.
   *   - 'type' (string): The type attribute of the button. Default is 'submit'.
   *   - 'class' (string): Additional CSS classes to apply to the button.
   *   - 'disabled' (string): The disabled attribute of the button. Default is ''.
   *
   * @return {HTMLElement} The button DOM element.
   */
  static Button(params = {}) {
    const label = Components.merge(params, 'label');
    const variant = Components.merge(params, 'variant', 'primary');
    const classList = Components.merge(params, 'class');
    const disabled = Components.merge(params, 'disabled', '', 'disabled');
    const href = Components.merge(params, 'href');
    const icon = Components.merge(params, 'icon');

    const variantClasses = {
      primary:
        'dark:bg-primary-dark dark:text-neutral-200 bg-primary-light text-neutral-100',
      secondary:
        'dark:bg-neutral-700 dark:text-neutral-400 bg-neutral-300 text-neutral-600',
      ghost: 'border dark:text-neutral-600 text-neutral-400',
      danger:
        'dark:bg-red-600 dark:text-neutral-100 bg-red-600 text-neutral-100',
      square: 'size-10'
    };

    const variantClass = variant
      .split(' ')
      .map((val) => variantClasses[val])
      .join(' ');
    const baseClasses =
      'cursor-pointer transition-all duration-200 ease-in-out px-4 py-2 font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed items-center justify-center flex flex-row gap-2 text-center active:scale-95';
    const classes = tw(baseClasses, variantClass, classList);
    const iconElement = icon ? Components.Icon({ icon }) : null;

    const restParams = Object.keys(params).reduce((acc, param) => {
      const value = Components.merge(params, param);
      if (value) {
        acc[param] = value === true ? '' : value;
      }
      return acc;
    }, {});

    const element = href
      ? document.createElement('a')
      : document.createElement('button');

    element.classList = classes;
    if (iconElement) {
      element.appendChild(iconElement);
    }
    element.innerHTML += `${label}`;
    element.disabled = disabled === 'disabled';

    if (href) {
      element.href = href;
    } else {
      element.type = 'submit';
    }

    Object.assign(element, restParams);

    return element;
  }

  /**
   * Constructor for the link component.
   *
   * @param {Object} params - An object of parameters.
   *   - 'label' (string): The text to be displayed inside the anchor tag.
   *   - 'href' (string): The URL the anchor tag should link to.
   *   - 'class' (string): Additional CSS classes to be applied to the anchor tag.
   *   - 'disabled' (string): Optional. If set, adds a 'disabled' attribute to the anchor tag.
   *
   * @return {HTMLElement} The anchor DOM element.
   */
  static Link(params = {}) {
    const label = Components.merge(params, 'label');
    const href = Components.merge(params, 'href');
    const classList = Components.merge(params, 'class');
    const id = Components.merge(params, 'id');

    let classNames = classList;
    if (Components.merge(params, 'disabled')) {
      classNames = tw(
        classList,
        'cursor-not-allowed text-neutral-700 hover:text-neutral-700 dark:text-neutral-400 dark:hover:text-neutral-400 no-underline'
      );
      href = '#';
    }

    const classes = tw(
      'cursor-pointer font-medium items-center justify-center inline-flex flex-row gap-2 text-neutral-700 hover:text-neutral-900 dark:text-neutral-100 dark:hover:text-neutral-400 underline transition-colors active:scale-95',
      classNames
    );

    const element = document.createElement('a');
    element.href = href;
    element.classList = classes;
    element.textContent = label;
    if (id) {
      element.id = id;
    }

    return element;
  }

  /**
   * Constructor for the input component.
   *
   * @param {Object} params - An object of parameters to configure the component.
   *   - 'placeholder' (string): The placeholder text for the input field.
   *   - 'id' (string): The ID for the input field. Defaults to 'primary'.
   *   - 'label' (string|null): The label text for the input field. If null, no label is rendered.
   *   - 'type' (string): The type of the input field. Defaults to 'text'.
   *   - 'class' (string): The CSS class for the input field.
   *   - 'disabled' (bool): Whether the input field is disabled. If true, the 'disabled' attribute is added.
   *
   * @return {HTMLElement} The input DOM element.
   */
  static Input(params = {}) {
    const id = Components.merge(params, 'id', false);
    if (!id) {
      throw new Error('Input field must have an ID');
    }

    const labelText = Components.merge(params, 'label');
    const placeholder = Components.merge(params, 'placeholder', labelText);
    const type = Components.merge(params, 'type', 'text');
    const classList = Components.merge(params, 'class');
    const disabled = Components.merge(params, 'disabled', '', 'disabled');
    const value = Components.merge(params, 'value');
    const name = Components.merge(params, 'name', id);

    const inputElement = document.createElement('input');
    inputElement.type = type;
    inputElement.id = id;
    inputElement.name = name;
    inputElement.classList = classList;
    inputElement.placeholder = placeholder;
    inputElement.disabled = disabled === 'disabled';
    inputElement.value = value || '';

    const restParams = [
      'min',
      'max',
      'step',
      'required',
      'pattern',
      'autocomplete'
    ].reduce((acc, param) => {
      const val = Components.merge(params, param);
      if (val) {
        acc[param] = val === true ? '' : val;
      }
      return acc;
    }, {});

    Object.assign(inputElement, restParams);

    const wrapper = document.createElement('div');
    wrapper.classList = 'block w-full';

    if (labelText) {
      const labelElement = document.createElement('label');
      labelElement.htmlFor = id;
      labelElement.textContent = labelText;
      wrapper.appendChild(labelElement);
    }

    wrapper.appendChild(inputElement);

    return wrapper;
  }

  /**
   * Constructor for the alert component.
   *
   * @param {Object} params - An object of parameters.
   *   - 'text' (string): The text content to be displayed in the component.
   *   - 'variant' (string): The variant type of the component. Defaults to 'primary'.
   *     Possible values are 'danger', 'warning', 'success', and 'info'.
   *
   * @return {HTMLElement} The alert DOM element.
   */
  static Alert(params = {}) {
    const text = Components.merge(params, 'text');
    const variant = Components.merge(params, 'variant', 'danger');

    const alertClasses = {
      danger:
        'bg-neutral-100 text-red-600 dark:bg-neutral-800 dark:text-red-400',
      warning:
        'bg-neutral-100 text-yellow-600 dark:bg-neutral-800 dark:text-yellow-400',
      success:
        'bg-neutral-100 text-green-600 dark:bg-neutral-800 dark:text-green-400',
      info: 'bg-neutral-100 text-blue-600 dark:bg-neutral-800 dark:text-blue-400'
    };

    const icons = {
      danger:
        '<path d="M12 16h.01"/><path d="M12 8v4"/><path d="M15.312 2a2 2 0 0 1 1.414.586l4.688 4.688A2 2 0 0 1 22 8.688v6.624a2 2 0 0 1-.586 1.414l-4.688 4.688a2 2 0 0 1-1.414.586H8.688a2 2 0 0 1-1.414-.586l-4.688-4.688A2 2 0 0 1 2 15.312V8.688a2 2 0 0 1 .586-1.414l4.688-4.688A2 2 0 0 1 8.688 2z"/>',
      warning:
        '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
      success:
        '<path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/>',
      info: '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>'
    };

    const alertTitles = {
      danger: 'Erreur',
      warning: 'Attention',
      success: 'Succès',
      info: 'Information'
    };

    if (!alertTitles[variant]) {
      throw new Error('Invalid variant type');
    }

    const alertElement = document.createElement('div');
    alertElement.classList = `flex items-center p-4 mb-4 rounded-lg ${tw(alertClasses[variant], Components.merge(params, 'class'))}`;
    alertElement.role = 'alert';

    const iconElement = document.createElementNS(
      'http://www.w3.org/2000/svg',
      'svg'
    );
    iconElement.setAttribute('viewBox', '0 0 24 24');
    iconElement.setAttribute('fill', 'none');
    iconElement.setAttribute('stroke', 'currentColor');
    iconElement.setAttribute('stroke-width', '2');
    iconElement.setAttribute('stroke-linecap', 'round');
    iconElement.setAttribute('stroke-linejoin', 'round');
    iconElement.classList = 'shrink-0 size-4';
    iconElement.innerHTML = icons[variant];

    const titleElement = document.createElement('span');
    titleElement.classList = 'sr-only';
    titleElement.textContent = alertTitles[variant];

    const textElement = document.createElement('div');
    textElement.classList = 'ms-3 text-sm font-medium';
    textElement.textContent = text;

    alertElement.appendChild(iconElement);
    alertElement.appendChild(titleElement);
    alertElement.appendChild(textElement);

    // Set other properties passed in the params object
    const restParams = ['id'].reduce((acc, param) => {
      const value = Components.merge(params, param);
      if (value) {
        acc[param] = value === true ? '' : value;
      }
      return acc;
    }, {});

    Object.assign(alertElement, restParams);

    return alertElement;
  }

  /**
   * Generates an SVG icon based on the provided parameters.
   *
   * @param {Object} params - An object of parameters.
   *                      - 'icon' (string): The name of the icon to generate. Required.
   *                      - 'class' (string): Additional CSS classes to apply to the SVG element. Optional.
   *
   * @return {SVGElement} The generated SVG icon DOM element.
   *
   * @throws {Error} If the 'icon' parameter is not provided or if an invalid icon name is given.
   */
  static Icon(params = {}) {
    const icon = Components.merge(params, 'icon');
    if (!icon) {
      throw new Error('Icon name is required');
    }

    const classList = Components.merge(params, 'class');
    const classes = tw('size-6', classList);

    const iconPaths = {
      plus: '<path d="M5 12h14"/><path d="M12 5v14"/>',
      Extérieur:
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
      Domicile:
        '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/>',
      arrowRight: '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
      birthday:
        '<path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/><path d="M2 21h20"/><path d="M7 8v3"/><path d="M12 8v3"/><path d="M17 8v3"/><path d="M7 4h.01"/><path d="M12 4h.01"/><path d="M17 4h.01"/>',
      weight:
        '<circle cx="12" cy="5" r="3"/><path d="M6.5 8a2 2 0 0 0-1.905 1.46L2.1 18.5A2 2 0 0 0 4 21h16a2 2 0 0 0 1.925-2.54L19.4 9.5A2 2 0 0 0 17.48 8Z"/>',
      status:
        '<circle cx="10" cy="7" r="4"/><path d="M10.3 15H7a4 4 0 0 0-4 4v2"/><circle cx="17" cy="17" r="3"/><path d="m21 21-1.9-1.9"/>',
      chart:
        '<path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/>',
      poste:
        '<path d="M20.38 3.46 16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.47a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.47a2 2 0 0 0-1.34-2.23z"/>',
      sun: '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
      trash:
        '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/>'
    };

    if (!iconPaths[icon]) {
      throw new Error('Invalid icon name');
    }

    const svgElement = document.createElementNS(
      'http://www.w3.org/2000/svg',
      'svg'
    );
    svgElement.setAttribute('viewBox', '0 0 24 24');
    svgElement.setAttribute('fill', 'none');
    svgElement.setAttribute('stroke', 'currentColor');
    svgElement.setAttribute('stroke-width', '2');
    svgElement.setAttribute('stroke-linecap', 'round');
    svgElement.setAttribute('stroke-linejoin', 'round');
    svgElement.classList = classes;
    svgElement.innerHTML = iconPaths[icon];

    return svgElement;
  }

  /**
   * Generates and outputs a select dropdown component.
   *
   * @param {Object} params - An object of parameters for the select component.
   *                      - 'id' (string, required): The ID of the select element.
   *                      - 'label' (string, optional): The label text for the select element.
   *                      - 'class' (string, optional): The CSS class for the select element.
   *                      - 'disabled' (bool, optional): Whether the select element is disabled.
   *                      - 'name' (string, optional): The name attribute for the select element.
   *                      - 'options' (array, optional): An array of options for the select element. Each option can be a string or an object with 'value' and 'text' keys.
   *                      - 'value' (string, optional): The value of the option to be selected by default.
   *
   * @throws {Error} If the 'id' parameter is not provided.
   */
  static Select(params = {}) {
    const id = Components.merge(params, 'id', false);
    if (!id) {
      throw new Error('Select field must have an ID');
    }

    const labelText = Components.merge(params, 'label');
    const classList = Components.merge(params, 'class');
    const disabled = Components.merge(params, 'disabled', '', 'disabled');
    const name = Components.merge(params, 'name', id);

    const selectElement = document.createElement('select');
    selectElement.id = id;
    selectElement.name = name;
    selectElement.classList = classList;
    selectElement.disabled = disabled === 'disabled';

    if (params.options) {
      params.options.forEach((option) => {
        const optionElement = document.createElement('option');
        const optionValue = typeof option === 'object' ? option.value : option;
        const optionText = typeof option === 'object' ? option.text : option;
        optionElement.value = optionValue;
        optionElement.textContent = optionText;
        optionElement.selected =
          optionValue === Components.merge(params, 'value');
        selectElement.appendChild(optionElement);
      });
    }

    const wrapper = document.createElement('div');
    wrapper.classList = 'block w-full';

    if (labelText) {
      const labelElement = document.createElement('label');
      labelElement.htmlFor = id;
      labelElement.textContent = labelText;
      wrapper.appendChild(labelElement);
    }

    wrapper.appendChild(selectElement);

    return wrapper;
  }

  static render(target, component, replace = false) {
    // If is JQuery object, convert to DOM element
    if (target instanceof jQuery) {
      target = target.get(0);
    }

    if (typeof target === 'string') {
      target = document.querySelector(target);
    }

    if (!target) {
      throw new Error('Invalid target element');
    }

    if (replace) {
      target.replaceWith(component);
    } else {
      target.appendChild(component);
    }
  }
}

export default Components;
