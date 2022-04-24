<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted. bn',
    'active_url' => 'The :attribute is not a valid URL. bn',
    'after' => 'The :attribute must be a date after :date. bn',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date. bn',
    'alpha' => 'The :attribute may only contain letters. bn',
    'alpha_dash' => 'The :attribute may only contain letters, numbers, dashes and underscores. bn',
    'alpha_num' => 'The :attribute may only contain letters and numbers. bn',
    'array' => 'The :attribute must be an array. bn',
    'before' => 'The :attribute must be a date before :date. bn',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date. bn',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max. bn',
        'file' => 'The :attribute must be between :min and :max kilobytes. bn',
        'string' => 'The :attribute must be between :min and :max characters. bn',
        'array' => 'The :attribute must have between :min and :max items. bn',
    ],
    'boolean' => 'The :attribute field must be true or false. bn',
    'confirmed' => 'The :attribute confirmation does not match. bn',
    'date' => 'The :attribute is not a valid date. bn',
    'date_equals' => 'The :attribute must be a date equal to :date. bn',
    'date_format' => 'The :attribute does not match the format :format. bn',
    'different' => 'The :attribute and :other must be different. bn',
    'digits' => 'The :attribute must be :digits digits. bn',
    'digits_between' => 'The :attribute must be between :min and :max digits. bn',
    'dimensions' => 'The :attribute has invalid image dimensions. bn',
    'distinct' => 'The :attribute field has a duplicate value. bn',
    'email' => 'The :attribute must be a valid email address. bn',
    'ends_with' => 'The :attribute must end with one of the following: :values. bn',
    'exists' => 'The selected :attribute is invalid. bn',
    'file' => 'The :attribute must be a file. bn',
    'filled' => 'The :attribute field must have a value. bn',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value. bn',
        'file' => 'The :attribute must be greater than :value kilobytes. bn',
        'string' => 'The :attribute must be greater than :value characters. bn',
        'array' => 'The :attribute must have more than :value items. bn',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value. bn',
        'file' => 'The :attribute must be greater than or equal :value kilobytes. bn',
        'string' => 'The :attribute must be greater than or equal :value characters. bn',
        'array' => 'The :attribute must have :value items or more. bn',
    ],
    'image' => 'The :attribute must be an image. bn',
    'in' => 'The selected :attribute is invalid. bn',
    'in_array' => 'The :attribute field does not exist in :other. bn',
    'integer' => 'The :attribute must be an integer. bn',
    'ip' => 'The :attribute must be a valid IP address. bn',
    'ipv4' => 'The :attribute must be a valid IPv4 address. bn',
    'ipv6' => 'The :attribute must be a valid IPv6 address. bn',
    'json' => 'The :attribute must be a valid JSON string. bn',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value. bn',
        'file' => 'The :attribute must be less than :value kilobytes. bn',
        'string' => 'The :attribute must be less than :value characters. bn',
        'array' => 'The :attribute must have less than :value items. bn',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value. bn',
        'file' => 'The :attribute must be less than or equal :value kilobytes. bn',
        'string' => 'The :attribute must be less than or equal :value characters. bn',
        'array' => 'The :attribute must not have more than :value items. bn',
    ],
    'max' => [
        'numeric' => 'The :attribute may not be greater than :max. bn',
        'file' => 'The :attribute may not be greater than :max kilobytes. bn',
        'string' => 'The :attribute may not be greater than :max characters. bn',
        'array' => 'The :attribute may not have more than :max items. bn',
    ],
    'mimes' => 'The :attribute must be a file of type: :values. bn',
    'mimetypes' => 'The :attribute must be a file of type: :values. bn',
    'min' => [
        'numeric' => 'The :attribute must be at least :min. bn',
        'file' => 'The :attribute must be at least :min kilobytes. bn',
        'string' => 'The :attribute must be at least :min characters. bn',
        'array' => 'The :attribute must have at least :min items. bn',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value bn',
    'not_in' => 'The selected :attribute is invalid. bn',
    'not_regex' => 'The :attribute format is invalid. bn',
    'numeric' => 'The :attribute must be a number. bn',
    'password' => 'The password is incorrect. bn',
    'present' => 'The :attribute field must be present. bn',
    'regex' => 'The :attribute format is invalid. bn',
    'required' => 'The :attribute field is required. bn',
    'required_if' => 'The :attribute field is required when :other is :value. bn',
    'required_unless' => 'The :attribute field is required unless :other is in :values. bn',
    'required_with' => 'The :attribute field is required when :values is present. bn',
    'required_with_all' => 'The :attribute field is required when :values are present. bn',
    'required_without' => 'The :attribute field is required when :values is not present. bn',
    'required_without_all' => 'The :attribute field is required when none of :values are present. bn',
    'same' => 'The :attribute and :other must match. bn',
    'size' => [
        'numeric' => 'The :attribute must be :size. bn',
        'file' => 'The :attribute must be :size kilobytes. bn',
        'string' => 'The :attribute must be :size characters. bn',
        'array' => 'The :attribute must contain :size items. bn',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values. bn',
    'string' => 'The :attribute must be a string. bn',
    'timezone' => 'The :attribute must be a valid zone. bn',
    'unique' => 'The :attribute has already been taken. bn',
    'uploaded' => 'The :attribute failed to upload. bn',
    'url' => 'The :attribute format is invalid. bn',
    'uuid' => 'The :attribute must be a valid UUID. bn',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message bn',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
