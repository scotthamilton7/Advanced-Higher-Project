CREATE DATABASE project;
USE project;


#Create table for user details
CREATE TABLE user (
    userID INT NOT NULL AUTO_INCREMENT,
    userName VARCHAR(70) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(30) NOT NULL CHECK(LENGTH(password) >= 8),
    
    PRIMARY KEY(userID)
);


#Create table for business details
CREATE TABLE business (
    businessID INT NOT NULL AUTO_INCREMENT,
    businessName VARCHAR(70) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phoneNo VARCHAR(15) NOT NULL,
    password VARCHAR(30) NOT NULL CHECK(LENGTH(password) >= 8),
    
    PRIMARY KEY(businessID)
);


#Create table for job listings
CREATE TABLE job (
    jobID INT NOT NULL AUTO_INCREMENT,
    title VARCHAR(80) NOT NULL,
    jobType VARCHAR(14) NOT NULL CHECK(type IN('Full-time', 'Part-time', 'Apprenticeship')),
    businessID INT NOT NULL,
    description VARCHAR(4000) NOT NULL,
    weeklyHours DECIMAL(3,2) NOT NULL CHECK(weeklyHours <= 168),
    hourlyRate DECIMAL(5,2) NOT NULL,
    location VARCHAR(200) NOT NULL,
    
    PRIMARY KEY(jobID),
    FOREIGN KEY(businessID) REFERENCES business(businessID)
);


#Create table for applications
CREATE TABLE application (
    applicationID INT NOT NULL AUTO_INCREMENT,
    userID INT NOT NULL,
    jobID INT NOT NULL,
    coverLetter VARCHAR(2000),
    PRIMARY KEY(applicationID),
    FOREIGN KEY(userID) REFERENCES user(userID),
    FOREIGN KEY(jobID) REFERENCES job(jobID)
);


#Insert into user table
INSERT INTO user VALUES (1,'a','a@a.com','a'),(2,'John Smith','johnsmith@email.com','JSmith24'),(3,'Kara White','karawhite@email.com','Password1'),(4,'Sam Richardon','samrich03@email.com','SamRich123'),(5,'Ameerah Kiani','ak2006@email.com','Password1'),(6,'Emma Taylor','fashionexplorer23@email.com','FdcDf3fs9'),(7,'Alex Johnson','techuser23@example.com','TRf23SA23!'),(8,'Alex Thompson','avid.gamer.87@email.com','GameOn#2024'),(9,'Sarah Smith','sara.smith@email.com','sarasmith123');


#Insert into business table
INSERT INTO business VALUES (1,'b','b@b.com','123','b'),(2,'Fresh Fruits Ltd','fresh@fruits.com','01632 963847','FreshFruits'),(3,'Vintage Clothes','contact@vintageclothes.com','01632 963826','VintageClothes'),(4,'Artistic Co','sales@artisticco.com','01632 963745','ArtisticCo'),(5,'HorizonTech Innovations','tech@horizon.com','01632 969821','HorizonTech'),(6,'Spice Harbour','restaurant@spiceharbour.com','01632 960935','in07Y3xhKN'),(7,'Style Studio','enquiries@stylestudio.com','01632 968462','XA0x8iq86S'),(8,'Urban Elegance','elegance@urbanshop.com','01632 963847','U75aOlPe1n'),(9,'Pixel Playgrounds','playgrounds@pixelstudio.com','01632 963746','hb39H98Q60'),(10,'MasterCraft Construction','construction@mastercarft.com','01632 962846','QR9eb4mT3W'),(11,'QuantumLeap Innovators','innovation@quantumleap.com','01632 963821','Quantum2024'),(12,'QuantumCraft Studios','studios@quantum.com','01632 385739','QuantumStudios');


#Insert into job table
INSERT INTO `job` VALUES (1,'Team Member','Full-time',2,'Job Description',35.00,11.50,'Glasgow'),(2,'Sales Assistant','Part-time',3,'Job Description',14.00,10.42,'Paisley'),(3,'Device Expert','Part-time',5,'Job Description',12.00,12.00,'Renfrew'),(4,'Shift Manager','Full-time',8,'Job Description',38.00,12.50,'Renfrew'),(5,'Sales Assistant - Full Time','Full-time',8,'Job Description',36.00,11.80,'Renfrew'),(6,'Sales Assistant - Part Time','Part-time',8,'Job Description',20.00,11.80,'Renfrew'),(7,'Head Chef','Full-time',6,'Job Description',40.00,13.70,'Paisley'),(8,'Front of House','Part-time',6,'Job Description',18.00,9.80,'Paisley'),(9,'Games Developer','Full-time',9,'Job Descritpion',35.00,10.42,'Glasgow'),(10,'Warehouse Operator','Full-time',8,'Job Description',40.00,12.00,'Renfrew'),(11,'Warehouse Manager','Full-time',8,'Job Description',40.00,13.50,'Renfrew'),(12,'Bricklayer','Apprenticeship',10,'Job Description',18.00,5.80,'Paisley'),(13,'Software Engineer','Full-time',12,'Job Description',40.00,14.00,'Glasgow');


#Insert into application table
INSERT INTO application VALUES (1,2,3,'Cover Letter'),(2,4,2,'Cover Letter'),(3,4,6,'Cover Letter'),(4,4,7,'Cover Letter'),(5,5,9,'Cover Letter'),(6,4,10,'Cover Letter'),(7,3,10,'Cover Letter'),(8,4,12,'Cover Letter');
