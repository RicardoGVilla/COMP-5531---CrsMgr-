-- Roles
INSERT INTO Role (RoleName) VALUES ('Student'), ('Instructor'), ('TA'), ('Admin');

-- Users
INSERT INTO `User` (Name, EmailAddress, Password) VALUES
('Alice Anderson', 'alice.anderson@example.edu', 'hashed_password123'),
('Bob Brown', 'bob.brown@example.edu', 'hashed_password456'),
('Cathy Charles', 'cathy.charles@example.edu', 'hashed_password789'),
('David Davis', 'david.davis@example.edu', 'hashed_password012'),
('Quinn Quasar', 'quinn.quasar@example.edu', 'hashed_password456'),
('Rachel Ray', 'rachel.ray@example.edu', 'hashed_password567'),
('Simon Smith', 'simon.smith@example.edu', 'hashed_password678'),
('Tina Turner', 'tina.turner@example.edu', 'hashed_password789');

INSERT INTO UserRole (UserID, RoleID) VALUES
(1, 1), -- Alice as Student
(2, 2), -- Bob as Instructor
(3, 1), -- Cathy as Student
(3, 3), -- Cathy also as TA
(4, 4), -- David as Admin
(5, 1), -- Quinn Quasar as Student
(6, 1), -- Rachel Ray as Student
(7, 1), -- Simon Smith as Student
(8, 1); -- Tina Turner as Student

-- Courses
INSERT INTO Course (CourseCode, Name, StartDate, EndDate) VALUES
('COMP5531', 'Software Engineering', '2024-09-01', '2025-01-15'),
('COMP5552', 'Database Systems', '2024-09-01', '2025-01-15');

-- Course instructors
INSERT INTO CourseInstructor (CourseID, InstructorID) VALUES
(1, 2), -- Bob teaches Software Engineering
(2, 2); -- Bob also teaches Database Systems

-- Course sections
INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES
(1, 'COMP5531-NN', '2024-09-01', '2025-01-15'), -- Section NN of Software Engineering
(2, 'COMP5552-DD', '2024-09-01', '2025-01-15'); -- Section DD of Database Systems


-- Groups
INSERT INTO `Group` (CourseID, GroupLeaderID, DatabasePassword, MaxSize) VALUES
(1, 1, 'group1pass', 4), -- Group in Software Engineering led by Alice
(2, 3, 'group2pass', 4); -- Group in Database Systems led by Cathy

-- Student group memberships
INSERT INTO StudentGroupMembership (StudentID, GroupID) VALUES
(1, 1), -- Alice in Group 1
(3, 2); -- Cathy in Group 2

-- Student enrollments
INSERT INTO StudentEnrollment (StudentID, CourseID, SectionID, EnrollmentDate) VALUES
(1, 1, 1, '2024-09-01'), 
(3, 2, 2, '2024-09-01'), 
(5, 2, 2, '2024-09-01'), 
(6, 2, 2, '2024-09-01'), 
(7, 2, 2, '2024-09-01'), 
(8, 2, 2, '2024-09-01'); 

-- Internal emails
INSERT INTO InternalEmail (SenderID, Subject, Body, Timestamp) VALUES
(2, 'Welcome to Software Engineering', 'Excited to start this journey together!', NOW()),
(2, 'Database Systems Syllabus', 'Please find attached the syllabus.', NOW());

-- Email recipients
INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES
(1, 1), -- Email to Alice
(2, 3); -- Email to Cathy

-- FAQs
INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES
('What is Agile Methodology?', 'Agile methodology is an iterative approach to project management.', 2, 1);

-- Course materials
INSERT INTO CourseMaterial (GroupID, Title, Description, URL_Path, UploadTimestamp) VALUES
(1, 'Project Guidelines', 'Guidelines for the semester project.', '/materials/guidelines.pdf', NOW());