DROP TABLE IF EXISTS board;
DROP TABLE IF EXISTS empty_board;
DROP TABLE IF EXISTS pieces;
DROP TABLE IF EXISTS pieces_all;
DROP TABLE IF EXISTS players;
DROP TABLE IF EXISTS game_status;
DROP PROCEDURE IF EXISTS clean_board;
DROP IF EXISTS TRIGGER game_status_update;
DROP PROCEDURE IF EXISTS place_piece;

CREATE TABLE `board` (
`x` tinyint(1) NOT NULL,
`y` tinyint(1) NOT NULL,
FOREIGN KEY (piece_id) REFERENCES pieces(id),
PRIMARY KEY (`x`,`y`)
);

INSERT INTO board (x,y) VALUES (1,1);
INSERT INTO board (x,y) VALUES (1,2);
INSERT INTO board (x,y) VALUES (1,3);
INSERT INTO board (x,y) VALUES (1,4);
INSERT INTO board (x,y) VALUES (2,1);
INSERT INTO board (x,y) VALUES (2,2);
INSERT INTO board (x,y) VALUES (2,3);
INSERT INTO board (x,y) VALUES (2,4);
INSERT INTO board (x,y) VALUES (3,1);
INSERT INTO board (x,y) VALUES (3,2);
INSERT INTO board (x,y) VALUES (3,3);
INSERT INTO board (x,y) VALUES (3,4);
INSERT INTO board (x,y) VALUES (4,1);
INSERT INTO board (x,y) VALUES (4,2);
INSERT INTO board (x,y) VALUES (4,3);
INSERT INTO board (x,y) VALUES (4,4);


CREATE TABLE `empty_board` (
`x` tinyint(1) NOT NULL,
`y` tinyint(1) NOT NULL,
FOREIGN KEY (piece_id) REFERENCES pieces(id),
PRIMARY KEY (`x`,`y`)
);

INSERT INTO empty_board (x,y) VALUES (1,1);
INSERT INTO empty_board (x,y) VALUES (1,2);
INSERT INTO empty_board (x,y) VALUES (1,3);
INSERT INTO empty_board (x,y) VALUES (1,4);
INSERT INTO empty_board (x,y) VALUES (2,1);
INSERT INTO empty_board (x,y) VALUES (2,2);
INSERT INTO empty_board (x,y) VALUES (2,3);
INSERT INTO empty_board (x,y) VALUES (2,4);
INSERT INTO empty_board (x,y) VALUES (3,1);
INSERT INTO empty_board (x,y) VALUES (3,2);
INSERT INTO empty_board (x,y) VALUES (3,3);
INSERT INTO empty_board (x,y) VALUES (3,4);
INSERT INTO empty_board (x,y) VALUES (4,1);
INSERT INTO empty_board (x,y) VALUES (4,2);
INSERT INTO empty_board (x,y) VALUES (4,3);
INSERT INTO empty_board (x,y) VALUES (4,4);


CREATE TABLE `pieces`(
`id` INTEGER NOT NULL,
`White` BOOLEAN,
`Square` BOOLEAN,
`Tall` BOOLEAN,
`Hollow` BOOLEAN,
PRIMARY KEY (`id`)
);

INSERT INTO pieces VALUES (1, true, true, true, true);
INSERT INTO pieces VALUES (2, true, true, true, false);
INSERT INTO pieces VALUES (3, true, true, false, true); 
INSERT INTO pieces VALUES (4, true, true, false, false);
INSERT INTO pieces VALUES (5, true, false, true, true);
INSERT INTO pieces VALUES (6, true, false, true, false);
INSERT INTO pieces VALUES (7, true, false, false, true);
INSERT INTO pieces VALUES (8, true, false, false, false);
INSERT INTO pieces VALUES (9, false, true, true, true);
INSERT INTO pieces VALUES (10, false, true, true, false);
INSERT INTO pieces VALUES (11, false, true, false, true);
INSERT INTO pieces VALUES (12, false, true, false, false);
INSERT INTO pieces VALUES (13, false, false, true, true);
INSERT INTO pieces VALUES (14, false, false, true, false);
INSERT INTO pieces VALUES (15, false, false, false, true);
INSERT INTO pieces VALUES (16, false, false,false, false);


CREATE TABLE `pieces_all`(
`id` INTEGER NOT NULL,
`White` BOOLEAN,
`Square` BOOLEAN,
`Tall` BOOLEAN,
`Hollow` BOOLEAN,
PRIMARY KEY (`id`)
);

INSERT INTO pieces_all VALUES (1, true, true, true, true);
INSERT INTO pieces_all VALUES (2, true, true, true, false);
INSERT INTO pieces_all VALUES (3, true, true, false, true); 
INSERT INTO pieces_all VALUES (4, true, true, false, false);
INSERT INTO pieces_all VALUES (5, true, false, true, true);
INSERT INTO pieces_all VALUES (6, true, false, true, false);
INSERT INTO pieces_all VALUES (7, true, false, false, true);
INSERT INTO pieces_all VALUES (8, true, false, false, false);
INSERT INTO pieces_all VALUES (9, false, true, true, true);
INSERT INTO pieces_all VALUES (10, false, true, true, false);
INSERT INTO pieces_all VALUES (11, false, true, false, true);
INSERT INTO pieces_all VALUES (12, false, true, false, false);
INSERT INTO pieces_all VALUES (13, false, false, true, true);
INSERT INTO pieces_all VALUES (14, false, false, true, false);
INSERT INTO pieces_all VALUES (15, false, false, false, true);
INSERT INTO pieces_all VALUES (16, false, false,false, false);


CREATE TABLE `players` (
`id` INTEGER NOT NULL,
`username` varchar(20) DEFAULT NULL,
`playerNumber` enum('1','2') DEFAULT NULL,
`token` varchar(100) DEFAULT NULL,
PRIMARY KEY (`id`)
);


CREATE TABLE `game_status` (
`status` enum('not active','initialized','started','ended','aborded') NOT NULL DEFAULT 'not active',
`p_turn` enum('1','2') DEFAULT NULL,
`result` enum('1','2','D') DEFAULT NULL,
`last_change` timestamp NULL DEFAULT NULL
);


DELIMITER $$
CREATE PROCEDURE clean_board()
BEGIN
REPLACE INTO board SELECT * FROM board_empty;
REPLACE INTO pieces SELECT * FROM pieces_all;
END$$
DELIMITER ;


DELIMITER $$
CREATE
	TRIGGER game_status_update BEFORE UPDATE
	ON game_status
	FOR EACH ROW BEGIN
		SET NEW.last_change = NOW();
END$$
DELIMITER ;


DELIMITER $$
CREATE PROCEDURE place_piece(pid INTEGER, x1 TINYINT,y1 TINYINT)
BEGIN
UPDATE board
SET piece_id = pid 
WHERE x = x1 AND y = y1;

DELETE FROM pieces
WHERE id = pid;
END$$
DELIMITER ;

