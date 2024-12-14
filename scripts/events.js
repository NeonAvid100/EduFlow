const addEventBtn = document.getElementById('addEventBtn');
const eventModal = document.getElementById('eventModal');
const viewEventModal = document.getElementById('viewEventModal');
const eventForm = document.getElementById('eventForm');
const eventsList = document.getElementById('eventsList');
const closeButtons = document.querySelectorAll('.close');
const deleteEventBtn = document.getElementById('deleteEventBtn');

let events = JSON.parse(localStorage.getItem('events')) || [];
let isEditing = false;
let editingEventId = null;

addEventBtn.addEventListener('click', () => {
    isEditing = false;
    editingEventId = null;
    eventForm.reset();
    const submitBtn = eventForm.querySelector('.submit-btn');
    submitBtn.textContent = 'Create';
    eventModal.style.display = 'block';
});

closeButtons.forEach(button => {
    button.addEventListener('click', () => {
        eventModal.style.display = 'none';
        viewEventModal.style.display = 'none';
        isEditing = false;
        editingEventId = null;
    });
});

window.addEventListener('click', (e) => {
    if (e.target === eventModal) {
        eventModal.style.display = 'none';
        isEditing = false;
        editingEventId = null;
    }
    if (e.target === viewEventModal) {
        viewEventModal.style.display = 'none';
    }
});

eventForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const eventData = {
        id: isEditing ? editingEventId : Date.now(),
        title: document.getElementById('eventTitle').value,
        description: document.getElementById('eventDescription').value,
        link: document.getElementById('eventLink').value,
        dateTime: document.getElementById('eventDateTime').value
    };

    if (isEditing) {
        const eventIndex = events.findIndex(e => e.id === editingEventId);
        if (eventIndex !== -1) {
            events[eventIndex] = eventData;
        }
    } else {
        events.push(eventData);
    }

    localStorage.setItem('events', JSON.stringify(events));
    renderEvents();
    eventModal.style.display = 'none';
    eventForm.reset();
    isEditing = false;
    editingEventId = null;
});

function renderEvents() {
    eventsList.innerHTML = '';

    events.sort((a, b) => new Date(a.dateTime) - new Date(b.dateTime));
    
    events.forEach(event => {
        const eventBox = document.createElement('div');
        eventBox.className = 'event-box';
        eventBox.innerHTML = `
            <div class="event-header">
                <h3>${event.title}</h3>
                <button class="edit-icon" onclick="editEvent(event, ${event.id})">
                    <i class="fas fa-edit"></i>
                </button>
            </div>
            <p>${event.description.substring(0, 100)}${event.description.length > 100 ? '...' : ''}</p>
            ${event.link ? `<a href="${event.link}" class="event-link" target="_blank" onclick="event.stopPropagation()">
                <i class="fas fa-external-link-alt"></i> Follow the link
            </a>` : ''}
            <div class="event-time">${formatDateTime(event.dateTime)}</div>
        `;
        
        eventBox.addEventListener('click', () => showEventDetails(event));
        eventsList.appendChild(eventBox);
    });
}

function editEvent(e, eventId) {
    e.stopPropagation();
    const event = events.find(e => e.id === eventId);
    if (event) {
        isEditing = true;
        editingEventId = eventId;
        
        document.getElementById('eventTitle').value = event.title;
        document.getElementById('eventDescription').value = event.description;
        document.getElementById('eventLink').value = event.link || '';
        document.getElementById('eventDateTime').value = event.dateTime;
        
        const submitBtn = eventForm.querySelector('.submit-btn');
        submitBtn.textContent = 'Save Changes';
        
        eventModal.style.display = 'block';
    }
}

function showEventDetails(event) {
    document.getElementById('viewEventTitle').textContent = event.title;
    document.getElementById('viewEventDescription').textContent = event.description;
    
    const linkContainer = document.getElementById('viewEventLink');
    if (event.link) {
        linkContainer.innerHTML = `<a href="${event.link}" target="_blank">
            <i class="fas fa-external-link-alt"></i> Follow the link
        </a>`;
        linkContainer.style.display = 'block';
    } else {
        linkContainer.style.display = 'none';
    }
    
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
