USE Bostarter;

-- Utenti
INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo) VALUES
('alice.rossi@example.com','Alice','Rossi',1990,'Roma','AliceR','passAlice','creatore'),
('davide.gialli@example.com','Davide','Gialli',1988,'Torino','DaveG','passDave','amministratore'),
('elena.neri@example.com','Elena','Neri',1995,'Firenze','ElenaN','passElena','standard');

-- Creatore (usa default Affidabilita=0)
INSERT INTO Creatore (EmailUtente) VALUES
('alice.rossi@example.com');

-- Amministratore
INSERT INTO Amministratore (CodiceSicurezza, EmailUtente) VALUES
(1234,'davide.gialli@example.com');

-- Skills
INSERT INTO Skills (Competenza) VALUES
('Programmazione Java'),
('Database SQL'),
('Python');

-- SkillUtente
INSERT INTO SkillUtente (EmailUtente, CompetenzaUtente, Livello) VALUES
('alice.rossi@example.com','Programmazione Java',5),
('alice.rossi@example.com','Database SQL',4),
('elena.neri@example.com','Programmazione Java',3),
('elena.neri@example.com','Database SQL',2);

-- Progetto di esempio
INSERT INTO Progetto (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia) VALUES
('alice.rossi@example.com','App Gestionale','Sviluppo di un sistema gestionale aziendale completo.','2025-09-01','2025-12-31',20000,'aperto','software');
