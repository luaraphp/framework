<?php

/**
 * This code is based on Blade by Jens Segers (https://github.com/jenssegers/blade)
 * which was forked and modified by Reymart A. Calicdan (https://github.com/rcalicdan/blade)
 * Licensed under MIT License
 * Modified by Luara Team
 */

namespace Effortless\View;

use Closure;
use Illuminate\Container\Container as BaseContainer;

class BladeContainer extends BaseContainer
{
    protected array $terminatingCallbacks = [];

    public function terminating(Closure $callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    public function terminate()
    {
        foreach ($this->terminatingCallbacks as $terminatingCallback) {
            $terminatingCallback();
        }
    }
}
