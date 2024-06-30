<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOAST UI Calendar with MySQL</title>
    
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
    <style>
        #calendar {
            height: 800px;
        }
    </style>
</head>
<body>
    <div id="calendar"></div>
    
    <script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.js"></script>
    <script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.js"></script>
    <script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.js"></script>
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const Calendar = tui.Calendar;
            const calendar = new Calendar('#calendar', {
                defaultView: 'month',
                taskView: true,
                scheduleView: true
            });

            fetch('bookings')
                .then(response => response.json())
                .then(data => {
                    const schedules = data.map(booking => ({
                        id: booking.id,
                        calendarId: String(booking.item_id),
                        title: `User ${booking.user_id}`,
                        category: 'time',
                        start: booking.start_date,
                        end: booking.end_date
                    }));

                    calendar.createSchedules(schedules);
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
</body>
</html>