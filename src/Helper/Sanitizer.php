<?php

namespace Kasir\Kasir\Helper;

/**
 * Request params filters.
 *
 * It truncates fields that have length limit, remove not allowed characters from other fields
 *
 * This feature is optional, you can control it with config('sanitized') (default: false)
 */
class Sanitizer
{
    private array | null $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    public static function json(array &$json): void
    {
        $keys = [
            'item_details',
            'customer_details',
        ];
        foreach ($keys as $key) {
            if (! isset($json[$key])) {
                continue;
            }
            $camel = static::upperCamelize($key);
            $function = "field$camel";
            static::$function($json[$key]);
        }
    }

    private static function fieldItemDetails(array &$items): void
    {
        foreach ($items as $item) {
            $id = new self;
            $item['id'] = $id
                ->maxLength(50)
                ->apply($item['id']);
            $name = new self;
            $item['name'] = $name
                ->maxLength(50)
                ->apply($item['name']);
        }
    }

    private static function fieldCustomerDetails(array &$field): void
    {
        if (isset($field['first_name'])) {
            $first_name = new self;
            $field['first_name'] = $first_name->maxLength(255)->apply($field['first_name']);
        }

        if (isset($field['last_name'])) {
            $last_name = new self;
            $field['last_name'] = $last_name->maxLength(255)->apply($field['last_name']);
        }

        if (isset($field['email'])) {
            $email = new self;
            $field['email'] = $email->maxLength(255)->apply($field['email']);
        }

        if (isset($field['phone'])) {
            $phone = new self;
            $field['phone'] = $phone->maxLength(255)->apply($field['phone']);
        }

        if (! empty($field['billing_address']) || ! empty($field['shipping_address'])) {
            $keys = ['billing_address', 'shipping_address'];
            foreach ($keys as $key) {
                if (! isset($field[$key])) {
                    continue;
                }
                static::fieldAddress($field[$key]);
            }
        }
    }

    private static function fieldAddress(array &$field): void
    {
        $fields = [
            'first_name' => 255,
            'last_name' => 255,
            'address' => 255,
            'city' => 255,
            'country_code' => 3,
        ];

        foreach ($fields as $key => $length) {
            if (isset($field[$key])) {
                $self = new self;
                $field[$key] = $self
                    ->maxLength($length)
                    ->apply($field[$key]);
            }
        }

        if (isset($field['postal_code'])) {
            $postal_code = new self;
            $field['postal_code'] = $postal_code
                ->whitelist('A-Za-z0-9\\- ')
                ->maxLength(10)
                ->apply($field['postal_code']);
        }

        if (isset($field['phone'])) {
            static::fieldPhone($field['phone']);
        }
    }

    private static function fieldPhone($field): void
    {
        $hasPlus = str_starts_with($field, '+');
        $self = new self;
        $field = $self
            ->whitelist('\\d\\-\\(\\) ')
            ->maxLength(19)
            ->apply($field);

        if ($hasPlus) {
            $field = '+' . $field;
        }
        $self = new self;
        $field = $self
            ->maxLength(19)
            ->apply($field);
    }

    private function whitelist(string $regex): static
    {
        $this->filters[] = function ($input) use ($regex) {
            return preg_replace("/[^$regex]/", '', $input);
        };

        return $this;
    }

    private function maxLength(int $length): static
    {
        $this->filters[] = function ($input) use ($length) {
            return substr($input, 0, $length);
        };

        return $this;
    }

    private function apply(string $input)
    {
        foreach ($this->filters as $filter) {
            $input = call_user_func($filter, $input);
        }

        return $input;
    }

    private static function upperCamelize($string)
    {
        return str($string)->replace('_', ' ')->camel()->ucfirst();
    }
}
