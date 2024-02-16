<?php

namespace App\Services;

use App\Models\City;
use App\Models\Distributor;
use App\Models\Region;
use ErrorException;
use Exception;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;

class ParserService
{
    private Filesystem $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('distributors');
    }

    /**
     * @throws Exception
     */
    public function parse(string $fileName): bool
    {
        try {
            if ($this->disk->exists($fileName)) {

                $rootElement = new SimpleXMLElement($this->disk->get($fileName));
                $regions = [];
                foreach ($rootElement as $regionElement) {
                    $regions[] = $this->fetchRegionData($regionElement);
                }

                foreach ($regions as $region) {
                    //insert region
                    $regionId = $this->insertRegion([
                        'id' => (empty($region['attributes']['id'])) ? null : $region['attributes']['id'],
                        'region_name' => $region['attributes']['regname'],
                        'county' => $region['attributes']['county'],
                    ]);
                    if ($region['attributes']['centers'] != 0) {
                        //insert city
                        //такую проверку оставил из-за например РБ, где нет центра
                        if (array_key_exists('center', $region['attributes'])) {
                            $this->insertCity([
                                'name' => $region['attributes']['center'],
                                'region_id' => $regionId,
                            ]);
                        }
                        //insert center
                        foreach ($region['centers'] as $center) {
                            $this->insertDistributor($center, $regionId);
                        }
                    }
                }
            } else {
                return false;
            }
        } catch (Exception) {
            throw new ErrorException('Error while processing the document', 1);
        }

        return true;
    }

    private function fetchRegionData(SimpleXMLElement $regionElement): array
    {
        $regionData = [];
        foreach ($regionElement->attributes() as $attribute) {
            $regionData['attributes'][$attribute->getName()] = $attribute->__toString();
        }
        if (isset($regionElement->center)) {
            foreach ($regionElement->center as $center) {
                $centerData = [];
                foreach ($center->attributes() as $attribute) {
                    $centerData[$attribute->getName()] = $attribute->__toString();
                }
                $regionData['centers'][] = $centerData;
            }
        }

        return $regionData;
    }

    private function parseEmailPhone(?string $input): array
    {
        $url_pattern = '/\bhttps?:\/\/\S+(?<!,)/i';
        $email_pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/';
        preg_match($url_pattern, $input, $url_matches);
        preg_match($email_pattern, $input, $email_matches);

        return ['url_matches' => $url_matches, 'email_matches' => $email_matches];
    }

    private function insertDistributor(array $center, int $regionId): void
    {
        if (array_key_exists('city', $center)) {
            $cityId = $this->insertCity([
                'name' => $center['city'],
                'region_id' => $regionId,
            ]);
        } else {
            $cityId = null;
        }

        $emailWebsite = array_key_exists('email', $center) ? $this->parseEmailPhone($center['email']) : null;
        $distributor = new Distributor();
        $distributor->email = is_null($emailWebsite) ? null : (is_null($emailWebsite['email_matches']) ? null : $emailWebsite['email_matches']);
        $distributor->web_site = is_null($emailWebsite) ? null : (is_null($emailWebsite['url_matches']) ? null : $emailWebsite['url_matches']);
        $distributor->phone = explode(',', $center['phone']);
        $distributor->region_id = $center['regionid'];
        $distributor->city_id = $cityId;
        $distributor->name = str_replace("'", "\'", $center['name']);
        $distributor->status = $center['status'];
        $distributor->address = array_key_exists('address', $center) ? $center['address'] : null;
        $distributor->save();
    }

    private function insertCity(array $data): int
    {
        return City::firstOrCreate([
            'name' => $data['name'],
            'region_id' => $data['region_id'],
        ])->id;
    }

    private function insertRegion(array $data): int
    {
        return Region::firstOrCreate([
            'internal_id' => (empty($data['id'])) ? null : $data['id'],
            'region_name' => $data['region_name'],
            'county' => $data['county'],
        ])->id;
    }
}
