<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PgsqlArray implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        //работает только с одномерными массивами
        preg_match_all('/(?<=^\{|,)(([^,"{]*)|\s*"((?:[^"\\\\]|\\\\(?:.|[0-9]+|x[0-9a-f]+))*)"\s*)(,|(?<!^\{)(?=}$))/i', $value, $matches, PREG_SET_ORDER);
        $values = [];
        foreach ($matches as $match) {
            $values[] = $match[3] != '' ? stripcslashes($match[3]) : (strtolower($match[2]) == 'null' ? null : $match[2]);
        }

        return $values;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $encoded = '{}';
        if (! is_null($value)) {
            $spacelessValues = [];
            foreach ($value as $item) {
                //в номере телефона содержится такой пробел в строке, удаляю его, чтобы сформировался массив с правильными значениями
                $spacelessValues[] = str_replace(' ', '', $item);
            }
            $encoded = json_encode($spacelessValues);
            $encoded = str_replace('[', '{', $encoded);
            $encoded = str_replace(']', '}', $encoded);
        }

        return $encoded;
    }
}
