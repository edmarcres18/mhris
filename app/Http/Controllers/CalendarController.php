<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $holidays = Holiday::all();
        return view('calendar', compact('holidays'));
    }

    public function getHolidays()
    {
        $holidays = Holiday::all()->map(function ($holiday) {
            return [
                'title' => $holiday->title,
                'start' => $holiday->date,
                'allDay' => true,
                'color' => $this->getColorByType($holiday->type),
            ];
        });

        return response()->json($holidays);
    }

    private function getColorByType($type)
    {
        $colors = [
            'public' => '#FF0000',
            'religious' => '#00FF00',
            'other' => '#0000FF',
        ];

        return $colors[$type] ?? '#CCCCCC';
    }
}

