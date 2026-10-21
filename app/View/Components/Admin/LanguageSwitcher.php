<?php

namespace App\View\Components\Admin;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Top-bar language switcher — disabled in static Indonesian mode.
 */
class LanguageSwitcher extends Component
{
    public Collection $languages;

    public ?object $current = null;

    public function __construct()
    {
        $this->languages = collect();
    }

    public function shouldRender(): bool
    {
        return false;
    }

    public function render(): ?View
    {
        return null;
    }
}
