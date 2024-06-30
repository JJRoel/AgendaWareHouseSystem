<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
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
                useDetailPopup: true
            });

            fetch('/api/bookings')
                .then(response => response.json())
                .then(bookings => {
                    const parsedEvents = bookings.map(booking => ({
                        id: booking.id,
                        calendarId: booking.item_id,
                        title: `User ${booking.user_id}`,
                        category: 'time',
                        dueDateClass: '',
                        start: booking.start_at,
                        end: booking.end_at
                    }));

                    calendar.createEvents(parsedEvents);
                })
                .catch(error => console.error('Error fetching bookings:', error));
        });
    </script>
</body>
</html>
