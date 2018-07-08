<?php

namespace Tests\Unit;

use App\Measpoint;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiControllerTest extends TestCase
{
    protected $faker;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    public function testAdd()
    {

        $payload = [
            'sensor' => $this->faker->uuid,
            'lat' => 5.5555,
            'lon' => 6.6666,
            'datetime' => now()->timestamp,
            'data' => [
                [
                    'type' => 'temperature',
                    'unit' => 'celsius',
                    'value' => 22.3,
                ],
                [
                    'type' => 'humidity',
                    'unit' => 'rh',
                    'value' => 56.0,
                ],
                [
                    'type' => 'pm2',
                    'unit' => 'µg/m3',
                    'value' => 5.34,
                ],
                [
                    'type' => 'pm10',
                    'unit' => 'µg/m3',
                    'value' => 14.32,
                ],
            ],
        ];

        $response = $this->json('POST',
            '/api/add',
            $payload
        );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $measpoint = Measpoint::where('sensor', $payload['sensor'])->first();

        $this->assertEquals($payload['lat'], $measpoint->lat);
        $this->assertEquals($payload['lon'], $measpoint->lon);
        $this->assertEquals($payload['datetime'], $measpoint->datetime);
        for($i = 0; $i < 4; $i++)
        {
            $type = $payload['data'][$i]['type'];
            $this->assertEquals($payload['data'][$i]['value'], $measpoint->$type);
        }
    }
}
