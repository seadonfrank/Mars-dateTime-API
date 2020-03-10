<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/*
|--------------------------------------------------------------------------
| Date & Time Controller
|--------------------------------------------------------------------------
|
| This controller handles Earth's UTC date and time conversion to Mars Sol Date (MSD) and | Martian Coordinated Time (MTC)
|
*/

class DateTimeController extends Controller
{
    private const SECONDS_PER_SOL = 88775.244147;
    private const LEAP_SECONDS = 37;
    private const MSD_PRECISION = 5;

    /**
     * Recieves the time on EARTH in UTC as an input and return two values: 
     the Mars Sol Date (MSD) and Martian Coordinated Time (MTC)
     *
     * @param  Request  $requst
     * @return array
     */
    public function DateTime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'datetime' => 'required|date|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return array("success" => false, "message"=> $validator->errors()->first('datetime'));
        }

        $dateTime = new \Datetime($request->get('datetime'));

        $timestamp = $dateTime->getTimestamp();

        $marsSolDate = ($timestamp + self::LEAP_SECONDS) / self::SECONDS_PER_SOL + 34127.2954262;

        $marsSolDate = round($marsSolDate, self::MSD_PRECISION, PHP_ROUND_HALF_UP);

        $martianCoordinatedTime = round(fmod($marsSolDate, 1) * 86400, 0, PHP_ROUND_HALF_UP);

        $martianCoordinatedTime = gmdate('H:i:s', (int) $martianCoordinatedTime);
        
        return array("success" => true, "msd"=>$marsSolDate, "mct"=>$martianCoordinatedTime);
    }
}