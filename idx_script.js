
const studentName = "Student";

const assignments = 0;
const quizzes = 0;
const upcomingDeadlines = 0;

document.addEventListener("DOMContentLoaded", () => {
    // Set student name dynamically
    document.querySelector("h2").textContent = `Welcome, ${studentName}`;

    // Set overview card data
    document.querySelector("#overview .card:nth-child(1) p").textContent = `${assignments} Pending`;
    document.querySelector("#overview .card:nth-child(2) p").textContent = `${quizzes} Upcoming`;
    document.querySelector("#overview .card:nth-child(3) p").textContent = `${upcomingDeadlines} Upcoming`;

    const weeklySchedule = {
        Monday: ["Study Math", "Review History"],
        Tuesday: ["Study Chemistry"],
        Wednesday: ["Study Biology"],
        Thursday: ["Study Math", "Review English"],
        Friday: ["Study Physics"],
        Saturday: ["Study History"],
        Sunday: ["Relax and Review"]
    };

    const days = document.querySelectorAll(".schedule-grid .day");
    Object.keys(weeklySchedule).forEach((day, index) => {
        const dayElement = days[index];
        dayElement.querySelector("h4").textContent = day;
        dayElement.innerHTML += weeklySchedule[day].map(task => `<p>${task}</p>`).join('');
    });
});
