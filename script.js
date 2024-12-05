document.addEventListener('DOMContentLoaded', function() {
    const sidebarItems = document.querySelectorAll('.action-list .item');
    
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            sidebarItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            
            const sectionName = this.querySelector('span').textContent.trim();
            
            updateMainContent(sectionName);
        });
    });
});

function updateMainContent(section) {
    const contentArea = document.querySelector('.page-content');
    const header = contentArea.querySelector('.header');
    const tasksWrapper = contentArea.querySelector('.tasks-wrapper');
    
    header.textContent = section;
    
    tasksWrapper.innerHTML = '';
    
    switch(section) {
        case 'Overview of Assignments':
            tasksWrapper.innerHTML = `
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="assignment-1">
                    <label for="assignment-1">
                        <span class="label-text">Software Engineering Project Phase 1</span>
                    </label>
                    <span class="tag pending">Due Tomorrow</span>
                </div>
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="assignment-2">
                    <label for="assignment-2">
                        <span class="label-text">Database Design Document</span>
                    </label>
                    <span class="tag upcoming">Due Next Week</span>
                </div>
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="assignment-3" checked>
                    <label for="assignment-3">
                        <span class="label-text">Web Development Assignment</span>
                    </label>
                    <span class="tag completed">Completed</span>
                </div>
            `;
            break;
            
        case 'Quizzes':
            tasksWrapper.innerHTML = `
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="quiz-1">
                    <label for="quiz-1">
                        <span class="label-text">Database Systems Quiz - Chapter 5</span>
                    </label>
                    <span class="tag upcoming">Oct 20, 2023</span>
                </div>
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="quiz-2" checked>
                    <label for="quiz-2">
                        <span class="label-text">Software Engineering Quiz</span>
                    </label>
                    <span class="tag completed">Score: 85/100</span>
                </div>
            `;
            break;
            
        case 'Deadlines':
            tasksWrapper.innerHTML = `
                <div class="task urgent">
                    <input class="task-item" name="task" type="checkbox" id="deadline-1">
                    <label for="deadline-1">
                        <span class="label-text">Project Proposal Submission</span>
                    </label>
                    <span class="tag pending">2 Days Left</span>
                </div>
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="deadline-2">
                    <label for="deadline-2">
                        <span class="label-text">Mid-term Examination</span>
                    </label>
                    <span class="tag upcoming">Next Week</span>
                </div>
            `;
            break;
            
        case 'Weekly Milestones':
            tasksWrapper.innerHTML = `
                <div class="milestone-header">Week 8 Progress</div>
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="milestone-1" checked>
                    <label for="milestone-1">
                        <span class="label-text">Complete UML Diagrams</span>
                    </label>
                    <span class="tag completed">Done</span>
                </div>
                <div class="task">
                    <input class="task-item" name="task" type="checkbox" id="milestone-2">
                    <label for="milestone-2">
                        <span class="label-text">Database Schema Design</span>
                    </label>
                    <span class="tag progress">In Progress</span>
                </div>
            `;
            break;
            
        case 'Routines':
            tasksWrapper.innerHTML = `
                <div class="routine-header">Today's Schedule</div>
                <div class="task">
                    <label class="time-slot">
                        <span class="label-text">09:00 AM - Software Engineering</span>
                    </label>
                    <span class="tag">Room 301</span>
                </div>
                <div class="task">
                    <label class="time-slot">
                        <span class="label-text">11:00 AM - Database Lab</span>
                    </label>
                    <span class="tag">Lab 2</span>
                </div>
                <div class="task">
                    <label class="time-slot">
                        <span class="label-text">02:00 PM - Web Development</span>
                    </label>
                    <span class="tag">Room 303</span>
                </div>
            `;
            break;
            
        case 'Course Materials':
            tasksWrapper.innerHTML = `
                <div class="materials-header">Recent Materials</div>
                <div class="task">
                    <label class="material-item">
                        <span class="label-text">Software Design Patterns PDF</span>
                    </label>
                    <span class="tag">Download</span>
                </div>
                <div class="task">
                    <label class="material-item">
                        <span class="label-text">Database Normalization Slides</span>
                    </label>
                    <span class="tag">Download</span>
                </div>
                <div class="task">
                    <label class="material-item">
                        <span class="label-text">React Components Tutorial</span>
                    </label>
                    <span class="tag">View Online</span>
                </div>
            `;
            break;
    }
}
