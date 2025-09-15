USE Bostarter;

INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo) VALUES
('alice.rossi@example.com','Alice','Rossi',1990,'Roma','AliceR','passAlice','creatore'),
('davide.gialli@example.com','Davide','Gialli',1988,'Torino','DaveG','passDave','amministratore'),
('elena.neri@example.com','Elena','Neri',1995,'Firenze','ElenaN','passElena','standard');

INSERT INTO Creatore (EmailUtente) VALUES
('alice.rossi@example.com');

INSERT INTO Amministratore (CodiceSicurezza, EmailUtente) VALUES
(1234,'davide.gialli@example.com');

INSERT INTO Skills (Competenza) VALUES
('Programmazione Java'),
('Database SQL'),
('Python');

INSERT INTO SkillUtente (EmailUtente, CompetenzaUtente, Livello) VALUES
('alice.rossi@example.com','Programmazione Java',5),
('alice.rossi@example.com','Database SQL',4),
('elena.neri@example.com','Programmazione Java',3),
('elena.neri@example.com','Database SQL',2);

INSERT INTO Progetto
  (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia)
VALUES
('alice.rossi@example.com','App Gestionale','Sviluppo di un sistema gestionale aziendale completo.','2025-09-01','2025-12-31',20000,'aperto','software'),
('alice.rossi@example.com','Robot','Robot con AI.','2025-09-01','2025-12-31',400000,'aperto','hardware'),
('alice.rossi@example.com','App Social','Sviluppo di un social network','2025-09-01','2025-09-02',20000,'chiuso','software');


INSERT INTO Componenti (Nome, Descrizione, Quantita, Prezzo, NomeProgetto) VALUES
('Motore per le ruote', 'Motore', 4, 75.00, 'Robot');

INSERT INTO Profili (Nome, NomeProgetto, Assegnato) VALUES
('Sviluppatore Backend', 'App Gestionale', FALSE);

INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'Programmazione Java', 4);

INSERT INTO Rewards (Descrizione, NomeProgetto, PercorsoFoto) VALUES
('Ringraziamento pubblico sul sito', 'App Gestionale', 'caricamenti/reward_grazie.png');

INSERT INTO Finanziamento (EmailUtente, Importo, DataFinanziamento, NomeProgetto, CodiceReward) VALUES
('elena.neri@example.com', 50.00, '2025-09-01', 'App Gestionale',1);

INSERT INTO Commento (EmailUtente, DataCommento, Testo, NomeProgetto) VALUES
('elena.neri@example.com', '2025-09-03', 'Ottima idea! Avete pensato a integrare API di terze parti?', 'App Gestionale');

INSERT INTO Candidatura (EmailUtente, IdProfilo, Stato) VALUES
('elena.neri@example.com', (SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'in_attesa');