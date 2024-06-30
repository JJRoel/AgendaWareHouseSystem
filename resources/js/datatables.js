document.write('<script src="https://code.jquery.com/jquery-3.5.1.js"><\/script>');
document.write('<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"><\/script>');
document.write('<script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"><\/script>');

$(document).ready(function() {
    $('#example').DataTable({
        columnDefs: [
            {
                targets: [0],
                orderData: [0, 1]
            },
            {
                targets: [1],
                orderData: [1, 0]
            },
            {
                targets: [4],
                orderData: [4, 0]
            }
        ]
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var Calendar = tui.Calendar;
    var calendar = new Calendar('#calendar', {
        defaultView: 'month',
        taskView: true,
        scheduleView: ['time'],
        useCreationPopup: true,
        useDetailPopup: true
    });

    // Example schedules
    calendar.createSchedules([
        {
            id: '1',
            calendarId: '1',
            title: 'Sample Schedule',
            category: 'time',
            dueDateClass: '',
            start: '2023-01-18T22:30:00+09:00',
            end: '2023-01-19T02:30:00+09:00'
        }
    ]);
});
