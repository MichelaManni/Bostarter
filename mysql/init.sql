USE Bostarter;

CREATE TABLE Utente (
    Email VARCHAR(30) PRIMARY KEY,
    Nome VARCHAR(30),
    Cognome VARCHAR(30),
    AnnoNascita INT, 
    LuogoNascita VARCHAR(50),
    Nickname VARCHAR(30),
    Password VARCHAR(255) ,
    Ruolo ENUM('standard','creatore','amministratore') NOT NULL DEFAULT 'standard'
) ENGINE=INNODB;


CREATE TABLE Creatore (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    EmailUtente VARCHAR(30),
    Affidabilita INT ,
    nr_progetti INT, -- ridondanza???????????
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email)
) ENGINE=INNODB;


CREATE TABLE Amministratore (
    CodiceSicurezza INT PRIMARY KEY,
    EmailUtente VARCHAR(30),
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email)
) ENGINE=INNODB;

CREATE TABLE Progetto (
    IdCreatore INT,
    Nome VARCHAR(30) PRIMARY KEY,
    Descrizione VARCHAR(255), 
    DataInserimento DATE,
	DataLimite DATE,
    Budget DECIMAL(10,2),
    Stato ENUM('aperto','chiuso'),
    Tipologia ENUM('hardware','software'),
    FOREIGN KEY (IdCreatore) REFERENCES Creatore(Id)
) ENGINE=INNODB;

CREATE TABLE FotoProgetto(  
	IdFoto INT AUTO_INCREMENT PRIMARY KEY,
    IdCreatore INT,
    NomeProgetto VARCHAR(30),
    PercorsoFoto VARCHAR(255),    
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome),
    FOREIGN KEY(IdCreatore) REFERENCES Creatore(Id)
) ENGINE=INNODB;

CREATE TABLE Componenti (
    Nome VARCHAR(30) PRIMARY KEY,
    Descrizione VARCHAR(255),
    Quantita INT CHECK(Quantita>0),
    Prezzo DECIMAL(10,2), 
    NomeProgetto VARCHAR(30),
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome)
) ENGINE=INNODB;


CREATE TABLE Profili (
	Id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(30) NOT NULL,
    NomeProgetto VARCHAR(30) NOT NULL,
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome)
) ENGINE=INNODB;


CREATE TABLE Skills (
    Competenza VARCHAR(30) PRIMARY KEY
) ENGINE=INNODB;


CREATE TABLE SkillUtente (
    EmailUtente VARCHAR(30),
    CompetenzaUtente VARCHAR(30),
    Livello INT CHECK(Livello>=0 AND Livello<=5),
    PRIMARY KEY (EmailUtente, CompetenzaUtente),
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email),
    FOREIGN KEY (CompetenzaUtente) REFERENCES Skills(Competenza)
) ENGINE=INNODB;


CREATE TABLE SkillRichieste (
    IdProfilo INT,
    CompetenzaRichiesta VARCHAR(30),
    Livello INT CHECK(Livello>=0 AND Livello<=5),
    PRIMARY KEY (IdProfilo, CompetenzaRichiesta),
    FOREIGN KEY (IdProfilo) REFERENCES Profili(Id),
    FOREIGN KEY (CompetenzaRichiesta) REFERENCES Skills(Competenza)
) ENGINE=INNODB;


CREATE TABLE Rewards (
    Codice INT AUTO_INCREMENT PRIMARY KEY,
    Descrizione VARCHAR(300),
    NomeProgetto VARCHAR(30),
    PercorsoFoto VARCHAR(255), #aggiunta percorso foto
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome)
) ENGINE=INNODB;


CREATE TABLE Finanziamento (
    Codice INT AUTO_INCREMENT PRIMARY KEY,
    EmailUtente VARCHAR(30),
    Importo DECIMAL(10,2), 
    DataFinanziamento DATE,
    NomeProgetto VARCHAR(30),
    CodiceReward INT,
    UNIQUE(EmailUtente, NomeProgetto, DataFinanziamento),
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email),
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome),
    FOREIGN KEY (CodiceReward) REFERENCES Rewards(Codice)
) ENGINE=INNODB;

CREATE TABLE Commento (
    CodiceCommento INT AUTO_INCREMENT PRIMARY KEY,
    EmailUtente VARCHAR(30),
    DataCommento DATE,
    Testo VARCHAR(500),
    NomeProgetto VARCHAR(30),
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email),
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome)
) ENGINE=INNODB;


CREATE TABLE Risposta (
    IdCreatore INT,
    CodCommento INT PRIMARY KEY,
    DataRisposta DATE,
    Testo VARCHAR(500),
    FOREIGN KEY (CodCommento) REFERENCES Commento(CodiceCommento),
    FOREIGN KEY (IdCreatore) REFERENCES Creatore(Id)
) ENGINE=INNODB;


CREATE TABLE Candidatura (
    Id INT AUTO_INCREMENT PRIMARY KEY,
    EmailUtente VARCHAR(30) NOT NULL,
    IdProfilo INT NOT NULL,                      
    Stato ENUM('in_attesa','accettata','rifiutata') DEFAULT 'in_attesa',
    UNIQUE(EmailUtente, IdProfilo),
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email),
    FOREIGN KEY (IdProfilo) REFERENCES Profili(Id)
) ENGINE=INNODB;

--Test

INSERT INTO Utente (
  Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo
) VALUES (
  'mario.rossi@example.com','Mario','Rossi',1985,'Milano','mrossi85','PasswordSicura123!','standard');

INSERT INTO Utente (
  Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo
) VALUES (
  'A','Anna','Verdi',1990,'Roma','av1990','B','creatore');
  
insert into Creatore(
    EmailUtente,Affidabilita,nr_progetti)  
    VALUES('mario.rossi@example.com',9,1);

  INSERT INTO Progetto (
    IdCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia
) VALUES (
    1, 'Progetto Aperto 1', 'Prototipo hardware per rilevamento temperatura in ambienti industriali.', 
    '2025-06-19', '2025-07-30', 1500.00, 'aperto', 'hardware'
);

  INSERT INTO Progetto (
    IdCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia
) VALUES (
    1, 'Progetto Aperto 2', 'Prototipo', '2025-06-19', '2025-07-30', 15020.00, 'aperto', 'software'
);

  INSERT INTO Progetto (
    IdCreatore, Nome, Descrizione, DataInserimento, DataLimite, Budget, Stato, Tipologia
) VALUES (
    1, 'Progetto chiuso', 'Prototipo hardware per rilevamento temperatura in ambienti industriali.', 
    '2025-06-19', '2025-07-30', 15000.00, 'chiuso', 'hardware'
);

INSERT INTO Commento (EmailUtente,DataCommento, Testo, NomeProgetto)
VALUES ('mario.rossi@example.com','2025-06-15', 'Ottimo lavoro su questo progetto!', 'Progetto Aperto 1');

INSERT INTO Commento (EmailUtente,DataCommento, Testo, NomeProgetto)
VALUES ('mario.rossi@example.com','2025-06-18', 'Ci sono ancora alcuni miglioramenti da fare.', 'Progetto Aperto 2');


-- OPERAZIONI SUI DATI:

-- Operazioni degli utenti-----------------------------------------------------------------------------

-- Autenticazione e registrazione sulla piattaforma
DELIMITER //
CREATE PROCEDURE Registrazione 
	(IN New_Email VARCHAR(30),
    IN New_Nome VARCHAR(30),
    IN New_Cognome VARCHAR(30),
    IN New_Anno_nascita INT, 
    IN New_Luogo_nascita VARCHAR(50),
    IN New_Nickname VARCHAR(30),
    IN New_Password VARCHAR(255),
    IN New_Ruolo ENUM('standard','creatore','amministratore'),
    IN New_CodiceSicurezza INT)
BEGIN 
		IF EXISTS(SELECT 1 FROM Utente WHERE Email = New_Email) THEN
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email già registratata';
		END IF;
        
		INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo)
		VALUES (New_Email, New_Nome, New_Cognome, New_Anno_nascita, New_Luogo_nascita, New_Nickname, New_Password, New_Ruolo);
        
        IF New_Ruolo = 'creatore' THEN
			INSERT INTO Creatore(EmailUtente,Affidabilita,nr_progetti)
            VALUES (New_Email,0,0);
		ELSEIF New_Ruolo = 'amministratore' THEN
			IF New_CodiceSicurezza IS NULL THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Codice sicurezza obbligatorio per amministratori';
			END IF;
            INSERT INTO Amministratore(CodiceSicurezza, EmailUtente)
			VALUES (New_CodiceSicurezza, New_Email);
		END IF;
END //

CREATE PROCEDURE Autenticazione( IN Email_inserita VARCHAR(30), 
								IN Password_inserita VARCHAR(255),
								IN CodiceSicurezza_inserito INT, #richiesto solo per gli amministratori
                                OUT Esito BOOLEAN,
                                OUT Ruolo_Utente ENUM ('standard','creatore','amministratore'))
BEGIN
	
    DECLARE RuoloRegistrato ENUM ('standard','creatore','amministratore');
    DECLARE PasswordRegistrata VARCHAR(255);
    
    SET Esito=FALSE;
    SET Ruolo_utente = NULL;
    
    SELECT U.Ruolo, U.Password INTO RuoloRegistrato, PasswordRegistrata
    FROM UTENTE AS U
    WHERE U.Email = Email_inserita;
   
    IF RuoloRegistrato IS NOT NULL AND PasswordRegistrata=Password_inserita THEN
			IF RuoloRegistrato = 'amministratore' THEN
				IF EXISTS (
					SELECT 1
                    FROM Amministratore
                    WHERE EmailUtente = Email_inserita AND CodiceSicurezza = CodiceSicurezza_inserito)
				THEN SET Esito = TRUE;
                END IF;
			ELSE 
				SET Esito=TRUE;
			END IF;
	END IF;
    
    IF Esito = TRUE THEN
		SET Ruolo_utente = RuoloRegistrato;
	END IF;   

END //     

-- Inserire una skill di curriculum
Create Procedure AggiungiSkillUtente(in Email_utente varchar(30),in Competenza_utente varchar(30),in Livello_competenza int)
BEGIN
			IF NOT EXISTS (SELECT 1 FROM Utente WHERE Email = Email_utente) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email non trovata';
			END IF;
            IF NOT EXISTS (SELECT 1 FROM Skills WHERE Competenza = Competenza_utente) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Questa skill non esiste';
			END IF;
            
			INSERT INTO SkillUtente (EmailUtente, Competenza, Livello)
			VALUES (Email_utente,Competenza_utente,Livello_competenza);
            
END //

-- Visualizzazione di tutti i progetti disponibili(ovvero quelli aperti)
CREATE PROCEDURE VisualizzaProgettiDisponibili()
BEGIN
    SELECT 
        Nome AS 'Nome Progetto',
        Descrizione AS 'Descrizione',
        DataInserimento AS 'Data Inserimento',
		DataLimite AS 'Data Limite',
        Budget AS 'Budget Richiesto',
        Tipologia AS 'Tipologia'
    FROM Progetto
    WHERE Stato = 'aperto'
    ORDER BY DataInserimento DESC;
END //

-- Non riechiesta espressamente e aggiunta mentre facevo la pagina dei commenti per semplificare il processo 
CREATE PROCEDURE VisualizzaCommenti(IN Nome_Progetto VARCHAR(30))
BEGIN
    SELECT 
     U.Nickname as 'Poster',
     C.Testo as 'Contenuto',
     C.DataCommento as 'Data' 
    FROM Commento C JOIN Utente U ON C.EmailUtente = U.Email
    WHERE C.NomeProgetto = Nome_Progetto;
END //

-- Finanziare un progetto aperto e scelta del reward:
-- visualizzazione delle reward disponibili per permettere all'utente di scegliere
CREATE PROCEDURE VisualizzazioneReward( IN Nome_Progetto VARCHAR(30))
BEGIN
	IF NOT EXISTS ( SELECT 1 FROM Progetto 
	WHERE Nomeprogetto = Nome_progetto AND Stato = 'aperto') THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato o non aperto';
    END IF;
    
    SELECT R.Codice AS 'Codice del reward', R.Descrizione, R.PercorsoFoto
    FROM Rewards as R
    WHERE R.NomeProgetto = Nome_Progetto
    ORDER BY R.Codice;
END //

-- Finanziamento progetto e assegnazione reward
CREATE PROCEDURE FinanziaProgetto(
    IN Email_utente VARCHAR(30),
    IN Nome_progetto VARCHAR(30),
    IN Importo_finanziamento DECIMAL(10,2),
    IN Codice_reward INT  -- scelto dall'utente
)
BEGIN
	IF NOT EXISTS (SELECT 1 FROM Utente WHERE Email = Email_utente) THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email non trovata';
	END IF;
    IF NOT EXISTS ( SELECT 1 FROM Progetto 
	WHERE Nome = Nome_progetto AND Stato = 'aperto') THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato o non aperto';
    END IF;
	IF NOT EXISTS (SELECT 1 FROM Rewards 
		WHERE Codice = Codice_reward
		AND NomeProgetto = Nome_progetto) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Reward non valido';
	END IF;
    
    INSERT INTO Finanziamento (
        EmailUtente,
        Importo,
        DataFinanziamento,
        NomeProgetto,
        CodiceReward
    ) VALUES (
        Email_utente,
        Importo_finanziamento,
		CURDATE(),
        Nome_progetto,
        Codice_reward
    );   
END //

-- Aggiunta di un commento
CREATE PROCEDURE Inserimento_Commento(IN Testo_inserito  VARCHAR(500), IN NomeProgetto_Scelto VARCHAR(30))
	BEGIN
    DECLARE ControlloEsistenzaProgetto BOOLEAN;
    SELECT EXISTS (SELECT 1
					FROM PROGETTO AS P
                    WHERE NomeProgetto_Scelto = P.Nomeprogetto)INTO ControlloEsistenzaProgetto;
	
    IF ControlloEsistenzaProgetto=TRUE THEN
		INSERT INTO COMMENTO( Data_commento,Testo ,Nome_progetto)
        VALUES (CURDATE(), Testo_inserito, NomeProgetto_scelto);
	ELSE 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non esistente';
	END IF;    
END //   

-- Inserimento di una candidatura
-- La piattaforma consente ad un utente di inserire una candidatura su un profilo SOLO se, 
-- per ogni skill richiesta da un profilo, l’utente dispone di un livello superiore o uguale al valore richiesto.
CREATE PROCEDURE InserimentoCandidatura(IN Email_Utente VARCHAR(30), IN Id_Profilo INT) 
BEGIN 
	DECLARE Nome_Progetto VARCHAR(30);
	DECLARE ControlloProgetto BOOLEAN;
	
    (SELECT Pr.Nome
    FROM Progetto AS Pr
    WHERE Pr.Id = Id_Profilo)INTO Nome_Progetto;
    # controllo progetto
    SELECT EXISTS(SELECT 1
				FROM Progetto AS Prog
                WHERE Prog.Nome = Nome_Progetto AND Prog.stato='aperto')INTO ControlloProgetto;
	IF ControlloProgetto = 'false' THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'progetto non valido';
	END IF;
    -- controllo che le skill dell'utente siano sufficienti
	IF EXISTS(SELECT 1 
					FROM SkillRichieste AS Sr
                    WHERE Sr.IdProfilo=Id_Profilo AND
                    NOT EXISTS(SELECT 1
								FROM SkillUtente AS Su
                                WHERE Su.EmailUtente = Email_Utente AND 
                                Sr.CompetenzaRichiesta = Su.CompetenzaUtente AND
                                Su.Livello >= Sr.Livello)
	)
	THEN SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mancono delle skill per la candidatura';
    END IF;
					
	#se tutta va a buon fine si inserisce candidatura            
	INSERT INTO Candidatura(EmailUtente, IdProfilo, stato)
    VALUES (Email_Utente, Id_Profilo, 'in_attesa'); #stato inizializzato come 'in_attesa'
 
END // 

-- Operazioni degli Amministratori----------------------------------------------------------

-- Inserimento di una nuova competenza
CREATE PROCEDURE InserimentoCompetenza (IN Nome_competenza VARCHAR(30), IN Codice_sicurezza INT)
BEGIN 
	DECLARE ControlloEsistenzaCompetenza BOOLEAN;
    DECLARE ControlloAmministratore BOOLEAN;
    
    SELECT EXISTS (SELECT 1
					FROM AMMINISTRATORE AS A
                    WHERE A.CodiceSicurezza = Codice_sicurezza)INTO ControlloAmministratore;
	SELECT EXISTS (SELECT 1
					FROM Skills AS S
                    WHERE S.Competenza=Nome_competenza)INTO ControlloEsistenzaCompetenza;
	
    IF ControlloEsistenzaCompetenza=FALSE AND ControlloAmministratore=TRUE THEN
		INSERT INTO Skills(Competenza)
        VALUES (NuovaStringa);
	ELSE SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Competenza già inserita o atenticazione come amministratore non andata a buon fine';
	END IF;
END //

-- Operazioni dei Creatori------------------------------------------------------------------

-- Inserire un nuovo progetto
CREATE PROCEDURE AggiungiProgetto(
    IN Id_Creatore INT,
    IN Nome_Progetto VARCHAR(30),
    IN Descrizione_Progetto VARCHAR(255),
    IN Budget DECIMAL(10,2),
    IN Limite DATE,
    IN Tipologia ENUM('hardware','software')
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Creatore WHERE Id = Id_Creatore) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Id creatore non valido';
    END IF;
            
    IF EXISTS (SELECT 1 FROM Progetto WHERE Nome = Nome_Progetto) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nome progetto già esistente';
    END IF;
            
    INSERT INTO Progetto (
        IdCreatore,
        Nome,
        Descrizione,
        DataInserimento,
        Budget,
        DataLimite,
        Stato,
        Tipologia
    ) VALUES (
        Id_Creatore,
        Nome_Progetto,
        Descrizione_Progetto,
        CURDATE(),
        Budget,
        Limite,
        'aperto',
        Tipologia
    );
END //

-- Inserimento foto nel Progetto
CREATE PROCEDURE AggiungiFotoProgetto( IN NomeProgetto_inserito VARCHAR(30), IN Percorsofoto_inserito VARCHAR(255),
									IN IdCreatore_inserito INT )
BEGIN
    DECLARE Controllo BOOLEAN;
	#controllo associazione tra creatore e progetto
    SELECT EXISTS (SELECT 1
					FROM PROGETTO AS P
					WHERE P.NomeProgetto = NomeProgetto_inserito 
                    AND  P.IdCreatore = IdCreatore_inserito)INTO Controllo;
    IF (Controllo = TRUE) THEN
		INSERT INTO FotoProgetto(IdCreatore,NomeProgetto,PercorsoFoto)
        VALUES (IdCreatore_inserito,NomeProgetto_inserito, Percorsofoto_inserito);
	END IF;
END //

-- inserimento Reward 
CREATE PROCEDURE InserimentoReward( IN Descrizione_reward VARCHAR(300),IN Nome_progetto VARCHAR(30),
									IN Id_creatore INT, IN  Percorso_Foto VARCHAR(255))
BEGIN
	DECLARE Controllo BOOLEAN;
	#controllo esistenza progetto e corrispondenza con il creatore
    SELECT EXISTS (SELECT 1
					FROM PROGETTO AS P
                    WHERE P.Nome = Nome_progetto
					AND P.IdCreatore = Id_creatore
                    AND P.Stato = 'aperto')
                    INTO Controllo;
	IF Controllo= TRUE THEN
		INSERT INTO Rewards(Descrizione, NomeProgetto, PercorsoFoto )
		VALUES (Descrizione_reward, Nome_progetto,Percorso_foto);
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato o chiuso';
	END IF;
END //

-- inserimento di una risposta
CREATE PROCEDURE InserimentoRisposta (IN Id_creatore INT,IN Cod_commento INT,IN Testo_comm VARCHAR(500))
BEGIN
    DECLARE ControlloCodiceCommento BOOLEAN;
    DECLARE ControlloCreatore BOOLEAN;
    DECLARE ControlloRisposta BOOLEAN ; 
    -- controllo esistenza del codice commento
    SELECT EXISTS (SELECT 1
					FROM COMMENTO AS C
                    WHERE C.CodiceCommento = Cod_commento)
                    INTO ControlloCodiceCommento;
	-- controllo associazione tra creatore e progetto
    SELECT EXISTS (SELECT 1
				FROM Commento AS C
                JOIN Progetto AS P ON C.NomeProgetto = P.NomeProgetto
                WHERE C.Codice = Cod_commento 
                AND P.IdCreatore = Id_creatore) INTO ControlloCreatore;
    -- controllo che non sia già stata inserita risposta    
     SELECT EXISTS (SELECT 1 
					FROM Risposta 
					WHERE CodCommento = Cod_commento)  INTO ControlloRisposta;
                    
    IF ControlloCodiceCommento = TRUE AND ControlloCreatore=TRUE AND ControlloRisposta=FALSE THEN
		INSERT INTO Risposta(idCreatore,Codice_commento,Data_risposta,Testo)
        VALUES (Id_creatore,Cod_commento,CURDATE(), Testo_comm);
	ELSE SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Commento non trovato o risposta già inserita';
	END IF;
END //

-- Inserimento nuovo profilo per un progetto software
Create Procedure AggiungiProfiloSoftware(in Id_Creatore int,in Nome_Progetto varchar(30),in Nome_Profilo varchar(30)) 
BEGIN
			IF NOT EXISTS (SELECT 1 FROM Creatore WHERE idCreatore = Id_Creatore) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Id non valido';
			END IF;
			IF NOT EXISTS (SELECT 1 FROM Progetto WHERE Nome = Nome_Progetto and Tipologia = "Software" and Stato = "Aperto") THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato';
			END IF;
            
            INSERT INTO Profili (Nome,Nome_progetto) VALUES (Nome_Profilo,Nome_Progetto);
	 END //

-- Accettazione o meno di una candidatura
CREATE PROCEDURE AccettazioneCandidatura (IN Id_Candidatura INT, 
										IN Id_creatore INT, 
                                        IN Esito_Candidatura ENUM('accettata', 'rifiutata'))
BEGIN
	DECLARE controlloCandidatura BOOLEAN DEFAULT FALSE;
    DECLARE id_profilo INT;
    DECLARE nome_progetto VARCHAR(30);
    
    -- controllo esistenza candidatura e appartenenza progetto a creatore
    SELECT EXISTS (
        SELECT 1
        FROM Candidatura AS C
        JOIN Profili PR ON C.IdProfilo = PR.Id
        JOIN Progetto P ON PR.NomeProgetto = P.Nome
        WHERE C.Id = Id_candidatura
        AND P.IdCreatore = Id_creatore
    ) INTO controlloCandidatura;
    
    IF controlloCandidatura=FALSE THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Candidatura non valida o problemi come creatore';
    END IF;
    
    -- controllo stato candidatura
    IF NOT EXISTS (
        SELECT 1 
        FROM Candidatura 
        WHERE Id = Id_candidatura 
        AND Stato = 'in_attesa'
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Candidatura non è in attesa';
    END IF;

    UPDATE Candidatura
    SET Stato = Esito_candidatura
    WHERE Id = Id_candidatura;
END //

-- Statistiche(Viste)------------------------------------------------------------------------------------------------

-- Top 3 creatori più affidabili
CREATE VIEW ClassificaAffidabili AS
SELECT Nickname
FROM Creatore C join Utente U on C.EmailUtente = U.Email
ORDER BY affidabilita DESC
LIMIT 3;

-- 3 Progetti più vicini al completamento
CREATE VIEW ProgettiQuasiCompletati AS
Select p.Nome,p.Descrizione,p.Budget,(p.Budget - SUM(F.Importo)) as Differenza
From Progetto p left join Finanziamento F on p.Nome = F.NomeProgetto
where p.stato = 'Aperto'
Group By p.Nome
order by Differenza asc
Limit 3;

-- 3 utenti con più finanziamenti
CREATE VIEW ClassificaUtenti AS 
SELECT U.Nickname
FROM Finanziamento AS F
JOIN Utente AS U ON U.Email = F.EmailUtente
GROUP BY F.EmailUtente, U.Nickname
ORDER BY SUM(F.Importo) DESC
LIMIT 3;

-- Triggers----------------------------------------------------------------------------------------------------------------------

-- Aggiornare l'affidabilità dopo l'inserimento di un progetto (Da rivedere)
CREATE TRIGGER Affidabilità_progetto
AFTER INSERT ON Progetto
FOR EACH ROW
BEGIN
    DECLARE progetti_totali INT;
    DECLARE progetti_finanziati INT;
    DECLARE nuova_affidabilita INT;
    
	-- Contrare il numero di progetti di un creatore -> contrare i progetti finanziati almeno una volta
    SELECT COUNT(*) INTO progetti_totali
    FROM Progetto
    WHERE IdCreatore = NEW.IdCreatore;
    
    SELECT COUNT(DISTINCT p.Nome) INTO progetti_finanziati
    FROM Progetto p
    JOIN Finanziamento f ON p.Nome = f.NomeProgetto
    WHERE p.IdCreatore = NEW.IdCreatore;
    
    IF progetti_totali > 0 THEN
        SET nuova_affidabilita = (progetti_finanziati * 100) / progetti_totali;
    ELSE
        SET nuova_affidabilita = 0;
    END IF;
        -- Aggiorna l'affidabilità del creatore
    UPDATE Creatore
    SET Affidabilita = nuova_affidabilita,
        nr_progetti = progetti_totali
    WHERE Id = NEW.IdCreatore;
END //

-- Aggiornare l'affidabilità dopo un finanziamento (Da rivedere)
CREATE TRIGGER Affidabilità_finanziamento
AFTER INSERT ON Finanziamento
FOR EACH ROW
BEGIN
    DECLARE progetti_totali INT;
    DECLARE progetti_finanziati INT;
    DECLARE nuova_affidabilita INT;
    DECLARE id_creatore_progetto INT;
    
    -- Trova l'ID del creatore del progetto finanziato
    SELECT IdCreatore INTO id_creatore_progetto
    FROM Progetto
    WHERE Nome = NEW.NomeProgetto;
    
    -- Conta il numero totale di progetti del creatore
    SELECT COUNT(*) INTO progetti_totali
    FROM Progetto
    WHERE IdCreatore = id_creatore_progetto;
    
    -- Conta il numero di progetti finanziati del creatore (almeno un finanziamento)
    SELECT COUNT(DISTINCT p.Nome) INTO progetti_finanziati
    FROM Progetto p
    JOIN Finanziamento f ON p.Nome = f.NomeProgetto
    WHERE p.IdCreatore = id_creatore_progetto;
    
    -- Calcola la nuova affidabilità (percentuale progetti finanziati)
    IF progetti_totali > 0 THEN
        SET nuova_affidabilita = (progetti_finanziati * 100) / progetti_totali;
    ELSE
        SET nuova_affidabilita = 0;
    END IF;
    
    -- Aggiorna l'affidabilità del creatore
    UPDATE Creatore
    SET Affidabilita = nuova_affidabilita,
        nr_progetti = progetti_totali
    WHERE Id = id_creatore_progetto;
END //

-- Cambiare lo stato di un progetto da aperto a chiuso
CREATE TRIGGER Chiusura_progetto
AFTER INSERT ON Finanziamento
FOR EACH ROW
BEGIN
    DECLARE totale_finanziamenti DECIMAL(10,2);
    DECLARE budget_progetto DECIMAL(10,2);
    
-- Ottenere il totale dei finanziamenti -> Ottenere il budget del progetto
    SELECT SUM(Importo) INTO totale_finanziamenti
    FROM Finanziamento
    WHERE NomeProgetto = NEW.NomeProgetto;
    
    SELECT Budget INTO budget_progetto
    FROM Progetto
    WHERE Nome = NEW.NomeProgetto;
    
    -- Controllo se effettivamente sia abbastanza
    IF totale_finanziamenti >= budget_progetto THEN
        UPDATE Progetto
        SET Stato = 'chiuso'
        WHERE Nome = NEW.NomeProgetto AND Stato = 'aperto';
    END IF;
END // 

-- Incrementare il numero di progetti
CREATE TRIGGER Incrementa_progetti
AFTER INSERT ON Progetto
FOR EACH ROW
BEGIN
    UPDATE Creatore
    SET nr_progetti = nr_progetti + 1
    WHERE Id = NEW.IdCreatore;
END //

-- Evento per cambiare lo stato di un progetto in data di scadenza
CREATE EVENT Scadenza_progetto
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_DATE + INTERVAL 1 DAY
DO
BEGIN
    UPDATE Progetto
    SET Stato = 'chiuso'
    WHERE Stato = 'aperto' 
    AND DataLimite < CURDATE();
END // DELIMITER;



