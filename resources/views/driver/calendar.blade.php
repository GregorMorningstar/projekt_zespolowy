<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center py-5 bg-center">
            {{ __('Kalendarz z zleceniami') }}
        </h2>

        @include('driver.nav.sidebarDriver')
        <meta name="csrf-token" content="{{ csrf_token() }}"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/pl.js"></script>

        <!-- Custom CSS for styling -->
        <style>
            .fc {
                background-color: white;
                color: black;
                margin-left: auto;
                margin-right: auto;
            }
            .fc-toolbar h2 {
                color: black;
            }
            .fc-day-header {
                background-color: #f8f9fa;
                color: black;
            }
            .fc-day {
                background-color: white;
            }
            .fc-today {
                background-color: #d3d3d3;
                color: black;
            }
            .fc-event-dot {
                border-color: black;
                background-color: black; /* Kolor kropki zdarzenia */
            }
            .fc-event .fc-content {
                color: black; /* Kolor tekstu zdarzenia */
            }
            .fc-title-null {
                color: red; /* Kolor tekstu dla zadań z null */
            }
        </style>
    </x-slot>

    <div class="container mt-5 text-black bg-center">
        <div class="row justify-content-center">
            <div class="col-12">
                <h3 class="text-center mb-4">Zlecenia w kalendarzu</h3>
                <div id="calendar" style="max-width: 900px; margin: auto;"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Convert PHP orders to JavaScript
            var orders = @json($orders);

            // Initialize FullCalendar
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay',
                },
                locale: 'pl',
                events: orders.map(function(order) {
                    var title = '';

                    if (order.odjazd_zaladunek === null || order.odjazd_dostawa === null) {
                        title =  order.order.miejsce_zaladunku + ' ( Kierowca nie dotarał na załadunek)' +
                            '\n' + order.order.miejsce_docelowe + ' ( Kierowca nie dotarał na rozładunek)';
                        return {
                            title: title,
                            start: order.order.data_zaladunku,
                            className: 'fc-title-null', // Dodaj klasę dla zmiany koloru tekstu
                            display: 'background' // Ustaw wyświetlanie jako tło
                        };
                    } else {
                        title =order.order.miejsce_zaladunku +
                            '\n' + order.order.miejsce_docelowe ;
                        return {
                            title: title,
                            start: order.odjazd_zaladunek,
                            end: order.odjazd_dostawa,
                        };
                    }
                }),
                eventRender: function(event, element) {
                    if (event.display === 'background') {
                        element.css('background-color', '#ff9f89'); // Kolor tła zleceń z null
                        element.css('border-color', '#ff9f89'); // Kolor obramowania zleceń z null
                    }
                },
            });
        });
    </script>
</x-app-layout>
