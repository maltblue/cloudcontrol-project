-- database table

DROP TABLE IF EXISTS staff;

-- the staff table
CREATE TABLE staff (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  firstName VARCHAR(100) NOT NULL,
  lastName VARCHAR(100),
  emailAddress VARCHAR(150) NOT NULL,
  occupation VARCHAR(50),
  CONSTRAINT UNIQUE INDEX uniq_email(emailAddress)
) ENGINE = InnoDB, COMMENT = "Store list of staff members";

INSERT INTO staff (firstName, lastName, emailAddress, occupation) 
VALUES ("Muhammad","Ali","mali@gmail.com","Boxer"),
("Lou","Ambers","lambers@gmail.com","Boxer"),
("Vito","Antuofermo","vantuofermo@gmail.com","Boxer"),
("Jorge","Arce","j.arce@gmail.com","Boxer"),
("Alexis","Arguello","a.arguello@gmail.com","Boxer"),
("Henry","Armstrong","h.armstrong@gmail.com","Boxer"),
("Abe","Attell","a.attell@gmail.com","Boxer"),
("Monte","Attell","m.attell@gmail.com","Boxer"),
("Yuri","Arbachakov","y.arbachakov@gmail.com","Boxer"),
("Satoshi","Aragaki","s.aragaki@gmail.com","Boxer");