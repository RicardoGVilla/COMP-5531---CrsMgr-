-- Drop the existing database if it exists
DROP DATABASE IF EXISTS crs_manager_final;

-- Create a new database
CREATE DATABASE crs_manager_final;

-- Use the created database
USE crs_manager_final;

-- Create User table
CREATE TABLE IF NOT EXISTS `User` (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    EmailAddress VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
);

-- Create Role table
CREATE TABLE IF NOT EXISTS Role (
    RoleID INT AUTO_INCREMENT PRIMARY KEY,
    RoleName VARCHAR(50) UNIQUE NOT NULL
);

-- Create UserRole table
CREATE TABLE IF NOT EXISTS UserRole (
    UserID INT,
    RoleID INT,
    PRIMARY KEY (UserID, RoleID),
    FOREIGN KEY (UserID) REFERENCES `User`(UserID) ON DELETE CASCADE,
    FOREIGN KEY (RoleID) REFERENCES Role(RoleID) ON DELETE CASCADE
);

-- Create Course table
CREATE TABLE IF NOT EXISTS Course (
    CourseID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255),
    StartDate DATE,
    EndDate DATE
);

-- Create CourseInstructor table
CREATE TABLE IF NOT EXISTS CourseInstructor (
    CourseID INT,
    InstructorID INT,
    PRIMARY KEY (CourseID, InstructorID),
    FOREIGN KEY (CourseID) REFERENCES Course(CourseID) ON DELETE CASCADE,
    FOREIGN KEY (InstructorID) REFERENCES `User`(UserID) ON DELETE CASCADE
);

-- Create CourseSection table
CREATE TABLE IF NOT EXISTS CourseSection (
    SectionID INT AUTO_INCREMENT PRIMARY KEY,
    CourseID INT,
    SectionNumber INT,
    StartDate DATE,
    EndDate DATE,
    FOREIGN KEY (CourseID) REFERENCES Course(CourseID) ON DELETE CASCADE
);

-- Create Group table
CREATE TABLE IF NOT EXISTS `Group` (
    GroupID INT AUTO_INCREMENT PRIMARY KEY,
    CourseID INT,
    GroupLeaderID INT,
    DatabasePassword VARCHAR(255),
    MaxSize INT,
    FOREIGN KEY (CourseID) REFERENCES Course(CourseID) ON DELETE CASCADE,
    FOREIGN KEY (GroupLeaderID) REFERENCES `User`(UserID) ON DELETE CASCADE
);

-- Create StudentGroupMembership table
CREATE TABLE IF NOT EXISTS StudentGroupMembership (
    StudentID INT,
    GroupID INT,
    PRIMARY KEY (StudentID, GroupID),
    FOREIGN KEY (StudentID) REFERENCES `User`(UserID) ON DELETE CASCADE,
    FOREIGN KEY (GroupID) REFERENCES `Group`(GroupID) ON DELETE CASCADE
);

-- Create StudentEnrollment table
CREATE TABLE IF NOT EXISTS StudentEnrollment (
    EnrollmentID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT,
    CourseID INT,
    SectionID INT,
    EnrollmentDate DATE,
    FOREIGN KEY (StudentID) REFERENCES `User`(UserID),
    FOREIGN KEY (CourseID) REFERENCES Course(CourseID),
    FOREIGN KEY (SectionID) REFERENCES CourseSection(SectionID)
);

-- Create InternalEmail table
CREATE TABLE IF NOT EXISTS InternalEmail (
    EmailID INT AUTO_INCREMENT PRIMARY KEY,
    SenderID INT,
    Subject VARCHAR(255),
    Body TEXT,
    Timestamp DATETIME,
    FOREIGN KEY (SenderID) REFERENCES `User`(UserID) ON DELETE CASCADE
);

-- Create EmailRecipient table
CREATE TABLE IF NOT EXISTS EmailRecipient (
    EmailID INT,
    RecipientID INT,
    PRIMARY KEY (EmailID, RecipientID),
    FOREIGN KEY (EmailID) REFERENCES InternalEmail(EmailID) ON DELETE CASCADE,
    FOREIGN KEY (RecipientID) REFERENCES `User`(UserID) ON DELETE CASCADE
);

-- Create FAQ table
CREATE TABLE IF NOT EXISTS FAQ (
    FAQID INT AUTO_INCREMENT PRIMARY KEY,
    Question TEXT NOT NULL,
    Answer TEXT,
    ContributorID INT,
    CourseID INT DEFAULT NULL,
    FOREIGN KEY (ContributorID) REFERENCES `User`(UserID) ON DELETE CASCADE,
    FOREIGN KEY (CourseID) REFERENCES Course(CourseID) ON DELETE CASCADE
);

-- Create CourseMaterial table
CREATE TABLE IF NOT EXISTS CourseMaterial (
    MaterialID INT AUTO_INCREMENT PRIMARY KEY,
    GroupID INT,
    Title VARCHAR(255),
    Description TEXT,
    URL_Path TEXT,
    UploadTimestamp DATETIME,
    FOREIGN KEY (GroupID) REFERENCES `Group`(GroupID) ON DELETE CASCADE
);
