<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test TOAST UI Calendar</title>
    <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css">
    <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #calendar-controls {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin: 20px 0;
            padding: 0 20px;
        }
        #calendar-controls button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #calendar-controls button:hover {
            background-color: #0056b3;
        }
        #calendar {
            width: 100%;
            max-width: 1200px;
            height: 800px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 10px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        #filter-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
        }
        #filter-container input {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #filter-container button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        #filter-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="calendar-controls">
        <button id="prevMonthBtn">Previous Month</button>
        <button id="nextMonthBtn">Next Month</button>
    </div>
    <div id="calendar"></div>
    <div id="filter-container">
        <input type="text" id="filterUserId" placeholder="Enter User ID">
        <button id="filterBtn">Filter</button>
    </div>

    <div id="eventModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Nieuw Evenement</h2>
            <form id="eventForm">
                <label for="userId">User ID:</label><br>
                <input type="text" id="userId" name="userId"><br>
                <label for="itemId">Item ID:</label><br>
                <select id="itemId" name="itemId"></select><br>
                <label for="description">Beschrijving:</label><br>
                <textarea id="description" name="description"></textarea><br>
                <label for="startDate">Startdatum:</label><br>
                <input type="datetime-local" id="startDate" name="startDate"><br>
                <label for="endDate">Einddatum:</label><br>
                <input type="datetime-local" id="endDate" name="endDate"><br><br>
                <button type="button" id="saveEvent">Opslaan</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const Calendar = tui.Calendar;
            let calendar;
            let allEvents = [];

            function initCalendar(date) {
                calendar = new Calendar('#calendar', {
                    defaultView: 'month',
                    useCreationPopup: false,
                    useDetailPopup: false,
                    calendars: [
                        {
                            id: '1',
                            name: 'My Calendar',
                            color: '#ffffff',
                            bgColor: '#9e5fff',
                            dragBgColor: '#9e5fff',
                            borderColor: '#9e5fff'
                        }
                    ],
                    date: date
                });

                fetch('/bookings')
                    .then(response => response.json())
                    .then(bookings => {
                        allEvents = bookings.map(booking => ({
                            id: booking.id,
                            calendarId: '1',
                            title: `Item ${booking.item_id}`,
                            category: 'time',
                            start: new Date(booking.start_date),
                            end: new Date(booking.end_date),
                            description: booking.description,
                            userId: String(booking.user_id)  // Ensure userId is a string for comparison
                        }));
                        console.log("All Events:", allEvents);  // Log all events to debug
                        calendar.createEvents(allEvents);
                    })
                    .catch(error => console.error('Error fetching bookings:', error));

                calendar.on('selectDateTime', function(event) {
                    selectedStart = event.start;
                    selectedEnd = event.end;
                    document.getElementById('startDate').value = formatDateForInput(selectedStart);
                    document.getElementById('endDate').value = formatDateForInput(selectedEnd);
                    loadGroups();  // Load groups when selecting date/time
                    modal.style.display = 'block';
                });
            }

            let selectedStart, selectedEnd;
            const modal = document.getElementById('eventModal');
            const closeModal = document.getElementsByClassName('close')[0];

            function loadGroups() {
                fetch('/group')
                    .then(response => response.json())
                    .then(groups => {
                        const itemIdSelect = document.getElementById('itemId');
                        itemIdSelect.innerHTML = '';
                        groups.forEach(group => {
                            const option = document.createElement('option');
                            option.value = group.id;
                            option.textContent = group.name;
                            itemIdSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching groups:', error));
            }

            document.getElementById('saveEvent').addEventListener('click', function() {
                const userId = document.getElementById('userId').value;
                const itemId = document.getElementById('itemId').value;
                const description = document.getElementById('description').value;
                const startDate = new Date(document.getElementById('startDate').value);
                const endDate = new Date(document.getElementById('endDate').value);

                if (userId && itemId && startDate && endDate) {
                    const schedule = {
                        user_id: userId,
                        item_id: itemId,
                        description: description,
                        start_date: formatDateForDatabase(startDate),
                        end_date: formatDateForDatabase(endDate, true)
                    };

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch('/bookings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(schedule)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => Promise.reject(text));
                        }
                        return response.json();
                    })
                    .then(data => {
                        const newEvent = {
                            id: data.id,
                            calendarId: '1',
                            title: `Item ${data.item_id}`,
                            category: 'time',
                            start: new Date(data.start_date),
                            end: new Date(data.end_date),
                            description: data.description,
                            userId: String(data.user_id)  // Ensure userId is a string for comparison
                        };
                        allEvents.push(newEvent);
                        calendar.createEvents([newEvent]);
                        modal.style.display = 'none';
                        refreshPage();
                    })
                    .catch(error => {
                        console.error('Error creating booking:', error);
                        alert('Error creating booking: ' + error);
                    });
                } else {
                    alert("Vul alle velden in.");
                }
            });

            closeModal.onclick = function() {
                modal.style.display = "none";
                refreshPage();
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                    refreshPage();
                }
            }

            function formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = String(date.getHours()).padStart(2, '0');
                const minutes = String(date.getMinutes()).padStart(2, '0');
                return `${year}-${month}-${day}T${hours}:${minutes}`;
            }

            function formatDateForDatabase(date, isEndDate = false) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                const hours = isEndDate ? '23' : String(date.getHours()).padStart(2, '0');
                const minutes = isEndDate ? '59' : String(date.getMinutes()).padStart(2, '0');
                const seconds = isEndDate ? '59' : String(date.getSeconds()).padStart(2, '0');
                return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
            }

            function refreshPage() {
                localStorage.setItem('currentMonth', calendar.getDate());
                location.reload();
            }

            const prevMonthBtn = document.getElementById('prevMonthBtn');
            const nextMonthBtn = document.getElementById('nextMonthBtn');

            prevMonthBtn.addEventListener('click', function() {
                calendar.prev();
                saveCurrentMonth();
            });

            nextMonthBtn.addEventListener('click', function() {
                calendar.next();
                saveCurrentMonth();
            });

            function saveCurrentMonth() {
                localStorage.setItem('currentMonth', calendar.getDate());
            }

            const savedMonth = localStorage.getItem('currentMonth');
            if (savedMonth) {
                initCalendar(new Date(savedMonth));
                localStorage.removeItem('currentMonth');
            } else {
                initCalendar(new Date());
            }

            document.getElementById('filterBtn').addEventListener('click', function() {
                const userId = document.getElementById('filterUserId').value;
                if (userId) {
                    const filteredEvents = allEvents.filter(event => event.userId === userId);
                    console.log("Filtered Events:", filteredEvents);  // Log filtered events to debug
                    calendar.clear();
                    calendar.createEvents(filteredEvents);
                } else {
                    calendar.clear();
                    calendar.createEvents(allEvents);
                }
            });
        });
    </script>
</body>
</html>
