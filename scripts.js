// Add tasks
// const tasks = [
//     { date: "2023-04-12", time: "09:00", task: "Buy groceries" , posted: 1},
//     { date: "2023-04-12", time: "14:00", task: "Complete project report" , posted: 1},
//     { date: "2023-04-13", time: "11:00", task: "Schedule a doctor's appointment" , posted: 1},
//     { date: "2023-04-13", time: "15:30", task: "Attend yoga class" , posted: 1},
//     { date: "2023-04-13", time: "14:38", task: "Volunteer at a local charity" , posted: 1},
//     { date: "2023-04-13", time: "14:58", task: "Volunteer at a local charity 2" , posted: 0},
//     { date: "2023-04-14", time: "10:00", task: "Prepare presentation slides" , posted: 0},
//     { date: "2023-04-14", time: "16:00", task: "Call plumber for maintenance" , posted: 0},
//     { date: "2023-04-15", time: "09:30", task: "Pick up dry cleaning" , posted: 0},
//     { date: "2023-04-15", time: "19:00", task: "Water the plants" , posted: 0},
//     { date: "2023-04-16", time: "20:00", task: "Read a chapter from a book" , posted: 0},
//     { date: "2023-04-17", time: "14:00", task: "Organize files on computer" , posted: 0},
//     { date: "2023-04-17", time: "18:30", task: "Plan a weekend trip" , posted: 0},
//     { date: "2023-04-18", time: "12:00", task: "Pay bills" , posted: 0},
//     { date: "2023-04-18", time: "17:00", task: "Return library books" , posted: 0},
//     { date: "2023-04-18", time: "18:00", task: "Return library books 2" , posted: 0},
//     { date: "2023-04-19", time: "21:00", task: "Write a blog post" , posted: 0},
//     { date: "2023-04-20", time: "10:00", task: "Schedule a meeting with the team" , posted: 0},
//     { date: "2023-04-20", time: "18:00", task: "Cook dinner" , posted: 0},
//     { date: "2023-04-21", time: "09:00", task: "Clean the house" , posted: 0},
//     { date: "2023-04-21", time: "20:00", task: "Watch a movie" , posted: 0},
//     { date: "2023-04-22", time: "14:00", task: "Catch up with friends" , posted: 0},
//     { date: "2023-04-22", time: "19:00", task: "Visit parents" , posted: 0},
//     { date: "2023-04-23", time: "11:00", task: "Work on a side project" , posted: 0},
//     { date: "2023-04-23", time: "16:00", task: "Exercise for 30 minutes" , posted: 0},
//     { date: "2023-04-24", time: "20:30", task: "Listen to a podcast" , posted: 0},
//     { date: "2023-04-25", time: "09:00", task: "Update resume" , posted: 0},
//     { date: "2023-04-25", time: "14:00", task: "Submit project proposal" , posted: 0},
//     { date: "2023-04-26", time: "18:00", task: "Attend online webinar" , posted: 0},
//     { date: "2023-04-26", time: "20:00", task: "Practice playing the guitar" , posted: 0},
//     { date: "2023-04-27", time: "09:30", task: "Drop off package at post office" , posted: 0},
//     { date: "2023-04-27", time: "15:00", task: "Review and edit a report" , posted: 0},
//     { date: "2023-04-28", time: "18:00", task: "Volunteer at a local charity" , posted: 0},
// ];

let tasks =[];
// Fetch tasks from the API endpoint
fetch("actions/fetch_tasks.php")
    .then(response => response.json())
    .then(data => {
        tasks = data;
        console.log(tasks);
        generateCalendar(); // Ensure the calendar is generated after tasks are fetched
    })
    .catch(error => console.error("Error fetching tasks:", error));


// Generate calendar
const generateCalendar = () => {
    const calendarContainer = document.getElementById("calendar-container");
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();
    const currentYear = currentDate.getFullYear();

    const calendar = new Date(currentYear, currentMonth + 1, 0);
    const daysInMonth = calendar.getDate();

    let calendarHTML = "<table>";
    calendarHTML += "<tr><th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th></tr>";
    calendarHTML += "<tr>";

    for (let i = 1; i <= daysInMonth; i++) {
        const day = new Date(currentYear, currentMonth, i);
        const dayOfWeek = day.getDay();
        const formattedDate = `${currentYear}-${String(currentMonth + 1).padStart(2, "0")}-${String(i).padStart(2, "0")}`;

        // Count the number of tasks for the current day
        const taskCount = tasks.filter(task => task.date === formattedDate).length;
        const hasTasks = taskCount > 0;

        if (i === 1) {
            for (let j = 0; j < dayOfWeek; j++) {
                calendarHTML += "<td></td>";
            }
        }

        const today = new Date();
        const isCurrentDate = day.getDate() === today.getDate() && day.getMonth() === today.getMonth() && day.getFullYear() === today.getFullYear();

        // Check if the date is in the past
        const isPastDate = day < today;

        // Add the data-task-count attribute with the number of tasks for the current day
        calendarHTML += `<td data-date="${formattedDate}"${hasTasks ? ' data-tasks' : ''} data-task-count="${taskCount}"${isCurrentDate ? ' class="current-date"' : ''}${isPastDate ? ' class="past-date"' : ''}>${i}${taskCount > 0 ? ` (${taskCount})` : ''}</td>`;

        if (dayOfWeek === 6) {
            calendarHTML += "</tr><tr>";
        }
    }

    calendarHTML += "</tr></table>";
    calendarContainer.innerHTML = calendarHTML;
};


const displayTasks = (selectedDate) => {
    const filteredTasks = tasks.filter(task => task.date === selectedDate);
    tasksList.innerHTML = '';

    // Define the currentTime inside the function
    const currentDate = new Date();
    const currentTime = `${currentDate.getHours()}:${String(currentDate.getMinutes()).padStart(2, "0")}`;

    filteredTasks.slice(0, maxTasks).forEach(task => {
        const listItem = document.createElement("li");
        listItem.textContent = `${task.time} - ${task.task}`;

        // Check if the current task has the current time
        if (task.time === currentTime) {
            listItem.classList.add("current-time");
        }

        // Add the 'posted-task' class if the task is posted
        if (task.posted === '1') {
            listItem.classList.add("posted-task");
        }

        tasksList.appendChild(listItem);
    });
};





const tasksList = document.getElementById("tasks-list");
const maxTasks = 24;

tasks.slice(0, maxTasks).forEach(task => {
    const listItem = document.createElement("li");
    listItem.textContent = `${task.time} - ${task.task}`; // Update this line
    tasksList.appendChild(listItem);
});


document.getElementById("calendar-container").addEventListener("click", (event) => {
    const target = event.target;

    if (target.tagName.toLowerCase() === "td" && target.hasAttribute("data-date")) {
        const selectedDate = target.getAttribute("data-date");
        displayTasks(selectedDate);
    }
});

