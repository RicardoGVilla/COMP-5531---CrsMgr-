INSERT INTO Role (RoleName) VALUES ('Student'), ('Instructor'), ('TA'), ('Admin');

-- Users, with Rachel Ray as the new user
INSERT INTO `User` (UserID, Name, EmailAddress, Password, NewUser) VALUES
(1234567, 'Alice Anderson', 'alice.anderson@example.edu', 'hashed_password123', FALSE),
(1234568, 'Bob Brown', 'bob.brown@example.edu', 'hashed_password456', FALSE),
(1234569, 'Cathy Charles', 'cathy.charles@example.edu', 'hashed_password789', FALSE),
(1234570, 'David Davis', 'david.davis@example.edu', 'hashed_password012', FALSE),
(1234571, 'Quinn Quasar', 'quinn.quasar@example.edu', 'hashed_password456', FALSE),
(1234572, 'Rachel Ray', 'rachel.ray@example.edu', 'hashed_password567', TRUE), 
(1234573, 'Simon Smith', 'simon.smith@example.edu', 'hashed_password678', FALSE),
(1234574, 'Tina Turner', 'tina.turner@example.edu', 'hashed_password789', FALSE),
(1234575, 'Eva Evans', 'eva.evans@example.edu', 'hashed_password123', FALSE),
(1234576, 'Frank Franklin', 'frank.franklin@example.edu', 'hashed_password456', FALSE),
(1234577, 'Grace Fulford', 'grace.fulford@example.edu', 'hashed_password789', FALSE),
(1234578, 'Henry Harris', 'henry.harris@example.edu', 'hashed_password012', FALSE),
(1234579, 'Ivy Ingram', 'ivy.ingram@example.edu', 'hashed_password345', FALSE),
(1234580, 'David Johnson', 'david.johnson@example.edu', 'hashed_password678', FALSE);

-- User Roles
INSERT INTO UserRole (UserID, RoleID) VALUES
(1234567, 1), -- Alice as Student
(1234568, 2), -- Bob as Instructor
(1234569, 1), -- Cathy as Student
(1234569, 3), -- Cathy also as TA
(1234570, 4), -- David as Admin
(1234571, 1), -- Quinn Quasar as Student
(1234572, 1), -- Rachel Ray as Student
(1234573, 1), -- Simon Smith as Student
(1234574, 1), -- Tina Turner as Student
(1234575, 1), -- Eva Evans as Student
(1234576, 1), -- Frank Franklin as Student
(1234577, 1), -- Grace Fulford as Student
(1234578, 1), -- Henry Harris as Student
(1234579, 1), -- Ivy Ingram as Student
(1234580, 2); -- David Johnson as Instructor


-- Courses
INSERT INTO Course (CourseCode, Name, StartDate, EndDate) VALUES
('COMP5531', 'Software Engineering', '2024-09-01', '2025-01-15'),
('COMP5552', 'Database Systems', '2024-09-01', '2025-01-15'),
('COMP5503', 'Data Structures and Algorithms', '2024-09-01', '2025-01-15');

-- Course Instructors
INSERT INTO CourseInstructor (CourseID, InstructorID) VALUES
(1, 1234568), -- Bob teaches Software Engineering
(2, 1234568), -- Bob also teaches Database Systems
(3, 1234580); -- David teaches Data Structures and Algorithms

-- Course Sections
INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES
(1, 'NN', '2024-09-01', '2025-01-15'), -- Section NN of Software Engineering
(2, 'DD', '2024-09-01', '2025-01-15'), -- Section DD of Database Systems
(3, 'XX', '2024-09-01', '2025-01-15'); -- Section XX of Data Structures and Algorithms

-- Student Enrollments
INSERT INTO StudentEnrollment (StudentID, CourseID, SectionID, EnrollmentDate) VALUES
(1234567, 1, 1, '2024-09-01'), -- Alice enrolled in Software Engineering (Section ID 1)
(1234569, 2, 2, '2024-09-01'), -- Cathy enrolled in Database Systems (Section ID 2)
(1234571, 2, 2, '2024-09-01'), -- Quinn enrolled in Database Systems (Section ID 2)
(1234572, 2, 2, '2024-09-01'), -- Rachel enrolled in Database Systems (Section ID 2)
(1234573, 2, 2, '2024-09-01'), -- Simon enrolled in Database Systems (Section ID 2)
(1234574, 1, 1, '2024-09-01'), -- Tina enrolled in Software Engineering (Section ID 1)
(1234574, 2, 2, '2024-09-01'), -- Tina also enrolled in Database Systems (Section ID 2)
(1234575, 1, 1, '2024-09-01'), -- Eva enrolled in Software Engineering (Section ID 1)
(1234575, 2, 2, '2024-09-01'), -- Eva also enrolled in Database Systems (Section ID 2)
(1234576, 1, 1, '2024-09-01'), -- Frank enrolled in Software Engineering (Section ID 1)
(1234576, 2, 2, '2024-09-01'), -- Frank also enrolled in Database Systems (Section ID 2)
(1234577, 1, 1, '2024-09-01'), -- Grace enrolled in Software Engineering (Section ID 1)
(1234577, 2, 2, '2024-09-01'), -- Grace also enrolled in Database Systems (Section ID 2)
(1234578, 1, 1, '2024-09-01'), -- Henry enrolled in Software Engineering (Section ID 1)
(1234578, 2, 2, '2024-09-01'), -- Henry also enrolled in Database Systems (Section ID 2)
(1234579, 1, 1, '2024-09-01'), -- Ivy enrolled in Software Engineering (Section ID 1)
(1234579, 2, 2, '2024-09-01'); -- Ivy also enrolled in Database Systems (Section ID 2)

-- Internal Emails
INSERT INTO InternalEmail (SenderID, Subject, Body, Timestamp) VALUES
(1234568, 'Welcome to Software Engineering', 'Excited to start this journey together!', NOW()),
(1234568, 'Database Systems Syllabus', 'Please find attached the syllabus.', NOW());

-- Email Recipients
INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES
(1, 1234567), -- Email to Alice
(2, 1234569); -- Email to Cathy

-- FAQs
INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES
('What is Agile Methodology?', 'Agile methodology is an iterative approach to project management.', 1234568, 1);


-- Course Materials
-- INSERT INTO CourseMaterial (GroupID, Title, Description, URL_Path, UploadTimestamp) VALUES