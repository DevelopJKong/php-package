<?php

namespace Eclair;

use Eclair\Support\ServiceProvider;

class Application
{
    private $provider = [];
    public function __construct($providers = [])
    {
        $this->provider = $providers;
        array_walk($this->provider, fn ($provider) => $provider::register());
    }

    public function boot()
    {
        array_walk($this->provider, fn ($provider) => $provider::boot());
    }


}
