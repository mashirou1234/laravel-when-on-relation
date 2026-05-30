<?php

namespace Mashirou1234\LaravelWhenOnRelation;

use Closure;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\HigherOrderWhenProxy;
use Illuminate\Support\ServiceProvider;

class WhenOnRelationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package services.
     */
    public function boot(): void
    {
        if (Relation::hasMacro('whenOnRelation')) {
            return;
        }

        Relation::macro('whenOnRelation', function ($value = null, ?callable $callback = null, ?callable $default = null) {
            $value = $value instanceof Closure ? $value($this) : $value;

            if (func_num_args() === 0) {
                return new HigherOrderWhenProxy($this);
            }

            if (func_num_args() === 1) {
                return (new HigherOrderWhenProxy($this))->condition($value);
            }

            if ($value) {
                return $callback($this, $value) ?? $this;
            } elseif ($default) {
                return $default($this, $value) ?? $this;
            }

            return $this;
        });
    }
}
