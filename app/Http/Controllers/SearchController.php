<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Handle the global search functionality across multiple models
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function globalSearch(Request $request)
    {
        try {
            $query = trim($request->input('query'));
            
            if (empty($query)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No search query provided',
                    'results' => []
                ]);
            }

            // Limit results per category
            $limit = $request->input('limit', 10);
            
            // Search Employees
            $employees = Employee::where(function ($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('company_id', 'LIKE', "%{$query}%")
                  ->orWhere('email_address', 'LIKE', "%{$query}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$query}%");
            })
            ->with(['department', 'position'])
            ->limit($limit)
            ->get()
            ->map(function ($employee) {
                // Get status color
                $statusColor = $this->getEmploymentStatusColor($employee->employment_status);
                $fullName = trim($employee->first_name . ' ' . ($employee->middle_name ? $employee->middle_name[0] . '. ' : '') . $employee->last_name);
                
                return [
                    'id' => $employee->id,
                    'slug' => $employee->slug ?? $employee->id,
                    'type' => 'employee',
                    'type_label' => 'Employee',
                    'title' => $fullName,
                    'subtitle' => $employee->position ? $employee->position->name : 'No Position',
                    'description' => 'ID: ' . $employee->company_id . ' | ' . 
                                  ($employee->department ? $employee->department->name : 'No Department'),
                    'url' => url("/employees/" . ($employee->slug ?? $employee->id)),
                    'image' => $employee->profile ? asset('storage/' . $employee->profile) : null,
                    'status' => $employee->employment_status,
                    'status_color' => $statusColor,
                    'highlight_terms' => [$employee->first_name, $employee->last_name, $employee->company_id],
                    'meta' => [
                        'company_id' => $employee->company_id,
                        'email' => $employee->email_address,
                        'date_hired' => $employee->date_hired ? Carbon::parse($employee->date_hired)->format('M d, Y') : 'N/A',
                        'department' => $employee->department ? $employee->department->name : 'N/A',
                        'position' => $employee->position ? $employee->position->name : 'N/A'
                    ]
                ];
            });
            
            // Search Leaves
            $leaves = Leave::where(function ($q) use ($query) {
                $q->where('status', 'LIKE', "%{$query}%")
                  ->orWhere('reason_to_leave', 'LIKE', "%{$query}%")
                  ->orWhere('payment_status', 'LIKE', "%{$query}%")
                  ->orWhereHas('employee', function ($q) use ($query) {
                      $q->where('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('last_name', 'LIKE', "%{$query}%")
                        ->orWhere('company_id', 'LIKE', "%{$query}%");
                  })
                  ->orWhereHas('type', function ($q) use ($query) {
                      $q->where('name', 'LIKE', "%{$query}%");
                  });
            })
            ->with(['employee', 'type'])
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($leave) {
                // Get status color
                $statusColor = $this->getLeaveStatusColor($leave->status);
                $dateFrom = Carbon::parse($leave->date_from);
                $dateTo = Carbon::parse($leave->date_to);
                $employeeName = $leave->employee ? trim($leave->employee->first_name . ' ' . $leave->employee->last_name) : 'Unknown Employee';
                
                return [
                    'id' => $leave->id,
                    'type' => 'leave',
                    'type_label' => 'Leave Request',
                    'title' => $employeeName,
                    'subtitle' => $leave->type ? $leave->type->name : 'Unknown Type',
                    'description' => 'Status: ' . ucfirst($leave->status) . ' | ' . 
                                  $dateFrom->format('M d, Y') . ' - ' . 
                                  $dateTo->format('M d, Y'),
                    'url' => url("/leaves/{$leave->id}"),
                    'image' => null,
                    'status' => $leave->status,
                    'status_color' => $statusColor,
                    'highlight_terms' => [$employeeName, $leave->status, $leave->type ? $leave->type->name : ''],
                    'meta' => [
                        'days' => $dateFrom->diffInDays($dateTo) + 1,
                        'payment_status' => $leave->payment_status,
                        'reason' => Str::limit($leave->reason_to_leave, 50),
                        'date_requested' => Carbon::parse($leave->created_at)->format('M d, Y'),
                        'company_id' => $leave->employee ? $leave->employee->company_id : 'N/A'
                    ]
                ];
            });
            
            // Search Attendance
            $attendances = Attendance::where(function ($q) use ($query) {
                $q->where('remarks', 'LIKE', "%{$query}%")
                  ->orWhere('date_attended', 'LIKE', "%{$query}%")
                  ->orWhereHas('employee', function ($q) use ($query) {
                      $q->where('first_name', 'LIKE', "%{$query}%")
                        ->orWhere('last_name', 'LIKE', "%{$query}%")
                        ->orWhere('company_id', 'LIKE', "%{$query}%");
                  });
            })
            ->with(['employee'])
            ->limit($limit)
            ->orderBy('date_attended', 'desc')
            ->get()
            ->map(function ($attendance) {
                // Get status color based on remarks
                $statusColor = $this->getAttendanceStatusColor($attendance->remarks);
                $employeeName = $attendance->employee ? trim($attendance->employee->first_name . ' ' . $attendance->employee->last_name) : 'Unknown Employee';
                $attendanceDate = Carbon::parse($attendance->date_attended);
                
                return [
                    'id' => $attendance->id,
                    'type' => 'attendance',
                    'type_label' => 'Attendance Record',
                    'title' => $employeeName,
                    'subtitle' => 'Attendance - ' . $attendanceDate->format('l'),
                    'description' => 'Date: ' . $attendanceDate->format('M d, Y') . 
                                  ' | Status: ' . $attendance->remarks,
                    'url' => url("/attendances/{$attendance->id}"),
                    'image' => null,
                    'status' => $attendance->remarks,
                    'status_color' => $statusColor,
                    'highlight_terms' => [$employeeName, $attendance->remarks, $attendanceDate->format('Y-m-d')],
                    'meta' => [
                        'time_in' => $attendance->time_in ? Carbon::parse($attendance->time_in)->format('h:i A') : 'N/A',
                        'time_out' => $attendance->time_out ? Carbon::parse($attendance->time_out)->format('h:i A') : 'N/A',
                        'hours_worked' => $attendance->hours_worked,
                        'day_of_week' => $attendanceDate->format('l'),
                        'company_id' => $attendance->employee ? $attendance->employee->company_id : 'N/A',
                        'late_time' => $attendance->late_time ?? 'None',
                        'under_time' => $attendance->under_time ?? 'None'
                    ]
                ];
            });
            
            // Combine all results
            $allResults = [
                'employees' => $employees,
                'leaves' => $leaves,
                'attendances' => $attendances,
                'total_count' => count($employees) + count($leaves) + count($attendances),
                'query' => $query
            ];
            
            return response()->json([
                'success' => true,
                'message' => 'Search results fetched successfully',
                'results' => $allResults,
                'timestamp' => now()->timestamp
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during search: ' . $e->getMessage(),
                'results' => []
            ], 500);
        }
    }
    
    /**
     * Get color for employment status
     * 
     * @param string $status
     * @return string
     */
    private function getEmploymentStatusColor($status)
    {
        return match(strtoupper($status ?? '')) {
            'REGULAR', 'REGULAR EMPLOYEE' => 'success',
            'PROBITIONARY' => 'warning',
            'TRAINEE' => 'info',
            default => 'secondary'
        };
    }
    
    /**
     * Get color for leave status
     * 
     * @param string $status
     * @return string
     */
    private function getLeaveStatusColor($status)
    {
        return match(strtolower($status ?? '')) {
            'approved' => 'success',
            'rejected' => 'danger',
            'pending' => 'warning',
            'validated' => 'info',
            default => 'secondary'
        };
    }
    
    /**
     * Get color for attendance status
     * 
     * @param string $remarks
     * @return string
     */
    private function getAttendanceStatusColor($remarks)
    {
        return match($remarks ?? '') {
            'Present' => 'success',
            'Absent' => 'danger',
            'Late' => 'warning',
            'UnderTime' => 'warning',
            'On Leave' => 'info',
            'Sunday', 'Saturday', 'Holiday' => 'primary',
            'No Clock Out' => 'danger',
            'Half Day' => 'warning',
            default => 'secondary'
        };
    }
}
