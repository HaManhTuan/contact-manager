<?php

namespace VCComponent\Laravel\Contact\Validators;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;
use VCComponent\Laravel\Post\Validators\ContactValidatorInterface;
use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;
use VCComponent\Laravel\Vicoders\Core\Validators\ValidatorInterface;

class ContactValidator extends AbstractValidator
{
    protected $rules = [
        ValidatorInterface::RULE_ADMIN_CREATE => [
            'email' => ['email'],
        ],
        ValidatorInterface::RULE_ADMIN_UPDATE => [
            'email' => ['email'],
        ],
        ValidatorInterface::RULE_CREATE => [
            'email' => ['email'],
        ],
        'RULE_EXPORT'                          => [
            'label'     => ['required'],
            'extension' => ['required', 'regex:/(^xlsx$)|(^csv$)/'],
        ],
    ];

    public function getSchemaRules($entity)
    {
        $schema = $this->getSchemaFunction($entity);

        $rules = $schema->map(function ($item) {
            return $item['rule'];
        });

        return $rules->toArray();
    }

    public function getNoRuleFields($entity)
    {
        $schema = $this->getSchemaFunction($entity);

        $fields = $schema->filter(function ($item) {
            return count($item['rule']) === 0;
        });

        return $fields->toArray();
    }

    private function getSchemaFunction($entity)
    {
        $schema = collect($entity->schema());
        return $schema;
    }

    public function isSchemaValid($data, $rules)
    {
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new Exception($validator->errors(), 1000);
        }
        return true;
    }
}
