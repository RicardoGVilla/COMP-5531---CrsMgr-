INSERT INTO Role (RoleName) VALUES
('Student'),
('Instructor'),
('TA'),
('Admin');

INSERT INTO `User` (Name, EmailAddress, Password) VALUES
('John Doe', 'john.doe@example.com', 'password123'),
('Jane Smith', 'jane.smith@example.com', 'password123'),
('Carlos Rivera', 'carlos.rivera@example.com', 'password123'),
('Alex Admin', 'alex.admin@example.com', 'securepassword123');

INSERT INTO UserRole (UserID, RoleID) VALUES
(1, 1), -- John Doe as Student
(2, 2), -- Jane Smith as Instructor
(3, 1), -- Carlos Rivera as Student
(3, 3), -- Carlos Rivera also as TA
(4, 4); -- Alex Admin as Admin

INSERT INTO Course (Name, StartDate, EndDate) VALUES
('Introduction to Database Systems', '2024-01-15', '2024-05-15'),
('Advanced Web Development', '2024-01-15', '2024-05-15');

INSERT INTO CourseInstructor (CourseID, InstructorID) VALUES
(1, 2), -- Jane Smith instructs the first course
(2, 2); -- Jane Smith also instructs the second course

INSERT INTO CourseSection (CourseID, SectionNumber, StartDate, EndDate) VALUES
(1, 101, '2024-01-15', '2024-05-15'),
(2, 201, '2024-01-15', '2024-05-15');

INSERT INTO `Group` (CourseID, GroupLeaderID, DatabasePassword, MaxSize) VALUES
(1, 1, 'group1pass', 5),
(2, 3, 'group2pass', 5);

INSERT INTO StudentGroupMembership (StudentID, GroupID) VALUES
(1, 1), -- John Doe in Group 1
(3, 2); -- Carlos Rivera in Group 2

INSERT INTO InternalEmail (SenderID, Subject, Body, Timestamp) VALUES
(2, 'Welcome to the Course', 'I am looking forward to an exciting semester.', NOW());

INSERT INTO EmailRecipient (EmailID, RecipientID) VALUES
(1, 1), -- Email sent to John Doe
(1, 3); -- Email sent to Carlos Rivera

INSERT INTO FAQ (Question, Answer, ContributorID, CourseID) VALUES
('What is normalization?', 'Normalization is the process of organizing data.', 2, 1);

INSERT INTO CourseMaterial (GroupID, Title, Description, URL_Path, UploadTimestamp) VALUES
(1, 'Project Specs', 'Details on the project.', '/materials/specs.pdf', NOW());