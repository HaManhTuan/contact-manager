<?php

namespace VCComponent\Laravel\Contact\Entities;

use Illuminate\Database\Eloquent\Model;
use VCComponent\Laravel\Contact\Traits\ContactSchemaTrait;

class Contact extends Model
{
    use ContactSchemaTrait;
    protected $fillable = [
        'email',
        'full_name',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'note',
        'type',
        'status',
    ];
    public function schema()
    {
        return [];
    }
    public function ableToUse($user)
    {
        return true;
    }
}
