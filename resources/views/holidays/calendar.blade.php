@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 px-md-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 gap-md-0">
                        <h5 class="mb-0 fs-5 text-primary fw-bold">Holiday Calendar</h5>
                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-start align-items-sm-center">
                            <div class="calendar-controls d-flex align-items-center">
                                <input type="month" id="monthYearFilter" class="form-control form-control-sm rounded-pill" 
                                    value="{{ date('Y-m') }}">
                            </div>
                            @can('holiday-create')
                            <div>
                                <a href="{{ route('holidays.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
                                    <i class="fas fa-plus me-1"></i> Add Holiday
                                </a>
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body p-2 p-md-4">
                    <div id="calendarContainer">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Legend -->
<div class="container-fluid px-2 px-md-4 mt-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fs-6 fw-bold">Calendar Legend</h6>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-danger me-2" style="width: 20px; height: 20px;">&nbsp;</span>
                                <div class="small">
                                    <strong>Regular Holiday</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-warning me-2" style="width: 20px; height: 20px;">&nbsp;</span>
                                <div class="small">
                                    <strong>Special Non-Working</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info me-2" style="width: 20px; height: 20px;">&nbsp;</span>
                                <div class="small">
                                    <strong>Special Working</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 20px; height: 20px;">&nbsp;</span>
                                <div class="small">
                                    <strong>Pay Day</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2" style="width: 20px; height: 20px;">&nbsp;</span>
                                <div class="small">
                                    <strong>Quarterly Review</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Holiday Modal -->
<div class="modal fade" id="addHolidayModal" tabindex="-1" aria-labelledby="addHolidayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold" id="addHolidayModalLabel">Add Holiday</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addHolidayForm" action="{{ route('holidays.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Holiday Name</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="Regular Holiday">Regular Holiday</option>
                            <option value="Special Non-Working Holiday">Special Non-Working Holiday</option>
                            <option value="Special Working Holiday">Special Working Holiday</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Holiday</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<style>
    /* Base calendar styling */
    #calendarContainer {
        overflow-x: auto;
        width: 100%;
        -webkit-overflow-scrolling: touch;
    }
    
    #calendar {
        margin: 0;
        background: white;
        min-width: 300px;
    }
    
    /* FullCalendar Overrides */
    .fc-toolbar {
        padding: 10px 5px;
        margin-bottom: 0 !important;
    }
    
    .fc-view-container {
        background-color: #fff;
        border-radius: 0 0 4px 4px;
    }
    
    .fc-day-header {
        padding: 8px 0 !important;
        font-weight: 600 !important;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    
    .fc-day-number {
        font-weight: 500;
        font-size: 0.9rem;
        padding: 5px 8px !important;
    }
    
    .fc-day-top {
        text-align: right;
    }
    
    .fc-day-grid-event {
        border-radius: 20px;
        padding: 3px 8px !important;
        margin: 1px 3px !important;
        border: none !important;
        transition: all 0.2s ease;
    }
    
    .fc-day-grid-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 5px rgba(0,0,0,0.1);
    }
    
    .fc-day-grid-event .fc-content {
        white-space: normal;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.8rem;
        font-weight: 500;
        line-height: 1.3;
    }
    
    /* Today highlight */
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.05) !important;
    }
    
    /* Hover effect on days */
    .fc-day:hover {
        background-color: rgba(13, 110, 253, 0.03);
        cursor: pointer;
    }
    
    /* Event color classes */
    .holiday-regular {
        background-color: #dc3545 !important;
        color: #fff !important;
    }
    
    .holiday-special {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    .holiday-special-working {
        background-color: #0dcaf0 !important;
        color: #212529 !important;
    }
    
    .pay-day {
        background-color: #198754 !important;
        color: #fff !important;
    }
    
    .quarterly-sales {
        background-color: #0d6efd !important;
        color: #fff !important;
    }
    
    /* Calendar Controls */
    .calendar-controls {
        position: relative;
    }
    
    #monthYearFilter {
        min-width: 140px;
        border-color: #dee2e6;
    }
    
    /* Tooltip Styles */
    .holiday-tooltip-inner {
        max-width: 280px;
        padding: 10px;
        text-align: left;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .holiday-tooltip .holiday-title {
        font-size: 1rem;
        margin-bottom: 8px;
        color: #212529;
    }
    
    .holiday-tooltip .holiday-type {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .holiday-tooltip .holiday-date {
        font-size: 0.85rem;
        color: #495057;
        font-weight: 500;
    }
    
    .tooltip-inner.holiday-tooltip-inner {
        background-color: white;
        color: #212529;
        border: 1px solid #dee2e6;
    }
    
    /* Mobile Event Modal */
    #mobileEventsModal .modal-content {
        border-radius: 12px;
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    #mobileEventsModal .modal-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 15px 20px;
    }
    
    #mobileEventsModal .modal-body {
        padding: 20px;
    }
    
    #mobileEventsModal .modal-footer {
        border-top: 1px solid rgba(0,0,0,0.05);
        padding: 15px 20px;
    }
    
    /* Responsive Calendar Styles */
    /* Tablets */
    @media screen and (max-width: 992px) {
        .fc-toolbar h2 {
            font-size: 1.3rem;
        }
        
        .fc-toolbar .fc-button {
            padding: 0.3rem 0.6rem;
        }
        
        .fc-day-grid-event .fc-content {
            font-size: 0.75rem;
        }
    }
    
    /* Mobile Phones */
    @media screen and (max-width: 768px) {
        .fc-toolbar {
            flex-direction: column;
            gap: 10px;
        }
        
        .fc-toolbar .fc-left,
        .fc-toolbar .fc-center,
        .fc-toolbar .fc-right {
            float: none;
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .fc-toolbar h2 {
            font-size: 1.2rem;
        }
        
        .fc-day-grid-event {
            padding: 2px 5px !important;
            margin: 1px 2px !important;
            border-radius: 15px;
        }
        
        .fc-day-grid-event .fc-content {
            font-size: 0.7rem;
            max-height: 2.4em;
        }
        
        .fc-basic-view .fc-body .fc-row {
            min-height: 3em;
        }
    }
    
    /* Very Small Screens */
    @media screen and (max-width: 480px) {
        .fc-toolbar button {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }
        
        .fc-toolbar h2 {
            font-size: 1rem;
        }
        
        .fc-basic-view .fc-day-number {
            font-size: 0.8em;
            padding: 2px 4px !important;
        }
        
        .fc-basic-view .fc-day-header {
            font-size: 0.75em;
            padding: 5px 2px !important;
        }
        
        /* Force list view on very small screens */
        .fc-view-container .fc-month-view {
            display: none;
        }
        
        .fc-view-container .fc-listMonth-view {
            display: block !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const holidays = @json($holidays);
        const payDays = @json($payDays ?? []);
        const quarterlySales = @json($quarterlySales ?? []);
        
        // Function to determine holiday class based on type
        function getHolidayClass(type) {
            if (type === 'Regular Holiday') {
                return 'holiday-regular';
            } else if (type === 'Special Non-Working Holiday') {
                return 'holiday-special';
            } else if (type === 'Special Working Holiday') {
                return 'holiday-special-working';
            } else {
                return 'holiday-regular';
            }
        }
        
        // Combine all events
        const allEvents = [
            ...holidays.map(holiday => ({
                title: holiday.title,
                start: holiday.date,
                className: getHolidayClass(holiday.type),
                allDay: true,
                description: holiday.type
            }))
        ];
        
        // Add pay days if they exist
        if (payDays && payDays.length > 0) {
            allEvents.push(...payDays.map(payDay => ({
                title: payDay.title,
                start: payDay.date,
                className: 'pay-day',
                allDay: true,
                description: 'Pay Day'
            })));
        }
        
        // Add quarterly sales if they exist
        if (quarterlySales && quarterlySales.length > 0) {
            allEvents.push(...quarterlySales.map(quarter => ({
                title: quarter.title,
                start: quarter.date,
                className: 'quarterly-sales',
                allDay: true,
                description: 'Quarterly Sales Review'
            })));
        }
        
        // Determine default view based on screen size
        function getInitialView() {
            if (window.innerWidth < 480) {
                return 'listMonth';
            } else if (window.innerWidth < 768) {
                return 'basicWeek';
            } else {
                return 'month';
            }
        }
        
        const calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,listMonth'
            },
            buttonText: {
                today: 'Today',
                month: 'Month',
                week: 'Week',
                list: 'List'
            },
            views: {
                month: {
                    titleFormat: 'MMMM YYYY',
                    eventLimit: 2
                },
                basicWeek: {
                    titleFormat: 'MMM D YYYY',
                    eventLimit: false
                },
                listMonth: {
                    titleFormat: 'MMMM YYYY',
                }
            },
            events: allEvents,
            eventRender: function(event, element) {
                // Add a small indicator icon based on event type
                let iconClass = '';
                if (event.description === 'Regular Holiday') {
                    iconClass = 'fas fa-star fa-fw';
                } else if (event.description === 'Special Non-Working Holiday') {
                    iconClass = 'far fa-star fa-fw';
                } else if (event.description === 'Pay Day') {
                    iconClass = 'fas fa-money-bill-wave fa-fw';
                } else if (event.description.includes('Quarterly')) {
                    iconClass = 'fas fa-chart-line fa-fw';
                } else {
                    iconClass = 'far fa-calendar-check fa-fw';
                }
                
                // Only add icons on non-list views - list view already shows type
                if (!$('.fc-list-view').is(':visible')) {
                    const icon = '<i class="' + iconClass + ' me-1" style="font-size: 0.85em;"></i>';
                    element.find('.fc-title').prepend(icon);
                }
                
                // Create tooltip content
                $(element).tooltip({
                    title: '<div class="holiday-tooltip">' +
                           '<div class="holiday-title"><strong>' + event.title + '</strong></div>' +
                           '<div class="holiday-type"><i class="' + iconClass + '"></i> ' + event.description + '</div>' +
                           '<div class="holiday-date"><i class="far fa-calendar-alt me-1"></i>' + moment(event.start).format('dddd, MMMM D, YYYY') + '</div>' +
                           '</div>',
                    html: true,
                    container: 'body',
                    placement: 'auto',
                    template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner holiday-tooltip-inner"></div></div>'
                });
                
                return true;
            },
            viewRender: function(view) {
                // Update the month/year filter when the calendar view changes
                const currentDate = $('#calendar').fullCalendar('getDate');
                $('#monthYearFilter').val(moment(currentDate).format('YYYY-MM'));
            },
            windowResize: function(view) {
                // Only check once per 300ms to avoid multiple triggers
                clearTimeout(window.resizedFinished);
                window.resizedFinished = setTimeout(function() {
                    // Adjust view based on screen width
                    if (window.innerWidth < 480) {
                        $('#calendar').fullCalendar('changeView', 'listMonth');
                    } else if (window.innerWidth < 768) {
                        $('#calendar').fullCalendar('changeView', 'basicWeek');
                    } else if (window.innerWidth >= 768 && view.name !== 'month') {
                        $('#calendar').fullCalendar('changeView', 'month');
                    }
                }, 300);
            },
            defaultView: getInitialView(),
            height: 'auto',
            aspectRatio: 1.5,
            navLinks: true,
            eventLimit: true,
            eventLimitText: function(n) {
                return '+' + n + ' more';
            },
            handleWindowResize: true,
            themeSystem: 'bootstrap4'
        });
        
        // Month/Year filter handling
        $('#monthYearFilter').on('change', function() {
            const date = moment($(this).val() + '-01');
            $('#calendar').fullCalendar('gotoDate', date);
        });
        
        // Add modal for mobile event viewing
        if (!document.getElementById('mobileEventsModal')) {
            const modalHTML = '<div class="modal fade" id="mobileEventsModal" tabindex="-1" aria-labelledby="mobileEventsModalLabel" aria-hidden="true">' +
                '<div class="modal-dialog modal-dialog-centered">' +
                    '<div class="modal-content">' +
                        '<div class="modal-header">' +
                            '<h5 class="modal-title" id="mobileEventsModalLabel">Events</h5>' +
                            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                        '</div>' +
                        '<div class="modal-body" id="mobileEventsModalBody"></div>' +
                        '<div class="modal-footer">' +
                            '<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';
            
            document.body.insertAdjacentHTML('beforeend', modalHTML);
        }
        
        // Handle click on date
        $('#calendar').on('click', '.fc-day-top', function() {
            if (window.innerWidth < 768) {
                const date = $(this).data('date');
                showEventsForDate(date);
            }
        });
        
        // Also handle date click on day cells
        $('#calendar').on('click', '.fc-day', function() {
            if (window.innerWidth < 768) {
                const date = $(this).data('date');
                if (date) showEventsForDate(date);
            }
        });
        
        // Function to show events for a specific date
        function showEventsForDate(date) {
            if (!date) return;
            
            const eventsForDate = allEvents.filter(function(event) {
                return moment(event.start).format('YYYY-MM-DD') === date;
            });
            
            if (eventsForDate.length > 0) {
                let eventList = '';
                eventsForDate.forEach(function(event) {
                    let iconClass = '';
                    if (event.description === 'Regular Holiday') {
                        iconClass = 'fas fa-star text-danger';
                    } else if (event.description === 'Special Non-Working Holiday') {
                        iconClass = 'far fa-star text-warning';
                    } else if (event.description === 'Special Working Holiday') {
                        iconClass = 'fas fa-briefcase text-info';
                    } else if (event.description === 'Pay Day') {
                        iconClass = 'fas fa-money-bill-wave text-success';
                    } else if (event.description.includes('Quarterly')) {
                        iconClass = 'fas fa-chart-line text-primary';
                    }
                    
                    eventList += '<div class="p-2 border-bottom">' +
                                '<div class="d-flex align-items-center mb-1">' +
                                '<i class="' + iconClass + ' me-2"></i>' +
                                '<strong>' + event.title + '</strong>' +
                                '</div>' +
                                '<div class="small text-muted ps-4">' + event.description + '</div>' +
                                '</div>';
                });
                
                // Update and show Bootstrap modal
                document.getElementById('mobileEventsModalLabel').textContent = 
                    'Events on ' + moment(date).format('MMMM D, YYYY');
                document.getElementById('mobileEventsModalBody').innerHTML = eventList;
                
                const modal = new bootstrap.Modal(document.getElementById('mobileEventsModal'));
                modal.show();
            } else {
                // Show "No events" message
                document.getElementById('mobileEventsModalLabel').textContent = 
                    moment(date).format('MMMM D, YYYY');
                document.getElementById('mobileEventsModalBody').innerHTML = 
                    '<div class="p-3 text-center text-muted">No events scheduled for this date</div>';
                
                const modal = new bootstrap.Modal(document.getElementById('mobileEventsModal'));
                modal.show();
            }
        }
        
        // Add swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        const calendarEl = document.getElementById('calendar');
        
        calendarEl.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, false);
        
        calendarEl.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, false);
        
        function handleSwipe() {
            const threshold = 50; // minimum distance for swipe
            if (touchEndX < touchStartX - threshold) {
                // Swipe left - next
                $('#calendar').fullCalendar('next');
            } else if (touchEndX > touchStartX + threshold) {
                // Swipe right - prev
                $('#calendar').fullCalendar('prev');
            }
        }
    });
</script>
@endpush
