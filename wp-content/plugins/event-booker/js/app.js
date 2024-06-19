document.addEventListener('DOMContentLoaded', function() {

    const calendarEvents = variables.allevents;
    const availableEvents = calendarEvents.map((calendarEvent)=>{
      return {
        title: calendarEvent?.title,
        start: calendarEvent?.start_date,
        end: calendarEvent?.end_date,
      }
    })

    var calendarEl = document.getElementById('calendar');
    var modal = jQuery('#modalWindow');
    var modalContent = jQuery('#modalWindowContent');

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
        
        
        calendar.unselect()
      },
      eventClick: function(arg) {
        const title = arg?.event?.title;
        if(title){
          getEventData(title);
        }
      },
      editable: true,
      dayMaxEvents: true, // allow "more" link when too many events
      events: availableEvents,
    });

    calendar.render();


    const getEventData = (title) => {
      const data = {
        action: "get_event_data_action",
        title: title,
      };
      jQuery.ajax({
        url: variables.ajaxurl,
        type: "POST",
        data: data,
        success: function (response) {
          renderModalWindow(response);
        },
      });
    }

    const renderModalWindow = (response)=>{
      modalContent.html(response);
      modal.show();
      // console.log(response)
    }
    jQuery('#modalWindow .close').on('click', function() {
      modal.hide();
  });
  });