<aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ url('/home') }}" class="brand-link">
                <img src="{{ asset('vendor/adminlte/dist/img/whiteICON_APP.png') }}" alt="Task List Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{config('app.name')}}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-1">
                    <div class="user-panel-container d-flex">
                        <div class="image">
                            <img src="{{ Auth::user()->adminlte_image() }}" class="img-circle elevation-2" alt="User Image" data-toggle="tooltip" title="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                        </div>
                        <div class="info">
                            <a href="#" class="d-block text-truncate" data-toggle="tooltip" title="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}">
                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('/home') }}" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        @canany(['admin', 'super-admin', 'hrcompliance', 'vpfinance-admin','finance'])
                        <li class="nav-item">
                            <a href="{{ url('/employees') }}" class="nav-link {{ Request::is('employees*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user-tie"></i>
                                <p>Employee Management</p>
                            </a>
                        </li>
                        @endcanany

                        @auth
                            @if(auth()->user()->hasRole('Employee'))
                        <li class="nav-item">
                            <a href="{{ url('/my-tasks') }}" class="nav-link {{ Request::is('my-tasks') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>My Task</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ url('/messaging') }}" class="nav-link {{ Request::is('messaging') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-envelope"></i>
                                <p>Messaging</p>
                            </a>
                        </li>
                      @endauth
                        @canany(['admin', 'super-admin', 'hrcomben', 'normal-employee','supervisor','vpfinance-admin','finance'])
                        <li class="nav-item has-treeview {{ Request::is('attendances*', 'timesheets*', 'my-timesheet', 'attendance', 'overtime*', 'night-premium*', 'employee-overtime*', 'employee-night-premium*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('attendances*', 'timesheets*', 'my-timesheet', 'attendance', 'overtime*', 'night-premium*', 'employee-overtime*', 'employee-night-premium*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-clock"></i>
                                <p>
                                    Attendance
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin', 'hrcomben','supervisor','vpfinance-admin', 'finance'])
                                <li class="nav-item">
                                    <a href="{{ url('/attendances') }}" class="nav-link {{ Request::is('attendances*') || Request::is('timesheets*') || Request::is('overtime*') || Request::is('night-premium*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list nav-icon"></i>
                                        <p>Attendance</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                <li class="nav-item">
                                    <a href="{{ route('attendances.attendance') }}" class="nav-link {{ Request::is('attendance') ? 'active' : '' }}">
                                        <i class="fas fa-clock nav-icon"></i>
                                        <p>Clock In/Out</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/employee-overtime/apply') }}" class="nav-link {{ Request::is('employee-overtime*') ? 'active' : '' }}">
                                        <i class="fas fa-business-time nav-icon"></i>
                                        <p>Apply Overtime</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/employee-night-premium/apply') }}" class="nav-link {{ Request::is('employee-night-premium*') ? 'active' : '' }}">
                                        <i class="fas fa-moon nav-icon"></i>
                                        <p>Apply Night Premium</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/my-timesheet') }}" class="nav-link {{ Request::is('my-timesheet') ? 'active' : '' }}">
                                        <i class="fas fa-user-clock nav-icon"></i>
                                        <p>My Timesheet</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </li>
                    @endcanany
                    @canany(['admin', 'super-admin', 'hrcomben','normal-employee','supervisor','vpfinance-admin'])
                    <li class="nav-item has-treeview {{ Request::is('leaves*') || Request::is('leaves-employees*') || Request::is('my-leave-sheet*') || Request::is('my-leave-detail*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ Request::is('leaves*') || Request::is('leaves-employees*') || Request::is('my-leave-sheet*') || Request::is('my-leave-detail*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>
                                    Leave Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin', 'hrcomben','supervisor','vpfinance-admin'])
                                <li class="nav-item">
                                    <a href="{{ url('/leaves') }}" class="nav-link {{ Request::is('leaves') || request()->routeIs('leaves.show*') ? 'active' : '' }}">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>Leave List</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                <li class="nav-item">
                                    <a href="{{ url('/leaves/create') }}" class="nav-link {{ Request::is('leaves/create') ? 'active' : '' }}">
                                        <i class="fas fa-calendar-check nav-icon"></i>
                                        <p>Apply Leave</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                                @canany(['admin', 'super-admin', 'hrcomben'])
                                <li class="nav-item">
                                    <a href="{{ url('/leaves-employees') }}" class="nav-link {{ Request::is('leaves-employees*') ? 'active' : '' }}">
                                        <i class="fas fa-file nav-icon"></i>
                                        <p>Leave Sheet</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                <li class="nav-item">
                                    <a href="{{ route('leaves.my_leave_sheet') }}" class="nav-link {{ request()->routeIs('leaves.my_leave_sheet') || request()->routeIs('leaves.myLeaveDetail') ? 'active' : '' }}">
                                        <i class="fas fa-print nav-icon"></i>
                                        <p>My Leaves</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </li>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcomben','finance','normal-employee','vpfinance-admin'])
                        <li class="nav-item has-treeview {{ Request::is('payroll*', 'my-payrolls*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('payroll*', 'my-payrolls*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-coins"></i>
                                <p>
                                    Payroll Management
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin','hrcomben','finance','vpfinance-admin'])
                                <li class="nav-item">
                                    <a href="{{ url('/payroll') }}" class="nav-link {{ Request::is('payroll*') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-wave nav-icon"></i>
                                        <p>Payroll</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee'))
                                <li class="nav-item">
                                    <a href="{{ url('/my-payrolls') }}" class="nav-link {{ Request::is('my-payrolls*') ? 'active' : '' }}">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>My Payroll</p>
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </li>
                        @endcanany
                        @canany(['admin', 'super-admin', 'hrcomben', 'finance', 'normal-employee', 'supervisor','vpfinance-admin'])
                        <li class="nav-item has-treeview {{ Request::is('sss*', 'philhealth*', 'pagibig*', 'loan_sss*','loan_pagibig*', 'cash_advances*', 'my-contributions*', 'my-loans*', 'contributions-employees-list*', 'loans-employees-list*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('sss*', 'philhealth*', 'pagibig*', 'loan_sss*', 'loan_pagibig*', 'cash_advances*', 'my-contributions*', 'my-loans*', 'contributions-employees-list*', 'loans-employees-list*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-hands-helping"></i>
                                <p>
                                    Loans & Contributions
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @canany(['admin', 'super-admin', 'hrcomben', 'finance','vpfinance-admin'])
                                <li class="nav-item">
                                    <a href="{{ url('/sss') }}" class="nav-link {{ Request::is('sss*', 'philhealth*', 'pagibig*','contributions-employees-list') ? 'active' : '' }}">
                                        <i class="fas fa-file-alt nav-icon"></i>
                                        <p>Contributions</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/loan_sss') }}" class="nav-link {{ Request::is('loan_sss*','loan_pagibig*', 'cash_advances*', 'loans-employees-list*') ? 'active' : '' }}">
                                        <i class="fas fa-money-bill-alt nav-icon"></i>
                                        <p>Loans</p>
                                    </a>
                                </li>
                                @endcanany
                                @auth
                                    @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                                    <li class="nav-item">
                                        <a href="{{ route('cash_advances.create') }}" class="nav-link {{ Request::is('cash_advances/create') ? 'active' : '' }}">
                                            <i class="fas fa-money-bill-wave nav-icon"></i>
                                            <p>Apply Company Loan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/my-contributions') }}" class="nav-link {{ Request::is('my-contributions*') ? 'active' : '' }}">
                                            <i class="fas fa-solid fa-gift nav-icon"></i>
                                            <p>My Contribution</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/my-loans') }}" class="nav-link {{ Request::is('my-loans*') || Request::is('cash_advances/*/ledger') ? 'active' : '' }}">
                                            <i class="fas fa-hand-holding-usd nav-icon"></i>
                                            <p>My Loan</p>
                                        </a>
                                    </li>
                                    @endif
                                @endauth
                            </ul>
                        </li>
                        @endcanany
                        @can('hrhiring')
                        <li class="nav-item">
                            <a href="{{ url('/hirings') }}" class="nav-link {{ Request::is('hirings*', 'all-careers*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-briefcase"></i>
                                <p>
                                    Hiring Management
                                    @php
                                        $unreadCareersCount = \App\Models\Career::where('is_read', false)->count();
                                    @endphp
                                    <span id="unread-careers-badge" class="badge badge-danger right {{ $unreadCareersCount > 0 ? '' : 'd-none' }}">{{ $unreadCareersCount }}</span>
                                </p>
                            </a>
                        </li>
                        @endcan

                        @canany(['admin', 'super-admin', 'hrcompliance', 'it-staff', 'hrpolicy'])
                        <li class="nav-item has-treeview {{ Request::is('accountabilities*', 'credentials*', 'inventory*', 'properties*', 'subsidiaries*', 'company-emails*') ? 'menu-open' : '' }}">
                            <a href="#" class="nav-link {{ Request::is('accountabilities*', 'credentials*', 'inventory*', 'properties*', 'subsidiaries*', 'company-emails*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-cogs"></i>
                                <p>
                                    Others
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                @can('hrcompliance')
                                <li class="nav-item">
                                    <a href="{{ url('/accountabilities') }}" class="nav-link {{ Request::is('accountabilities*') ? 'active' : '' }}">
                                        <i class="fas fa-check-circle nav-icon"></i>
                                        <p>Employee Accountability</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/company-emails') }}" class="nav-link {{ Request::is('company-emails*') ? 'active' : '' }}">
                                        <i class="fas fa-envelope nav-icon"></i>
                                        <p>Company Emails</p>
                                    </a>
                                </li>
                                @endcan
                                @can('hrpolicy')
                                <li class="nav-item">
                                    <a href="{{ url('/credentials') }}" class="nav-link {{ Request::is('credentials*') ? 'active' : '' }}">
                                        <i class="fas fa-phone nav-icon"></i>
                                        <p>Contacts and Emails</p>
                                    </a>
                                </li>
                                @endcan
                                @canany(['hrcompliance', 'it-staff'])
                                <li class="nav-item">
                                    <a href="{{ url('/inventory') }}" class="nav-link {{ Request::is('inventory*') ? 'active' : '' }}">
                                        <i class="fas fa-cubes nav-icon"></i>
                                        <p>Inventory</p>
                                    </a>
                                </li>
                                @endcanany
                            </ul>
                        </li>
                        @endcanany
                        @auth
                            @if(auth()->user()->hasRole('Employee') || auth()->user()->hasRole('Supervisor'))
                        <li class="nav-item">
                            <a href="{{ url('/my-profile') }}" class="nav-link {{ Request::is('my-profile*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-user"></i>
                                <p>My Profile</p>
                            </a>
                        </li>
                        @endif
                        @endauth
                        <li class="nav-item">
                            <a href="{{ url('/birthdays') }}" class="nav-link {{ Request::is('birthdays*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-birthday-cake"></i>
                                <p>Birthdays</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('holidays.calendar') }}" class="nav-link {{ Request::is('holidays-calendar') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>MHR Calendar</p>
                            </a>
                        </li>
                        @canany(['product-manager', 'super-admin'])
                        <li class="nav-item">
                            <a href="{{ route('analytics.dashboard') }}" class="nav-link {{ Request::is('analytics*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-pills"></i>
                                <p>MHRHCI Management</p>
                            </a>
                        </li>
                        @endcanany
                        @can('super-admin')
                        <li class="nav-item">
                            <a href="{{ url('/reports') }}" class="nav-link {{ Request::is('reports*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('controller.analysis') }}" class="nav-link {{ request()->routeIs('controller.analysis*') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-sitemap"></i>
                                <p>System Routes Reports</p>
                            </a>
                        </li>
                    </ul>
                    @endcanany
                    @if(auth()->user()->hasRole('Employee'))
                    <li class="nav-item">
                        <a href="{{ url('/get-the-app') }}" class="nav-link {{ Request::is('get-the-app*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mobile-screen-button"></i>
                            <p>Get the App</p> <i class="fas fa-info-circle float-right"></i>
                        </a>
                    </li>
                    @endif
                    
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <script>
            $(document).ready(function() {
                // Function to update the unread careers count
                function updateUnreadCareersCount() {
                    $.ajax({
                        url: '{{ route("careers.unread-count") }}',
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            var badge = $('#unread-careers-badge');
                            if (response.count > 0) {
                                badge.text(response.count).show();
                            } else {
                                badge.hide();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching unread careers count:', xhr.responseText);
                        }
                    });
                }
                
                // Update count every 30 seconds
                setInterval(updateUnreadCareersCount, 30000);
            });
        </script>