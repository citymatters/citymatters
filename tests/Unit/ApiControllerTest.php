<?php

namespace Tests\Unit;

use App\Measpoint;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    protected $faker;

    protected function setUp()
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Deletes all measpoints from the database
     */
    protected function resetDatabase() {
        $measpoints = Measpoint::all();
        foreach($measpoints as $measpoint)
        {
            $measpoint->delete();
        }
    }

    /**
     * Tests the /api/add endpoint by sending an example json and retrieving the saved Measpoint from the database
     */
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

    /**
     * Generates 1000 measpoints and retrieves them via the /api/measpoints/byArea endpoint (smoke test)
     */
    public function testMeaspointsByArea() {
        $this->resetDatabase();
        $measpoints = factory(Measpoint::class, 1000)->create();

        $response = $this->json('GET',
            '/api/measpoints/byArea/47.9/7.6/48/7.9');
        $response->assertStatus(200);
        $response->assertJsonCount(1000);

        // reversing the arguments to test if everything still works
        $response = $this->json('GET',
            '/api/measpoints/byArea/48/7.9/47.9/7.6');
        $response->assertStatus(200);
        $response->assertJsonCount(1000);
    }

    /**
     * Generates 1000 measpoints and retrieves them via the /api/measpoints/byArea endpoint and retrieves
     * only a portion of that
     */
    public function testMeaspointsByAreaFilterArea()
    {
        $this->resetDatabase();
        $measpoints = factory(Measpoint::class, 500)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.95, 48),
                'lon' => $this->faker->randomFloat(6, 7.65, 7.9),
            ]
        );

        $measpoints = factory(Measpoint::class, 500)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.9, 47.95),
                'lon' => $this->faker->randomFloat(6, 7.6, 7.65),
            ]
        );

        $response = $this->json('GET',
            '/api/measpoints/byArea/47.9/7.6/47.95/7.65');
        $response->assertStatus(200);
        $response->assertJsonCount(500);
    }

    /**
     * Generates 1000 measpoints and retrieves them via the /api/measpoints/byAreaAndTime endpoint (smoke test)
     */
    public function testMeaspointsByAreaAndTime() {
        $this->resetDatabase();
        $measpoints = factory(Measpoint::class, 1000)->create();

        $response = $this->json('GET',
            '/api/measpoints/byAreaAndTime/47.9/7.6/48/7.9/from/'
            . now()->subMonth()->subDay()->timestamp
            . '/to/'
            . now()->addDay()->timestamp);
        $response->assertStatus(200);
        $response->assertJsonCount(1000);

        // reversing the arguments to test if everything still works
        $response = $this->json('GET',
            '/api/measpoints/byAreaAndTime/48/7.9/47.9/7.6/from/'
            . now()->addDay()->timestamp
            . '/to/'
            . now()->subMonth()->subDay()->timestamp);
        $response->assertStatus(200);
        $response->assertJsonCount(1000);
    }

    /**
     * Generates 1000 measpoints and retrieves them via the /api/measpoints/byAreaAndTime endpoint
     * The results should be filtered by area
     */
    public function testMeaspointsByAreaAndTimeFilterArea() {
        $this->resetDatabase();
        $measpoints = factory(Measpoint::class, 500)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.95, 48),
                'lon' => $this->faker->randomFloat(6, 7.65, 7.9),
            ]
        );

        $measpoints = factory(Measpoint::class, 500)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.9, 47.95),
                'lon' => $this->faker->randomFloat(6, 7.6, 7.65),
            ]
        );

        $response = $this->json('GET',
            '/api/measpoints/byAreaAndTime/47.9/7.6/47.95/7.65/from/'
            . now()->subMonth()->subDay()->timestamp
            . '/to/'
            . now()->addDay()->timestamp);
        $response->assertStatus(200);
        $response->assertJsonCount(500);
    }

    /**
     * Generates 1000 measpoints and retrieves them via the /api/measpoints/byAreaAndTime endpoint
     * The results should be filtered by time
     */
    public function testMeaspointsByAreaAndTimeFilterTime() {
        $this->resetDatabase();

        $start = now()->subDays(30)->timestamp;
        $middle = now()->subDays(15)->timestamp;
        $end = now()->addDay()->timestamp;

        $measpoints = factory(Measpoint::class, 500)->create(
            [
                'datetime' => $this->faker->numberBetween($start, $middle),
            ]
        );

        $measpoints = factory(Measpoint::class, 500)->create(
            [
                'datetime' => $this->faker->numberBetween($middle, $end),
            ]
        );

        $endpoint = '/api/measpoints/byAreaAndTime/47.9/7.6/48/7.9/from/'
            . $start
            . '/to/'
            . $middle;

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(500);
    }
}
