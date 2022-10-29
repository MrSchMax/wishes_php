CREATE TABLE `users` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `categories` (
    `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `userId` int NOT NULL,
    UNIQUE KEY `category` (`userId`, `name`),
    FOREIGN KEY (`userId`)  REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `gifts` (
    `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `product_price` decimal(10, 2) NOT NULL,
    `photo` varchar(2083) NOT NULL,
    `link` varchar(2083) NOT NULL,
    `userId` int NOT NULL,
    UNIQUE KEY `gift` (`userId`, `link`),
    FOREIGN KEY (`userId`)  REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `lists` (
    `id` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `public` boolean DEFAULT FALSE,
    `userId` int NOT NULL,
    UNIQUE KEY `list` (`userId`, `name`),
    FOREIGN KEY (`userId`)  REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `list_categories` (
    `listId` int NOT NULL,
    `categoryId` int NOT NULL,
    UNIQUE KEY `pair` (`listId`, `categoryId`),
    FOREIGN KEY (`listId`)  REFERENCES `lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`categoryId`)  REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `list_gifts` (
    `listId` int NOT NULL,
    `giftId` int NOT NULL,
    UNIQUE KEY `pair` (`listId`, `giftId`),
    FOREIGN KEY (`listId`)  REFERENCES `lists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`giftId`)  REFERENCES `gifts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;