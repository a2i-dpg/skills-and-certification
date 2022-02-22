<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public string $id;
    public bool $xl;
    public bool $lg;
    public bool $sm;
    public string $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $id, string $type, bool $xl = false, bool $lg = false, bool $sm = false)
    {
        $this->id = $id;
        $this->type = $type;
        $this->xl = $xl;
        $this->lg = $lg;
        $this->sm = $sm;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal');
    }
}
