<?php

namespace App\Livewire\Public;

use Livewire\Component;
use Livewire\Attributes\Reactive;

class KpiCard extends Component
{
    #[Reactive]
    public $label = '';

    #[Reactive]
    public $value = 0;

    #[Reactive]
    public $icon = 'chart-bar';

    #[Reactive]
    public $color = 'blue';

    private const COLOR_CLASSES = [
        'blue' => 'bg-blue-100 text-blue-600',
        'orange' => 'bg-orange-100 text-orange-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'green' => 'bg-green-100 text-green-600',
        'red' => 'bg-red-100 text-red-600',
        'purple' => 'bg-purple-100 text-purple-600',
    ];

    public function render()
    {
        return view('livewire.public.kpi-card', [
            'colorClass' => self::COLOR_CLASSES[$this->color] ?? self::COLOR_CLASSES['blue'],
        ]);
    }
}
