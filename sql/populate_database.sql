INSERT INTO Role (RoleName) VALUES
('Student'),
('Instructor'),
('TA'),
('Admin');

INSERT INTO `User` (Name, EmailAddress, Password) VALUES
('Alice Anderson', 'alice.anderson@example.edu', 'hashed_password123'),
('Bob Brown', 'bob.brown@example.edu', 'hashed_password456'),
('Cathy Charles', 'cathy.charles@example.edu', 'hashed_password789'),
('David Davis', 'david.davis@example.edu', 'hashed_password012');

INSERT INTO UserRole (UserID, RoleID) VALUES
(1, 1), -- Alice as Student
(2, 2), -- Bob as Instructor
(3, 1), -- Cathy as Student
(3, 3), -- Cathy also as TA
(4, 4); -- David as Admin


INSERT INTO Course (Name, StartDate, EndDate) VALUES
('Software Engineering', '2024-09-01', '2025-01-15'),
('Database Systems', '2024-09-01', '2025-01-15');

INSERT INTO CourseInstructor (CourseID, InstructorID) VALUES
(1, 2), -- Bob teaches Software Engineering
(2, 2); -- Bob also teaches Database Systems

INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES
(1, 101, '2024-09-01', '2025-01-15'), -- Section 101 of Software Engineering
(2, 201, '2024-09-01', '2025-01-15'); -- Section 201 of Database Systems

INSERT INTO `Group` (CourseID, GroupLeaderID, DatabasePassword, MaxSize) VALUES
(1, 1, 'group1pass', 4), -- Group in Software Engineering led by Alice
(2, 3, 'group2pass', 4); -- Group in Database Systems led by Cathy

INSERT INTO StudentGroupMembership (StudentID, GroupID) VALUES
(1, 1), -- Alice in Group 1
(3, 2); -- Cathy in Group 2

INSERT INTO StudentEnrollment (StudentID, CourseID, SectionID, EnrollmentDate) VALUES
(1, 1, 1, '2024-09-01'), -- Alice enrolled in Software Engineering, Section 101
(3, 2, 2, '2024-09-01'); -- Cathy enrolled in Database Systems, Section 201

INSERT INTO InternalEmail (SenderID, Subject, Body, Timestamp) VALUES
(2, 'Welcome to Software Engineering', 'Excited to start this journey together!', NOW()),
(2, 'Database Systems Syllabus', 'Please find attached the syllabus.', NOW());

INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES
(1, 1), -- Email to Alice
(2, 3); -- Email to Cathy

INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES
('What is Agile Methodology?', 'Agile methodology is an iterative approach to project management.', 2, 1);

INSERT INTO CourseMaterial (GroupID, Title, Description, URL_Path, UploadTimestamp) VALUES
(1, 'Project Guidelines', 'Guidelines for the semester project.', '/materials/guidelines.pdf', NOW());
