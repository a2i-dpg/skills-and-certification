<?php

namespace App\View\Components\Input;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{
    public ?string $id;
    public ?string $name;
    public ?string $class;
    public ?string $defaultValue;
    public ?string $label;
    public ?string $placeholder;

    /**
     * InputText constructor.
     * @param string $name
     * @param string $id
     * @param string $class
     * @param string|null $defaultValue
     * @param string $label
     * @param string $placeholder
     */
    public function __construct(string $name, string $id = '', string $class = '', string $defaultValue = null, string $label = '', string $placeholder = '')
    {
        $this->id = $id;
        $this->name = $name;
        $this->class = $class;
        $this->defaultValue = $defaultValue;
        $this->placeholder = $placeholder;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render(): View
    {
        return view('components.input.text');
    }
}
