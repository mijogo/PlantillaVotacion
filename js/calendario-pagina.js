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
                title: 'Enfrentamiento Exhibición 1 2 '
               	,start: new Date(2015,4,17,00,00)
                ,end: new Date(2015,4,17,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-17'
            },
            {
                title: 'Enfrentamiento Final 1 '
               	,start: new Date(2015,10,01,00,00)
                ,end: new Date(2015,10,01,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-11-01'
            },
            {
                title: 'Enfrentamiento Semifinal 1 '
               	,start: new Date(2015,9,29,00,00)
                ,end: new Date(2015,9,29,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-29'
            },
            {
                title: 'Enfrentamiento Semifinal 2 '
               	,start: new Date(2015,9,30,00,00)
                ,end: new Date(2015,9,30,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-30'
            },
            {
                title: 'Enfrentamiento Cuartos de Final 1 '
               	,start: new Date(2015,9,25,00,00)
                ,end: new Date(2015,9,25,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-25'
            },
            {
                title: 'Enfrentamiento Cuartos de Final 2 '
               	,start: new Date(2015,9,26,00,00)
                ,end: new Date(2015,9,26,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-26'
            },
            {
                title: 'Enfrentamiento Cuartos de Final 3 '
               	,start: new Date(2015,9,27,00,00)
                ,end: new Date(2015,9,27,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-27'
            },
            {
                title: 'Enfrentamiento Cuartos de Final 4 '
               	,start: new Date(2015,9,28,00,00)
                ,end: new Date(2015,9,28,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-28'
            },
            {
                title: 'Enfrentamiento Final de Grupo A-1 B-1 '
               	,start: new Date(2015,9,21,00,00)
                ,end: new Date(2015,9,21,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-21'
            },
            {
                title: 'Enfrentamiento Final de Grupo C-1 D-1 '
               	,start: new Date(2015,9,22,00,00)
                ,end: new Date(2015,9,22,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-22'
            },
            {
                title: 'Enfrentamiento Final de Grupo E-1 F-1 '
               	,start: new Date(2015,9,23,00,00)
                ,end: new Date(2015,9,23,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-23'
            },
            {
                title: 'Enfrentamiento Final de Grupo G-1 H-1 '
               	,start: new Date(2015,9,24,00,00)
                ,end: new Date(2015,9,24,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-24'
            },
            {
                title: 'Enfrentamiento Tercera Ronda A-1 B-1 C-1 D-1 E-1 F-1 G-1 H-1 '
               	,start: new Date(2015,9,19,00,00)
                ,end: new Date(2015,9,19,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-19'
            },
            {
                title: 'Enfrentamiento Tercera Ronda A-2 B-2 C-2 D-2 E-2 F-2 G-2 H-2 '
               	,start: new Date(2015,9,20,00,00)
                ,end: new Date(2015,9,20,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-20'
            },
            {
                title: 'Enfrentamiento Segunda Ronda A-1 A-2 B-1 B-2 C-1 C-2 D-1 D-2 '
               	,start: new Date(2015,9,12,00,00)
                ,end: new Date(2015,9,12,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-12'
            },
            {
                title: 'Enfrentamiento Segunda Ronda A-3 B-3 C-3 D-3 '
               	,start: new Date(2015,9,13,00,00)
                ,end: new Date(2015,9,13,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-13'
            },
            {
                title: 'Enfrentamiento Segunda Ronda A-4 B-4 C-4 D-4 '
               	,start: new Date(2015,9,14,00,00)
                ,end: new Date(2015,9,14,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-14'
            },
            {
                title: 'Enfrentamiento Segunda Ronda E-1 F-1 G-1 H-1 '
               	,start: new Date(2015,9,15,00,00)
                ,end: new Date(2015,9,15,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-15'
            },
            {
                title: 'Enfrentamiento Segunda Ronda E-2 F-2 G-2 H-2 '
               	,start: new Date(2015,9,16,00,00)
                ,end: new Date(2015,9,16,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-16'
            },
            {
                title: 'Enfrentamiento Segunda Ronda E-3 F-3 G-3 H-3 '
               	,start: new Date(2015,9,17,00,00)
                ,end: new Date(2015,9,17,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-17'
            },
            {
                title: 'Enfrentamiento Segunda Ronda E-4 F-4 G-4 H-4 '
               	,start: new Date(2015,9,18,00,00)
                ,end: new Date(2015,9,18,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-18'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-12 B-8 C-1 C-5 '
               	,start: new Date(2015,9,11,00,00)
                ,end: new Date(2015,9,11,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-11'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-2 A-10 B-6 C-9 D-9 F-2 '
               	,start: new Date(2015,9,10,00,00)
                ,end: new Date(2015,9,10,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-10'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-3 B-2 C-4 D-8 E-11 F-12 '
               	,start: new Date(2015,9,09,00,00)
                ,end: new Date(2015,9,09,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-09'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-4 B-4 C-10 E-2 F-4 G-7 H-8 '
               	,start: new Date(2015,9,08,00,00)
                ,end: new Date(2015,9,08,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-08'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-6 B-7 C-12 E-3 F-8 G-11 '
               	,start: new Date(2015,9,07,00,00)
                ,end: new Date(2015,9,07,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-07'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-7 C-2 D-7 E-12 F-10 '
               	,start: new Date(2015,9,06,00,00)
                ,end: new Date(2015,9,06,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-06'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-8 B-11 D-3 E-5 F-5 G-6 '
               	,start: new Date(2015,9,05,00,00)
                ,end: new Date(2015,9,05,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-05'
            },
            {
                title: 'Enfrentamiento Primera Ronda C-3 D-4 E-7 F-11 G-10 '
               	,start: new Date(2015,9,04,00,00)
                ,end: new Date(2015,9,04,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-04'
            },
            {
                title: 'Enfrentamiento Primera Ronda A-11 C-6 D-10 F-1 G-3 G-12 H-4 H-7 '
               	,start: new Date(2015,9,03,00,00)
                ,end: new Date(2015,9,03,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-03'
            },
            {
                title: 'Enfrentamiento Primera Ronda B-1 B-12 D-2 E-6 G-1 H-1 H-10 '
               	,start: new Date(2015,9,02,00,00)
                ,end: new Date(2015,9,02,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-10-02'
            },
            {
                title: 'Enfrentamiento Primera Ronda B-3 C-8 D-6 E-8 F-7 G-8 H-5 '
               	,start: new Date(2015,8,30,00,00)
                ,end: new Date(2015,8,30,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-09-30'
            },
            {
                title: 'Enfrentamiento Primera Ronda B-5 D-1 E-1 F-6 G-4 H-6 '
               	,start: new Date(2015,8,29,00,00)
                ,end: new Date(2015,8,29,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-09-29'
            },
            {
                title: 'Enfrentamiento Primera Ronda B-9 C-11 D-12 E-10 G-9 H-12 '
               	,start: new Date(2015,8,28,00,00)
                ,end: new Date(2015,8,28,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-09-28'
            },
            {
                title: 'Enfrentamiento Primera Ronda B-10 E-9 H-2 H-11 '
               	,start: new Date(2015,8,27,00,00)
                ,end: new Date(2015,8,27,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-09-27'
            },
            {
                title: 'Enfrentamiento Primera Ronda C-7 D-5 D-11 E-4 F-3 F-9 G-2 G-5 H-3 H-9 '
               	,start: new Date(2015,8,26,00,00)
                ,end: new Date(2015,8,26,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-09-26'
            },
            {
                title: 'Enfrentamiento Preliminar 1 '
               	,start: new Date(2015,4,23,00,00)
                ,end: new Date(2015,4,23,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-23'
            },
            {
                title: 'Sorteo Preliminar 1'
               	,start: new Date(2015,4,22,00,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 2'
               	,start: new Date(2015,4,22,00,20)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 3'
               	,start: new Date(2015,4,22,00,40)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 4'
               	,start: new Date(2015,4,22,00,40)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 5'
               	,start: new Date(2015,4,22,00,40)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 6'
               	,start: new Date(2015,4,22,00,40)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 7'
               	,start: new Date(2015,4,22,00,40)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 8'
               	,start: new Date(2015,4,22,05,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 9'
               	,start: new Date(2015,4,22,05,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 10'
               	,start: new Date(2015,4,22,05,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 11'
               	,start: new Date(2015,4,22,05,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 12'
               	,start: new Date(2015,4,22,05,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 13'
               	,start: new Date(2015,4,22,06,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 14'
               	,start: new Date(2015,4,22,06,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 15'
               	,start: new Date(2015,4,22,06,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Preliminar 16'
               	,start: new Date(2015,4,22,06,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda A'
               	,start: new Date(2015,4,23,18,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda B'
               	,start: new Date(2015,4,24,12,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda C'
               	,start: new Date(2015,4,24,12,30)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda D'
               	,start: new Date(2015,4,24,13,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda E'
               	,start: new Date(2015,4,24,13,30)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda F'
               	,start: new Date(2015,4,24,14,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda G'
               	,start: new Date(2015,4,24,14,30)
                
                ,allDay: false
            	
            },
            {
                title: 'Sorteo Primera Ronda H'
               	,start: new Date(2015,4,24,15,00)
                
                ,allDay: false
            	
            },
            {
                title: 'Enfrentamiento Primera Ronda A-1 A-5 A-9 '
               	,start: new Date(2015,4,25,00,00)
                ,end: new Date(2015,4,25,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-25'
            },
            {
                title: 'Enfrentamiento Preliminar 2 3 '
               	,start: new Date(2015,4,26,00,00)
                ,end: new Date(2015,4,26,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-26'
            },
            {
                title: 'Enfrentamiento Preliminar 4 '
               	,start: new Date(2015,4,27,00,00)
                ,end: new Date(2015,4,27,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-27'
            },
            {
                title: 'Enfrentamiento Preliminar 5 '
               	,start: new Date(2015,4,28,00,00)
                ,end: new Date(2015,4,28,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-28'
            },
            {
                title: 'Enfrentamiento Preliminar 6 '
               	,start: new Date(2015,4,29,00,00)
                ,end: new Date(2015,4,29,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-29'
            },
            {
                title: 'Enfrentamiento Preliminar 7 '
               	,start: new Date(2015,4,30,00,00)
                ,end: new Date(2015,4,30,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-30'
            },
            {
                title: 'Enfrentamiento Preliminar 8 '
               	,start: new Date(2015,4,31,00,00)
                ,end: new Date(2015,4,31,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-05-31'
            },
            {
                title: 'Enfrentamiento Preliminar 9 '
               	,start: new Date(2015,5,01,00,00)
                ,end: new Date(2015,5,01,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-01'
            },
            {
                title: 'Enfrentamiento Preliminar 10 '
               	,start: new Date(2015,5,02,00,00)
                ,end: new Date(2015,5,02,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-02'
            },
            {
                title: 'Enfrentamiento Preliminar 11 '
               	,start: new Date(2015,5,03,00,00)
                ,end: new Date(2015,5,03,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-03'
            },
            {
                title: 'Enfrentamiento Preliminar 12 '
               	,start: new Date(2015,5,04,00,00)
                ,end: new Date(2015,5,04,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-04'
            },
            {
                title: 'Enfrentamiento Preliminar 13 '
               	,start: new Date(2015,5,05,00,00)
                ,end: new Date(2015,5,05,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-05'
            },
            {
                title: 'Enfrentamiento Preliminar 14 '
               	,start: new Date(2015,5,06,00,00)
                ,end: new Date(2015,5,06,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-06'
            },
            {
                title: 'Enfrentamiento Preliminar 15 '
               	,start: new Date(2015,5,07,00,00)
                ,end: new Date(2015,5,07,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-07'
            },
            {
                title: 'Enfrentamiento Preliminar 16 '
               	,start: new Date(2015,5,08,00,00)
                ,end: new Date(2015,5,08,20,00)
                
            	,url: 'resultados.php?tipo=fecha&fecha=2015-06-08'
            }
        ]
    });
}();