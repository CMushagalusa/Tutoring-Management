<?php
// Including the database connection
include("../settings/connection.php");

// Checking if the search term is set
if(isset($_POST['searchTerm'])) {
    $searchTerm = mysqli_real_escape_string($connection, $_POST['searchTerm']);
    // SQL query to fetch courses matching the search term
    $query = "SELECT CourseID, CourseName FROM Courses WHERE CourseName LIKE '%$searchTerm%' ORDER BY CourseName ASC";

    $result = mysqli_query($connection, $query);
    $courses = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Checking if we have results
    if(count($courses) > 0) {
        foreach($courses as $course) {
            // Displaying each course as a clickable element. Customize as needed.
            echo "<p><a href='course_detail.php?courseID=" . $course['CourseID'] . "'>" . htmlspecialchars($course['CourseName']) . "</a></p>";
        }
    } else {
        echo "<p>No courses found.</p>";
    }
} else {
    echo "<p>No search term provided.</p>";
}
