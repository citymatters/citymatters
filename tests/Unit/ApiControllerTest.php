<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

namespace Tests\Unit;

use App\Measpoint;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Deletes all measpoints from the database.
     */
    protected function resetDatabase()
    {
        $measpoints = Measpoint::all();
        foreach ($measpoints as $measpoint) {
            $measpoint->delete();
        }
    }

    /**
     * Tests the /api/add endpoint by sending an example json and retrieving the saved Measpoint from the database.
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
                    'type' => 'Temperature',
                    'unit' => 'celsius',
                    'value' => 22.3,
                ],
                [
                    'type' => 'Humidity',
                    'unit' => 'RH',
                    'value' => 56.0,
                ],
                [
                    'type' => 'PM2.5',
                    'unit' => 'µg/m3',
                    'value' => 5.34,
                ],
                [
                    'type' => 'PM10',
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

        $measpoint = Measpoint::where('sensor_id', $payload['sensor'])->first();

        $this->assertEquals($payload['lat'], $measpoint->lat);
        $this->assertEquals($payload['lon'], $measpoint->lon);
        $this->assertEquals(Carbon::createFromTimestamp($payload['datetime']), $measpoint->datetime);
        foreach($measpoint->values as $value) {
            $found = false;
            foreach($payload['data'] as $data) {
                $found = ($data['type'] == $value->type
                && $data['unit'] == $value->unit
                && $data['value'] == $value->value);
                if($found) {
                    break;
                }
            }
            $this->assertTrue($found);
        }
        $measpoint->delete();
    }

    /**
     * Generates 10 measpoints and retrieves them via the /api/measpoints/byArea endpoint (smoke test).
     */
    public function testMeaspointsByArea()
    {
        $measpoints = factory(Measpoint::class, 10)->create();

        $response = $this->json('GET',
            '/api/measpoints/byArea/47.9/7.6/48/7.9');
        $response->assertStatus(200);
        $response->assertJsonCount(10);

        // reversing the arguments to test if everything still works
        $response = $this->json('GET',
            '/api/measpoints/byArea/48/7.9/47.9/7.6');
        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    /**
     * Generates 10 measpoints and retrieves them via the /api/measpoints/byArea endpoint and retrieves
     * only a portion of that.
     */
    public function testMeaspointsByAreaFilterArea()
    {
        $measpoints = factory(Measpoint::class, 5)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.95, 48),
                'lon' => $this->faker->randomFloat(6, 7.65, 7.9),
            ]
        );

        $measpoints = factory(Measpoint::class, 5)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.9, 47.95),
                'lon' => $this->faker->randomFloat(6, 7.6, 7.65),
            ]
        );

        $response = $this->json('GET',
            '/api/measpoints/byArea/47.9/7.6/47.95/7.65');
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    /**
     * Generates 10 measpoints and retrieves them via the /api/measpoints/byAreaAndTime endpoint (smoke test).
     */
    public function testMeaspointsByAreaAndTime()
    {
        $measpoints = factory(Measpoint::class, 10)->create();

        $response = $this->json('GET',
            '/api/measpoints/byAreaAndTime/47.9/7.6/48/7.9/from/'
            .now()->subMonth()->subDay()->timestamp
            .'/to/'
            .now()->addDay()->timestamp);
        $response->assertStatus(200);
        $response->assertJsonCount(10);

        // reversing the arguments to test if everything still works
        $response = $this->json('GET',
            '/api/measpoints/byAreaAndTime/48/7.9/47.9/7.6/from/'
            .now()->addDay()->timestamp
            .'/to/'
            .now()->subMonth()->subDay()->timestamp);
        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    /**
     * Generates 10 measpoints and retrieves them via the /api/measpoints/byAreaAndTime endpoint
     * The results should be filtered by area.
     */
    public function testMeaspointsByAreaAndTimeFilterArea()
    {
        $measpoints = factory(Measpoint::class, 5)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.95, 48),
                'lon' => $this->faker->randomFloat(6, 7.65, 7.9),
            ]
        );

        $measpoints = factory(Measpoint::class, 5)->create(
            [
                'lat' => $this->faker->randomFloat(6, 47.9, 47.95),
                'lon' => $this->faker->randomFloat(6, 7.6, 7.65),
            ]
        );

        $response = $this->json('GET',
            '/api/measpoints/byAreaAndTime/47.9/7.6/47.95/7.65/from/'
            .now()->subMonth()->subDay()->timestamp
            .'/to/'
            .now()->addDay()->timestamp);
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }

    /**
     * Generates 10 measpoints and retrieves them via the /api/measpoints/byAreaAndTime endpoint
     * The results should be filtered by time.
     */
    public function testMeaspointsByAreaAndTimeFilterTime()
    {
        $subStart = 30;
        $subMiddle = 15;
        $start = now()->subDays($subStart)->timestamp;
        $middle = now()->subDays($subMiddle)->timestamp;
        $end = now()->addDay()->timestamp;

        $measpoints = factory(Measpoint::class, 5)->create(
            [
                'datetime' => now()->subDays($this->faker->numberBetween($subMiddle, $subStart))->toDateTimeString(),
            ]
        );

        $measpoints = factory(Measpoint::class, 5)->create(
            [
                'datetime' => now()->subDays($this->faker->numberBetween(-1, $subMiddle))->toDateTimeString(),
            ]
        );

        $endpoint = '/api/measpoints/byAreaAndTime/47.9/7.6/48/7.9/from/'
            .$start
            .'/to/'
            .$middle;

        $response = $this->json('GET', $endpoint);
        $response->assertStatus(200);
        $response->assertJsonCount(5);
    }
}
