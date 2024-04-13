<?php
    session_start(); // Start the session

    include("../settings/connection.php"); // Including the connection file

    // Redirect to login page if the user is not logged in
    if (!isset($_SESSION['StudentID'])) {
        header("Location:../view/login.php");
        exit; // It's important to call exit after header redirection
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['SessionID'])) {
            $session_id = $_POST['SessionID'];

            $query = "DELETE FROM Tutoring_Sessions WHERE SessionID=?";
            if ($statement = $connection->prepare($query)) {
                $statement->bind_param("i", $session_id);
                if ($statement->execute()) {
                    $_SESSION['message'] = 'Session Successfully Deleted!';
                    header("Location:../view/tutor_management.php");
                    exit;
                } else {
                    echo "Error deleting record: " . $connection->error;
                }
                $statement->close();
            } else {
                echo "Error preparing statement: " . $connection->error;
            }
        } else {
            echo 'Session ID not provided.';
        }
        $connection->close();
    }
?>
