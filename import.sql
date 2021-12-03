CREATE DATABASE `catbook`;

USE `catbook`;

CREATE TABLE `userdb`(
    userid MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    email varchar(100) NOT NULL,
    password VARCHAR(500) NOT NULL,
    gender enum ('male', 'female') NOT NULL,
    species enum('cat', 'human') NOT NULL,
    breed varchar(50) CHECK (
        (species = 'human')
        OR (breed IS NOT NULL)
    ),
    profilepicture VARCHAR(400) NOT NULL,
    description varchar(850),
    friends INT NOT NULL DEFAULT '0'
);

CREATE TABLE `messagedb`(
    messageid MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userid MEDIUMINT NOT NULL,
    senderid MEDIUMINT NOT NULL,
    title varchar (50) NOT NULL,
    message VARCHAR(1000) NOT NULL
);

INSERT INTO
    `userdb` (
        `name`,
        `email`,
        `password`,
        `gender`,
        `species`,
        `breed`,
        `profilepicture`,
        `description`
    )
values
    (
        'baozi',
        'baozi@baozi.nl',
        password('baozi1'),
        'male',
        'cat',
        'european shorthair',
        'uploads/baozi.jpg',
        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur temporibus, sed porro blanditiis, magni quidem vitae nihil alias distinctio unde autem? Porro nam temporibus eligendi! Consequatur mollitia optio repellat quasi. Commodi iste, vel fugit dolor similique, laborum illo quisquam, praesentium id minima quas nemo repudiandae laudantium atque corrupti dolorum. Nihil debitis sed, fugit possimus ab qui laboriosam iste atque fugiat harum impedit nostrum incidunt, sit consequuntur sequi, necessitatibus aliquid ipsum. Harum tenetur, facere repellat, animi fuga exercitationem, necessitatibus dicta eius explicabo neque earum deserunt. Omnis, nemo. Inventore sed, adipisci vitae id quaerat hic dolorem doloremque aspernatur ipsam. Totam, nihil sit.'
    ),
    (
        'jiaozi',
        'jiaozi@jiaozi.nl',
        password('jiaozi1'),
        'male',
        'cat',
        'european shorthair',
        'uploads/jiaozi.jpg',
        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur temporibus, sed porro blanditiis, magni quidem vitae nihil alias distinctio unde autem? Porro nam temporibus eligendi! Consequatur mollitia optio repellat quasi. Commodi iste, vel fugit dolor similique, laborum illo quisquam, praesentium id minima quas nemo repudiandae laudantium atque corrupti dolorum. Nihil debitis sed, fugit possimus ab qui laboriosam iste atque fugiat harum impedit nostrum incidunt, sit consequuntur sequi, necessitatibus aliquid ipsum. Harum tenetur, facere repellat, animi fuga exercitationem, necessitatibus dicta eius explicabo neque earum deserunt. Omnis, nemo. Inventore sed, adipisci vitae id quaerat hic dolorem doloremque aspernatur ipsam. Totam, nihil sit.'
    ),
    (
        'catowner',
        'catowner@catowner.nl',
        password('1'),
        'male',
        'human',
        '',
        'uploads/jiaozi.jpg',
        'Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur temporibus, sed porro blanditiis, magni quidem vitae nihil alias distinctio unde autem? Porro nam temporibus eligendi! Consequatur mollitia optio repellat quasi. Commodi iste, vel fugit dolor similique, laborum illo quisquam, praesentium id minima quas nemo repudiandae laudantium atque corrupti dolorum. Nihil debitis sed, fugit possimus ab qui laboriosam iste atque fugiat harum impedit nostrum incidunt, sit consequuntur sequi, necessitatibus aliquid ipsum. Harum tenetur, facere repellat, animi fuga exercitationem, necessitatibus dicta eius explicabo neque earum deserunt. Omnis, nemo. Inventore sed, adipisci vitae id quaerat hic dolorem doloremque aspernatur ipsam. Totam, nihil sit.'
    );

;

INSERT INTO
    `messagedb` (
        `userid`,
        `senderid`,
        `title`,
        `message`
    )
values
    (
        '1',
        '2',
        'hello',
        'i am baozi'
    ),
    (
        '2',
        '1',
        'hello',
        'i am jiaozi'
    );