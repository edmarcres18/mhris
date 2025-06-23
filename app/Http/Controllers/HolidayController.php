<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Add this line for HTTP requests
use Carbon\Carbon; // Make sure Carbon is imported
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\HolidayImport;
use App\Exports\HolidayExport;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:holiday-list|holiday-create|holiday-edit|holiday-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:holiday-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:holiday-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:holiday-delete'], ['only' => ['destroy']]);
    }

    public function index()
    {
        $holidays = Holiday::all();
        return view('holidays.index', compact('holidays'));
    }

    public function create()
    {
        return view('holidays.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string', // Validate the type
        ]);

        Holiday::create($request->only('title', 'date', 'type')); // Include type in the creation

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        return view('holidays.show', compact('holiday'));
    }

    public function edit($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->date = Carbon::parse($holiday->date); // Ensure it's a Carbon instance

        return view('holidays.edit', compact('holiday'));
    }

    public function update(Request $request, Holiday $holiday)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string', // Validate the type
        ]);

        $holiday->update($request->only('title', 'date', 'type')); // Include type in the update

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday updated successfully.');
    }

    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('holidays.index')
            ->with('success', 'Holiday deleted successfully.');
    }

    public function fetchHolidaysFromGoogleCalendar()
    {
        // URL of the public Google Calendar for Philippine Holidays
        $icalUrl = 'https://calendar.google.com/calendar/ical/en.philippines%23holiday%40group.v.calendar.google.com/public/basic.ics';

        try {
            // Fetch the iCal feed
            $icalData = file_get_contents($icalUrl);

            if ($icalData === false) {
                throw new \Exception('Failed to fetch iCal data.');
            }

            // Split the data into lines
            $lines = explode("\n", $icalData);
            $holidays = [];
            $event = null;
            $currentYear = Carbon::now()->year;

            foreach ($lines as $line) {
                $line = trim($line);

                if ($line === 'BEGIN:VEVENT') {
                    $event = [];
                } elseif ($line === 'END:VEVENT') {
                    if (!empty($event) && isset($event['title']) && isset($event['date']) && isset($event['type'])) {
                        // Only add the event if it belongs to the current year
                        $eventDate = Carbon::parse($event['date']);
                        if ($eventDate->year == $currentYear) {
                            $holidays[] = $event;
                        }
                    }
                    $event = null;
                } elseif ($event !== null) {
                    if (strpos($line, 'SUMMARY:') === 0) {
                        $event['title'] = substr($line, 8);
                    } elseif (strpos($line, 'DTSTART;VALUE=DATE:') === 0) {
                        $dateStr = substr($line, 19);
                        $event['date'] = Carbon::createFromFormat('Ymd', $dateStr)->toDateString();
                    } elseif (strpos($line, 'DESCRIPTION:') === 0) {
                        // Assuming the type is included in the description
                        $description = substr($line, 12);
                        if (strpos($description, 'Special Non-Working') !== false) {
                            $event['type'] = 'Special Non-Working Holiday';
                        } elseif (strpos($description, 'Regular Holiday') !== false) {
                            $event['type'] = 'Regular Holiday';
                        } elseif (strpos($description, 'Common Local') !== false) {
                            $event['type'] = 'Special Working Holiday';
                        } else {
                            $event['type'] = 'Regular Holiday'; // Default type if not specified
                        }
                    }
                }
            }

            // Store the holidays in the database
            $storedCount = 0;
            foreach ($holidays as $holiday) {
                $result = Holiday::updateOrCreate(
                    ['title' => $holiday['title'], 'date' => $holiday['date']],
                    [
                        'title' => $holiday['title'],
                        'date' => $holiday['date'],
                        'type' => $holiday['type'] // Store the type
                    ]
                );
                if ($result->wasRecentlyCreated || $result->wasChanged()) {
                    $storedCount++;
                }
            }

            return [
                'success' => true,
                'message' => "Fetched and stored $storedCount holidays successfully.",
                'count' => $storedCount
            ];
        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error fetching holidays from Google Calendar: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to fetch holidays from Google Calendar.',
                'count' => 0
            ];
        }
    }
    public function holidayCalendar()
    {
        $holidays = Holiday::all()->map(function($holiday) {
            return [
                'title' => $holiday->title,
                'date' => Carbon::parse($holiday->date)->format('Y-m-d'),
                'type' => $holiday->type
            ];
        });
        
        // Generate pay days (15th and last day of each month)
        $payDays = [];
        $currentYear = Carbon::now()->year;
        
        // Add pay days for all months in the current year
        for ($month = 1; $month <= 12; $month++) {
            // 15th of the month
            $midMonth = Carbon::createFromDate($currentYear, $month, 15);
            // If 15th falls on weekend, adjust to previous Friday
            if ($midMonth->isWeekend()) {
                $midMonth = $midMonth->copy()->previous(Carbon::FRIDAY);
            }
            
            $payDays[] = [
                'title' => 'Pay Day (Mid Month)',
                'date' => $midMonth->format('Y-m-d'),
                'type' => 'pay-day'
            ];
            
            // Last day of the month
            $lastDay = Carbon::createFromDate($currentYear, $month, 1)->endOfMonth();
            // If last day falls on weekend, adjust to previous Friday
            if ($lastDay->isWeekend()) {
                $lastDay = $lastDay->copy()->previous(Carbon::FRIDAY);
            }
            
            $payDays[] = [
                'title' => 'Pay Day (Month End)',
                'date' => $lastDay->format('Y-m-d'),
                'type' => 'pay-day'
            ];
        }
        
        // Add quarterly sales events
        $quarterlyDates = [
            Carbon::createFromDate($currentYear, 3, 31)->toDateString(),
            Carbon::createFromDate($currentYear, 6, 30)->toDateString(),
            Carbon::createFromDate($currentYear, 9, 30)->toDateString(),
            Carbon::createFromDate($currentYear, 12, 31)->toDateString()
        ];
        
        $quarterlySales = [];
        foreach ($quarterlyDates as $index => $date) {
            $quarterlySales[] = [
                'title' => 'Q' . ($index + 1) . ' Sales Review',
                'date' => $date,
                'type' => 'quarterly-sales'
            ];
        }
        
        return view('holidays.calendar', compact('holidays', 'payDays', 'quarterlySales'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new HolidayImport, $request->file('file'));
            
            return response()->json([
                'success' => true,
                'message' => 'Holidays imported successfully.'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            
            foreach ($failures as $failure) {
                $errors[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);
        } catch (\Exception $e) {
            Log::error('Holiday import error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error importing holidays. Please check your file format.'
            ], 500);
        }
    }

    public function export()
    {
        return Excel::download(new HolidayExport, 'holidays.xlsx');
    }
}
