<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Career;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Post;
use App\Models\Holiday;
use Carbon\Carbon;
use App\Models\SssContribution;
use App\Models\PagibigContribution;
use App\Models\PhilhealthContribution;
use App\Models\SssLoan;
use App\Models\PagibigLoan;
use App\Models\CashAdvance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemUpdate;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Count leaves by status.
     *
     * @param string $status
     * @return int
     */
    public function countLeavesByStatus($status)
    {
        return Leave::where('status', $status)->count();
    }

    /**
     * Fetch detailed leave information for a user and employee with matching first names.
     *
     * @return array|null
     */
    public function fetchLeaveDetails()
    {
        // Assuming auth user's first name matches employee's first name
        $authUser = auth()->user();
        $employee = Employee::where('first_name', $authUser->first_name)->first();

        if (!$employee) {
            return null; // No matching employee found
        }

        // Fetch leave details for the employee
        $leaveDetails = [
            'sick_leave' => $employee->sick_leave,
            'emergency_leave' => $employee->emergency_leave,
            'vacation_leave' => $employee->vacation_leave,
        ];

        return $leaveDetails;
    }

    /**
     * Convert month number to month name.
     *
     * @param int $monthNumber
     * @return string
     */
    protected function monthName($monthNumber)
    {
        return Carbon::create()->month($monthNumber)->format('F');
    }

    /**
     * Get upcoming holidays and check if today is a holiday.
     *
     * @return array
     */
    public function upcomingHolidays()
    {
        // Get today's date in Y-m-d format
        $today = Carbon::today();
        $todayFormatted = $today->format('Y-m-d');
        
        // Log today's date for debugging
        Log::info("Checking holidays for date: {$todayFormatted}");
        
        // Fetch all holidays with explicit date formatting
        $holidays = Holiday::get()->map(function($holiday) {
            // Ensure date is properly parsed and formatted
            $holiday->formatted_date = Carbon::parse($holiday->date)->format('Y-m-d');
            return $holiday;
        });
        
        // Log all holidays for debugging
        Log::info("Found " . $holidays->count() . " total holidays");
        
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Filter upcoming holidays that are in the current month
        $upcomingHolidays = $holidays->filter(function ($holiday) use ($currentMonth, $currentYear, $todayFormatted) {
            $holidayDate = Carbon::parse($holiday->date);
            return $holidayDate->isFuture() && 
                    $holidayDate->month === $currentMonth && 
                    $holidayDate->year === $currentYear &&
                    $holiday->formatted_date !== $todayFormatted; // Exclude today
        });

        // Check if today is a holiday with multiple approaches for better matching
        $todayHoliday = $holidays->first(function ($holiday) use ($todayFormatted) {
            return $holiday->formatted_date === $todayFormatted;
        });

        // If not found using the formatted approach, try direct database query
        if (!$todayHoliday) {
            $todayHoliday = Holiday::whereDate('date', $todayFormatted)->first();
        }
        
        // Log the result of today's holiday check
        if ($todayHoliday) {
            Log::info("Today is a holiday: {$todayHoliday->title}");
        } else {
            Log::info("No holiday found for today");
        }

        // Get the month name
        $monthName = $this->monthName($currentMonth);

        return [
            'upcomingHolidays' => $upcomingHolidays,
            'todayHoliday' => $todayHoliday,
            'currentMonthName' => $monthName,
        ];
    }

    /**
     * Get upcoming birthdays and check if today is someone's birthday.
     *
     * @return array
     */
    public function upcomingBirthdays()
    {
        // Get the current month and day
        $currentMonth = Carbon::now()->month;
        $currentDay = Carbon::now()->day;

        // Get the authenticated user
        $authUser = auth()->user();

        // Get employees whose birthdays are in the current month
        $upcomingBirthdays = Employee::whereMonth('birth_date', $currentMonth)
            ->whereDay('birth_date', '>', $currentDay)
            ->get();

        // Filter employees whose birthday is today
        $todaysBirthdays = Employee::whereMonth('birth_date', $currentMonth)
            ->whereDay('birth_date', $currentDay)
            ->get();

        // Check if today is the authenticated user's birthday
        $isUserBirthday = $todaysBirthdays->contains('email_address', $authUser->email);

        // Generate a greeting if it's the user's birthday
        $greeting = $isUserBirthday ? "It's your birthday! Happy Birthday, {$authUser->name}!" : null;

        // Get the month name
        $currentMonthName = $this->monthName($currentMonth);

        return [
            'upcomingBirthdays' => $upcomingBirthdays,
            'todaysBirthdays' => $todaysBirthdays,
            'currentMonthName' => $currentMonthName,
            'greeting' => $greeting,
        ];
    }

    /**
     * Count the total number of careers.
     *
     * @return int
     */
    public function countCareers()
    {
        return Career::count();
    }

    /**
     * Count employees by department.
     *
     * @return array
     */
    public function countEmployeesByDepartment()
    {
        return Employee::select('department_id', DB::raw('count(*) as count'))
            ->groupBy('department_id')
            ->with('department') // Assuming you have a relationship defined
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->department->name => $item->count]; // Adjust based on your department model
            })->toArray();
    }

    /**
     * Get analytics for SSS, Pagibig, Philhealth, and other related data.
     *
     * @return array
     */
    public function getAnalytics()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;

        // SSS Analytics
        $sssContributions = SssContribution::whereYear('date', $currentYear)->get();
        $sssAnalytics = [
            'total_contributions' => $sssContributions->sum('sss_contribution'),
            'average_contribution' => $sssContributions->avg('sss_contribution'),
            'contribution_count' => $sssContributions->count(),
            'monthly_trend' => $this->getMonthlyTrend($sssContributions, 'sss_contribution'),
        ];

        // Pagibig Analytics
        $pagibigContributions = PagibigContribution::whereYear('date', $currentYear)->get();
        $pagibigAnalytics = [
            'total_contributions' => $pagibigContributions->sum('pagibig_contribution'),
            'average_contribution' => $pagibigContributions->avg('pagibig_contribution'),
            'contribution_count' => $pagibigContributions->count(),
            'monthly_trend' => $this->getMonthlyTrend($pagibigContributions, 'pagibig_contribution'),
        ];

        // Philhealth Analytics
        $philhealthContributions = PhilhealthContribution::whereYear('date', $currentYear)->get();
        $philhealthAnalytics = [
            'total_contributions' => $philhealthContributions->sum('philhealth_contribution'),
            'average_contribution' => $philhealthContributions->avg('philhealth_contribution'),
            'contribution_count' => $philhealthContributions->count(),
            'monthly_trend' => $this->getMonthlyTrend($philhealthContributions, 'philhealth_contribution'),
        ];

        // Cash Advance Analytics
        $cashAdvances = CashAdvance::whereYear('created_at', $currentYear)->get();
        $cashAdvanceAnalytics = [
            'total_amount' => $cashAdvances->sum('cash_advance_amount'),
            'average_amount' => $cashAdvances->avg('cash_advance_amount'),
            'advance_count' => $cashAdvances->count(),
            'monthly_trend' => $this->getMonthlyTrend($cashAdvances, 'cash_advance_amount'),
        ];

        // Loan Analytics (updated to include Cash Advances)
        $sssLoans = SssLoan::whereYear('created_at', $currentYear)->get();
        $pagibigLoans = PagibigLoan::whereYear('created_at', $currentYear)->get();
        $loanAnalytics = [
            'sss_loans' => [
                'total_amount' => $sssLoans->sum('loan_amount'),
                'average_amount' => $sssLoans->avg('loan_amount'),
                'loan_count' => $sssLoans->count(),
                'monthly_trend' => $this->getMonthlyTrend($sssLoans, 'loan_amount'),
            ],
            'pagibig_loans' => [
                'total_amount' => $pagibigLoans->sum('loan_amount'),
                'average_amount' => $pagibigLoans->avg('loan_amount'),
                'loan_count' => $pagibigLoans->count(),
                'monthly_trend' => $this->getMonthlyTrend($pagibigLoans, 'loan_amount'),
            ],
            'cash_advances' => [
                'total_amount' => $cashAdvances->sum('cash_advance_amount'),
                'average_amount' => $cashAdvances->avg('cash_advance_amount'),
                'advance_count' => $cashAdvances->count(),
                'monthly_trend' => $this->getMonthlyTrend($cashAdvances, 'cash_advance_amount'),
            ],
        ];

        return [
            'sss' => $sssAnalytics,
            'pagibig' => $pagibigAnalytics,
            'philhealth' => $philhealthAnalytics,
            'cash_advance' => $cashAdvanceAnalytics,
            'loans' => $loanAnalytics,
        ];
    }

    /**
     * Get monthly trend for contributions.
     *
     * @param \Illuminate\Support\Collection $contributions
     * @param string $contributionField
     * @return array
     */
    private function getMonthlyTrend($items, $amountField)
    {
        $trend = array_fill(1, 12, 0); // Initialize all months with 0
        foreach ($items as $item) {
            $month = Carbon::parse($item->created_at)->month;
            $trend[$month] += $item->$amountField;
        }
        return array_values($trend); // Convert to indexed array
    }

    /**
     * Get active system updates.
     *
     * @return array
     */
    public function getSystemUpdates()
    {
        $updates = SystemUpdate::where('is_active', true)
            ->orderBy('published_at', 'desc')
            ->get();

        return [
            'updates' => $updates,
            'hasUnreadUpdates' => $updates->isNotEmpty()
        ];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Add this line to fetch the employee data for the authenticated user
        $employees = Employee::where('email_address', Auth::user()->email)->get();
        $employeeActive = Employee::where('employee_status', 'Active')->count();

        // Get counts of employees by department
        $employeesByDepartment = $this->countEmployeesByDepartment();

        // Fetch upcoming holidays and check if today is a holiday
        $holidaysData = $this->upcomingHolidays();

        // Extract the data
        $upcomingHolidays = $holidaysData['upcomingHolidays'];
        $todayHoliday = $holidaysData['todayHoliday'];
        $currentMonthName = $holidaysData['currentMonthName'];

        // Fetch upcoming birthdays and check if today is someone's birthday
        $birthdaysData = $this->upcomingBirthdays();

        // Extract the data
        $upcomingBirthdays = $birthdaysData['upcomingBirthdays'];
        $todaysBirthdays = $birthdaysData['todaysBirthdays'];
        $currentMonthNameBirthdays = $birthdaysData['currentMonthName'];
        $greeting = $birthdaysData['greeting'];

        // Get counts of models
        $userCount = User::count();
        $employeeCount = Employee::count();
        $attendanceAllCount = Attendance::count();
        // Get attendance count up to the current date
        $attendanceCount = Attendance::whereDate('date_attended', '=', now())->count();
        // Get counts of leaves by status
        $pendingLeavesCount = $this->countLeavesByStatus('pending');
        $approvedLeavesCount = $this->countLeavesByStatus('approved');
        $rejectedLeavesCount = $this->countLeavesByStatus('rejected');
        $leaveCount = Leave::count();

        // Delete posts where date_end is equal to today
        Post::whereDate('date_end', '=', Carbon::today())->delete();

        // Get the latest 5 posts ordered by the most recent, where the post date is greater than or equal to tomorrow
        $latestPosts = Post::whereDate('date_start', '>=', Carbon::tomorrow())
            ->orderBy('created_at', 'desc')
            ->take(5) // You can adjust this to 5 to display the latest 5 posts
            ->get();

        // Get posts where date_start is equal to today
        $todayPosts = Post::whereDate('date_start', '=', Carbon::today())->get();

        // Fetch detailed leave information for the authenticated user and employee
        $leaveDetails = $this->fetchLeaveDetails();

        // Get count of careers
        $careerCount = $this->countCareers();

        // Get analytics
        $analytics = $this->getAnalytics();

        // Get system updates
        $systemUpdates = $this->getSystemUpdates();

        // Pass the counts, sum, monthly contributions, latest posts, leave details, holidays, birthdays, and career count to the view
        return view('home', compact(
            'employees',
            'userCount',
            'employeeCount',
            'attendanceAllCount',
            'attendanceCount',
            'leaveCount',
            'pendingLeavesCount',
            'approvedLeavesCount',
            'rejectedLeavesCount',
            'latestPosts',
            'todayPosts',
            'leaveDetails',
            'upcomingHolidays',
            'todayHoliday',
            'currentMonthName',
            'upcomingBirthdays',
            'todaysBirthdays',
            'currentMonthNameBirthdays',
            'employeesByDepartment',
            'greeting',
            'careerCount',
            'analytics',
            'employeeActive',
            'systemUpdates'
        ));
    }
}