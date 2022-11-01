CREATE TABLE IF NOT EXISTS  `Users` (
 `id` INTEGER PRIMARY KEY,
 `name` VARCHAR ( 255 ) NOT NULL,
 `email` VARCHAR ( 255 ) NOT NULL,
 `avatar` VARCHAR ( 255 ) NOT NULL,
 `pass` VARCHAR ( 255 ) NOT NULL,
 `lang` VARCHAR ( 50 ) NOT NULL,
 `about` VARCHAR ( 255 ) NOT NULL,
 `created` INTEGER,
 `edited` INTEGER,
 `logged` INTEGER

                                    );