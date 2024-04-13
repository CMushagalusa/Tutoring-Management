<?php
    // Start the session and include database connection
    session_start();
    include("../settings/connection.php");
    include("../function/function.php");

    // Check if the user is logged in
    if (!isset($_SESSION['StudentID'])) {
        // Redirect to login page if the user is not logged in
        header("Location:../view/login.php");
        die;
    }

    if(isset($_GET['SessionID'])) {
        $sessionId = $_GET['SessionID'];
        $query = "SELECT * FROM Tutoring_Sessions WHERE SessionID = ?";
        if($statement = $connection->prepare($query)) {
            $statement->bind_param("i", $sessionId);
            $statement->execute();
            $result = $statement->get_result();
            if($sessionDetails = $result->fetch_assoc()) {
                echo json_encode($sessionDetails);
            } else {
                echo json_encode(['error' => 'Session not found']);
            }
            $statement->close();
        }
        $connection->close();
    } else {
        echo json_encode(['error' => 'No Session ID provided']);
    }

