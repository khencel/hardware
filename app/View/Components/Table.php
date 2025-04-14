<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;

class Table extends Component
{

    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $columns,
        public mixed $rows = [],
        public string $createRoute,
        public string $editRoute,
        public string $deleteRoute,
        public string $createLabel = 'Add'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table');
    }
}
