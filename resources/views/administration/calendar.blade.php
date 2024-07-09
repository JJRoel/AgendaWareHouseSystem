<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css">
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    <style>
        #calendar {
            width: 100%;
            height: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Calendar = tui.Calendar;
            const calendar = new Calendar('#calendar', {
                defaultView: 'month',
                useCreationPopup: true,
                useDetailPopup: true,
                calendars: [
                    {
                        id: '1',
                        name: 'My Calendar',
                        color: '#ffffff',
                        bgColor: '#9e5fff',
                        dragBgColor: '#9e5fff',
                        borderColor: '#9e5fff'
                    }
                ]
            });

            // Fetch existing bookings from the server
            fetch('/bookings')
                .then(response => response.json())
                .then(bookings => {
                    const parsedEvents = bookings.map(booking => ({
                        id: booking.id,
                        calendarId: '1',
                        title: `Item ${booking.item_id}`, // Display item_id as title
                        category: 'time',
                        start: new Date(booking.start_date),
                        end: new Date(booking.end_date)
                    }));
                    calendar.createEvents(parsedEvents);
                })
                .catch(error => console.error('Error fetching bookings:', error));

            // Event listeners for popups
            calendar.on('beforeCreateSchedule', function(event) {
                const schedule = {
                    item_id: parseInt(prompt('Enter item ID:', '1')),  // Replace with actual input method
                    user_id: parseInt(prompt('Enter user ID:', '1')),  // Replace with actual input method
                    start_date: event.start.toISOString(),
                    end_date: event.end.toISOString()
                };

                fetch('/bookings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(schedule)
                })
                .then(response => response.json())
                .then(data => {
                    calendar.createEvents([{
                        id: data.id,
                        calendarId: '1',
                        title: `Item ${data.item_id}`,
                        category: 'time',
                        start: new Date(data.start_date),
                        end: new Date(data.end_date)
                    }]);
                })
                .catch(error => console.error('Error creating booking:', error));
            });

            calendar.on('beforeUpdateSchedule', function(event) {
                const { schedule, changes } = event;

                fetch(`/bookings/${schedule.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        item_id: schedule.item_id,
                        user_id: schedule.user_id,
                        ...changes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    calendar.updateSchedule(schedule.id, schedule.calendarId, changes);
                })
                .catch(error => console.error('Error updating booking:', error));
            });

            calendar.on('beforeDeleteSchedule', function(event) {
                const { schedule } = event;

                fetch(`/bookings/${schedule.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(() => {
                    calendar.deleteSchedule(schedule.id, schedule.calendarId);
                })
                .catch(error => console.error('Error deleting booking:', error));
            });

            // Custom handler for date selection to trigger the creation popup
            calendar.on('selectDateTime', function(event) {
                const triggerPopupEvent = new CustomEvent('beforeCreateSchedule', {
                    detail: {
                        start: event.start,
                        end: event.end,
                        isAllDay: event.isAllDay,
                        calendarId: '1'
                    }
                });
                document.querySelector('#calendar').dispatchEvent(triggerPopupEvent);
            });

            calendar.on('clickSchedule', function(event) {
                calendar.openDetailPopup(event.schedule);
            });

            calendar.on('clickMore', function(event) {
                calendar.openDetailPopup(event.schedule);
            });
        });
    </script>
</body>
</html>
