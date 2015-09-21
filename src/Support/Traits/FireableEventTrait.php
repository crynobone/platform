<?php namespace Shopalicious\Support\Traits;

use Illuminate\Support\Facades\Event;

trait FireableEventTrait
{
    protected function fireEvent($module, $action, array $parameters = [])
    {
        Event::fire("shopalicious.{$module}: {$action}", $parameters);
    }
}
