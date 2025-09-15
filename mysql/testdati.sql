USE Bostarter;

--Dati per il testing
---
-- 1. Tabella `Utente`
---
INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo) VALUES
('alice.rossi@example.com', 'Alice', 'Rossi', 1990, 'Roma', 'AliceR', 'passAlice', 'creatore'),
('bruno.bianchi@example.com', 'Bruno', 'Bianchi', 1985, 'Milano', 'BrunoB', 'passBruno', 'standard'),
('carla.verdi@example.com', 'Carla', 'Verdi', 1992, 'Napoli', 'CarlaV', 'passCarla', 'creatore'),
('davide.gialli@example.com', 'Davide', 'Gialli', 1988, 'Torino', 'DaveG', 'passDave', 'amministratore'),
('elena.neri@example.com', 'Elena', 'Neri', 1995, 'Firenze', 'ElenaN', 'passElena', 'standard'),
('franco.blu@example.com', 'Franco', 'Blu', 1980, 'Bologna', 'FrancoB', 'passFranco', 'standard');

---
-- 2. Tabella `Creatore`
---
INSERT INTO Creatore (EmailUtente, Affidabilita) VALUES
('alice.rossi@example.com', 0),
('carla.verdi@example.com', 0);

---
-- 3. Tabella `Amministratore`
---
INSERT INTO Amministratore (CodiceSicurezza, EmailUtente) VALUES
(1234, 'davide.gialli@example.com');

---
-- 4. Tabella `Skills`
---
INSERT INTO Skills (Competenza) VALUES
('Programmazione Java'),
('Database SQL'),
('HTML/CSS'),
('Python'),
('C++'),
('Project Management'),
('UX/UI Design'),
('Elettronica Digitale'),
('Modellazione 3D'),
('Testing Software'), 
('Meccanica');        

---
-- 5. Tabella `SkillUtente`
---
INSERT INTO SkillUtente (EmailUtente, CompetenzaUtente, Livello) VALUES
('alice.rossi@example.com', 'Programmazione Java', 5),
('alice.rossi@example.com', 'Database SQL', 4),
('alice.rossi@example.com', 'Project Management', 4), 
('bruno.bianchi@example.com', 'HTML/CSS', 3),
('bruno.bianchi@example.com', 'UX/UI Design', 4),
('bruno.bianchi@example.com', 'Testing Software', 3), 
('carla.verdi@example.com', 'Python', 5),
('carla.verdi@example.com', 'Project Management', 4),
('carla.verdi@example.com', 'Elettronica Digitale', 5), 
('elena.neri@example.com', 'Programmazione Java', 3),
('elena.neri@example.com', 'Database SQL', 2),
('franco.blu@example.com', 'Elettronica Digitale', 5),
('franco.blu@example.com', 'C++', 4),
('franco.blu@example.com', 'Modellazione 3D', 4); 

---
-- 6. Tabella `Progetto`
---
-- Progetti 'aperti' per testing
INSERT INTO Progetto (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia) VALUES
('alice.rossi@example.com', 'App Gestionale', 'Sviluppo di un sistema gestionale aziendale completo.', '2025-01-10', '2025-12-31', 5000.00, 'aperto', 'software'),
('carla.verdi@example.com', 'Drone Ricerca', 'Progettazione e costruzione di un drone autonomo per ricerca.', '2025-02-15', '2025-11-30', 7500.00, 'aperto', 'hardware'),
('alice.rossi@example.com', 'Sito E-commerce', 'Realizzazione di una piattaforma di vendita online con catalogo prodotti.', '2025-03-01', '2025-09-30', 3000.00, 'aperto', 'software'),
('alice.rossi@example.com', 'Game Indie', 'Sviluppo di un videogioco indie 2D platform.', '2025-04-05', '2026-03-31', 4000.00, 'aperto', 'software');

-- Progetto 'chiuso' per testing
INSERT INTO Progetto (EmailCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia) VALUES
('carla.verdi@example.com', 'Robot Domestico', 'Sviluppo di un piccolo robot per la pulizia domestica.', '2024-05-01', '2024-10-30', 2000.00, 'chiuso', 'hardware');

---
-- 7. Tabella `FotoProgetto`
---
INSERT INTO FotoProgetto (NomeProgetto, PercorsoFoto) VALUES
('App Gestionale', 'caricamenti/app_img1.png'),
('App Gestionale', 'caricamenti/app_img2.png'),
('Drone Ricerca', 'caricamenti/drone_img1.png'),
('Drone Ricerca', 'caricamenti/drone_img2.png'),
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