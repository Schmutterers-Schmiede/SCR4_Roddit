SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `roeddit` DEFAULT CHARACTER SET latin1 COLLATE latin1_general_ci;
USE `roeddit`;

-- +=============================================+
-- |                    USERS                    |
-- +=============================================+

CREATE TABLE `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(255) NOT NULL,
  `passwordHash` char(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD UNIQUE KEY `userName` (`userName`);
	
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
	
-- +=============================================+
-- |                   THREADS                   |
-- +=============================================+

CREATE TABLE `threads` (
  `threadId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `title` char(60) NOT NULL,
  `timestamp` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `threads`
  ADD PRIMARY KEY (`threadId`),
  ADD UNIQUE KEY `title` (`title`),
  MODIFY `threadId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  
ALTER TABLE `threads`
  ADD CONSTRAINT fk_threads_userId
  FOREIGN KEY (userId)
  REFERENCES users(userId);

-- +=============================================+
-- |                   ENTRIES                   |
-- +=============================================+

CREATE TABLE `entries` (
  `entryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `threadId` int(11) NOT NULL,
  `timestamp` DATETIME NOT NULL,
  `text` VARCHAR(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `entries`
  ADD PRIMARY KEY (`entryId`),  
  MODIFY `entryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
  
ALTER TABLE `entries`
  ADD CONSTRAINT fk_entries_userId
  FOREIGN KEY (userId)
  REFERENCES users(userId),

  ADD CONSTRAINT fk_threadId
  FOREIGN KEY (threadId)
  REFERENCES threads(threadId);

-- +===============================================+
-- |                   TEST DATA                   |
-- +===============================================+

INSERT INTO `users` (`userName`, `passwordHash`) 
VALUES ('scr4', '$2y$10$0dhe3ngxlmzgZrX6MpSHkeoDQ.dOaceVTomUq/nQXV0vSkFojq.VG');

INSERT INTO `users` (`userName`, `passwordHash`) 
VALUES ('detlev', '$2y$10$aw5Cwa/7d8U5d.8pIPSMEu.B7vSo75B8MhqbqfgpV9upPSvtm88Pm');

INSERT INTO `users` (`userName`, `passwordHash`) 
VALUES ('bob', '$2y$10$oONze35NXSz0oqI5MCG15OJzQ3tKxrZ2b5CkR21doiCg9zxNiejwq');

INSERT INTO `users` (`userName`, `passwordHash`) 
VALUES ('arnold', '$2y$10$DUAV8EAzGhaJnlQ.FLit/ecbx/p..KXjxZr8hokVWr0G3RmagO/w2');

INSERT INTO `users` (`userName`, `passwordHash`) 
VALUES ('rickSanchez', '$2y$10$HILXaiJZwKX.jFkCmWgfCeLx3kg7LCTqmqxc.MUfFh/F3v2.9Zsta');





INSERT INTO `threads` (`userId`, `title`, `timestamp`) 
VALUES (1, 'PHP is awesome!', '2023-4-12T12:12:12');

INSERT INTO `threads` (`userId`, `title`, `timestamp`) 
VALUES (2, 'Knees weak...', '2023-4-13T12:12:12');

INSERT INTO `threads` (`userId`, `title`, `timestamp`) 
VALUES (3, 'Star Wars Quotes', '2023-4-14T12:12:12');

INSERT INTO `threads` (`userId`, `title`, `timestamp`) 
VALUES (4, 'Terminator quotes', '2023-4-15T12:12:12');

INSERT INTO `threads` (`userId`, `title`, `timestamp`) 
VALUES (5, 'book recommendations', '2023-4-16T12:12:12');

INSERT INTO `threads` (`userId`, `title`, `timestamp`) 
VALUES (1, 'delete test', '2023-4-17T12:12:12');




INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (1, 1, '2023-7-4T12:12:12', 'Please help! I am being held against my will!');

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (2, 1, '2023-7-5T12:12:12', 'Please end my suffering!');


INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (1, 2, '2023-12-5T12:12:12', 'Arms are heavy...');

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (4, 2, '2023-12-7T12:12:12', 'There\'s vomit on his sweater already...');

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (2, 2, '2023-12-8T12:12:12', 'Mom\'s Spaghetti!!!');


INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (3, 3, '2023-9-5T12:12:12', 'This is where the fun begins.');

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (1, 3, '2023-9-6T12:12:12', 'I love democracy.');

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (2, 3, '2023-9-7T12:12:12', 'Flying is for droids!');

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (4, 3, '2023-9-8T12:12:12', "It\'s over Anakin. I have the high ground.");


INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (4, 4, '2023-6-7T12:12:12', "Hasta la vista, baby.");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (2, 4, '2023-6-8T12:12:12', "I need your boots, your clothes and your motorcycle.");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (3, 4, '2023-6-9T12:12:12', "You are terminated.");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (1, 4, '2023-6-10T12:12:12', "Your confusion is not rational. She\'s a healthy female of breeding age.");


INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (1, 5, '2023-8-9T12:12:12', "The King Killer Chronicles by Patrick Rothfuss");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (2, 5, '2023-8-10T12:12:12', "Harry Potter by J.K. Rowling");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (3, 5, '2023-8-11T12:12:12', "The Stormlight Archives by Brandon Sanderson");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (4, 5, '2023-8-12T12:12:12', "The Metro series by Dmitry Glukhovsky");


INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (1, 6, '2023-12-1T12:12:12', "test");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (2, 6, '2023-12-2T12:12:12', "test");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (3, 6, '2023-12-3T12:12:12', "test");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (4, 6, '2023-12-4T12:12:12', "test");

INSERT INTO `entries` (`userId`, `threadId`, `timestamp`, `text`) 
VALUES (5, 6, '2023-12-5T12:12:12', 'test');