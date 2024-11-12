<?php
require_once __DIR__ . '/../../vendor/autoload.php';
use TailwindMerge\TailwindMerge;
/**
 * Class Components
 *
 * This class is a factory class that provides static methods to create various components.
 *
 * @package lib/components
 */
class Components
{
  /**
   * Get the value of an array key if it exists, otherwise return a default value.
   *
   * @param array $arr The array to search for the key.
   * @param string $key The key to search for in the array.
   * @param string $default The default value to return if the key does not exist.
   * @param string $ifTrue The value to return if the key exists. If not set, the value of the key is returned.
   *
   * @return string The value of the key if it exists, otherwise the default value.
   */
  private static function merge($arr, $key, $default = '', $ifTrue = false)
  {
    return array_key_exists($key, $arr) && !empty($arr[$key])
      ? ($ifTrue
        ? $ifTrue
        : $arr[$key])
      : $default;
  }

  /**
   * Button constructor.
   *
   * @param array $params An associative array of parameters to customize the button.
   *   - 'label' (string): The text to display on the button.
   *   - 'variant' (string): The style variant of the button. Default is 'primary'.
   *   - 'type' (string): The type attribute of the button. Default is 'submit'.
   *   - 'class' (string): Additional CSS classes to apply to the button.
   *   - 'disabled' (string): The disabled attribute of the button. Default is ''.
   *
   * 	@return string The HTML string representing the button component.
   */
  public static function Button($params = [])
  {
    $tw = TailwindMerge::instance();
    $label = Components::merge($params, 'label');
    $variant = Components::merge($params, 'variant', 'primary');
    $type = Components::merge($params, 'type', 'submit');
    $class = Components::merge($params, 'class');
    $disabled = Components::merge($params, 'disabled', '', 'disabled');
    $href = Components::merge($params, 'href');
    $id = Components::merge($params, 'id');
    $icon = Components::merge($params, 'icon');
    $variantClasses = [
      'primary' => 'bg-primary-600 text-white',
      'secondary' => 'bg-neutral-100 text-neutral-600',
      'ghost' => 'border text-neutral-600',
      'danger' => 'bg-red-600 text-white',
      'warning' => 'bg-yellow-600 text-white',
      'info' => 'bg-primary-600 text-white',
      'success' => 'bg-green-600 text-white',
      'square' => 'size-10',
    ];
    $variantClasses = implode(
      ' ',
      array_map(fn($val) => $variantClasses[$val], explode(' ', $variant))
    );
    $baseClasses =
      'cursor-pointer transition-all duration-200 ease-in-out px-4 py-2 font-medium rounded-lg disabled:opacity-50 disabled:cursor-not-allowed items-center justify-center flex flex-row gap-2 text-center';
    $classes = $tw->merge($baseClasses, $variantClasses, $class);
    $iconHTML = $icon != '' ? Components::Icon(['icon' => $icon]) : '';
    if ($href) {
      echo "<a href='$href' " .
        ($id && 'id=' . $id) .
        " class='$classes' $disabled type='$type'>$iconHTML $label</a>";
    } else {
      echo "<button type='submit' " .
        ($id && 'id=' . $id) .
        " class='$classes' $disabled type='$type'>$iconHTML $label</button>";
    }
  }

  /**
   * Constructor for the component.
   *
   * @param array $params An associative array of parameters.
   *   - 'label' (string): The text to be displayed inside the anchor tag.
   *   - 'href' (string): The URL the anchor tag should link to.
   *   - 'class' (string): Additional CSS classes to be applied to the anchor tag.
   *   - 'disabled' (string): Optional. If set, adds a 'disabled' attribute to the anchor tag.
   *
   *  @return string The HTML string representing the link component.
   */
  public static function Link($params = [])
  {
    $tw = TailwindMerge::instance();
    $label = Components::merge($params, 'label');
    $href = Components::merge($params, 'href');
    $class = Components::merge($params, 'class');
    $id = Components::merge($params, 'id');
    if (Components::merge($params, 'disabled')) {
      $class = $tw->merge(
        $class,
        'cursor-not-allowed text-neutral-500 hover:text-neutral-500 no-underline'
      );
      $href = '#';
    }
    $classes = $tw->merge(
      'cursor-pointer font-medium items-center justify-center inline-flex flex-row gap-2 text-neutral-900 hover:text-primary-600 underline transition-colors',
      $class
    );
    echo "<a href='$href' " .
      ($id && 'id=' . $id) .
      " class='$classes'>$label</a>";
  }

  /**
   * Constructor for the component.
   *
   * @param array $params An associative array of parameters to configure the component.
   *   - 'placeholder' (string): The placeholder text for the input field.
   *   - 'id' (string): The ID for the input field. Defaults to 'primary'.
   *   - 'label' (string|null): The label text for the input field. If null, no label is rendered.
   *   - 'type' (string): The type of the input field. Defaults to 'text'.
   *   - 'class' (string): The CSS class for the input field.
   *   - 'disabled' (bool): Whether the input field is disabled. If true, the 'disabled' attribute is added.
   *
   *  @return string The HTML string representing the input component.
   */
  public static function Input($params = [])
  {
    $id = Components::merge($params, 'id', false);
    if (!$id) {
      throw new Exception('Input field must have an ID');
    }
    $label = Components::merge(
      $params,
      'label',
      '',
      "<label for='input'>" . Components::merge($params, 'label') . '</label>'
    );
    $placeholder = htmlspecialchars(
      Components::merge(
        $params,
        'placeholder',
        Components::merge($params, 'label')
      ),
      ENT_QUOTES
    );
    $type = Components::merge($params, 'type', 'text');
    $class = Components::merge($params, 'class');
    $disabled = Components::merge($params, 'disabled', '', 'disabled');
    $value = Components::merge($params, 'value');
    $name = Components::merge($params, 'name', $id);
    if ($value) {
      $value = "value='$value'";
    }
    echo "
        <div class='block w-full'>
          $label
          <input type='$type' $value id='$id' name='$name' class='$class' placeholder='$placeholder' $disabled />
        </div>
      ";
  }

  /**
   * Constructor for the component class.
   *
   * @param array $params An associative array of parameters.
   *   - 'text' (string): The text content to be displayed in the component.
   *   - 'variant' (string): The variant type of the component. Defaults to 'primary'.
   *     Possible values are 'danger', 'warning', 'success', and 'info'.
   *   - 'class' (string): Additional CSS classes to be applied to the component.
   *
   *  @return string The HTML string representing the input component.
   */
  public static function Alert($params = [])
  {
    $tw = TailwindMerge::instance();
    $text = Components::merge($params, 'text');
    $variant = Components::merge($params, 'variant', 'primary');
    $class = Components::merge($params, 'class');
    $id = Components::merge($params, 'id');
    $variantClasses = [
      'danger' => 'bg-red-50 text-red-600 border-red-200',
      'warning' => 'bg-yellow-50 text-yellow-600 border-yellow-200',
      'success' => 'bg-green-50 text-green-600 border-green-200',
      'info' => 'bg-primary-50 text-primary-600 border-primary-200',
    ];
    $icons = [
      'danger' =>
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
      'warning' =>
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>',
      'success' =>
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10"><path d="M21.801 10A10 10 0 1 1 17 3.335"/><path d="m9 11 3 3L22 4"/></svg>',
      'info' =>
        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="size-10"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>',
    ];
    if (!array_key_exists($variant, $variantClasses)) {
      throw new Exception('Invalid variant type');
    }
    $classes = $tw->merge(
      'rounded-lg px-4 py-2 flex flex-row border gap-4 items-center',
      $variantClasses[$variant],
      $class
    );
    echo '<div ' .
      ($id && 'id=' . $id) .
      " class='$classes'>$icons[$variant]<p class='p-0 m-0'>$text</p></div>";
  }

  /**
   * Generates an SVG icon based on the provided parameters.
   *
   * @param array $params An associative array of parameters.
   *                      - 'icon' (string): The name of the icon to generate. Required.
   *                      - 'class' (string): Additional CSS classes to apply to the SVG element. Optional.
   *
   * @return string The generated SVG icon as an HTML string.
   *
   * @throws Exception If the 'icon' parameter is not provided or if an invalid icon name is given.
   */
  public static function Icon($params = [])
  {
    $icon = Components::merge($params, 'icon');
    if (!$icon) {
      throw new Exception('Icon name is required');
    }
    $class = Components::merge($params, 'class');
    $classes = TailwindMerge::instance()->merge('size-6', $class);
    $iconPaths = [
      'plus' => '<path d="M5 12h14"/><path d="M12 5v14"/>',
      'Extérieur' =>
        '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/>',
      'Domicile' =>
        '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/>',
      'arrowRight' => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
      'birthday' =>
        '<path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 16s.5-1 2-1 2.5 2 4 2 2.5-2 4-2 2.5 2 4 2 2-1 2-1"/><path d="M2 21h20"/><path d="M7 8v3"/><path d="M12 8v3"/><path d="M17 8v3"/><path d="M7 4h.01"/><path d="M12 4h.01"/><path d="M17 4h.01"/>',
      'weight' =>
        '<circle cx="12" cy="5" r="3"/><path d="M6.5 8a2 2 0 0 0-1.905 1.46L2.1 18.5A2 2 0 0 0 4 21h16a2 2 0 0 0 1.925-2.54L19.4 9.5A2 2 0 0 0 17.48 8Z"/>',
      'status' =>
        '<circle cx="10" cy="7" r="4"/><path d="M10.3 15H7a4 4 0 0 0-4 4v2"/><circle cx="17" cy="17" r="3"/><path d="m21 21-1.9-1.9"/>',
    ];
    if (!array_key_exists($icon, $iconPaths)) {
      throw new Exception('Invalid icon name');
    }
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='$classes'>$iconPaths[$icon]</svg>";
  }

  public static function Select($params = [])
  {
    $id = Components::merge($params, 'id', false);
    if (!$id) {
      throw new Exception('Select field must have an ID');
    }
    $label = Components::merge(
      $params,
      'label',
      '',
      "<label for='input'>" . Components::merge($params, 'label') . '</label>'
    );
    $class = Components::merge($params, 'class');
    $disabled = Components::merge($params, 'disabled', '', 'disabled');
    $name = Components::merge($params, 'name', $id);
    $optionsHTML = '';
    if (array_key_exists('options', $params)) {
      foreach ($params['options'] as $option) {
        if (is_array($option)) {
          $optionValue = $option['value'];
          $optionText = $option['text'];
        } else {
          $optionValue = $option;
          $optionText = $option;
        }
        $selected =
          $optionValue == Components::merge($params, 'value') ? 'selected' : '';
        $optionsHTML .= "<option value='$optionValue' $selected>$optionText</option>";
      }
    }
    echo "
        <div class='block w-full'>
          $label
          <select id='$id' name='$name' class='$class' $disabled>
            $optionsHTML
          </select>
        </div>
      ";
  }
}

?>
