<?php

namespace VCComponent\Laravel\Contact\Traits;

use Illuminate\Http\Request;
use VCComponent\Laravel\Contact\Entities\ContactMeta;
trait ContactSchemaTrait
{
    public function metaContact()
    {
        return $this->hasMany(ContactMeta::class);
    }

    public function schema()
    {
        return [];
    }
}
