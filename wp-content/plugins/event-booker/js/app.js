document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth'
      },
      initialDate: '2024-06-19',
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      select: function(arg) {
        var title = prompt('Event Title:');
        if (title) {
          calendar.addEvent({
            title: title,
            start: arg.start,
            end: arg.end,
            allDay: arg.allDay
          })
        }
        calendar.unselect()
      },
      eventClick: function(arg) {
        if (confirm('Are you sure you want to delete this event?')) {
          arg.event.remove()
        }
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: [
        {
          title: 'All Day Event',
          start: '2024-06-19'
        },
        {
          title: 'Long Event',
          start: '2024-06-20'
        },

        {
          title: 'Conference',
          start: '2024-06-21'
        },
        {
          title: 'Meeting',
          start: '2024-06-22'
        },
        {
          title: 'Lunch',
          start: '2024-06-23'
        },
        {
          title: 'Meeting',
          start: '2024-06-24'
        },
        {
          title: 'Happy Hour',
          start: '2024-06-25'
        },
        {
          title: 'Dinner',
          start: '2024-06-26'
        },
        {
          title: 'Birthday Party',
          start: '2024-06-27'
        },
        {
          title: 'Click for Google',
          start: '2024-06-28'
        }
      ]
    });

    calendar.render();
  });