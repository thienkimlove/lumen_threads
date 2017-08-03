<?php

namespace App\Engine;

class TestThread extends \Thread
{
    private $argument;

    public function __construct($arg)
    {
        $this->argument = $arg;
    }

    public function run()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';

        $district_name = str_replace('Huyện ', '', $this->argument->district_name);
        $district_name = str_replace('Thành phố ', '', $district_name);
        $district_name = str_replace('TThị xã ', '', $district_name);
        $district_name = str_replace('Thị Xã ', '', $district_name);

        $responseJson = \GoogleMaps::load('geocoding')
            ->setParam (['address' =>  $district_name.', '. $this->argument->province_name.', Việt Nam'])
            ->get();

        $response = json_decode($responseJson, true);

        if (!empty($response['results'][0]['place_id'])) {
            $app->db->table($this->argument->db_name)->where('id', $this->argument->district_id)->update(['google_place_id' => $response['results'][0]['place_id']]);
        } else {
            file_put_contents(__DIR__.'/../../storage/logs/run.txt', $responseJson, FILE_APPEND);
        }
    }
}