<?php

declare(strict_types=1);

namespace App\Http\Requests\Concerns;

use App\Enums\AutomatizationType;
use App\Models\Automatization;
use LogicException;

trait ResolvesAutomatizationType
{
    protected function automatizationType(): ?AutomatizationType
    {
        if ($this->has('type')) {
            $type = $this->input('type');

            if (! is_string($type)) {
                return null;
            }

            return AutomatizationType::tryFrom($type);
        }

        $automatization = $this->route('automatization');

        if ($automatization instanceof Automatization) {
            return $automatization->type;
        }

        throw new LogicException('Cannot resolve automatization type.');
    }
}
