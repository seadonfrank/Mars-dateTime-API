<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DateTimeTest extends TestCase
{
   
    /**
     * Testing response data and format.
     *
     * @return void
     */

    private const UTC = "2020-03-10 22:25:13";
    private const MSD = 51968.74622;
    private const MCT = "17:54:33";

    public function testingDataFormat()
    {
        $response = $this->json('POST', '/api/v1/datetime', ['datetime' => self::UTC]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'msd' => self::MSD,
                'mct' => self::MCT,
            ]);
    }

}
