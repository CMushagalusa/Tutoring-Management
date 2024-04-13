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

   // Fetching tutors and their information.
   $tutorsInfo = array();
   $query = "SELECT Tutors.TutorID, CONCAT(Students.FirstName, ' ', Students.LastName) AS FullName, Majors.MajorName, Students.Class, COUNT(Tutor_Courses.CourseID) AS CourseCount, Students.Profile
            FROM Tutors JOIN Students ON Tutors.StudentID = Students.StudentID JOIN Majors ON Students.MajorID = Majors.MajorID
            LEFT JOIN Tutor_Courses ON Tutors.TutorID = Tutor_Courses.TutorID GROUP BY Tutors.TutorID";

   $result = $connection->query($query);

   if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
         $tutorsInfo[] = $row;
      }
   }

   $connection->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tutors Page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/tutors.css">

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

      <div class="profile">
         <img class="image" src="<?php echo htmlspecialchars($_SESSION['ProfileImage']); ?>"  alt="">
         <h5 class="name"><?php echo htmlspecialchars($_SESSION['Fullname']); ?></h5>
         <p class="role">Student</p>
         <a href="../view/profile.php" class="btn">view profile</a>
         <div class="flex-btn">
            <a href="../view/tutor_login.php" class="option-btn">Login</a>
            <a href="../view/tutor_register.php" class="option-btn">Be Tutor</a>
         </div>
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

<section class="tutors">
   
   <div class="top-section">
      <h1 class="heading">Meet The Tutors</h1>
      <div class="right-content">
         <form action="" method="POST" class="search-tutor">
            <input type="text" id="search_box" name="search_box" placeholder="Search Tutors..." required maxlength="100">
            <button type="submit" class="fas fa-search" name="search_tutor"></button>
         </form>
         <a href="../view/tutor_register.php" class="become-tutor">Become a Tutor</a>
      </div>
   </div>

   <div class="box-container">
      <?php foreach ($tutorsInfo as $tutor): ?>
      <div class="box">
         <div class="tutor">
            <img src="<?php echo !empty($tutor['Profile']) ? htmlspecialchars($tutor['Profile']) : '../images/pic-2.jpg'; ?>" alt="Profile Image">
            <div>
               <h3 class="tutor-name"><?php echo htmlspecialchars($tutor['FullName']); ?></h3>
               <span><?php echo htmlspecialchars($tutor['MajorName']); ?></span>
            </div>
         </div>
         <p>Class : <span><?php echo htmlspecialchars($tutor['Class']); ?></span></p>
         <p>Courses : <span><?php echo htmlspecialchars($tutor['CourseCount']); ?></span></p>
         <a href="../view/tutor_profile.php?tutorID=<?php echo htmlspecialchars($tutor['TutorID']); ?>" class="inline-btn">view profile</a>
      </div>
      <?php endforeach; ?>
      <?php if(count($tutorsInfo) == 0):?>
         <p class="message">No registered tutors at the moment!!!</p><br>
         <p class="message">Come back later, we hope to have one then!!!</p>
      <?php endif;?>
   </div>

</section>

<!-- custom js file link  -->
<script src="../js/script.js"></script>
<script>
   document.addEventListener("DOMContentLoaded", function() {
      var searchBox = document.getElementById('search_box');
      searchBox.addEventListener('keyup', function() {
         var searchTerm = searchBox.value.toLowerCase();
         var courses = document.querySelectorAll('.box-container .box');

         courses.forEach(function(course) {
               var title = course.querySelector('.tutor-name').textContent.toLowerCase();
               if (title.includes(searchTerm)) {
                  course.style.display = '';
               } else {
                  course.style.display = 'none';
               }
         });
      });
   });
</script>
   
</body>
</html>