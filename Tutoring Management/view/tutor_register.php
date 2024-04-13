<?php
   // Start the session
   session_start();

   // Including the connection file
   include("../settings/connection.php");
   include("../function/function.php");

   // Check if the user is logged in
   if (!isset($_SESSION['StudentID'])) {
      // Redirect to login page if the user is not logged in
      header("Location:../view/login.php");
      die;
   }

   // Handling Tutor Registration Form Submission
   if($_SERVER['REQUEST_METHOD'] == 'POST'){

      $email = $_POST['email'];
      $password = $_POST['password'];
      $courseID = $_POST['course'];

      // Checking if the password matches the confirmed password.
      if($password!= $_POST['confirmPassword']){
         echo "<script>alert('Passwords do not match!');</script>";
         echo "<script>window.location.href='../view/tutor_register.php';</script>";
      }

      else{
         // Hashing the password.
         $password = password_hash($password, PASSWORD_DEFAULT);

         // Finding the StudentID using the email given.
         $statement = $connection->prepare("SELECT StudentID FROM Students WHERE Email = ?");
         $statement->bind_param("s", $email);
         $statement->execute();
         $result = $statement->get_result();
         $student = $result->fetch_assoc();

         if($student){
            $studentID = $student['StudentID'];

            // Inserting the student into the Tutors table.
            $insertTutorQuery = "INSERT INTO Tutors (StudentID, Password) VALUES (?, ?)";
            $insertTutorStatement = $connection->prepare($insertTutorQuery);
            $insertTutorStatement->bind_param("is", $studentID, $password);
            $insertTutorStatement->execute();
            $newTutorID = $insertTutorStatement->insert_id;

            // Inserting the tutor into the Tutor_Courses table for each selected course.
            $insertCourseQuery = "INSERT INTO Tutor_Courses (TutorID, CourseID) VALUES (?, ?)";
            $courseStatement = $connection->prepare($insertCourseQuery);
            $courseStatement->bind_param("ii", $newTutorID, $courseID);
            $courseStatement->execute();
            
            echo "<script>alert('Registration successful');</script>";
            echo "<script>window.location.href='../view/tutor_login.php';</script>";
         }
         else{
            echo "<script>alert('Email not recognized');</script>";
            echo "<script>window.location.href='../view/registration.php';</script>";
         }
      }
   } 

   // Fetch courses from database for tutor registration
   $coursesQuery = "SELECT CourseID, CourseName FROM courses";
   $coursesResult = mysqli_query($connection, $coursesQuery);
   $courses = mysqli_fetch_all($coursesResult, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutor Registration Form</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/register.css">

</head>
<body>

<header class="header">
   
   <section class="flex">

      <p class="logo">Learning Sphere</p>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="search-btn" class="fas fa-search"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="toggle-btn" class="fas fa-sun"></div>
      </div>
   </section>

</header>   

<div class="side-bar">

   <div id="close-btn">
      <i class="fas fa-times"></i>
   </div>

   <div class="profile">
      <img class="image" src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
      <h3 class="name"><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h3>
      <p class="role">Student</p>
      <a href="../view/profile.php" class="btn">view profile</a>
   </div>

   <nav class="navbar">
      <a href="../view/home.php"><i class="fas fa-home"></i><span>Home</span></a>
      <a href="../view/tutors.php"><i class="fas fa-user"></i><span>Tutors</span></a>
      <a href="../view/courses.php"><i class="fas fa-graduation-cap"></i><span>Courses</span></a>
      <a href="../view/about.php"><i class="fas fa-question"></i><span>About</span></a>
      <a href="../view/contact.php"><i class="fas fa-headset"></i><span>Contact Us</span></a>
      <a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a>
   </nav>

</div>

<section class="registration">
   <h1 class="heading">Register To Become a Tutor</h1>

   <div class="form-container">
      <form action="" method="POST" enctype="multipart/form-data">
         <h3>Register</h3>
         
         <div class="register">
            <div class="form-field">
               <input type="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" placeholder="Email" name="email" id="email" class="box" required>
               <i class='bx bxs-envelope'></i>
            </div>
                  
            <div class="form-field">
               <select name="course" class="choose" required>
                  <option value="">Select Course</option>
                  <?php foreach ($courses as $course): ?>
                  <option value="<?php echo htmlspecialchars($course['CourseID']); ?>">
                     <?php echo htmlspecialchars($course['CourseName']); ?>
                  </option>
                  <?php endforeach; ?>
               </select>     
            </div>
            
            <div class="form-field">
               <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" placeholder="Password" name="password" id="password" class="box" required>
               <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="form-field">
               <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}" placeholder="Confirm Password" name="confirmPassword" id="confirmPassword" class="box" required>
               <i class='bx bxs-lock-alt' ></i>
            </div>
            <div class="login-link">
               <p>Already have a tutor account? <a href="../view/tutor_login.php">Log in</a></p>
            </div>
            <button type="submit" class="register-button">Become Tutor</button>
         </div>
      </form> 
   </div>

</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>

   
</body>
</html>