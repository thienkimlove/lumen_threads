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

        $response = \GoogleMaps::load('geocoding')
            ->setParam (['address' =>  $this->argument->district_name.', '. $this->argument->province_name.', Việt Nam'])
            ->get();

        $response = json_decode($response, true);

        if (!empty($response['results'][0]['place_id'])) {
            $app->db->table('districts')->where('id', $this->argument->district_id)->update(['google_place_id' => $response['results'][0]['place_id']]);
        }
    }
}