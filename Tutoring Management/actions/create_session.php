<?php
    session_start();
    include("../settings/connection.php");

    if (!isset($_SESSION['TutorID'])) {
        header('Location: ../view/tutor_login.php');
        exit;
    }

    if (isset($_POST['CourseID'], $_POST['type'], $_POST['SessionDate'], $_POST['SessionTime'], $_POST['Location'], $_POST['duration'], $_POST['maxParticipants'])) {
        $tutorID = $_SESSION['TutorID'];
        $courseID = $_POST['CourseID'];
        $typeID = $_POST['type'];
        $sessionDate = $_POST['SessionDate'];
        $startingTime = $_POST['SessionTime'];
        $duration = $_POST['duration'];
        $location = $_POST['Location'];
        $maxParticipants = $_POST['maxParticipants'];

        $query = "INSERT INTO Tutoring_Sessions (TutorID, CourseID, TypeID, SessionDate, StartingTime, Duration, Location, MaxParticipants) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        if ($statement = $connection->prepare($query)) {
            $statement->bind_param('iiissisi', $tutorID, $courseID, $typeID, $sessionDate, $startingTime, $duration, $location, $maxParticipants);

            if ($statement->execute()) {
                echo "<script>alert('Session created successfully.');</script>";
                echo "<script>window.location.href='../view/tutor_dashboard.php';</script>";
            } else {
                echo "Error: " . $statement->error;
            }
            $statement->close();
        } else {
            echo "Database prepare error: " . $connection->error;
        }
    } else {
        echo "All fields are required.";
    }
