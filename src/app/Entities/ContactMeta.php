<?php

namespace VCComponent\Laravel\Contact\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
class ContactMeta extends Model implements Transformable
{
    use TransformableTrait;
    protected $table = 'contact_meta';

    protected $fillable = [
        'key',
        'value',
    ];
}
