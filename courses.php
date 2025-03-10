<?php 
    // Include necessary files
    include 'redirection.php'; // Redirect if not logged in
    require_once 'Course.php'; // Include Course class
    require_once 'DatabaseConnection.php'; // Include DatabaseConnection class

    // Retrieve all courses from the database
    $courses = Course::getAll($conn); 
    

    // Call the getTotalCourseHours function to retrieve the total hours on all courses
    $totalCourseHours = Course::getTotalCourseHours($conn);

    // Call the getMostPopularCourse function to retrieve the most popular course details
    $popularCourse = Course::getMostPopularCourse($conn);

    // Check if a popular course is found
    if ($popularCourse) {
        // Retrieve the course name
        $popularCourseName = $popularCourse['course_name'];
    } else {
        // Set a default value if no popular course is found
        $popularCourseName = "N/A";
    }

    // Handle form submission
    if (isset($_POST['search'])) {
        $query = $_POST['query'];
        $courseLevel = $_POST['course-level'];
    
        // Check if any field is empty
        if (!empty(trim($query))) {
            // Use the search method with the level parameter
            $courses = Course::search($conn, $query, $courseLevel);
        } else {
            // Handle the case when the query is empty
            $courses = Course::search($conn, '', $courseLevel);
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style/common-style.css">

</head>
<body>


<?php
    // Include common section of the page
    $pageTitleOverride = "Courses";
    include 'common-section.php';
?>


<div id="content">
    <h2>Courses</h2>
    <!-- Search Bar -->
    <div class="search-bar">
        <form action="courses.php" method="post">
            <input type="text" id="course-search" class="search-field" name="query" placeholder="Search by course...">
            <select id="level-search" class="search-field" name="course-level">
                <option value="">Search by level</option>
                <option value="Beginner">Beginner</option>
                <option value="Intermediate">Intermediate</option>
                <option value="Advanced">Advanced</option>
            </select>
            <button class="search-btn" type="submit" name="search"><i class="fas fa-search"></i> Search</button>
        </form> 
    </div>

    <!-- Add New Course button -->
    <button class="enroll-student-btn" onclick="openCourseForm('add')"><i class="fas fa-plus"></i> Add New Course </button>

    <!-- Alert file -->
    <?php include 'alert-file.php'; ?>

    <!-- Courses table -->
    <table id="courseTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Duration(H)</th>
                <th>Instructor</th>
                <th>Level</th>
                <th>Fee($)</th>
                <th>Enrolled Students</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

<!-- Course data will be populated here -->
<?php $i = 0; ?>
<?php foreach ($courses as $course): ?>
    <tr>
        <td><?= $course->getId() ?></td>
        <td><?= $course->getName() ?></td>
        <td><?= $course->getDescription() ?></td>
        <td><?= $course->getDuration() ?></td>
        <td><?= $course->getInstructor() ?></td>
        <td><?= $course->getLevel() ?></td>
        <td><?= $course->getFee() ?></td>
        <td><button class='view-btn' data-course-id="<?= $course->getId() ?>">view</button></td>
        <td>
            <div class="action-buttons">
                <button class="edit-btn" data-row-index="<?= $i ?>" onclick="openCourseForm('edit')"><i class="fas fa-edit"></i>Edit</button>
                <button class="delete-btn" data-row-index="<?= $i ?>" onclick="openRemoveCourseForm()"><i class="fas fa-trash-alt"></i>Delete</button>
            </div>
        </td>
    </tr>
    <?php $i++; ?>
<?php endforeach; ?>
        </tbody>
    </table>








<!-- Table to display enrolled students -->
<table id="enrolledStudentsTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Student Name</th>
            <th>Mark</th>
            <th>Enrollment ID</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Enrolled students will be populated dynamically -->
    </tbody>
</table>






<!-- Cards container -->
    <div class="cards-container">
        <div class="card">
            <i class="fas fa-book"></i>
            <h3>Total Courses</h3>
            <span><?= count(Course::getAll($conn))?></span>
        </div>
        <div class="card">
            <i class="fas fa-clock"></i>
            <h3>Total Course Hours</h3>
            <span><?php echo $totalCourseHours; ?></span>
        </div>
        <div class="card">
            <i class="fas fa-star"></i>
            <h3>Popular Course</h3>
            <span><?php echo $popularCourseName; ?></span>
        </div>
    </div>




</div>

<!-- Modal for adding/editing course -->
<div class="modal" id="addCourse">
    <div class="modal-content">
        <span class="close-btn" onclick="closeAddForm()">&times;</span>
        <h2 id='modal-title'>Add New Course</h2>
        <form action="process_course.php" method="post">
            <input type="hidden" id="courseID" name="course_id" required>
            <div class="form-group">
                <label for="courseName">Name</label>
                <input type="text" id="courseName" name="course_name" required>
            </div>
            <div class="form-group">
                <label for="courseDescription">Description</label>
                <input type="text" id="courseDescription" name="course_description" required>
            </div>
            <div class="form-group">
                <label for="courseDuration">Duration</label>
                <input type="number" id="courseDuration" name="course_duration" required>
            </div>
            <div class="form-group">
                <label for="courseInstructor">Instructor</label>
                <input type="text" id="courseInstructor" name="course_instructor" required>
            </div>
            <div class="form-group">
                <label for="courseLevel">Level</label>
                <select id="courseLevel" name="course_level" required>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Advanced">Advanced</option>
                </select>
            </div>
            <div class="form-group">
                <label for="courseFee">Fee</label>
                <input type="text" id="courseFee" name="course_fee" required>
            </div>
            <button id='add-edit' class="enroll-btn" type="submit" name="add">Add Course</button>
        </form>
    </div>
</div>



<!-- Modal for removing course -->
<div class="modal" id="removeCourseForm">
    <div class="modal-content">
        <span class="close-btn" onclick="closeRemoveCourseForm()">&times;</span>
        <h2 id='modal-remove-title'>Remove Course</h2>
        <p>Are you sure you want to remove this course?</p>
        <form action="process_course.php" method="post">
            <input type="hidden" id="removeCourseId" name="removeCourseId" required>
            <button id='remove' class="enroll-btn" type="submit" name="remove">Delete Course</button>
        </form>
    </div>
</div>




<!-- Modal for setting mark -->
<div class="modal" id="setMarkModal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeSetMarkModal()">&times;</span>
        <h2 id='modal-title'>Add Mark</h2>
        <!-- Mark form -->
        <form id="markForm" action="process_mark.php" method="post">
          <div class="form-group">
            <label for="enrollmentId">Enrollment ID</label>
            <input type="text" class="form-control" id="markEnrollmentID" name="markEnrollmentID" readonly>
          </div>
          <div class="form-group">
            <label for="mark">Mark</label>
            <input type="text" class="form-control" id="mark" name="mark" placeholder="Enter mark">
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status">
              <option value="pass">Pass</option>
              <option value="fail">Fail</option>
            </select>
          </div>
          <div class="form-group">
            <label for="remark">Remark</label>
            <textarea class="form-control" id="remark" name="remark" rows="3" placeholder="Enter remark"></textarea>
          </div>
          <button id='add-edit' class="enroll-btn" type="submit" name="addMark">Add Mark</button>
        </form>
    </div>
</div>






<script>
    // Function to open the form for adding or editing a course
    function openCourseForm(add_OR_edit) {
        if(add_OR_edit == "add"){
            // Display the add course form
            document.getElementById("addCourse").style.display = "flex";
            // Set modal title for adding course
            document.getElementById('modal-title').innerText = "Add Course";
            // Set form action for adding course
            document.getElementById('add-edit').name = "add";
            // Set button text for adding course
            document.getElementById('add-edit').innerText = "Add";
            // Clear form fields
            document.getElementById('courseID').value = "";
            document.getElementById('courseName').value = "";
            document.getElementById('courseDescription').value = "";
            document.getElementById('courseDuration').value = "";
            document.getElementById('courseInstructor').value = "";
            document.getElementById('courseLevel').value = "";
            document.getElementById('courseFee').value = ""; 
        }
    }

    // Function to open the form for removing a course
    function openRemoveCourseForm() {
        document.getElementById("removeCourseForm").style.display = "flex";
    }

    // Function to close the add course form
    function closeAddForm() {
        document.getElementById("addCourse").style.display = "none";
    }

    // Function to close the remove course form
    function closeRemoveCourseForm() {
        document.getElementById("removeCourseForm").style.display = "none";
    }

    // Get all the edit buttons
    const editButtons = document.querySelectorAll('.edit-btn');
    // Get all the delete buttons
    const removeButtons = document.querySelectorAll('.delete-btn');

    // Add a click event listener to each edit button
    editButtons.forEach((button, index) => {
        button.addEventListener('click', (event) => {
            // Get the table row based on the row index
            const tableRow = document.querySelector(`table tbody tr:nth-child(${index + 1})`);
            // Get the values from the table row cells
            const courseID = tableRow.cells[0].textContent;
            const courseName = tableRow.cells[1].textContent;
            const courseDescription = tableRow.cells[2].textContent;
            const courseDuration = tableRow.cells[3].textContent;
            const courseInstructor = tableRow.cells[4].textContent;
            const courseLevel = tableRow.cells[5].textContent.trim();
            const courseFee = tableRow.cells[6].textContent;
            // Populate the form fields in the modal with the retrieved values
            document.getElementById('courseID').value = courseID;
            document.getElementById('courseName').value = courseName;
            document.getElementById('courseDescription').value = courseDescription;
            document.getElementById('courseDuration').value = courseDuration;
            document.getElementById('courseInstructor').value = courseInstructor;
            document.getElementById('courseFee').value = courseFee;
            // Select the corresponding level in the dropdown menu
            const courseLevelDropdown = document.getElementById('courseLevel');
            for (let i = 0; i < courseLevelDropdown.options.length; i++) {
                if (courseLevelDropdown.options[i].value === courseLevel) {
                    courseLevelDropdown.options[i].selected = true;
                    break;
                }
            }
            // Show the modal for editing course
            document.getElementById("addCourse").style.display = "flex";
            document.getElementById('modal-title').innerText = "Edit Course";
            document.getElementById('add-edit').name = "edit";
            document.getElementById('add-edit').innerText = "Edit";
        });
    });

    // Add a click event listener to each remove button
    removeButtons.forEach((button, index) => {
        button.addEventListener('click', (event) => {
            // Get the table row based on the row index
            const tableRow = document.querySelector(`table tbody tr:nth-child(${index + 1})`);
            // Get the values from the table row cells
            const courseID = tableRow.cells[0].textContent;
            // Populate the form fields in the modal with the retrieved values
            document.getElementById('removeCourseId').value = courseID;
            // Show the modal for removing course
            document.getElementById("removeCourseForm").style.display = "flex";
        });
    });
</script>

<script src="js/pagination.js"></script>

<script>
    // Call handlePagination() for "courseTable"
    handlePagination('courseTable', 3);
</script>

<script src="js/close-msg.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get all the view buttons
        const viewButtons = document.querySelectorAll('.view-btn');
        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                // Get the course ID associated with the button
                const courseId = button.getAttribute('data-course-id');
                // Call function to fetch enrolled students for the course
                fetchEnrolledStudents(courseId);
            });
        });

        // Function to fetch enrolled students for a course
        function fetchEnrolledStudents(courseId) {
            // Send an AJAX request to the server to retrieve enrolled students for the selected course
            fetch(`getEnrolledStudents.php?courseId=${courseId}`)
                .then(response => {
                    // Check if the response is successful
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear existing table data
                    const enrolledStudentsTableBody = document.querySelector('#enrolledStudentsTable tbody');
                    enrolledStudentsTableBody.innerHTML = '';

                    // Populate table with fetched data
                    data.forEach(student => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${student.id}</td>
                            <td>${student.name}</td>
                            <td>${student.mark ? student.mark : 'None'}</td>
                            <td>${student.enrollment_id}</td>
                            <td>${student.mark ? 'Marked' : `<button class="mark-btn" onclick="openSetMarkModal(${student.enrollment_id})"><i class="fas fa-plus-circle"></i>Set Mark</button>`}</td>
                        `;
                        enrolledStudentsTableBody.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching enrolled students:', error));
                // Scroll to the enrolledInCoursesTable
                var table = document.getElementById('enrolledStudentsTable');
                if (table) {
                    table.scrollIntoView({ behavior: 'smooth' });
                }
        }
    });

    // Function to open the set mark modal
    function openSetMarkModal(enroll_id) {
        document.getElementById("setMarkModal").style.display = "flex";
        document.getElementById('markEnrollmentID').value = enroll_id;
        //document.getElementById('enrollmentDate').value = "";
        document.getElementById('status').value = "";
    }

    // Function to close the set mark modal
    function closeSetMarkModal() {
        document.getElementById("setMarkModal").style.display = "none";
    }
</script>



</body>
</html>



