


import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) { // Vérifiez que l'élément existe
        const calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            events: '/calendar/events', // URL pour récupérer les événements
            editable: true,
            eventClick: function(info) {
                alert('Chirurgie: ' + info.event.title);
            }
        });
        calendar.render();
    } else {
        console.error("L'élément #calendar n'a pas été trouvé dans le DOM.");
    }
});