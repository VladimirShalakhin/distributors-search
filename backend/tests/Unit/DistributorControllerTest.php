<?php

namespace Tests\Unit;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DistributorControllerTest extends TestCase
{
    use DatabaseTruncation;
    use SerializesModels;

    /**
     * Проверка, что если в базе ничего не было заполнено/нет дистрибьютора с таким id, вернется 500 ошибка
     *
     * @return void
     */
    public function test_response_error()
    {
        $response = $this->getJson('/api/distributors/1');
        $response->assertNotFound();
    }

    /**
     * Проверка, что вообще возвращается json объект, который содержит ключ date
     *
     * @return void
     */
    public function test_response_object_distributor_exists()
    {
        //$this->seed();

        Region::factory(5)->has(City::factory(10)->has(Distributor::factory(4)))->create();

        $response = $this->getJson('/api/distributors/1');
        $response->assertJson(fn (AssertableJson $json) => $json->has('data'));
    }

    /**
     * Проверка того, что возвращенный объект имеет все необхоимые ключи
     *
     * @return void
     */
    public function test_response_object_distributor_web_site_email_phone_arrays_not_empty()
    {
        Region::factory(2)->has(City::factory(7)->has(Distributor::factory(5)))->create();
        $response = $this->getJson('/api/distributors/1');
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'city' => [
                    'id',
                    'name',
                ],
                'web_site' => [],
                'email' => [],
                'phone' => [],
                'address',
            ],
        ]);
    }

    /**
     * Проверка того, что возвращенный объект имеет правильное содержание (ключи и значения)
     *
     * @return void
     */
    public function test_response_object_distributor_correct_data()
    {
        Distributor::factory()->create([
            'region_id' => '/99/1/0/1/',
            'city_id' => City::factory()->create([
                'name' => 'Faviantown',
                'region_id' => Region::factory()->create([
                    'region_name' => 'testRegion',
                ]),
            ]),
            'name' => 'Экспертцентр',
            'status' => 'Дистрибьютор',
            'address' => '241023, Брянская обл., г. Брянск, ул. Степная, д. 11, офис 3',
            'email' => ['volga@cntd.ru', 'volga@cntd222.ru'],
            'web_site' => ['http://dvolgograd.cntd.ru'],
            'phone' => ['+7 (4922) 32 73 01'],
        ]);
        $response = $this->getJson('api/distributors/1');
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'name' => 'Экспертцентр',
                'city' => [
                    'id' => 1,
                    'name' => 'Faviantown',
                ],
                'web_site' => [
                    'http://dvolgograd.cntd.ru',
                ],
                'email' => [
                    'volga@cntd.ru', 'volga@cntd222.ru',
                ],
                'phone' => [
                    '+7(4922)327301',
                ],
                'address' => '241023, Брянская обл., г. Брянск, ул. Степная, д. 11, офис 3',
            ],
        ]);
    }
}
