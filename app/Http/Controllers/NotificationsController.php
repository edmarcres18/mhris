<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\CashAdvance;
use Illuminate\Support\Facades\Auth;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use App\Models\OvertimePay;
use App\Models\User;
use App\Models\NightPremium;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Spatie\Permission\Models\Permission;

class NotificationsController extends Controller
{
    private $notifications = [
        'leave_requests' => [],
        'cash_advances' => [],
        'leave_approved' => [],
        'leave_validated' => [],
        'leave_rejected' => [],
        'cash_advance_active' => [],
        'cash_advance_declined' => [],
        'overtime_pay_pending' => [],
        'overtime_pay_approved' => [],
        'overtime_pay_rejected' => [],
        'night_premium_pending' => [],
        'night_premium_approved' => [],
        'night_premium_rejected' => [],
    ];

    // Method to fetch notifications data
    public function getNotificationsData(Request $request)
    {
        try {
            $this->generateNotifications();
            $allNotifications = $this->flattenNotifications();

            $response = [
                'notifications' => array_map(function($notification) {
                    $details = isset($notification['data']) ? $notification['data'] : [];
                    $type = $details['type'] ?? '';
                    
                    $formattedDetails = [];
                    if (strpos($type, 'leave') !== false) {
                        $formattedDetails = [
                            'type' => 'leave',
                            'employee_name' => $details['employee_name'] ?? '',
                            'start_date' => $details['start_date'] ?? '',
                            'end_date' => $details['end_date'] ?? '',
                            'reason' => $details['reason'] ?? '',
                            'status' => ucfirst($details['status'] ?? 'pending'),
                            'approved_by' => $details['approved_by'] ?? null,
                            'approved_at' => $details['approved_at'] ?? null,
                            'leave_type' => $details['leave_type'] ?? '',
                            'duration' => $details['duration'] ?? '',
                            'payment_status' => $details['payment_status'] ?? '',
                            'department' => $details['department'] ?? ''
                        ];
                    } elseif (strpos($type, 'cash_advance') !== false) {
                        $formattedDetails = [
                            'type' => 'cash_advance',
                            'employee_name' => $details['employee_name'] ?? '',
                            'amount' => $details['amount'] ?? 0,
                            'reason' => $details['reason'] ?? '',
                            'status' => ucfirst($details['status'] ?? 'pending'),
                            'approved_by' => $details['approved_by'] ?? null,
                            'approved_at' => $details['approved_at'] ?? null
                        ];
                    } elseif (strpos($type, 'overtime') !== false) {
                        $formattedDetails = [
                            'type' => 'overtime',
                            'employee_name' => $details['employee_name'] ?? '',
                            'date' => $details['date'] ?? '',
                            'overtime_hours' => $details['overtime_hours'] ?? 0,
                            'overtime_rate' => $details['overtime_rate'] ?? 0,
                            'overtime_pay' => $details['overtime_pay'] ?? 0,
                            'status' => ucfirst($details['status'] ?? 'pending'),
                            'approved_by' => $details['approved_by'] ?? null,
                            'approved_at' => $details['approved_at'] ?? null,
                            'department' => $details['department'] ?? '',
                            'rejection_reason' => $details['rejection_reason'] ?? null
                        ];
                    } elseif (strpos($type, 'night_premium') !== false) {
                        $formattedDetails = [
                            'type' => 'night_premium',
                            'employee_name' => $details['employee_name'] ?? '',
                            'date' => $details['date'] ?? '',
                            'time_in' => $details['time_in'] ?? null,
                            'time_out' => $details['time_out'] ?? null,
                            'night_hours' => $details['night_hours'] ?? 0,
                            'night_rate' => $details['night_rate'] ?? 0,
                            'night_premium_pay' => $details['night_premium_pay'] ?? 0,
                            'status' => ucfirst($details['status'] ?? 'pending'),
                            'approved_by' => $details['approved_by'] ?? null,
                            'approved_at' => $details['approved_at'] ?? null,
                            'department' => $details['department'] ?? '',
                            'rejection_reason' => $details['rejection_reason'] ?? null,
                            'reason' => $details['reason'] ?? 'No reason provided'
                        ];
                    }

                    return [
                        'id' => $notification['id'],
                        'title' => $this->getNotificationTitle($notification),
                        'message' => $notification['text'],
                        'created_at' => Carbon::createFromTimestamp($notification['timestamp'])->toIso8601String(),
                        'read_at' => null,
                        'icon' => $notification['icon'],
                        'details' => $formattedDetails,
                        'type' => $type
                    ];
                }, $allNotifications),
                'unread_count' => $this->countTotalNotifications()
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Error in getNotificationsData: ' . $e->getMessage());
            return response()->json([
                'notifications' => [],
                'unread_count' => 0,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Generate all notifications
    private function generateNotifications()
    {
        $this->generateLeaveRequestNotifications();
        $this->generateCashAdvanceRequestNotifications();
        $this->generateLeaveApprovedNotification();
        $this->generateLeaveValidatedNotification();
        $this->generateLeaveRejectedNotification();
        $this->generateCashAdvanceActiveNotification();
        $this->generateCashAdvanceDeclinedNotification();
        $this->generateOvertimePayPendingNotification();
        $this->generateOvertimePayApprovedNotification();
        $this->generateOvertimePayRejectedNotification();
        $this->generateNightPremiumPendingNotification();
        $this->generateNightPremiumApprovedNotification();
        $this->generateNightPremiumRejectedNotification();
    }

    private function generateLeaveRequestNotifications()
    {
        $user = Auth::user();
        // Check if user has Super Admin or Admin role
        if ($user && ($user->hasRole('Super Admin') || $user->hasRole('Admin'))) {
            $leaves = Leave::with('employee')
                ->where('status', 'pending')
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_requests'][] = [
                    'id' => 'leave_' . $leave->id,
                    'icon' => 'fas fa-calendar-times',
                    'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                    'time' => $leave->created_at->diffForHumans(),
                    'timestamp' => $leave->created_at->timestamp,
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason,
                        'status' => 'pending'
                    ]
                ];
            }
        } elseif ($user && $user->hasRole('Supervisor')) {
            $supervisorDepartmentId = $user->department_id;
            
            $leaves = Leave::with('employee')
                ->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId);
                })
                ->where('status', 'pending')
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_requests'][] = [
                    'id' => 'leave_' . $leave->id,
                    'icon' => 'fas fa-calendar-times',
                    'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                    'time' => $leave->created_at->diffForHumans(),
                    'timestamp' => $leave->created_at->timestamp,
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'reason' => $leave->reason,
                        'status' => 'pending'
                    ]
                ];
            }
        }
    }

    private function generateCashAdvanceRequestNotifications()
    {
        $user = Auth::user();
        if ($user && ($user->hasRole('Super Admin') || $user->hasRole('Admin'))) {
            $advances = CashAdvance::with('employee')
                ->where('status', 'pending')
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->get();

            foreach ($advances as $advance) {
                $this->notifications['cash_advances'][] = [
                    'id' => 'cash_advance_' . $advance->id,
                    'icon' => 'fas fa-money-bill-wave',
                    'text' => "{$advance->employee->first_name} {$advance->employee->last_name} requested cash advance",
                    'time' => $advance->created_at->diffForHumans(),
                    'timestamp' => $advance->created_at->timestamp,
                    'data' => [
                        'id' => $advance->id,
                        'type' => 'cash_advance',
                        'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                        'amount' => $advance->amount,
                        'reason' => $advance->reason,
                        'department' => $advance->employee->department->name ?? 'N/A',
                        'date_requested' => $advance->created_at->format('M d, Y'),
                        'reference_number' => $advance->reference_number ?? 'N/A',
                        'status' => 'pending'
                    ]
                ];
            }
        }
    }
    
    // Other notification generators with added timestamp and unique ID
    private function generateLeaveApprovedNotification()
    {
        $user = Auth::user();
        if ($user && ($user->hasRole('HR ComBen') || $user->hasRole('Admin') || $user->hasRole('Super Admin'))) {
            $leaves = Leave::with(['employee', 'approvedByUser'])
                ->where('status', 'approved')
                ->where('validated_by_signature', null)
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_approved'][] = [
                    'id' => 'leave_approved_' . $leave->id,
                    'icon' => 'fas fa-check-circle',
                    'text' => "Leave request for {$leave->employee->first_name} {$leave->employee->last_name} has been approved",
                    'time' => $leave->updated_at->diffForHumans(),
                    'timestamp' => $leave->updated_at->timestamp,
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_approved',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'approved_by' => $leave->approvedByUser->name,
                        'approved_at' => $leave->updated_at->format('M d, Y h:i A'),
                        'leave_type' => $leave->type->name,
                        'duration' => $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s)',
                        'payment_status' => $leave->payment_status,
                        'department' => $leave->employee->department->name,
                        'reason' => $leave->reason
                    ]
                ];
            }
        }
    }

    private function generateLeaveValidatedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $leaves = Leave::with(['employee', 'approvedByUser'])
                ->where('status', 'approved')
                ->where('is_view', false)
                ->where('validated_by_signature', '!=', null)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_validated'][] = [
                    'id' => 'leave_validated_' . $leave->id,
                    'icon' => 'fas fa-signature',
                    'text' => "Your leave request has been validated",
                    'time' => $leave->updated_at->diffForHumans(),
                    'timestamp' => $leave->updated_at->timestamp,
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_validated',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'approved_by' => $leave->approvedByUser->name,
                        'approved_at' => $leave->updated_at->format('M d, Y h:i A'),
                        'leave_type' => $leave->type->name,
                        'duration' => $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s)',
                        'payment_status' => $leave->payment_status,
                        'department' => $leave->employee->department->name,
                        'reason' => $leave->reason
                    ]
                ];
            }
        }
    }

    private function generateLeaveRejectedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $leaves = Leave::with(['employee', 'rejectedByUser'])
                ->where('status', 'rejected')
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($leaves as $leave) {
                $this->notifications['leave_rejected'][] = [
                    'id' => 'leave_rejected_' . $leave->id,
                    'icon' => 'fas fa-times-circle',
                    'text' => "Your leave request has been rejected",
                    'time' => $leave->updated_at->diffForHumans(),
                    'timestamp' => $leave->updated_at->timestamp,
                    'data' => [
                        'id' => $leave->id,
                        'type' => 'leave_rejected',
                        'employee_name' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                        'start_date' => $leave->start_date,
                        'end_date' => $leave->end_date,
                        'rejected_by' => $leave->rejectedByUser->name,
                        'rejected_at' => $leave->updated_at->format('M d, Y h:i A'),
                        'leave_type' => $leave->type->name,
                        'duration' => $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s)',
                        'payment_status' => $leave->payment_status,
                        'department' => $leave->employee->department->name,
                        'reason' => $leave->reason,
                        'status' => 'rejected'
                    ]
                ];
            }
        }
    }

    private function generateCashAdvanceActiveNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $advances = CashAdvance::with(['employee', 'approvedByUser'])
                ->where('status', 'active')
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($advances as $advance) {
                if (isset($advance->approvedByUser)) {
                    // Calculate payment information
                    $totalPaid = $advance->payments->sum('amount') ?? 0;
                    $remainingBalance = $advance->amount - $totalPaid;
                    
                    // Use the remainingBalance method if it exists, otherwise calculate manually
                    if (method_exists($advance, 'remainingBalance')) {
                        try {
                            $remainingBalance = $advance->remainingBalance();
                        } catch (\Exception $e) {
                            Log::error('Error calculating remaining balance: ' . $e->getMessage());
                        }
                    }
                    
                    $paymentsCount = $advance->payments->count();
                    $lastPaymentDate = $paymentsCount > 0 ? 
                        Carbon::parse($advance->payments->sortByDesc('payment_date')->first()->payment_date)->format('M d, Y') : 
                        'No payments yet';

                    $this->notifications['cash_advance_active'][] = [
                        'id' => 'cash_advance_active_' . $advance->id,
                        'icon' => 'fas fa-check-circle',
                        'text' => "Your cash advance request has been approved",
                        'time' => $advance->updated_at->diffForHumans(),
                        'timestamp' => $advance->updated_at->timestamp,
                        'data' => [
                            'id' => $advance->id,
                            'type' => 'cash_advance_approved',
                            'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                            'amount' => $advance->amount,
                            'approved_by' => $advance->approvedByUser->name,
                            'approved_at' => $advance->updated_at->format('M d, Y h:i A'),
                            'reason' => $advance->reason,
                            'department' => $advance->employee->department->name ?? 'N/A',
                            'reference_number' => $advance->reference_number ?? 'N/A',
                            'status' => 'active',
                            'total_paid' => $totalPaid,
                            'remaining_balance' => $remainingBalance,
                            'payments_count' => $paymentsCount,
                            'last_payment_date' => $lastPaymentDate,
                            'repayment_term' => $advance->repayment_term ?? 'N/A',
                            'monthly_amortization' => $advance->monthly_amortization ?? 0
                        ]
                    ];
                }
            }
        }
    }

    private function generateCashAdvanceDeclinedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $advances = CashAdvance::with(['employee', 'rejectedByUser'])
                ->where('status', 'declined')
                ->where('is_view', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($advances as $advance) {
                if (isset($advance->rejectedByUser)) {
                    $this->notifications['cash_advance_declined'][] = [
                        'id' => 'cash_advance_declined_' . $advance->id,
                        'icon' => 'fas fa-times-circle',
                        'text' => "Your cash advance request has been rejected",
                        'time' => $advance->updated_at->diffForHumans(),
                        'timestamp' => $advance->updated_at->timestamp,
                        'data' => [
                            'id' => $advance->id,
                            'type' => 'cash_advance_rejected',
                            'employee_name' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                            'amount' => $advance->amount,
                            'rejected_by' => $advance->rejectedByUser->name,
                            'rejected_at' => $advance->updated_at->format('M d, Y h:i A'),
                            'reason' => $advance->reason,
                            'rejection_reason' => $advance->rejection_reason ?? 'No reason provided',
                            'department' => $advance->employee->department->name ?? 'N/A',
                            'reference_number' => $advance->reference_number ?? 'N/A',
                            'status' => 'declined',
                            'date_requested' => $advance->created_at->format('M d, Y')
                        ]
                    ];
                }
            }
        }
    }

    private function countTotalNotifications()
    {
        return array_sum(array_map('count', $this->notifications));
    }

    private function generateDropdownHtml()
    {
        $allNotifications = $this->flattenNotifications();
        $html = '';

        if (empty($allNotifications)) {
            return '<div class="empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>No new notifications</p>
                    </div>';
        }

        foreach ($allNotifications as $notification) {
            $url = $this->getNotificationUrl($notification);
            $statusClass = $this->getStatusClass($notification);
            $timeAgo = $notification['time'];
            
            $html .= "
            <a href='{$url}' class='notification-item {$statusClass}' data-id='{$notification['id']}'>
                <div class='notification-icon {$this->getIconClass($notification)}'>
                    <i class='{$notification['icon']}'></i>
                </div>
                <div class='notification-content'>
                    <div class='notification-title'>{$this->getNotificationTitle($notification)}</div>
                    <div class='notification-text'>{$notification['text']}</div>
                    <div class='notification-time'>
                        <i class='far fa-clock mr-1'></i>{$timeAgo}
                    </div>
                </div>
            </a>";
        }

        return $html;
    }

    private function getNotificationUrl($notification)
    {
        $type = isset($notification['data']) ? ($notification['data']['type'] ?? '') : '';
        $id = isset($notification['data']) ? ($notification['data']['id'] ?? null) : null;
        
        $urls = [
            'leave_request' => route('leave.index'),
            'leave_approved' => route('leave.index'),
            'leave_rejected' => route('leave.index'),
            'leave_validated' => route('leave.index'),
            'cash_advance_request' => route('cashadvance.index'),
            'cash_advance_approved' => route('cashadvance.index'),
            'cash_advance_rejected' => route('cashadvance.index'),
            'overtime_pending' => route('overtime.index'),
            'overtime_approved' => route('overtime.index'),
            'overtime_rejected' => route('overtime.index'),
            'night_premium_pending' => route('nightpremium.index'),
            'night_premium_approved' => route('nightpremium.index'),
            'night_premium_rejected' => route('nightpremium.index'),
        ];
        
        return $urls[$type] ?? route('notifications.all');
    }

    private function getIconClass($notification)
    {
        if (strpos($notification['text'], 'requested leave') !== false) {
            return 'leave';
        }
        if (strpos($notification['text'], 'requested cash advance') !== false) {
            return 'cash-advance';
        }
        if (strpos($notification['text'], 'requested overtime pay') !== false) {
            return 'overtime';
        }
        if (strpos($notification['text'], 'requested night premium pay') !== false) {
            return 'night-premium';
        }
        return 'default';
    }

    private function getNotificationTitle($notification)
    {
        $type = isset($notification['data']) ? ($notification['data']['type'] ?? '') : '';
        
        $titles = [
            'leave_request' => 'Leave Request',
            'leave_approved' => 'Leave Approved',
            'leave_rejected' => 'Leave Rejected',
            'leave_validated' => 'Leave Validated',
            'cash_advance_request' => 'Cash Advance Request',
            'cash_advance_approved' => 'Cash Advance Approved',
            'cash_advance_rejected' => 'Cash Advance Rejected',
            'overtime_pending' => 'Overtime Request',
            'overtime_approved' => 'Overtime Approved',
            'overtime_rejected' => 'Overtime Rejected',
            'night_premium_pending' => 'Night Premium Request',
            'night_premium_approved' => 'Night Premium Approved',
            'night_premium_rejected' => 'Night Premium Rejected',
        ];
        
        return $titles[$type] ?? 'Notification';
    }

    private function getStatusClass($notification)
    {
        // You can add logic here to determine if a notification is read/unread
        return 'unread';
    }

    private function flattenNotifications()
    {
        $flattened = [];
        foreach ($this->notifications as $notifications) {
            $flattened = array_merge($flattened, $notifications);
        }
        
        // Sort by timestamp instead of parsed time for more accurate sorting
        usort($flattened, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        return array_slice($flattened, 0, 10);
    }

    private function getLatestNotificationMessage()
    {
        $allNotifications = $this->flattenNotifications();
        return !empty($allNotifications) ? $allNotifications[0]['text'] : '';
    }

    private function getLatestNotificationIcon()
    {
        $allNotifications = $this->flattenNotifications();
        return !empty($allNotifications) ? $allNotifications[0]['icon'] : 'fas fa-bell';
    }

    // Method to mark notification as read
    public function markAsRead(Request $request)
    {
        try {
            $notificationId = $request->input('id');
            $notificationType = $request->input('type');
            
            // Extract the actual ID
            $parts = explode('_', $notificationId);
            $id = end($parts);
            
            if (strpos($notificationType, 'leave') !== false) {
                $leave = Leave::find($id);
                if ($leave) {
                    $leave->is_read = true;
                    if (strpos($notificationType, 'validated') !== false || 
                        strpos($notificationType, 'rejected') !== false) {
                        $leave->is_view = true;
                    }
                    $leave->save();
                }
            } elseif (strpos($notificationType, 'cash_advance') !== false) {
                $advance = CashAdvance::find($id);
                if ($advance) {
                    $advance->is_read = true;
                    if (strpos($notificationType, 'approved') !== false || 
                        strpos($notificationType, 'declined') !== false) {
                        $advance->is_view = true;
                    }
                    $advance->save();
                }
            } elseif (strpos($notificationType, 'overtime') !== false) {
                $overtime = OvertimePay::find($id);
                if ($overtime) {
                    $overtime->is_read = true;
                    $overtime->read_at = now();
                    if (strpos($notificationType, 'approved') !== false || 
                        strpos($notificationType, 'rejected') !== false) {
                        $overtime->is_view = true;
                        $overtime->view_at = now();
                    }
                    $overtime->save();
                }
            } elseif (strpos($notificationType, 'night_premium') !== false) {
                $nightPremium = NightPremium::find($id);
                if ($nightPremium) {
                    // Determine which read flag to set based on user role
                    $user = Auth::user();
                    
                    if ($user->hasRole('Supervisor')) {
                        $nightPremium->is_read_by_supervisor = true;
                        $nightPremium->is_read_at_supervisor = now();
                    } elseif ($user->hasRole('Finance')) {
                        $nightPremium->is_read_by_finance = true;
                        $nightPremium->is_read_at_finance = now();
                    } elseif ($user->hasRole('Employee')) {
                        $nightPremium->is_read_by_employee = true;
                        $nightPremium->is_read_at_employee = now();
                    }
                    
                    // Handle approved/rejected status
                    if (strpos($notificationType, 'approved') !== false || 
                        strpos($notificationType, 'rejected') !== false) {
                        $nightPremium->is_read_by_employee = true;
                        $nightPremium->is_read_at_employee = now();
                    }
                    
                    $nightPremium->save();
                }
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // New method for real-time updates check
    public function checkForUpdates(Request $request)
    {
        try {
            $timestamp = $request->input('timestamp', 0);
            $newNotifications = $this->getNewNotificationsSince($timestamp);
            
            $hasUpdates = count($newNotifications) > 0;
            $newCount = count($newNotifications);
            
            return response()->json([
                'has_updates' => $hasUpdates,
                'new_count' => $newCount,
                'timestamp' => time()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in checkForUpdates: ' . $e->getMessage());
            return response()->json([
                'has_updates' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getNewNotificationsSince($timestamp)
    {
        $this->generateNotifications();
        $allNotifications = $this->flattenNotifications();
        
        if (!$timestamp) {
            return $allNotifications;
        }
        
        // Filter to only include notifications newer than the given timestamp
        return array_filter($allNotifications, function($notification) use ($timestamp) {
            return $notification['timestamp'] > $timestamp;
        });
    }

    public function showAllNotifications()
    {
        try {
            $sevenDaysAgo = now()->subDays(7);
            $user = Auth::user();
            
            // Initialize query builders
            $leavesQuery = Leave::with(['employee', 'employee.department', 'type', 'approvedByUser'])
                ->where('created_at', '>=', $sevenDaysAgo);
                
            $advancesQuery = CashAdvance::with(['employee', 'employee.department', 'payments'])
                ->where('created_at', '>=', $sevenDaysAgo);
                
            $overtimeQuery = OvertimePay::with(['employee', 'employee.department', 'approver'])
                ->where('created_at', '>=', $sevenDaysAgo);
                
            $nightPremiumQuery = NightPremium::with(['employee', 'employee.department', 'approver'])
                ->where('created_at', '>=', $sevenDaysAgo);

            // Apply role-based restrictions
            if ($user && ($user->hasRole('Super Admin') || $user->hasRole('Admin'))) {
                // Super Admin and Admin can see all notifications
                // No additional restrictions needed
            } elseif ($user && $user->hasRole('Supervisor')) {
                // Supervisors can only see notifications from their department
                $supervisorDepartmentId = $user->department_id;
                
                $leavesQuery->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId);
                });
                
                $nightPremiumQuery->where('approval_status', 'pending')
                    ->where('is_read_by_supervisor', false)
                    ->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                        $query->where('department_id', $supervisorDepartmentId)
                            ->where('rank', 'Rank File');
                    });
                
                // Set other queries to return no results for supervisors
                $advancesQuery->where('id', 0);
                $overtimeQuery->where('id', 0);
            } elseif ($user && $user->hasRole('HR ComBen')) {
                // HR ComBen can see approved leaves that need validation and pending overtime requests
                $leavesQuery->where('status', 'approved')
                           ->whereNull('validated_by_signature');
                
                $overtimeQuery->where('approval_status', 'pending');
                
                // HR ComBen cannot see cash advances and night premiums
                $advancesQuery->where('id', 0);
                $nightPremiumQuery->where('id', 0);
            } elseif ($user && $user->hasRole('Finance')) {
                // Finance can see pending overtime requests and night premium requests
                $overtimeQuery->where('approval_status', 'pending');
                
                $nightPremiumQuery->where('approval_status', 'pending')
                    ->where('is_read_by_finance', false)
                    ->whereHas('employee', function($query) {
                        $query->where('rank', '!=', 'Rank File');
                    });
                
                // Finance cannot see leaves or cash advances
                $leavesQuery->where('id', 0);
                $advancesQuery->where('id', 0);
            } elseif ($user && $user->hasRole('Employee')) {
                // Regular employees can only see their own notifications
                $leavesQuery->where('status', 'approved')
                           ->whereNotNull('validated_by_signature')
                           ->whereHas('employee', function($query) use ($user) {
                               $query->where('email_address', $user->email);
                           });
                
                $overtimeQuery->whereIn('approval_status', ['approved', 'rejected'])
                           ->whereHas('employee', function($query) use ($user) {
                               $query->where('email_address', $user->email);
                           });
                           
                $nightPremiumQuery->whereIn('approval_status', ['approvedByVPFinance', 'rejected'])
                           ->where('is_read_by_employee', false)
                           ->whereHas('employee', function($query) use ($user) {
                               $query->where('email_address', $user->email);
                           });
                
                // Employees cannot see cash advances in this view
                $advancesQuery->where('id', 0);
            } else {
                // Any other role sees nothing
                $leavesQuery->where('id', 0);
                $advancesQuery->where('id', 0);
                $overtimeQuery->where('id', 0);
                $nightPremiumQuery->where('id', 0);
            }

            // Execute queries with ordering
            $leaves = $leavesQuery->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($leave) {
                    $dateFrom = $leave->date_from ? Carbon::parse($leave->date_from) : null;
                    $dateTo = $leave->date_to ? Carbon::parse($leave->date_to) : null;
                    $createdAt = $leave->created_at ? Carbon::parse($leave->created_at) : now();

                    return [
                        'id' => 'leave_' . $leave->id,
                        'type' => 'leave',
                        'icon' => 'fas fa-calendar-times',
                        'title' => 'Leave Request',
                        'text' => "{$leave->employee->first_name} {$leave->employee->last_name} requested leave",
                        'time' => $createdAt,
                        'time_human' => $createdAt->diffForHumans(),
                        'timestamp' => $createdAt->timestamp,
                        'status' => $leave->status ?? 'pending',
                        'is_read' => $leave->is_read ?? false,
                        'details' => [
                            'Employee' => $leave->employee->first_name . ' ' . $leave->employee->last_name,
                            'Department' => $leave->employee->department->name ?? 'N/A',
                            'Leave Type' => $leave->type->name ?? 'N/A',
                            'Date From' => $dateFrom ? $dateFrom->format('M d, Y h:i A') : 'N/A',
                            'Date To' => $dateTo ? $dateTo->format('M d, Y h:i A') : 'N/A',
                            'Duration' => ($dateFrom && $dateTo) ? 
                                $leave->diffdays . ' day(s) ' . $leave->diffhours['hours'] . ' hour(s) ' . 
                                $leave->diffhours['minutes'] . ' minute(s)' : 'N/A',
                            'Reason' => $leave->reason_to_leave ?? 'No reason provided',
                            'Payment Status' => $leave->payment_status ?? 'N/A',
                            'Status' => ucfirst($leave->status ?? 'pending'),
                            'Approved By' => $leave->approvedByUser ? 
                                $leave->approvedByUser->name : 'Not yet approved',
                            'Reference Number' => $leave->id ?? 'N/A',
                            'Applied On' => $createdAt->format('M d, Y h:i A')
                        ]
                    ];
                });

            // Add the night premiums mapping here
            $nightPremiums = $nightPremiumQuery->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($nightPremium) {
                    $createdAt = $nightPremium->created_at ? Carbon::parse($nightPremium->created_at) : now();
                    $date = $nightPremium->date ? Carbon::parse($nightPremium->date) : null;
                    
                    // Determine notification text based on status
                    $notificationText = "{$nightPremium->employee->first_name} {$nightPremium->employee->last_name} requested night premium pay";
                    $notificationType = 'night_premium_pending';
                    $icon = 'fas fa-moon';
                    $title = 'Night Premium Pay Request';
                    
                    if ($nightPremium->approval_status === 'approvedByVPFinance') {
                        $notificationText = "Your night premium pay request has been approved";
                        $notificationType = 'night_premium_approved';
                        $icon = 'fas fa-check-circle';
                        $title = 'Night Premium Pay Approved';
                    } elseif ($nightPremium->approval_status === 'rejected') {
                        $notificationText = "Your night premium pay request has been rejected";
                        $notificationType = 'night_premium_rejected';
                        $icon = 'fas fa-times-circle';
                        $title = 'Night Premium Pay Rejected';
                    }
                    
                    return [
                        'id' => 'night_premium_' . $nightPremium->id,
                        'type' => $notificationType,
                        'icon' => $icon,
                        'title' => $title,
                        'text' => $notificationText,
                        'time' => $createdAt,
                        'time_human' => $createdAt->diffForHumans(),
                        'timestamp' => $createdAt->timestamp,
                        'status' => $nightPremium->approval_status ?? 'pending',
                        'is_read' => ($nightPremium->is_read_by_employee || $nightPremium->is_read_by_supervisor || $nightPremium->is_read_by_finance),
                        'url' => route('night-premium.show', ['night_premium' => $nightPremium->id]),
                        'details' => [
                            'Employee' => $nightPremium->employee->first_name . ' ' . $nightPremium->employee->last_name,
                            'Department' => $nightPremium->employee->department->name ?? 'N/A',
                            'Date' => $date ? $date->format('M d, Y') : 'N/A',
                            'Time In' => $nightPremium->time_in ? Carbon::parse($nightPremium->time_in)->format('h:i A') : 'N/A',
                            'Time Out' => $nightPremium->time_out ? Carbon::parse($nightPremium->time_out)->format('h:i A') : 'N/A',
                            'Night Hours' => $nightPremium->night_hours ?? 0,
                            'Night Rate' => $nightPremium->night_rate ?? 0,
                            'Night Premium Pay' => $nightPremium->night_premium_pay ?? 0,
                            'Status' => ucfirst($nightPremium->approval_status ?? 'pending'),
                            'Reason' => $nightPremium->reason ?? 'No reason provided',
                            'Rejection Reason' => $nightPremium->rejection_reason ?? 'N/A',
                            'Applied On' => $createdAt->format('M d, Y h:i A')
                        ]
                    ];
                });

            $advances = $advancesQuery->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($advance) {
                    $createdAt = $advance->created_at ? Carbon::parse($advance->created_at) : now();
                    $remainingBalance = $advance->remainingBalance();

                    return [
                        'id' => 'cash_advance_' . $advance->id,
                        'type' => 'cash_advance',
                        'icon' => 'fas fa-money-bill-wave',
                        'title' => 'Cash Advance Request',
                        'text' => "{$advance->employee->first_name} {$advance->employee->last_name} requested cash advance",
                        'time' => $createdAt,
                        'time_human' => $createdAt->diffForHumans(),
                        'timestamp' => $createdAt->timestamp,
                        'status' => $advance->status ?? 'pending',
                        'is_read' => $advance->is_read ?? false,
                        'details' => [
                            'Employee' => $advance->employee->first_name . ' ' . $advance->employee->last_name,
                            'Department' => $advance->employee->department->name ?? 'N/A',
                            'Reference Number' => $advance->reference_number ?? 'N/A',
                            'Cash Advance Amount' => '₱ ' . number_format($advance->cash_advance_amount ?? 0, 2),
                            'Repayment Term' => $advance->repayment_term . ' month(s)',
                            'Monthly Amortization' => '₱ ' . number_format($advance->monthly_amortization ?? 0, 2),
                            'Total Repayment' => '₱ ' . number_format($advance->total_repayment ?? 0, 2),
                            'Remaining Balance' => '₱ ' . number_format($remainingBalance, 2),
                            'Status' => ucfirst($advance->status ?? 'pending'),
                            'Applied On' => $createdAt->format('M d, Y h:i A'),
                            'Total Payments Made' => $advance->payments->count(),
                            'Last Payment Date' => $advance->payments->last() ? 
                                Carbon::parse($advance->payments->last()->payment_date)->format('M d, Y') : 'No payments yet'
                        ]
                    ];
                });
                
            $overtimes = $overtimeQuery->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($overtime) {
                    $createdAt = $overtime->created_at ? Carbon::parse($overtime->created_at) : now();
                    $updatedAt = $overtime->updated_at ? Carbon::parse($overtime->updated_at) : now();
                    $overtimeDate = $overtime->date ? Carbon::parse($overtime->date) : null;
                    
                    $status = $overtime->approval_status ?? 'pending';
                    $statusClass = $status === 'approved' ? 'text-success' : 
                                   ($status === 'rejected' ? 'text-danger' : 'text-warning');
                                   
                    $title = $status === 'pending' ? 'Overtime Pay Request' : 
                             ($status === 'approved' ? 'Overtime Pay Approved' : 'Overtime Pay Rejected');
                             
                    $text = $status === 'pending' ? 
                        "{$overtime->employee->first_name} {$overtime->employee->last_name} requested overtime pay" : 
                        ($status === 'approved' ? 
                            "Overtime pay for {$overtime->employee->first_name} {$overtime->employee->last_name} has been approved" : 
                            "Overtime pay for {$overtime->employee->first_name} {$overtime->employee->last_name} has been rejected");
                    
                    return [
                        'id' => 'overtime_' . $overtime->id,
                        'type' => 'overtime_' . strtolower($status),
                        'icon' => $status === 'approved' ? 'fas fa-check-circle' : 
                                 ($status === 'rejected' ? 'fas fa-times-circle' : 'fas fa-clock'),
                        'title' => $title,
                        'text' => $text,
                        'time' => $createdAt,
                        'time_human' => $createdAt->diffForHumans(),
                        'timestamp' => $createdAt->timestamp,
                        'status' => $status,
                        'status_class' => $statusClass,
                        'is_read' => $overtime->is_read ?? false,
                        'url' => route('overtime.show', ['overtime' => $overtime->id]),
                        'details' => [
                            'Employee' => $overtime->employee->first_name . ' ' . $overtime->employee->last_name,
                            'Department' => $overtime->employee->department->name ?? 'N/A',
                            'Overtime Date' => $overtimeDate ? $overtimeDate->format('M d, Y') : 'N/A',
                            'Overtime Hours' => $overtime->overtime_hours . ' hour(s)',
                            'Overtime Rate' => $overtime->overtime_rate . 'x',
                            'Overtime Pay' => '₱ ' . number_format($overtime->overtime_pay, 2),
                            'Status' => ucfirst($status),
                            'Applied On' => $createdAt->format('M d, Y h:i A'),
                            'Approved/Rejected By' => $overtime->approver ? $overtime->approver->name : 'Not yet reviewed',
                            'Approved/Rejected At' => $overtime->approved_at ? Carbon::parse($overtime->approved_at)->format('M d, Y h:i A') : 'Pending',
                            'Reference Number' => $overtime->id ?? 'N/A',
                            'Rejection Reason' => $status === 'rejected' ? ($overtime->rejection_reason ?? 'No reason provided') : 'N/A'
                        ]
                    ];
                });

            // Merge and sort notifications
            $allNotifications = $leaves->concat($advances)->concat($overtimes)->concat($nightPremiums)
                ->filter(function ($notification) {
                    return !is_null($notification['time']);
                })
                ->sortByDesc('timestamp') // Use timestamp for sorting
                ->groupBy(function($notification) {
                    return $notification['time']->format('Y-m-d');
                });

            return view('notifications.all', compact('allNotifications'));
        } catch (\Exception $e) {
            Log::error('Error in showAllNotifications: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load notifications. Please try again later.');
        }
    }

    // Method to mark all notifications as read
    public function markAllAsRead(Request $request)
    {
        try {
            Leave::where('is_read', false)->update(['is_read' => true]);
            CashAdvance::where('is_read', false)->update(['is_read' => true]);
            OvertimePay::where('is_read', false)->update([
                'is_read' => true,
                'read_at' => now()
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error marking all notifications as read: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Generate notifications for pending overtime pay
     * Visible to Super Admin, Supervisors, and Finance
     */
    private function generateOvertimePayPendingNotification()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // For Super Admin, show all pending overtime requests
        if ($user->hasRole('Super Admin')) {
            $overtimePays = OvertimePay::with(['employee', 'employee.department'])
                ->where('approval_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        } 
        // For Supervisors, show only pending overtime requests for Rank File employees in their department
        elseif ($user->hasRole('Supervisor')) {
            $supervisorDepartmentId = $user->department_id;
            
            $overtimePays = OvertimePay::with(['employee', 'employee.department'])
                ->where('approval_status', 'pending')
                ->where('is_read_by_supervisor', false)
                ->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId)
                          ->where('rank', 'Rank File');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } 
        // For Finance role, show overtime requests for non-Rank File employees
        elseif ($user->hasRole('Finance')) {
            $overtimePays = OvertimePay::with(['employee', 'employee.department'])
                ->where('approval_status', 'pending')
                ->where('is_read_by_finance', false)
                ->whereHas('employee', function($query) {
                    $query->where('rank', '!=', 'Rank File');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $overtimePays = collect(); // Empty collection for other roles
        }

        foreach ($overtimePays as $overtime) {
            $this->notifications['overtime_pay_pending'][] = [
                'id' => 'overtime_pending_' . $overtime->id,
                'icon' => 'fas fa-clock',
                'text' => "{$overtime->employee->first_name} {$overtime->employee->last_name} requested overtime pay",
                'time' => $overtime->created_at->diffForHumans(),
                'timestamp' => $overtime->created_at->timestamp,
                'data' => [
                    'id' => $overtime->id,
                    'type' => 'overtime_pending',
                    'employee_name' => $overtime->employee->first_name . ' ' . $overtime->employee->last_name,
                    'date' => $overtime->date->format('Y-m-d'),
                    'overtime_hours' => $overtime->overtime_hours,
                    'overtime_rate' => $overtime->overtime_rate,
                    'overtime_pay' => $overtime->overtime_pay,
                    'status' => 'pending',
                    'department' => $overtime->employee->department->name ?? 'N/A',
                    'rank' => $overtime->employee->rank ?? 'N/A'
                ]
            ];
        }
    }

    /**
     * Generate notifications for approved overtime pay
     * Visible to Employees who requested the overtime
     */
    private function generateOvertimePayApprovedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $overtimePays = OvertimePay::with(['employee', 'approver'])
                ->where('approval_status', 'approvedByVPFinance')
                ->where('is_read_by_employee', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($overtimePays as $overtime) {
                $this->notifications['overtime_pay_approved'][] = [
                    'id' => 'overtime_approved_' . $overtime->id,
                    'icon' => 'fas fa-check-circle',
                    'text' => "Your overtime pay request has been approved",
                    'time' => $overtime->updated_at->diffForHumans(),
                    'timestamp' => $overtime->updated_at->timestamp,
                    'data' => [
                        'id' => $overtime->id,
                        'type' => 'overtime_approved',
                        'employee_name' => $overtime->employee->first_name . ' ' . $overtime->employee->last_name,
                        'date' => $overtime->date->format('Y-m-d'),
                        'overtime_hours' => $overtime->overtime_hours,
                        'overtime_rate' => $overtime->overtime_rate,
                        'overtime_pay' => $overtime->overtime_pay,
                        'approved_by' => $overtime->approver->name ?? 'System',
                        'approved_at' => $overtime->approved_at->format('M d, Y h:i A'),
                        'status' => 'approved',
                        'department' => $overtime->employee->department->name ?? 'N/A'
                    ]
                ];
            }
        }
    }

    /**
     * Generate notifications for rejected overtime pay
     * Visible to Employees who requested the overtime
     */
    private function generateOvertimePayRejectedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $overtimePays = OvertimePay::with(['employee', 'approver'])
                ->where('approval_status', 'rejected')
                ->where('is_read_by_employee', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($overtimePays as $overtime) {
                $this->notifications['overtime_pay_rejected'][] = [
                    'id' => 'overtime_rejected_' . $overtime->id,
                    'icon' => 'fas fa-times-circle',
                    'text' => "Your overtime pay request has been rejected",
                    'time' => $overtime->updated_at->diffForHumans(),
                    'timestamp' => $overtime->updated_at->timestamp,
                    'data' => [
                        'id' => $overtime->id,
                        'type' => 'overtime_rejected',
                        'employee_name' => $overtime->employee->first_name . ' ' . $overtime->employee->last_name,
                        'date' => $overtime->date->format('Y-m-d'),
                        'overtime_hours' => $overtime->overtime_hours,
                        'overtime_rate' => $overtime->overtime_rate,
                        'overtime_pay' => $overtime->overtime_pay,
                        'rejected_by' => $overtime->approver->name ?? 'System',
                        'rejected_at' => $overtime->approved_at->format('M d, Y h:i A'),
                        'status' => 'rejected',
                        'department' => $overtime->employee->department->name ?? 'N/A',
                        'rejection_reason' => $overtime->rejection_reason ?? 'No reason provided'
                    ]
                ];
            }
        }
    }

    /**
     * Generate notifications for pending night premium pay
     * Visible to Super Admin, Supervisors, and Finance
     */
    private function generateNightPremiumPendingNotification()
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // For Super Admin, show all pending night premium requests
        if ($user->hasRole('Super Admin')) {
            $nightPremiums = NightPremium::with(['employee', 'employee.department'])
                ->where('approval_status', 'pending')
                ->orderBy('created_at', 'desc')
                ->get();
        } 
        // For Supervisors, show only pending night premium requests for Rank File employees in their department
        elseif ($user->hasRole('Supervisor')) {
            $supervisorDepartmentId = $user->department_id;
            
            $nightPremiums = NightPremium::with(['employee', 'employee.department'])
                ->where('approval_status', 'pending')
                ->where('is_read_by_supervisor', false)
                ->whereHas('employee', function($query) use ($supervisorDepartmentId) {
                    $query->where('department_id', $supervisorDepartmentId)
                          ->where('rank', 'Rank File');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } 
        // For Finance role, show night premium requests for non-Rank File employees
        elseif ($user->hasRole('Finance')) {
            $nightPremiums = NightPremium::with(['employee', 'employee.department'])
                ->where('approval_status', 'pending')
                ->where('is_read_by_finance', false)
                ->whereHas('employee', function($query) {
                    $query->where('rank', '!=', 'Rank File');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $nightPremiums = collect(); // Empty collection for other roles
        }

        foreach ($nightPremiums as $nightPremium) {
            $this->notifications['night_premium_pending'][] = [
                'id' => 'night_premium_pending_' . $nightPremium->id,
                'icon' => 'fas fa-moon',
                'text' => "{$nightPremium->employee->first_name} {$nightPremium->employee->last_name} requested night premium pay",
                'time' => $nightPremium->created_at->diffForHumans(),
                'timestamp' => $nightPremium->created_at->timestamp,
                'data' => [
                    'id' => $nightPremium->id,
                    'type' => 'night_premium_pending',
                    'employee_name' => $nightPremium->employee->first_name . ' ' . $nightPremium->employee->last_name,
                    'date' => $nightPremium->date->format('Y-m-d'),
                    'time_in' => $nightPremium->time_in ? Carbon::parse($nightPremium->time_in)->format('h:i A') : null,
                    'time_out' => $nightPremium->time_out ? Carbon::parse($nightPremium->time_out)->format('h:i A') : null,
                    'night_hours' => $nightPremium->night_hours,
                    'night_rate' => $nightPremium->night_rate,
                    'night_premium_pay' => $nightPremium->night_premium_pay,
                    'status' => 'pending',
                    'department' => $nightPremium->employee->department->name ?? 'N/A',
                    'rank' => $nightPremium->employee->rank ?? 'N/A',
                    'reason' => $nightPremium->reason ?? 'No reason provided'
                ]
            ];
        }
    }

    /**
     * Generate notifications for approved night premium pay
     * Visible to Employees who requested the night premium
     */
    private function generateNightPremiumApprovedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $nightPremiums = NightPremium::with(['employee', 'approver'])
                ->where('approval_status', 'approvedByVPFinance')
                ->where('is_read_by_employee', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($nightPremiums as $nightPremium) {
                $this->notifications['night_premium_approved'][] = [
                    'id' => 'night_premium_approved_' . $nightPremium->id,
                    'icon' => 'fas fa-check-circle',
                    'text' => "Your night premium pay request has been approved",
                    'time' => $nightPremium->updated_at->diffForHumans(),
                    'timestamp' => $nightPremium->updated_at->timestamp,
                    'data' => [
                        'id' => $nightPremium->id,
                        'type' => 'night_premium_approved',
                        'employee_name' => $nightPremium->employee->first_name . ' ' . $nightPremium->employee->last_name,
                        'date' => $nightPremium->date->format('Y-m-d'),
                        'time_in' => $nightPremium->time_in ? Carbon::parse($nightPremium->time_in)->format('h:i A') : null,
                        'time_out' => $nightPremium->time_out ? Carbon::parse($nightPremium->time_out)->format('h:i A') : null,
                        'night_hours' => $nightPremium->night_hours,
                        'night_rate' => $nightPremium->night_rate,
                        'night_premium_pay' => $nightPremium->night_premium_pay,
                        'approved_by' => $nightPremium->approver->name ?? 'System',
                        'approved_at' => $nightPremium->approved_at->format('M d, Y h:i A'),
                        'status' => 'approved',
                        'department' => $nightPremium->employee->department->name ?? 'N/A',
                        'reason' => $nightPremium->reason ?? 'No reason provided'
                    ]
                ];
            }
        }
    }

    /**
     * Generate notifications for rejected night premium pay
     * Visible to Employees who requested the night premium
     */
    private function generateNightPremiumRejectedNotification()
    {
        $user = Auth::user();
        if ($user && $user->hasRole('Employee')) {
            $nightPremiums = NightPremium::with(['employee', 'approver'])
                ->where('approval_status', 'rejected')
                ->where('is_read_by_employee', false)
                ->whereHas('employee', function($query) use ($user) {
                    $query->where('email_address', $user->email);
                })
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($nightPremiums as $nightPremium) {
                $this->notifications['night_premium_rejected'][] = [
                    'id' => 'night_premium_rejected_' . $nightPremium->id,
                    'icon' => 'fas fa-times-circle',
                    'text' => "Your night premium pay request has been rejected",
                    'time' => $nightPremium->updated_at->diffForHumans(),
                    'timestamp' => $nightPremium->updated_at->timestamp,
                    'data' => [
                        'id' => $nightPremium->id,
                        'type' => 'night_premium_rejected',
                        'employee_name' => $nightPremium->employee->first_name . ' ' . $nightPremium->employee->last_name,
                        'date' => $nightPremium->date->format('Y-m-d'),
                        'time_in' => $nightPremium->time_in ? Carbon::parse($nightPremium->time_in)->format('h:i A') : null,
                        'time_out' => $nightPremium->time_out ? Carbon::parse($nightPremium->time_out)->format('h:i A') : null,
                        'night_hours' => $nightPremium->night_hours,
                        'night_rate' => $nightPremium->night_rate,
                        'night_premium_pay' => $nightPremium->night_premium_pay,
                        'rejected_by' => $nightPremium->approver->name ?? 'System',
                        'rejected_at' => $nightPremium->approved_at->format('M d, Y h:i A'),
                        'status' => 'rejected',
                        'department' => $nightPremium->employee->department->name ?? 'N/A',
                        'rejection_reason' => $nightPremium->rejection_reason ?? 'No reason provided',
                        'reason' => $nightPremium->reason ?? 'No reason provided'
                    ]
                ];
            }
        }
    }

    /**
     * Get the VAPID public key for WebPush notifications
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVapidPublicKey()
    {
        return response()->json([
            'publicKey' => config('webpush.vapid.public_key')
        ]);
    }

    /**
     * Check if push notifications are enabled for the current user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkNotificationStatus()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['enabled' => false]);
        }
        
        // Check if the user has any push subscriptions
        $enabled = $user->pushSubscriptions()->count() > 0;
        
        return response()->json([
            'enabled' => $enabled
        ]);
    }

    /**
     * Store a new push subscription for the authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storePushSubscription(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        $this->validate($request, [
            'endpoint' => 'required|string|max:500',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string'
        ]);
        
        // Delete any existing subscriptions with this endpoint
        $user->pushSubscriptions()
            ->where('endpoint', $request->endpoint)
            ->delete();
        
        // Create a new subscription
        $subscription = $user->pushSubscriptions()->create([
            'endpoint' => $request->endpoint,
            'public_key' => $request->keys['p256dh'],
            'auth_token' => $request->keys['auth'],
            'content_encoding' => $request->contentEncoding ?? 'aes128gcm'
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Send a test push notification to the current user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testPushNotification(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        
        try {
            // Create a test notification payload
            $payload = [
                'toast' => [
                    'title' => 'Notifications Enabled!',
                    'message' => 'You will now receive real-time notifications.',
                    'icon' => 'fas fa-bell'
                ],
                'url' => route('notifications.all'),
                'timestamp' => now()->timestamp
            ];
            
            // Send notifications to all subscriptions of this user
            foreach ($user->pushSubscriptions as $subscription) {
                $this->sendWebPushNotification($subscription, $payload);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error sending test push notification: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Send push notification to all user subscriptions
     *
     * @param  mixed  $notification
     * @return void
     */
    public function sendPushNotification($notification)
    {
        try {
            $user = Auth::user();
            if (!$user) return;
            
            // Prepare notification data
            $title = $this->getNotificationTitle($notification);
            $message = $notification['text'];
            $icon = $notification['icon'];
            $url = $this->getNotificationUrl($notification);
            
            $payload = [
                'toast' => [
                    'title' => $title,
                    'message' => $message,
                    'icon' => $icon
                ],
                'url' => $url,
                'timestamp' => $notification['timestamp'],
                'data' => $notification['data'] ?? []
            ];
            
            // Get all users who should receive the notification
            $userIds = $this->getUsersForNotification($notification);
            
            // Send to all relevant users
            $users = User::whereIn('id', $userIds)->get();
            
            foreach ($users as $recipient) {
                foreach ($recipient->pushSubscriptions as $subscription) {
                    $this->sendWebPushNotification($subscription, $payload);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending push notification: ' . $e->getMessage());
        }
    }
    
    /**
     * Get users who should receive a notification
     *
     * @param  mixed  $notification
     * @return array
     */
    private function getUsersForNotification($notification)
    {
        $type = isset($notification['data']) ? ($notification['data']['type'] ?? '') : '';
        $userIds = [];
        
        if (strpos($type, 'leave_request') !== false) {
            // Send to admins and supervisors
            $userIds = User::role(['Super Admin', 'Admin', 'Supervisor'])->pluck('id')->toArray();
        } elseif (strpos($type, 'leave_approved') !== false || 
                  strpos($type, 'leave_rejected') !== false ||
                  strpos($type, 'leave_validated') !== false) {
            // Send to employee
            if (isset($notification['data']['employee_id'])) {
                $employee = \App\Models\Employee::find($notification['data']['employee_id']);
                if ($employee && $employee->user) {
                    $userIds[] = $employee->user->id;
                }
            }
        } elseif (strpos($type, 'cash_advance') !== false) {
            // Determine recipients based on notification type
            if (strpos($type, 'request') !== false) {
                // Send to admins
                $userIds = User::role(['Super Admin', 'Admin'])->pluck('id')->toArray();
            } else {
                // Send to employee
                if (isset($notification['data']['employee_id'])) {
                    $employee = \App\Models\Employee::find($notification['data']['employee_id']);
                    if ($employee && $employee->user) {
                        $userIds[] = $employee->user->id;
                    }
                }
            }
        } elseif (strpos($type, 'overtime') !== false || strpos($type, 'night_premium') !== false) {
            // Determine recipients based on role and status
            if (strpos($type, 'pending') !== false) {
                if (strpos($type, 'night_premium') !== false) {
                    // Send to supervisors for rank file employees
                    // Send to finance for non-rank file employees
                    $userIds = User::role(['Super Admin', 'Finance', 'Supervisor'])->pluck('id')->toArray();
                } else {
                    // For overtime, send to supervisors and finance
                    $userIds = User::role(['Super Admin', 'Finance', 'Supervisor'])->pluck('id')->toArray();
                }
            } else {
                // For approved or rejected, send to employee
                if (isset($notification['data']['employee_id'])) {
                    $employee = \App\Models\Employee::find($notification['data']['employee_id']);
                    if ($employee && $employee->user) {
                        $userIds[] = $employee->user->id;
                    }
                }
            }
        }
        
        return $userIds;
    }

    /**
     * Send a web push notification to a specific subscription
     *
     * @param  object  $subscription
     * @param  array  $payload
     * @return void
     */
    private function sendWebPushNotification($subscription, $payload)
    {
        try {
            $webPush = new \Minishlink\WebPush\WebPush([
                'VAPID' => [
                    'subject' => config('app.url'),
                    'publicKey' => config('webpush.vapid.public_key'),
                    'privateKey' => config('webpush.vapid.private_key')
                ]
            ]);

            $webPush->sendNotification(
                $subscription->endpoint,
                json_encode($payload),
                $subscription->public_key,
                $subscription->auth_token,
                true
            );
        } catch (\Exception $e) {
            Log::error('Error sending web push notification: ' . $e->getMessage());
            
            // Check if the subscription is invalid
            if (strpos($e->getMessage(), '410 Gone') !== false) {
                // Delete invalid subscription
                $subscription->delete();
            }
        }
    }
}