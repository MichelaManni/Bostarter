USE Bostarter;

INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo) VALUES
('alice.rossi@example.com', 'Alice', 'Rossi', 1990, 'Roma', 'AliceR', 'passAlice', 'creatore'),
('davide.gialli@example.com', 'Davide', 'Gialli', 1988, 'Torino', 'DaveG', 'passDave', 'amministratore'),
('elena.neri@example.com', 'Elena', 'Neri', 1995, 'Firenze', 'ElenaN', 'passElena', 'standard'),


INSERT INTO Creatore (EmailUtente, Affidabilita) VALUES
('alice.rossi@example.com'),

INSERT INTO Amministratore (CodiceSicurezza, EmailUtente) VALUES
(1234, 'davide.gialli@example.com');

INSERT INTO Skills (Competenza) VALUES
('Programmazione Java'),
('Database SQL'),
('Python'),

INSERT INTO SkillUtente (EmailUtente, CompetenzaUtente, Livello) VALUES
('alice.rossi@example.com', 'Programmazione Java', 5),
('alice.rossi@example.com', 'Database SQL', 4),
('elena.neri@example.com', 'Programmazione Java', 3),
('elena.neri@example.com', 'Database SQL', 2),

INSERT INTO Progetto (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia) VALUES
('alice.rossi@example.com', 'App Gestionale', 'Sviluppo di un sistema gestionale aziendale completo.', '2025-01-10', '2025-12-31', 5000.00, 'aperto', 'software'),
('alice.rossi@example.com', 'Sito E-commerce', 'Realizzazione di una piattaforma di vendita online con catalogo prodotti.', '2025-03-01', '2025-12-31', 3000.00, 'aperto', 'software'),

INSERT INTO Progetto (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia) VALUES
('alice.rossi@example.com', 'Robot Domestico', 'Sviluppo di un piccolo robot per la pulizia domestica.', '2024-05-01', '2024-10-30', 2000.00, 'chiuso', 'hardware');


INSERT INTO FotoProgetto (NomeProgetto, PercorsoFoto) VALUES
('App Gestionale', 'caricamenti/app_img1.png'),
('App Gestionale', 'caricamenti/app_img2.png'),
('Sito E-commerce', 'caricamenti/ecommerce_img.png'),
('Robot Domestico', 'caricamenti/robot_img.png'),
('Game Indie', 'caricamenti/game_indie.png');

---
-- 8. Tabella `Componenti` (Per progetti Hardware)
---
INSERT INTO Componenti (Nome, Descrizione, Quantita, Prezzo, NomeProgetto) VALUES
('Motore brushless', 'Motore elettrico per droni', 4, 75.00, 'Drone Ricerca'),
('Scheda Raspberry Pi', 'Mini computer per sistemi embedded', 1, 50.00, 'Drone Ricerca'),
('Batteria LiPo 4S', 'Batteria al litio ad alta capacità', 1, 60.00, 'Drone Ricerca'),
('Sensore di distanza', 'Sensore a ultrasuoni', 2, 15.00, 'Robot Domestico'),
('Servomotore SG90', 'Piccolo servomotore per movimenti', 4, 5.00, 'Robot Domestico'),
('Sensore di linea', 'Sensore per seguire percorsi', 3, 10.00, 'Robot Domestico');

---
-- 9. Tabella `Profili` (Per progetti Software)
---
INSERT INTO Profili (Nome, NomeProgetto, Assegnato) VALUES
('Sviluppatore Backend', 'App Gestionale', FALSE),
('UI Designer', 'App Gestionale', FALSE),
('Sviluppatore Frontend', 'Sito E-commerce', FALSE),
('Game Developer', 'Game Indie', FALSE),
('Level Designer', 'Game Indie', FALSE);

---
-- 10. Tabella `SkillRichieste` (Ogni profilo ha almeno una skill)
---
-- App Gestionale - Sviluppatore Backend
INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'Programmazione Java', 4),
((SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'Database SQL', 4);

-- App Gestionale - UI Designer
INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'UI Designer' AND NomeProgetto = 'App Gestionale'), 'UX/UI Design', 3),
((SELECT Id FROM Profili WHERE Nome = 'UI Designer' AND NomeProgetto = 'App Gestionale'), 'HTML/CSS', 2);

-- Sito E-commerce - Sviluppatore Frontend
INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Frontend' AND NomeProgetto = 'Sito E-commerce'), 'HTML/CSS', 4),
((SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Frontend' AND NomeProgetto = 'Sito E-commerce'), 'Programmazione Java', 3);

-- Game Indie - Game Developer
INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'Game Developer' AND NomeProgetto = 'Game Indie'), 'C++', 4),
((SELECT Id FROM Profili WHERE Nome = 'Game Developer' AND NomeProgetto = 'Game Indie'), 'Python', 3);

-- Game Indie - Level Designer
INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello) VALUES
((SELECT Id FROM Profili WHERE Nome = 'Level Designer' AND NomeProgetto = 'Game Indie'), 'Modellazione 3D', 3);

---
-- 11. Tabella `Rewards` (Ogni progetto ha almeno una reward e ogni reward ha un'immagine)
---
INSERT INTO Rewards (Descrizione, NomeProgetto, PercorsoFoto) VALUES
('Ringraziamento pubblico sul sito', 'App Gestionale', 'caricamenti/reward_grazie.png'),
('Accesso anticipato alla beta', 'App Gestionale', 'caricamenti/reward_beta.png'),
('T-shirt personalizzata del progetto', 'Drone Ricerca', 'caricamenti/reward_tshirt.png'),
('Modello 3D stampabile del drone', 'Drone Ricerca', 'caricamenti/reward_3dmodel.png'),
('Sconto del 10% sui primi acquisti', 'Sito E-commerce', 'caricamenti/reward_ecomm_discount.png'),
('Consulenza di 1 ora per e-commerce', 'Sito E-commerce', 'caricamenti/reward_ecomm_consult.png'),
('Stickers esclusivi del robot', 'Robot Domestico', 'caricamenti/reward_robot_sticker.png'),
('Nome nei crediti del gioco', 'Game Indie', 'caricamenti/reward_game_credits.png');


---
-- 12. Tabella `Finanziamento`
---
INSERT INTO Finanziamento (EmailUtente, Importo, DataFinanziamento, NomeProgetto, CodiceReward) VALUES
('bruno.bianchi@example.com', 50.00, '2025-04-01', 'App Gestionale', (SELECT Codice FROM Rewards WHERE NomeProgetto = 'App Gestionale' AND Descrizione = 'Ringraziamento pubblico sul sito')),
('elena.neri@example.com', 100.00, '2025-04-05', 'App Gestionale', (SELECT Codice FROM Rewards WHERE NomeProgetto = 'App Gestionale' AND Descrizione = 'Accesso anticipato alla beta')),
('franco.blu@example.com', 200.00, '2025-04-10', 'Drone Ricerca', (SELECT Codice FROM Rewards WHERE NomeProgetto = 'Drone Ricerca' AND Descrizione = 'T-shirt personalizzata del progetto'));

---
-- 13. Tabella `Commento`
---
INSERT INTO Commento (EmailUtente, DataCommento, Testo, NomeProgetto) VALUES
('bruno.bianchi@example.com', '2025-04-03', 'Ottima idea! Avete pensato a integrare API di terze parti?', 'App Gestionale'),
('elena.neri@example.com', '2025-04-07', 'Il design del drone è fantastico, ma sarà resistente al vento?', 'Drone Ricerca'),
('franco.blu@example.com', '2025-04-15', 'Quando prevedete una demo per l e-commerce?', 'Sito E-commerce'),
('bruno.bianchi@example.com', '2025-04-20', 'Adoro i giochi 2D! Ci sarà una versione per Mac?', 'Game Indie');

---
-- 14. Tabella `Risposta`
---
INSERT INTO Risposta (EmailCreatore, CodCommento, DataRisposta, Testo) VALUES
('alice.rossi@example.com', (SELECT CodiceCommento FROM Commento WHERE Testo LIKE '%API di terze parti%' AND NomeProgetto = 'App Gestionale'), '2025-04-04', 'Sì, stiamo valutando diverse opzioni per le integrazioni.'),
('carla.verdi@example.com', (SELECT CodiceCommento FROM Commento WHERE Testo LIKE '%resistente al vento%' AND NomeProgetto = 'Drone Ricerca'), '2025-04-08', 'Abbiamo testato diversi materiali compositi per garantire stabilità anche in condizioni ventose.'),
('alice.rossi@example.com', (SELECT CodiceCommento FROM Commento WHERE Testo LIKE '%versione per Mac%' AND NomeProgetto = 'Game Indie'), '2025-04-22', 'Al lancio sarà disponibile per Windows, ma stiamo considerando una versione per Mac in futuro.');

---
-- 15. Tabella `Candidatura`
---
INSERT INTO Candidatura (EmailUtente, IdProfilo, Stato) VALUES
('bruno.bianchi@example.com', (SELECT Id FROM Profili WHERE Nome = 'UI Designer' AND NomeProgetto = 'App Gestionale'), 'in_attesa'),
('elena.neri@example.com', (SELECT Id FROM Profili WHERE Nome = 'Sviluppatore Backend' AND NomeProgetto = 'App Gestionale'), 'in_attesa'),
('franco.blu@example.com', (SELECT Id FROM Profili WHERE Nome = 'Level Designer' AND NomeProgetto = 'Game Indie'), 'accettata'); -- Candidatura accettata per test