<?php
    session_start();
    include("../settings/connection.php");
    include("../function/function.php");

    // Check if the user is logged in
    if (!isset($_SESSION['StudentID'])) {
        // Redirect to login page if the user is not logged in
        header("Location:../view/login.php");
        die;
    }

    if(isset($_POST['SessionID']) && isset($_POST['StudentID'])) {
        $sessionID = mysqli_real_escape_string($connection, $_POST['SessionID']);
        $studentID = mysqli_real_escape_string($connection, $_POST['StudentID']);

        // Check if the student is already registered
        $checkQuery = "SELECT * FROM Session_Participants WHERE SessionID = '$sessionID' AND StudentID = '$studentID'";
        $checkResult = mysqli_query($connection, $checkQuery);
        if (mysqli_num_rows($checkResult) == 0) {
            $query = "INSERT INTO Session_Participants (SessionID, StudentID) VALUES ('$sessionID', '$studentID')";
            if(mysqli_query($connection, $query)) {
                echo "<script>alert('You have successfully booked the session.'); window.location.href='tutor_profile.php';</script>";
            } else {
                echo "<script>alert('Error booking session.'); window.location.href='tutor_profile.php';</script>";
            }
        } else {
            echo "<script>alert('You are already registered for this session.'); window.location.href='tutor_profile.php';</script>";
        }
        mysqli_close($connection);
    }

