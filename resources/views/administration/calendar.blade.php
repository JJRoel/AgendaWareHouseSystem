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
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .popup.active {
            display: block;
        }
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 500;
        }
        .popup-overlay.active {
            display: block;
        }
    </style>
</head>
<body>
    <div id="calendar"></div>

    <div class="popup-overlay" id="popup-overlay"></div>
    <div class="popup" id="popup">
        <form id="popup-form">
            <input type="hidden" id="event-id" name="id">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <label for="start">Start Date:</label>
            <input type="datetime-local" id="start" name="start" required>
            <label for="end">End Date:</label>
            <input type="datetime-local" id="end" name="end" required>
            <button type="submit">Save Event</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Calendar = tui.Calendar;
            const calendar = new Calendar('#calendar', {
                defaultView: 'month',
                useCreationPopup: false,
                useDetailPopup: false
            });

            fetch('/bookings')
                .then(response => response.json())
                .then(bookings => {
                    const parsedEvents = bookings.map(booking => ({
                        id: booking.id,
                        calendarId: booking.id,
                        title: `Item ${booking.item_id}`,
                        start: booking.start_date,
                        end: booking.end_date
                    }));
                    calendar.createEvents(parsedEvents);
                })
                .catch(error => console.error('Error fetching bookings:', error));

            const popup = document.getElementById('popup');
            const popupOverlay = document.getElementById('popup-overlay');
            const popupForm = document.getElementById('popup-form');

            function openPopup() {
                popup.classList.add('active');
                popupOverlay.classList.add('active');
            }

            function closePopup() {
                popup.classList.remove('active');
                popupOverlay.classList.remove('active');
            }

            popupOverlay.addEventListener('click', closePopup);

            // Handle creating a new event
            calendar.on('beforeCreateSchedule', function(event) {
                openPopup();
                popupForm.reset();
                document.getElementById('event-id').value = '';
                document.getElementById('title').value = '';
                document.getElementById('start').value = event.start.toISOString().slice(0, 16);
                document.getElementById('end').value = event.end.toISOString().slice(0, 16);
            });

            // Handle editing an existing event
            calendar.on('clickSchedule', function(event) {
                const { schedule } = event;
                if (schedule) {
                    openPopup();
                    popupForm.reset();
                    document.getElementById('event-id').value = schedule.id;
                    document.getElementById('title').value = schedule.title;
                    document.getElementById('start').value = new Date(schedule.start).toISOString().slice(0, 16);
                    document.getElementById('end').value = new Date(schedule.end).toISOString().slice(0, 16);
                } else {
                    console.error('Schedule is undefined');
                }
            });

            popupForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(popupForm);
                const eventId = formData.get('id');
                const newEvent = {
                    id: eventId || String(Math.random()), // Generate a random ID for new events
                    calendarId: '1',
                    title: formData.get('title'),
                    category: 'time',
                    dueDateClass: '',
                    start: formData.get('start'),
                    end: formData.get('end')
                };

                if (eventId) {
                    // Update existing event
                    fetch(`/bookings/${eventId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(newEvent)
                    }).then(response => response.json())
                      .then(data => {
                          calendar.updateSchedule(data.id, data.calendarId, {
                              title: data.title,
                              start: new Date(data.start),
                              end: new Date(data.end)
                          });
                          closePopup();
                      }).catch(error => console.error('Error updating event:', error));
                } else {
                    // Create new event
                    fetch('/bookings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(newEvent)
                    }).then(response => response.json())
                      .then(data => {
                          calendar.createEvents([data]);
                          closePopup();
                      }).catch(error => console.error('Error creating event:', error));
                }
            });

            // Enable creation by selecting dates
            calendar.on('click', function(event) {
                if (event.date) {
                    openPopup();
                    popupForm.reset();
                    document.getElementById('event-id').value = '';
                    document.getElementById('title').value = '';
                    document.getElementById('start').value = event.date.toISOString().slice(0, 16);
                    document.getElementById('end').value = event.date.toISOString().slice(0, 16);
                }
            });
        });
    </script>
</body>
</html>
