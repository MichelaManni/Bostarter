USE Bostarter;

INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo) VALUES
('standard@email','Elena','Neri',1995,'Firenze','ElenaN','1','standard'),
('creatore@email','Alice','Rossi',1990,'Roma','AliceR','2','creatore'),
('admin@email','Davide','Gialli',1988,'Torino','DaveG','3','amministratore');

INSERT INTO Creatore (EmailUtente) VALUES
('creatore@email');

INSERT INTO Amministratore (CodiceSicurezza, EmailUtente) VALUES
(1234,'admin@email');

INSERT INTO Skills (Competenza) VALUES
('Programmazione Java'),
('Database SQL'),
('Python');

INSERT INTO SkillUtente (EmailUtente, CompetenzaUtente, Livello) VALUES
('creatore@email','Programmazione Java',5),
('creatore@email','Database SQL',4),
('standard@email','Programmazione Java',3),
('standard@email','Database SQL',2);

INSERT INTO Progetto
  (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia)
VALUES
('creatore@email','App Gestionale','Sviluppo di un sistema gestionale aziendale completo.','2025-09-01','2025-12-31',20000,'aperto','software'),
('creatore@email','Robot','Robot con AI.','2025-09-01','2025-12-31',400000,'aperto','hardware'),
('creatore@email','App Social','Sviluppo di un social network','2025-09-01','2025-09-02',20000,'chiuso','software');


INSERT INTO Componenti (Nome, Descrizione,Prezzo) VALUES
('Motore', 'Motore per le braccia', 4);

INSERT INTO Composizione (`NomeProgetto`, `NomeComponente`, `Quantita`) VALUES 
('Robot', 'Motore', '3');

INSERT INTO Profili (Nome, NomeProgetto, Assegnato) VALUES
('Sviluppatore Backend', 'App Gestionale', FALSE);

INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'Programmazione Java', 4);

INSERT INTO Rewards (Descrizione, NomeProgetto, PercorsoFoto) VALUES
('Ringraziamento pubblico sul sito', 'App Gestionale', 'caricamenti/reward_grazie.png');
INSERT INTO Rewards (Descrizione, NomeProgetto, PercorsoFoto) VALUES
('Ringraziamento pubblico sul sito', 'Robot', 'caricamenti/reward_grazie.png');

INSERT INTO Finanziamento (EmailUtente, Importo, DataFinanziamento, NomeProgetto, CodiceReward) VALUES
('standard@email', 50.00, '2025-09-01', 'App Gestionale',1);

INSERT INTO Commento (EmailUtente, DataCommento, Testo, NomeProgetto) VALUES
('standard@email', '2025-09-03', 'Ottima idea! Avete pensato a integrare API di terze parti?', 'App Gestionale');

INSERT INTO Candidatura (EmailUtente, IdProfilo, Stato) VALUES
('standard@email', (SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'in_attesa');