<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\Contribution;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Hiring;
use App\Models\Career;
use App\Models\Employee;
use App\Models\Department;
use Carbon\Carbon;
use PDF;
use App\Models\SssLoan;
use App\Models\PagibigLoan;
use App\Models\CashAdvance;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    private $pdfConfig = [
        'format' => 'A4',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 20,
        'margin_bottom' => 20,
    ];

    public function index()
    {
        return view('reports.index');
    }

    public function generateLoanReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $loans = Loan::whereBetween('date', [$startDate, $endDate])
                     ->with(['employee', 'employee.department'])
                     ->get();

        $reportData = [
            'loans' => $loans,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalAmount' => $loans->sum('sss_loan') + $loans->sum('pagibig_loan') + $loans->sum('cash_advance'),
            'summary' => [
                'total_loans' => $loans->count(),
                'by_department' => $loans->groupBy('employee.department.name')->map->count(),
                'average_amount' => $loans->average('amount'),
                'highest_loan' => $loans->max('amount'),
                'lowest_loan' => $loans->min('amount'),
            ],
            'generated_at' => now(),
            'generated_by' => auth()->user()->name ?? 'System',
            'company_details' => [
                'name' => config('app.company_name', 'Your Company Name'),
                'address' => config('app.company_address', 'Company Address'),
                'contact' => config('app.company_contact', 'Contact Information'),
            ]
        ];

        $pdf = PDF::loadView('reports.loans', $reportData)
                  ->setPaper('A4')
                  ->setOptions([
                      'isHtml5ParserEnabled' => true,
                      'isPhpEnabled' => true,
                      'isRemoteEnabled' => true,
                      'margin_top' => 20,
                      'margin_bottom' => 20,
                      'margin_left' => 10,
                      'margin_right' => 10,
                      'dpi' => 150,
                  ]);

        $filename = 'loan_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    public function generateContributionReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $contributions = Contribution::whereBetween('date', [$startDate, $endDate])
                                     ->with('employee')
                                     ->get();

        $totalContributions = [
            'sss' => $contributions->sum('sss_contribution'),
            'philhealth' => $contributions->sum('philhealth_contribution'),
            'pagibig' => $contributions->sum('pagibig_contribution'),
            'tin' => $contributions->sum('tin_contribution'),
        ];

        $pdf = PDF::loadView('reports.contributions', compact('contributions', 'startDate', 'endDate', 'totalContributions'));

        return $pdf->download('contribution_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function generateAttendanceReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $query = Attendance::whereBetween('date_attended', [$startDate, $endDate])
                           ->with('employee');

        if ($request->department_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $attendances = $query->get();

        $totalHoursWorked = $attendances->sum('hours_worked');

        $pdf = PDF::loadView('reports.attendances', compact('attendances', 'startDate', 'endDate', 'totalHoursWorked'));

        return $pdf->download('attendance_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function generateLeaveReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $query = Leave::whereBetween('date_from', [$startDate, $endDate])
                      ->with(['employee', 'type']);

        if ($request->department_id) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $leaves = $query->get();

        $leaveTypeCounts = $leaves->groupBy('type.name')->map->count();

        $pdf = PDF::loadView('reports.leaves', compact('leaves', 'startDate', 'endDate', 'leaveTypeCounts'));

        return $pdf->download('leave_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function generateHiringReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $hirings = Hiring::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalPositions = $hirings->count();
        $positionsByDepartment = $hirings->groupBy('department')->map->count();

        $pdf = PDF::loadView('reports.hirings', compact('hirings', 'startDate', 'endDate', 'totalPositions', 'positionsByDepartment'));

        return $pdf->download('hiring_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function generateCareerReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $careers = Career::whereBetween('created_at', [$startDate, $endDate])
                         ->with('hiring')
                         ->get();

        $totalApplications = $careers->count();
        $applicationsByPosition = $careers->groupBy('hiring.position')->map->count();
        $applicationStatus = $careers->groupBy('status')->map->count();

        $pdf = PDF::loadView('reports.careers', compact('careers', 'startDate', 'endDate', 'totalApplications', 'applicationsByPosition', 'applicationStatus'));

        return $pdf->download('career_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function generateDetailedLoanReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $sssLoans = SssLoan::whereBetween('created_at', [$startDate, $endDate])
                                   ->with('employee')
                                   ->get();

        $pagibigLoans = PagibigLoan::whereBetween('created_at', [$startDate, $endDate])
                                           ->with('employee')
                                           ->get();

        $cashAdvances = CashAdvance::whereBetween('created_at', [$startDate, $endDate])
                                           ->with('employee')
                                           ->get();

        $totalSssLoan = $sssLoans->sum('loan_amount');
        $totalPagibigLoan = $pagibigLoans->sum('loan_amount');
        $totalCashAdvance = $cashAdvances->sum('cash_advance_amount');

        $totalAmount = $totalSssLoan + $totalPagibigLoan + $totalCashAdvance;

        $pdf = PDF::loadView('reports.detailed_loans', compact('sssLoans', 'pagibigLoans', 'cashAdvances', 'startDate', 'endDate', 'totalAmount', 'totalSssLoan', 'totalPagibigLoan', 'totalCashAdvance'));

        return $pdf->download('detailed_loan_report_' . $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d') . '.pdf');
    }
}
