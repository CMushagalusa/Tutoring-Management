<?php
function check_tutor_login($connection){
    // Check if the session variable for pid is set
    if(isset($_SESSION['TutorID'])){
        // Use the pid from the session
        $Tutor = $_SESSION['TutorID'];

        $query = "SELECT * FROM tutors WHERE TutorID = '$Tutor' LIMIT 1";
        $result = mysqli_query($connection, $query);

        if ($result && mysqli_num_rows($result) > 0){
            $user_data = mysqli_fetch_assoc($result);
            return $user_data;
        }
    }
    else{
        // Redirect to login page if not logged in
        header("Location:../view/tutor_login.php");
        die;
    }
}