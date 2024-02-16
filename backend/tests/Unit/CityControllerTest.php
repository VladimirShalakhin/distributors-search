<?php

namespace Tests\Unit;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use Arr;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    use DatabaseTruncation;
    use SerializesModels;

    /**
     * Проверка, что если в базе ничего не было заполнено/нет города с таким id, вернется 500 ошибка
     *
     * @return void
     */
    public function test_response_error()
    {
        $response = $this->getJson('/api/cities/1');
        $response->assertNotFound();
    }

    /**
     * Проверка того, что возвращенный объект имеет все необхоимые ключи (в том числе проверяются ключи внутри массива distributors)
     *
     * @return void
     */
    public function test_response_object_city_distributors_array_not_empty()
    {
        Region::factory(92)->has(City::factory(3)->has(Distributor::factory(15)))->create();
        $response = $this->getJson('/api/cities/1');
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'region' => [
                    'id',
                    'name',
                ],
                'distributors' => [
                    '*' => [
                        'id',
                        'name',
                        'web_site',
                        'email',
                        'phone',
                        'address',
                    ],
                ],
                'numberOfDistributors',
            ],
        ]);
    }

    /**
     * Тест того, что неполный поиск работает
     *
     * @return void
     */
    public function test_fuzzy_search()
    {
        City::factory()->create([
            'name' => 'Faviantown',
            'region_id' => Region::factory()->create([
                'region_name' => 'testRegion',
            ]),
        ]);
        City::factory()->create([
            'name' => 'Faviantown',
            'region_id' => Region::factory()->create([
                'region_name' => 'testRegion2',
            ]),
        ]);
        $this->seed();
        $response = $this->get('api/cities?'.Arr::query(['name' => 'Raviantow']));
        $response->assertExactJson([
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Faviantown',
                    'region' => [
                        'id' => 1,
                        'name' => 'testRegion',
                    ],
                    'distributors' => [],
                    'numberOfDistributors' => 0,
                ],
                [
                    'id' => 2,
                    'name' => 'Faviantown',
                    'region' => [
                        'id' => 2,
                        'name' => 'testRegion2',
                    ],
                    'distributors' => [],
                    'numberOfDistributors' => 0,
                ],
            ],
        ]);
    }

    /**
     * Тест того, что endpoint возвращает ошибку, если переданы символы, которые не могут пройти валидацию (цифры)
     *
     * @return void
     */
    public function test_fuzzy_search_error_numbers_only()
    {
        Region::factory(10)->has(City::factory(4)->has(Distributor::factory(8)))->create();
        $response = $this->get('api/cities?'.Arr::query(['name' => rand(1, 1000)]));
        $response->assertBadRequest();
    }

    /**
     * Тест того, что endpoint возвращает ошибку, если передано более 250 (цифры и буквы)
     *
     * @return void
     */
    public function test_fuzzy_search_error_long_string()
    {
        Region::factory(9)->has(City::factory(7)->has(Distributor::factory(4)))->create();
        $response = $this->get('api/cities?'.Arr::query(['name' => Str::random(500)]));
        $response->assertBadRequest();
    }
}
