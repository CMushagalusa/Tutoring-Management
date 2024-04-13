<?php
    // Start the session and include database connection
    session_start();
    include("../settings/connection.php");

    if (!isset($_SESSION['TutorID'])) {
        header('Location: ../view/tutor_login.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $session_id = $_POST['SessionID'];
        $course_id = $_POST['CourseID'];
        $type = $_POST['type'];
        $session_date = $_POST['SessionDate'];
        $session_time = $_POST['SessionTime'];
        $location = $_POST['Location'];
        $duration = $_POST['duration'];
        $max_participants = $_POST['maxParticipants'];

        $query = "UPDATE Tutoring_Sessions SET CourseID=?, TypeID=?, SessionDate=?, StartingTime=?, Location=?, Duration=?, MaxParticipants=? WHERE SessionID=?";
        if ($statement = $connection->prepare($query)) {
            $statement->bind_param("iisssiii", $course_id, $type, $session_date, $session_time, $location, $duration, $max_participants, $session_id);
            $statement->execute();
            echo "Session updated successfully.";
            $statement->close();
        } else {
            echo "Error updating record: " . $connection->error;
        }
        $connection->close();
    }

