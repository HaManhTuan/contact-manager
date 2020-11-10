<?php

return [

    'namespace'       => env('CONTACT_COMPONENT_NAMESPACE', 'contact-management'),

    'models'          => [
        'contact' => VCComponent\Laravel\Contact\Entities\Contact::class,
        'contact_meta' => VCComponent\Laravel\Contact\Entities\ContactMeta::class
    ],

    'transformers'    => [
        'contact' => VCComponent\Laravel\Contact\Transformers\ContactTransformer::class,
    ],

    'auth_middleware' => [
        'admin'    => [
            // [
            //     'middleware' => '',
            //     'except'     => [],
            // ],
        ],
        'frontend' => [
            // [
            //     'middleware' => '',
            //     'except'     => [],
            // ],
        ],
    ],

];
