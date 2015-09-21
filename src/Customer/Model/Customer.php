<?php namespace Shopalicious\Customer\Model;

use Illuminate\Database\Eloquent\Builder;
use Orchestra\Model\Traits\MetableTrait;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Customer extends Eloquent
{
    protected $table = 'customers';

    public function account()
    {
        return $this->belongsTo(\App\Model\User::class, 'user_id');
    }
}
