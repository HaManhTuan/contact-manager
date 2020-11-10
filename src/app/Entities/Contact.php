<?php

namespace VCComponent\Laravel\Contact\Entities;

use Illuminate\Database\Eloquent\Model;
use VCComponent\Laravel\Contact\Entities\ContactMeta;
class Contact extends Model
{
    protected $fillable = [
        'email',
        'full_name',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'note',
        'type',
        'status'
    ];
    public function schema()
    {
        return [
            'fax' => [
                'type' => 'integer',
                'rule' => [],
            ],
            'phone_ct'    => [
                'type' => 'integer',
                'rule' => [],
            ]
        ];
    }
    public function metaContact()
    {
        return $this->hasMany(ContactMeta::class);
    }
    public function ableToUse($user)
    {
        return true;
    }

}
