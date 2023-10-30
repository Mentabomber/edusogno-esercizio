CREATE TABLE IF NOT EXISTS utenti (
id int NOT NULL AUTO_INCREMENT,
nome varchar(45),
cognome varchar(45),
email varchar(255),
password varchar(255),
tipo_user varchar (10) DEFAULT 'user',
PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS eventi (
id int NOT NULL AUTO_INCREMENT,
attendees text,
nome_evento varchar(255),
description varchar(255),
data_evento datetime,
PRIMARY KEY (id)
);

INSERT INTO `eventi`
(`attendees`, `nome_evento`, `description`, `data_evento`) 

VALUES 
('ulysses200915@varen8.com,qmonkey14@falixiao.com,mavbafpcmq@hitbase.net','Test Edusogno 1', 'descrizione 1', '2022-10-13 14:00'), ('dgipolga@edume.me,qmonkey14@falixiao.com,mavbafpcmq@hitbase.net','Test Edusogno 2', 'descrizione 2','2022-10-15 19:00'), ('dgipolga@edume.me,ulysses200915@varen8.com,mavbafpcmq@hitbase.net','Test Edusogno 3', 'descrizione 3','2023-10-17 21:00');


-- non funziona se voglio farlo funzionare in questa maniera devo usare SHA2 nella login e registration cosi da poter fare un controllo corretto delle password. Il problema è che cosi facendo la sicurezza del hashing delle password sarà di livello inferiore.

INSERT INTO `utenti`
(`nome`, `cognome`, `email`, `password`, `tipo_user`) 

VALUES 
('Marco', 'Rossi', 'ulysses200915@varen8.com', SHA2('Edusogno123', 256), 'user'),
('Filippo', "D'Amelio", 'qmonkey14@falixiao.com', SHA2('Edusogno?123', 256), 'user'),
('Gian Luca', 'Carta', 'mavbafpcmq@hitbase.net', SHA2('EdusognoCiao', 256), 'user'),
('Stella', 'De Grandis', 'dgipolga@edume.me', SHA2('EdusognoGia', 256), 'user'),
('Ciano', 'Cianeti', 'ciano.cianeti@miamail.com', SHA2('Cianeti', 256), 'admin');

