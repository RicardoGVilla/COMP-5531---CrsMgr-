-- Adding Roles
INSERT INTO Role (RoleName) VALUES ('Student'), ('Instructor'), ('TA'), ('Admin');

-- Users
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
(1234580, 'David Johnson', 'david.johnson@example.edu', 'hashed_password678', FALSE),
(1234581, 'Sarah Scott', 'sarah.scott@example.edu', 'hashed_password123', FALSE),
(1234582, 'Alex Allen', 'alex.allen@example.edu', 'hashed_password456', FALSE),
(1234583, 'Olivia Olsen', 'olivia.olsen@example.edu', 'hashed_password789', FALSE),
(1234584, 'William Wallace', 'william.wallace@example.edu', 'hashed_password012', FALSE),
(1234585, 'Sophia Stewart', 'sophia.stewart@example.edu', 'hashed_password345', FALSE),
(1234586, 'Emma Evans', 'emma.evans@example.edu', 'hashed_password678', FALSE),
(1234587, 'James Johnson', 'james.johnson@example.edu', 'hashed_password901', FALSE),
(1234588, 'Isabella Ingram', 'isabella.ingram@example.edu', 'hashed_password234', FALSE),
(1234589, 'Noah Nelson', 'noah.nelson@example.edu', 'hashed_password567', FALSE),
(1234590, 'Liam Lee', 'liam.lee@example.edu', 'hashed_password890', FALSE),
(1234591, 'Mia Martinez', 'mia.martinez@example.edu', 'hashed_password123', FALSE),
(1234592, 'Ava Adams', 'ava.adams@example.edu', 'hashed_password456', FALSE),
(1234593, 'Ethan Edwards', 'ethan.edwards@example.edu', 'hashed_password789', FALSE),
(1234594, 'Charlotte Clark', 'charlotte.clark@example.edu', 'hashed_password012', FALSE),
(1234595, 'Daniel Daniels', 'daniel.daniels@example.edu', 'hashed_password345', FALSE),
(1234596, 'Harper Hughes', 'harper.hughes@example.edu', 'hashed_password678', FALSE),
(1234597, 'Benjamin Bennett', 'benjamin.bennett@example.edu', 'hashed_password901', FALSE),
(1234598, 'Madison Mitchell', 'madison.mitchell@example.edu', 'hashed_password234', FALSE),
(1234599, 'Jackson Jackson', 'jackson.jackson@example.edu', 'hashed_password567', FALSE),
(1234600, 'Amelia Adams', 'amelia.adams@example.edu', 'hashed_password890', FALSE),
(1234601, 'Elijah Evans', 'elijah.evans@example.edu', 'hashed_password123', FALSE),
(1234602, 'Abigail Allen', 'abigail.allen@example.edu', 'hashed_password456', FALSE),
(1234603, 'Sofia Stewart', 'sofia.stewart@example.edu', 'hashed_password789', FALSE),
(1234604, 'Lucas Lewis', 'lucas.lewis@example.edu', 'hashed_password012', FALSE),
(1234605, 'Mason Martin', 'mason.martin@example.edu', 'hashed_password345', FALSE);

-- User Roles
INSERT INTO UserRole (UserID, RoleID) VALUES
(1234567, 1), -- Alice as Student
(1234568, 2), -- Bob as Instructor
(1234569, 1), -- Cathy as Student
(1234569, 3), -- Cathy also as TA
(1234570, 4), -- David as Admin
(1234571, 1), -- Quinn as Student
(1234572, 1), -- Rachel as Student
(1234573, 4), -- Simon as Student
(1234574, 1), -- Tina as Student
(1234575, 1), -- Eva as Student
(1234576, 1), -- Frank as Student
(1234577, 1), -- Grace as Student
(1234578, 1), -- Henry as Student
(1234579, 1), -- Ivy as Student
(1234580, 2), -- David Johnson as Instructor
(1234581, 1), -- Sarah as Student
(1234582, 1), -- Alex as Student
(1234583, 1), -- Olivia as Student
(1234584, 1), -- William as Student
(1234585, 1), -- Sophia as Student
(1234586, 1), -- Emma as Student
(1234587, 1), -- James as Student
(1234588, 1), -- Isabella as Student
(1234589, 1), -- Noah as Student
(1234590, 1), -- Liam as Student
(1234591, 1), -- Mia as Student
(1234592, 1), -- Ava as Student
(1234593, 1), -- Ethan as Student
(1234594, 1), -- Charlotte as Student
(1234595, 1), -- Daniel as Student
(1234596, 1), -- Harper as Student
(1234597, 1), -- Benjamin as Student
(1234598, 1), -- Madison as Student
(1234599, 1), -- Jackson as Student
(1234600, 1), -- Amelia as Student
(1234601, 1), -- Elijah as Student
(1234602, 1), -- Abigail as Student
(1234603, 1), -- Sofia as Student
(1234604, 2), -- Lucas as TA
(1234605, 2); -- Mason as TA

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
(1, 'AA', '2024-09-01', '2025-01-15'), -- Section A of Software Engineering
(2, 'BB', '2024-09-01', '2025-01-15'), -- Section B of Database Systems
(3, 'XX', '2024-09-01', '2025-01-15'); -- Section C of Data Structures and Algorithms

-- Student Enrollments
INSERT INTO StudentEnrollment (StudentID, CourseID, SectionID, EnrollmentDate) VALUES
(1234567, 1, 1, '2024-09-01'), -- Alice enrolled in Software Engineering (Section A)
(1234569, 2, 2, '2024-09-01'), -- Cathy enrolled in Database Systems (Section B)
(1234571, 2, 2, '2024-09-01'), -- Quinn enrolled in Database Systems (Section B)
(1234572, 2, 2, '2024-09-01'), -- Rachel enrolled in Database Systems (Section B)
(1234573, 2, 2, '2024-09-01'), -- Simon enrolled in Database Systems (Section B)
(1234574, 1, 1, '2024-09-01'), -- Tina enrolled in Software Engineering (Section A)
(1234574, 2, 2, '2024-09-01'), -- Tina also enrolled in Database Systems (Section B)
(1234575, 1, 1, '2024-09-01'), -- Eva enrolled in Software Engineering (Section A)
(1234575, 2, 2, '2024-09-01'), -- Eva also enrolled in Database Systems (Section B)
(1234576, 1, 1, '2024-09-01'), -- Frank enrolled in Software Engineering (Section A)
(1234576, 2, 2, '2024-09-01'), -- Frank also enrolled in Database Systems (Section B)
(1234577, 1, 1, '2024-09-01'), -- Grace enrolled in Software Engineering (Section A)
(1234577, 2, 2, '2024-09-01'), -- Grace also enrolled in Database Systems (Section B)
(1234578, 1, 1, '2024-09-01'), -- Henry enrolled in Software Engineering (Section A)
(1234578, 3, 2, '2024-09-01'), -- Henry also enrolled in Data Structures and Algorithms (Section B)
(1234579, 3, 1, '2024-09-01'), -- Ivy enrolled in Data Structures and Algorithms (Section A)
(1234579, 3, 2, '2024-09-01'); -- Ivy also enrolled in Data Structures and Algorithms (Section B)




INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES
('What is Agile Methodology?', 'Agile methodology is an iterative approach to project management.', 1234568, 1),
('What databases are covered in this course?', 'We cover relational databases such as MySQL, PostgreSQL, and SQLite.', 1234568, 2),
('Is prior programming experience required for this course?', 'While not required, familiarity with programming concepts will be beneficial.', 1234580, 3),
('How will assessments be conducted?', 'Assessments will include both written exams and programming assignments.', 1234580, 3),
('What is the grading policy?', 'The grading policy includes a combination of assignments, exams, and class participation.', 1234568, 1);


-- Create groups for each course
INSERT INTO `Group` (CourseID, GroupID, DatabasePassword, MaxSize, GroupLeaderID) 
VALUES
    (1, 'Group1', 'password1', 5, 1234567), -- Alice as group leader for CourseID 1
    (2, 'Group2', 'password2', 5, 1234572), -- Rachel as group leader for CourseID 2
    (3, 'Group3', 'password3', 5, 1234589); -- Noah as group leader for CourseID 3