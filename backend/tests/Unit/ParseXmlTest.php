<?php

namespace Tests\Unit;

use App\Jobs\ParserJob;
use App\Services\ParserService;
use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

/**
 * Тесты для ParserJob'а и ParserService'а
 */
class ParseXmlTest extends TestCase
{
    use Dispatchable;
    use Queueable;
    use RefreshDatabase;
    use SerializesModels;

    private function setUpFixtures(): void
    {
        parent::setUp();
        $storage = Storage::fake('test_storage');
        $contents = file_get_contents(base_path('tests/Fixtures/test_error_document.xml'));
        $storage->put('tests/Fixtures/test_error_document.xml', $contents);
        $contents = file_get_contents(base_path('tests/Fixtures/centers_techexpert.xml'));
        $storage->put('tests/Fixtures/centers_techexpert.xml', $contents);
    }

    /**
     * Тест, что команда запускает job ParserJob
     *
     * @return void
     */
    public function test_runs_ParserJob()
    {
        Queue::fake();
        Artisan::call('parse:xml centers_kodeks.xml');
        Queue::assertPushed(ParserJob::class);
    }

    /**
     * Тест того, что сервис парсера возвращает false, когда указанный файл не существует
     *
     * @throws \Exception
     */
    public function test_file_not_exists()
    {
        $this->setUpFixtures();
        $parser = new ParserService();
        $this->assertFalse($parser->parse('centers_techexpert_2.xml'));
    }

    /**
     * Тест того, что сервис парсера вернет ошибку, когда указанный файл имеет неверное форматирование
     *
     * @throws \Exception
     */
    public function test_file_wrong_format()
    {
        $this->setUpFixtures();
        $parser = new ParserService();
        $this->expectException(ErrorException::class);
        $parser->parse('test_error_document.xml');
    }

    /**
     * Тест того, что после отработки job'а в базу занеслись значения (города)
     *
     * @throws \Exception
     */
    public function test_job_runs_parser_insert_cities()
    {
        $this->setUpFixtures();
        ParserJob::dispatch('centers_techexpert.xml');
        $this->assertDatabaseCount('cities', 84);
    }

    /**
     * Тест того, что после отработки job'а в базу занеслись значения (дистрибьюторы)
     *
     * @return void
     */
    public function test_job_runs_parser_insert_distributors()
    {
        $this->setUpFixtures();
        ParserJob::dispatch('centers_techexpert.xml');
        $this->assertDatabaseCount('distributors', 207);
    }

    /**
     * Тест того, что после отработки job'а в базу занеслись значения (регионы)
     *
     * @return void
     */
    public function test_job_runs_parser_insert_regions()
    {
        $this->setUpFixtures();
        ParserJob::dispatch('centers_techexpert.xml');
        $this->assertDatabaseCount('regions', 94);
    }

    /**
     * Тест того, что job вообще запускает метод parse парсер сервиса
     * проверяет, что job вообще запустит метод parse парсер сервиса
     *
     * @throws \Exception
     */
    public function test_job_runs_parser_parse()
    {
        $fileName = 'test_error_document.xml';
        $parserServiceMock = Mockery::mock(ParserService::class);
        $parserServiceMock->shouldReceive('parse')->once();
        app()->instance(ParserService::class, $parserServiceMock);
        $job = app(ParserJob::class, ['name' => $fileName]);
        $job->handle();
    }
}
