var Script = function () {
    /* initialize the external events
     -----------------------------------------------------------------*/
    $('#external-events div.external-event').each(function() {
        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });
    /* initialize the calendar
     -----------------------------------------------------------------*/
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },
       
        events: [
            
            {
                title: 'Sorteo Preliminar 1'
               	,start: new Date(2015,3,20,23,37)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 2'
               	,start: new Date(2015,3,21,00,46)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 3'
               	,start: new Date(2015,3,21,00,46)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 4'
               	,start: new Date(2015,3,21,00,48)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Ronda 1 A'
               	,start: new Date(2015,3,22,01,28)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Ronda 1 B'
               	,start: new Date(2015,3,22,15,26)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Ronda 1 C'
               	,start: new Date(2015,3,22,15,33)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Ronda 1 D'
               	,start: new Date(2015,3,22,15,36)
                
                ,allDay: false
            	
            }
        ]
    });
}();