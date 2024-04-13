<?php
    session_start();

    if (isset($_SESSION['TutorID'])){
        unset($_SESSION['TutorID']);

        // Destroy all data registered the session
        session_destroy();
        header("Location:../view/tutor_login.php");
        
    }