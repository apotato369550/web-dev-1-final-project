<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Manage Schedule - Cebu Best Value Trading</title>
</head>
<body>
    <?php
    session_start();
    if (empty($_SESSION["user_type"]) || $_SESSION["user_type"] != "admin") {
        header("Location: ../index.php?error=unauthorized");
        exit();
    }

    include "admin-navbar.php";
    ?>
    <div class="content">
        <?php
        include "admin-sidebar.php";
        ?>
        <div class="manage-schedule-container">
            <div class="manage-schedule">
                <div class="manage-schedule-title">
                    <h1>Select Schedule</h1>
                </div>
                <div class="manage-schedule-controls">
                    <form>
                        <label for="start-date-picker">Start Date:</label>
                        <input type="date" id="start-date-picker" placeholder="Select Start Date" required>
                        <label for="end-date-picker">End Date:</label>
                        <input type="date" id="end-date-picker" placeholder="Select End Date" required>
                        <button id="filter-button">Filter</button>
                        <button id="reset-button">Reset</button>
                    </form>
                </div>
                <div class="manage-schedule-title">
                    <h1>View Tasks</h1>
                </div>
                <div id="manage-schedule-list">
                    <!-- Tasks will be displayed here -->
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        const startDatePicker = document.getElementById("start-date-picker");
        const endDatePicker = document.getElementById("end-date-picker");
        const filterButton = document.getElementById("filter-button");
        const resetButton = document.getElementById("reset-button");
        const manageScheduleList = document.getElementById("manage-schedule-list");
        var tasks = [];

        filterButton.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent form submission
            filterSchedule();
        });
        resetButton.addEventListener("click", function(event) {
            event.preventDefault(); // Prevent form submission
            resetDatePicker();
        });

        function resetDatePicker() {
            startDatePicker.value = "";
            endDatePicker.value = "";
            manageScheduleList.innerHTML = "";
            console.log("Reset date picker and cleared schedule list.");
        }

        function filterSchedule() {
            // do this
            const startDate = startDatePicker.value;
            const endDate = endDatePicker.value;

            manageScheduleList.innerHTML = "asdfasdfa";

            const xmlHttp = new XMLHttpRequest();
            xmlHttp.responseType = "json";
            xmlHttp.open("GET", "../includes/filter-schedule.inc.php?start-date=" + startDate + "&end-date=" + endDate, true);
            xmlHttp.onreadystatechange = function() {
                console.log(xmlHttp.response);
                tasks = xmlHttp.response;
                displayTasks(tasks);
            };
            xmlHttp.send();
        }

        function displayTasks(tasks) {
            manageScheduleList.innerHTML = ""; // Clear previous tasks
            if (tasks.length === 0) {
                manageScheduleList.innerHTML = "<p>No tasks found for the selected date range.</p>";
                return;
            }
            tasks.forEach(task => {
                const taskDiv = document.createElement("div");
                taskDiv.className = "task";
                taskDiv.innerHTML = `
                <div class="task-info">
                    <h2>${task.task_name}</h2>
                    <p>${task.task_description}</p>
                    <p>Date due: ${task.task_date}</p>
                    <p>Status: ${task.status}</p>
                </div>
                <div class="task-buttons">
                    <form action="manage-tasks.php" method="GET">
                        <input type="hidden" name="task-id" value="${task.task_id}">
                        <input type="hidden" name="job-id" value="${task.job_id}">
                        <input type="hidden" name="edit" value="true">
                        <button type="submit">View Task</button>
                    </form>
                </div>
                `;
                manageScheduleList.appendChild(taskDiv);
            });
        }

    </script>
</body>
</html>