<?php

namespace App\Console\Commands;

use App\Measpoint;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class CrawlSmarterTogetherApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:smartertogether {clientid} {clientsecret}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crawls the Smarter Together API for juicy lamp post data';

    private $apiToken = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lampposts = [];

        $this->apiToken = $this->getApiToken();

        $guzzle = new Client([
            'base_uri' => 'https://api.smartdataplatform.info',
            'timeout' => 3.0,
        ]);

        $lamppostResponse = $guzzle->request('GET', '/lampposts', [
            'headers' => [
                'Authorization' => 'bearer ' . $this->apiToken
            ]
        ]);
        $lamppostJson = $lamppostResponse->getBody()->getContents();
        foreach(json_decode($lamppostJson, true) as $lp)
        {
            $lampposts[$lp['id']] = [];
        }

        foreach($lampposts as $lpName => $_)
        {
            $req = $guzzle->request('GET', '/lampposts/' . $lpName,
                [
                    'headers' => [
                        'Authorization' => 'bearer ' . $this->apiToken
                    ]
                ]);
            $lampposts[$lpName] = json_decode($req->getBody()->getContents(), true);
            if(!array_key_exists('connectedSensors', $lampposts[$lpName]))
            {
                unset($lampposts[$lpName]);
            }
        }

        foreach($lampposts as $lp => $data)
        {
            foreach($data['connectedSensors'] as $sensor)
            {
                $endpoint = '/lampposts/' . $lp . '/connectedSensors/' . $sensor['connectedSensorId'] . '/measuredValues';
                $req = $guzzle->request('GET', $endpoint, [
                    'headers' => [
                        'Authorization' => 'bearer ' . $this->apiToken
                    ],
                    'query' => [
                        'timestampFrom' => '2018-01-01T11:00:00+01:00',
                        'timestampTo' => '2018-12-31T23:59:59+01:00',
                        'interval' => 'hourly'
                    ]
                ]);
                $sensorData = json_decode($req->getBody()->getContents(), true);
                foreach($sensorData as $dataset)
                {
                    $measpoint = new Measpoint();
                    $measpoint->sensor = 'st:' . $dataset['sensorId'];
                    $measpoint->lat = $lampposts[$dataset['lamppostId']]['location']['coordinates'][1];
                    $measpoint->lon = $lampposts[$dataset['lamppostId']]['location']['coordinates'][0];
                    $datetime = new Carbon($dataset['timestamp']);
                    $measpoint->datetime = $datetime->timestamp;

                    if(array_key_exists('particlePollutionFine', $dataset))
                    {
                        $measpoint->pm2 = $dataset['particlePollutionFine'];
                    }
                    else
                    {
                        $measpoint->pm2 = null;
                    }

                    if(array_key_exists('particlePollutionCoarse', $dataset))
                    {
                        $measpoint->pm10 = $dataset['particlePollutionCoarse'];
                    }
                    else
                    {
                        $measpoint->pm10 = null;
                    }

                    if(array_key_exists('ozone', $dataset))
                    {
                        $measpoint->ozone = $dataset['ozone'];
                    }
                    else
                    {
                        $measpoint->ozone = null;
                    }

                    if(array_key_exists('sulfurDioxide', $dataset))
                    {
                        $measpoint->sulfurDioxide = $dataset['sulfurDioxide'];
                    }
                    else
                    {
                        $measpoint->sulfurDioxide = null;
                    }

                    if(array_key_exists('carbonMonoxide', $dataset))
                    {
                        $measpoint->carbonMonoxide = $dataset['carbonMonoxide'];
                    }
                    else
                    {
                        $measpoint->carbonMonoxide = null;
                    }

                    if(array_key_exists('nitrogenDioxide', $dataset))
                    {
                        $measpoint->nitrogenDioxide = $dataset['nitrogenDioxide'];
                    }
                    else
                    {
                        $measpoint->nitrogenDioxide = null;
                    }

                    if(array_key_exists('humidity', $dataset))
                    {
                        $measpoint->humidity = $dataset['humidity'];
                    }
                    else
                    {
                        $measpoint->humidity = null;
                    }

                    if(array_key_exists('temperature', $dataset))
                    {
                        $measpoint->temperature = $dataset['temperature'];
                    }
                    else
                    {
                        $measpoint->temperature = null;
                    }
                    $measpoint->save();
                }
            }
        }
    }

    private function getApiToken()
    {
        $guzzle = new Client();
        $response = $guzzle->request('POST',
            'https://sso2.vmz.services/auth/realms/SmarterTogether/protocol/openid-connect/token',
            [
                'auth' => [
                    $this->argument('clientid'),
                    $this->argument('clientsecret')
                ],
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'scope' => 'ReadLampposts smarterTogether ReadLamppostMeasurements',
                ],
            ]);

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);
        return $body['access_token'];
    }
}
