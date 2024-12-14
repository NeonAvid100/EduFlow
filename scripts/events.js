const addEventBtn = document.getElementById('addEventBtn');
const eventModal = document.getElementById('eventModal');
const viewEventModal = document.getElementById('viewEventModal');
const eventForm = document.getElementById('eventForm');
const eventsList = document.getElementById('eventsList');
const closeButtons = document.querySelectorAll('.close');
const deleteEventBtn = document.getElementById('deleteEventBtn');

let events = JSON.parse(localStorage.getItem('events')) || [];

addEventBtn.addEventListener('click', () => {
    eventModal.style.display = 'block';
    eventForm.reset();
});

closeButtons.forEach(button => {
    button.addEventListener('click', () => {
        eventModal.style.display = 'none';
        viewEventModal.style.display = 'none';
    });
});

window.addEventListener('click', (e) => {
    if (e.target === eventModal) {
        eventModal.style.display = 'none';
    }
    if (e.target === viewEventModal) {
        viewEventModal.style.display = 'none';
    }
});

eventForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const eventData = {
        id: Date.now(),
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        dateTime: document.getElementById('eventDateTime').value
    };

    events.push(eventData);
    localStorage.setItem('events', JSON.stringify(events));
    
    renderEvents();
    eventModal.style.display = 'none';
    eventForm.reset();
});

function renderEvents() {
    eventsList.innerHTML = '';

    events.sort((a, b) => new Date(a.dateTime) - new Date(b.dateTime));
    
    events.forEach(event => {
        const eventBox = document.createElement('div');
        eventBox.className = 'event-box';
        eventBox.innerHTML = `
            <h3>${event.title}</h3>
            <p>${event.description.substring(0, 100)}${event.description.length > 100 ? '...' : ''}</p>
            <div class="event-time">${formatDateTime(event.dateTime)}</div>
        `;
        
        eventBox.addEventListener('click', () => showEventDetails(event));
        eventsList.appendChild(eventBox);
    });
}

function showEventDetails(event) {
    document.getElementById('viewEventTitle').textContent = event.title;
    document.getElementById('viewEventDescription').textContent = event.description;
    document.getElementById('viewEventDateTime').textContent = formatDateTime(event.dateTime);
    
    deleteEventBtn.onclick = () => {
        if (confirm('Are you sure you want to delete this event?')) {
            events = events.filter(e => e.id !== event.id);
            localStorage.setItem('events', JSON.stringify(events));
            renderEvents();
            viewEventModal.style.display = 'none';
        }
    };
    
    viewEventModal.style.display = 'block';
}

function formatDateTime(dateTime) {
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(dateTime).toLocaleDateString('en-US', options);
}



renderEvents();
