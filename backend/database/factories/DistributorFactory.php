<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Distributor>
 */
class DistributorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'region_id' => fake()->numerify('/##/##/##/#'),
            'city_id' => City::factory(),
            'name' => fake()->company,
            'status' => 'Дистрибьютор',
            'address' => fake()->address,
            'email' => $this->dataArrayGenerator('email'),
            'web_site' => $this->dataArrayGenerator('web_site'),
            'phone' => $this->dataArrayGenerator('phone'),
        ];
    }

    /**
     * Метод, предназначенный для создания массива любого числа данных (почтового адреса/номера телефна/адреса веб сайта)
     * для последующего использования в фабрике
     */
    private function dataArrayGenerator(string $type): array
    {
        $numberOfElements = fake()->randomDigit();
        $elements = [];
        for ($i = 1; $i <= $numberOfElements; $i++) {
            switch ($type) {
                case 'email':
                    $element = fake()->email();
                    break;
                case 'phone':
                    $element = fake()->phoneNumber();
                    break;
                case 'web_site':
                    $element = fake()->domainName();
                    break;
                default:
                    $element = fake()->randomAscii();
            }
            $elements[] = $element;
        }

        return $elements;
    }
}
