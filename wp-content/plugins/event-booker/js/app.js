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

    // Calendar
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

    // Modal Window:
    const renderModalWindow = (response)=>{
      modalContent.html(response);
      modal.show();
      const dynamicJsUrl = variables.plugindir + '/js/app-dynamic.js';
        loadScript(dynamicJsUrl)
        .then( data  => {
            console.log("Script loaded successfully", data);
        })
        .catch( err => {
            console.error(err);
        });
    }
    jQuery('#modalWindow .close').on('click', function() {
      modal.hide();
    });

    // dynamically load js 
    const loadScript = (FILE_URL, async = true, type = "text/javascript") => {
      return new Promise((resolve, reject) => {
          try {

              tryToRemoveScript(FILE_URL);

              const scriptEle = document.createElement("script");
              scriptEle.type = type;
              scriptEle.async = async;
              scriptEle.src =FILE_URL;
  
              scriptEle.addEventListener("load", (ev) => {
                  resolve({ status: true });
              });
  
              scriptEle.addEventListener("error", (ev) => {
                  reject({
                      status: false,
                      message: `Failed to load the script ${FILE_URL}`
                  });
              });
  
              document.body.appendChild(scriptEle);
          } catch (error) {
              reject(error);
          }
      });
  };

  const tryToRemoveScript = (FILE_URL) => {
    var scriptTag = document.querySelector(`script[src="${FILE_URL}"]`);

    if (scriptTag) {
        scriptTag.parentNode.removeChild(scriptTag);
    }
  }
});