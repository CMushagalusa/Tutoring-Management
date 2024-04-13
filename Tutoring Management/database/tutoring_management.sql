-- Creating Majors table
CREATE TABLE Majors (
    MajorID INT PRIMARY KEY AUTO_INCREMENT,
    MajorName VARCHAR(255) UNIQUE NOT NULL,
    MajorCode VARCHAR(5) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting predefined values into Majors table
INSERT INTO Majors (MajorName, MajorCode) VALUES
    ('Business Administration', 'BUSA'),
    ('Computer Engineering', 'CE'),
    ('Computer Science', 'CS'),
    ('Economics', 'ECON'),
    ('Electrical and Electronic Engineering', 'EEE'),
    ('Management Information Systems', 'MIS'),
    ('Mechanical Engineering', 'MNE'),
    ('Mechatronics Engineering', 'MTE');

-- Creating Students table with Major column
CREATE TABLE Students (
    StudentID INT PRIMARY KEY AUTO_INCREMENT,
    FirstName VARCHAR(255) NOT NULL,
    LastName VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Gender ENUM('Male', 'Female', 'Other') NOT NULL,
    Class INT NOT NULL,
    MajorID INT NOT NULL,
    Phone VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Profile VARCHAR(255) DEFAULT NULL,
    RegistrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (MajorID) REFERENCES Majors(MajorID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creating Tutors table
CREATE TABLE Tutors (
    TutorID INT PRIMARY KEY AUTO_INCREMENT,
    StudentID INT,
    Password VARCHAR(255) NOT NULL,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creating Session_Type table
CREATE TABLE Session_Type (
    TypeID INT PRIMARY KEY,
    TypeName VARCHAR(255) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting predefined values into Session_Type table
INSERT INTO Session_Type (TypeID, TypeName) VALUES
    (1, 'Group'),
    (2, 'Group and Individual'),
    (3, 'Individual');

-- Creating Courses table
CREATE TABLE Courses (
    CourseID INT PRIMARY KEY AUTO_INCREMENT,
    CourseName VARCHAR(255) NOT NULL,
    CourseCode VARCHAR(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting predefined values into Courses table
INSERT INTO Courses (CourseName, CourseCode) VALUES
    ('Algorithm Design and Analysis', 'CS456'),
    ('Applied Calculus', 'MATH145'),
    ('Calculus I', 'MATH141'),
    ('College Algebra', 'MATH101'),
    ('Discrete Structures and Theory', 'CS221'),
    ('Engineering Calculus', 'MATH161'),
    ('Financial Accounting', 'BUSA210'),
    ('Hardware and Systems Fundamentals', 'CS330'),
    ('Intermediate Computer Programming', 'CS313'),
    ('International Finance', 'BUSA423'),
    ('Internet of Things', 'CS458'),
    ('Managerial Economics', 'BUSA456'),
    ('Object-Oriented Programming', 'CS213'),
    ('Operations Management', 'BUSA304'),
    ('Power Engineering', 'EE451'),
    ('Pre-Calculus I', 'MATH121'),
    ('Pre-Calculus II', 'MATH122'),
    ('Principles of Economics', 'ECON100');

-- Creating Tutor_Courses junction table for many-to-many relationship between Tutors and Courses
CREATE TABLE Tutor_Courses (
    TutorID INT,
    CourseID INT,
    PRIMARY KEY (TutorID, CourseID),
    FOREIGN KEY (TutorID) REFERENCES Tutors(TutorID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creating Tutoring_Sessions table with TypeID to reference Session_Type
CREATE TABLE Tutoring_Sessions (
    SessionID INT PRIMARY KEY AUTO_INCREMENT,
    TutorID INT,
    CourseID INT,
    TypeID INT,
    SessionDate DATE NOT NULL,
    StartingTime TIME NOT NULL,
    Duration INT NOT NULL, -- Duration in minutes
    Location VARCHAR(255),
    MaxParticipants INT,
    FOREIGN KEY (TutorID) REFERENCES Tutors(TutorID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (TypeID) REFERENCES Session_Type(TypeID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Creating Session_Participants junction table for many-to-many relationship between Students and Tutoring_Sessions
CREATE TABLE Session_Participants (
    SessionID INT,
    StudentID INT,
    PRIMARY KEY (SessionID, StudentID),
    FOREIGN KEY (SessionID) REFERENCES Tutoring_Sessions(SessionID),
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
