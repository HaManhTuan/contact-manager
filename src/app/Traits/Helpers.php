<?php

namespace VCComponent\Laravel\Contact\Traits;

use Illuminate\Http\Request;

trait Helpers
{
    private function applyQueryScope($query, $field, $value)
    {
        $query = $query->where($field, $value);

        return $query;
    }

    private function filterContactRequestData(Request $request, $entity)
    {
        $request_data = collect($request->all());
        $schema = collect($entity->schema());
        $request_data_keys = $request_data->keys();
        $schema_keys = $schema->keys()->toArray();
        $default_keys = $request_data_keys->diff($schema_keys)->all();
        $data = [];
        $data['default'] = $request_data->filter(function ($value, $key) use ($default_keys) {
            return in_array($key, $default_keys);
        })->toArray();
        $data['schema'] = $request_data->filter(function ($value, $key) use ($schema_keys) {
            return in_array($key, $schema_keys);
        })->toArray();
        return $data;
    }
}
