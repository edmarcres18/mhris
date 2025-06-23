@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">MHRPCI Calendar</h1>
    <div id="calendar" class="custom-calendar"></div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css' rel='stylesheet' />
<style>
    .custom-calendar {
        max-width: 900px;
        margin: 0 auto;
        font-family: Arial, sans-serif;
    }
    .fc {
        margin-top: 0 !important;
    }
    .fc-header-toolbar {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .fc-day-today {
        background-color: #e6f3ff !important;
    }
    .fc-event {
        border: none;
        padding: 2px 5px;
        font-size: 0.85em;
        border-radius: 3px;
    }
    .fc-event-title {
        font-weight: bold;
    }
    .fc-daygrid-day-number {
        font-size: 1.2em;
        font-weight: bold;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            list: 'List'
        },
        events: function(info, successCallback, failureCallback) {
            fetch('{{ route("calendar.holidays") }}')
                .then(response => response.json())
                .then(data => {
                    successCallback(data);
                })
                .catch(error => {
                    failureCallback(error);
                });
        },
        eventClick: function(info) {
            Swal.fire({
                title: 'Holiday Details',
                html: `<p><strong>${info.event.title}</strong></p>
                       <p>Date: ${info.event.start.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</p>
                       <p>Country: Philippines</p>`,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        },
        eventDidMount: function(info) {
            info.el.title = info.event.title + '\nDate: ' + info.event.start.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
        },
        dayMaxEvents: true,
        eventColor: '#007bff',
        eventTextColor: '#ffffff',
    });
    calendar.render();

    // Add event listener for country filter
    document.getElementById('country-filter').addEventListener('change', function() {
        var selectedCountry = this.value;
        calendar.getEvents().forEach(function(event) {
            if (selectedCountry === 'all' || event.extendedProps.country === selectedCountry) {
                event.remove();
                calendar.addEvent(event);
            } else {
                event.remove();
            }
        });
    });

    calendar.setOption('dateClick', function(info) {
        var clickedDate = info.date;
        var events = calendar.getEvents().filter(function(event) {
            return event.start.toDateString() === clickedDate.toDateString();
        });

        if (events.length > 0) {
            var holidayList = events.map(function(event) {
                return `<li>${event.title} (${event.extendedProps.country})</li>`;
            }).join('');

            Swal.fire({
                title: 'Holidays on ' + clickedDate.toLocaleDateString(),
                html: `<ul>${holidayList}</ul>`,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        }
    });
});
</script>
@endpush
