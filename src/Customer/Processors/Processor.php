<?php namespace Shopalicious\Customer\Processors;

use Shopalicious\Support\Traits\FireableEventTrait;
use Orchestra\Foundation\Processor\Processor as BaseProcessor;

abstract class Processor extends BaseProcessor
{
    use FireableEventTrait;
}
