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
    EmailUtente VARCHAR(30) PRIMARY KEY,
    Affidabilita INT ,
    nr_progetti INT, -- ridondanza???????????
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email)
) ENGINE=INNODB;

CREATE TABLE Amministratore (
    CodiceSicurezza INT ,
    EmailUtente VARCHAR(30) PRIMARY KEY,
    FOREIGN KEY (EmailUtente) REFERENCES Utente(Email)
) ENGINE=INNODB;

CREATE TABLE Progetto (
    EmailCreatore VARCHAR(30),
    Nome VARCHAR(30) PRIMARY KEY,
    Descrizione VARCHAR(255), 
    DataInserimento DATE,
	DataLimite DATE,
    Budget DECIMAL(10,2),
    Stato ENUM('aperto','chiuso'),
    Tipologia ENUM('hardware','software'),
    FOREIGN KEY (EmailCreatore) REFERENCES Creatore(EmailUtente)
) ENGINE=INNODB;

CREATE TABLE FotoProgetto(  
	IdFoto INT AUTO_INCREMENT PRIMARY KEY,
    NomeProgetto VARCHAR(30),
    PercorsoFoto VARCHAR(255),    
    FOREIGN KEY (NomeProgetto) REFERENCES Progetto(Nome)
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
    Assegnato BOOLEAN DEFAULT FALSE,
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
    EmailCreatore VARCHAR(30),
    CodCommento INT PRIMARY KEY,
    DataRisposta DATE,
    Testo VARCHAR(500),
    FOREIGN KEY (CodCommento) REFERENCES Commento(CodiceCommento),
    FOREIGN KEY (EmailCreatore) REFERENCES Creatore(EmailUtente)
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

-- OPERAZIONI SUI DATI--

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
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email già registrata';
		END IF;
        
		INSERT INTO Utente (Email, Nome, Cognome, AnnoNascita, LuogoNascita, Nickname, Password, Ruolo)
		VALUES (New_Email, New_Nome, New_Cognome, New_Anno_nascita, New_Luogo_nascita, New_Nickname, New_Password, New_Ruolo);
        
        IF New_Ruolo = 'creatore' THEN
			INSERT INTO Creatore(EmailUtente,Affidabilita,nr_progetti)
            VALUES (New_Email,0,0);
		ELSEIF New_Ruolo = 'amministratore' THEN
			IF New_CodiceSicurezza IS NULL THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Codice sicurezza obbligatorio';
			END IF;
            INSERT INTO Amministratore(CodiceSicurezza, EmailUtente)
			VALUES (New_CodiceSicurezza, New_Email);
		END IF;
END //

--Autenticazione per gli utenti(tutti)
CREATE PROCEDURE Autenticazione(IN Email_inserita VARCHAR(30),IN Password_inserita VARCHAR(255),OUT Esito BOOLEAN,OUT RuoloRegistrato VARCHAR(20))
BEGIN
    DECLARE PasswordRegistrata VARCHAR(255);
    SET Esito=FALSE;
        SELECT Password,Ruolo
        INTO PasswordRegistrata,RuoloRegistrato
        FROM Utente
        WHERE Email = Email_inserita;
        IF Password_inserita IS NOT NULL AND PasswordRegistrata=Password_inserita THEN
            SET Esito=TRUE;
		END IF;
END //     

-- Inserire una skill di curriculum
Create Procedure AggiungiSkillUtente(in Email_utente varchar(30),in Competenza_utente varchar(30),in Livello_competenza int)
BEGIN
			IF NOT EXISTS (SELECT Email FROM Utente WHERE Email = Email_utente) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Email non trovata';
			END IF;
            IF NOT EXISTS (SELECT Competenza FROM Skills WHERE Competenza = Competenza_utente) THEN
				SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Questa skill non esiste';
			END IF;
            IF EXISTS (SELECT 1 FROM SkillUtente WHERE EmailUtente = Email_utente AND CompetenzaUtente = Competenza_utente) THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Skill è già inserita';
            END IF;
			INSERT INTO SkillUtente (EmailUtente, CompetenzaUtente, Livello)
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

-- Visualizza i commenti di un dato progetto,la risposta potrebbe non essere ancora stata inserita quindi controlla che non sia null
-- Un creatore può rispondere ad un commento una sola volta
CREATE PROCEDURE VisualizzaCommenti(IN Nome_Progetto VARCHAR(30))
BEGIN
    SELECT 
        C.CodiceCommento AS 'CodiceCommento',
        U.Nickname AS 'Poster',
        C.Testo AS 'Contenuto',
        C.DataCommento AS 'Data', 
        IFNULL(R.Testo, '') AS 'Risposta'
    FROM Commento C 
    JOIN Utente U ON C.EmailUtente = U.Email 
    LEFT JOIN Risposta R ON R.CodCommento = C.CodiceCommento
    WHERE C.NomeProgetto = Nome_Progetto
    ORDER BY CodiceCommento DESC;
END //

-- prende tutti i dati di un utente per restituire la lista delle sue skills inserite
CREATE PROCEDURE VisualizzaSkillsUtente(IN Email_Utente VARCHAR(30))
BEGIN
    SELECT CompetenzaUtente,Livello 
    FROM SkillUtente S JOIN Utente U ON U.Email = S.EmailUtente
    WHERE S.EmailUtente = Email_Utente;
END //

-- Finanziare un progetto aperto e scelta del reward:
-- visualizzazione delle reward disponibili per permettere all'utente di scegliere
CREATE PROCEDURE VisualizzazioneReward( IN Nome_Progetto VARCHAR(30))
BEGIN
	IF NOT EXISTS ( SELECT 1 FROM Progetto 
	WHERE Nome = Nome_Progetto AND Stato = 'aperto') THEN
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato o non aperto';
    END IF;
    
    SELECT R.Codice, R.Descrizione, R.PercorsoFoto
    FROM Rewards as R
    WHERE R.NomeProgetto = Nome_Progetto
    ORDER BY R.Codice;
END //

-- Restituisce le foto di un progetto dato il suo nome
CREATE PROCEDURE VisualizzaFotoProgetto(IN Nome_Progetto VARCHAR(30))
BEGIN
    SELECT PercorsoFoto FROM FotoProgetto WHERE NomeProgetto = Nome_Progetto;
END //

--Visualizza le componenti di progetto software
CREATE PROCEDURE VisualizzaComponenti(IN nome_Progetto VARCHAR(30))
BEGIN
    SELECT Nome, Descrizione, Quantita, Prezzo FROM Componenti WHERE NomeProgetto = nome_Progetto;
END //

--Visualizza profili software richiesti
CREATE PROCEDURE VisualizzaProfili(IN Nome_Progetto VARCHAR(30))
BEGIN 
    IF NOT EXISTS(SELECT 1 FROM Progetto WHERE Nome=Nome_Progetto AND Stato = 'aperto')
    THEN 
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato o non aperto';
    END IF;

    IF EXISTS(SELECT 1 FROM Progetto WHERE Nome=Nome_Progetto AND Tipologia = 'hardware')
    THEN 
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Progetto selezionato è progetto hardware, non software';
    END IF;

    SELECT P.Nome , S.CompetenzaRichiesta, S.Livello, P.Assegnato
    FROM Profili as P 
    JOIN SkillRichieste as S ON P.Id = S.IdProfilo
    WHERE P.NomeProgetto = Nome_Progetto;
END //

--Visualizzazione progetti del creatore di riferimento (solo x creatore)
CREATE PROCEDURE VisualizzaProgettiPersonali(IN email_creatore VARCHAR(30))
BEGIN
    SELECT P.Nome, P.Descrizione, P.DataInserimento, P.DataLimite, P.Budget, P.Tipologia, P.Stato
    FROM Progetto as P 
    WHERE P.EmailCreatore = email_creatore; 
END //
--Visualizza Candidature nei propri progetti (solo x creatore)
CREATE PROCEDURE VisualizzaCandidatureProgettiPersonali(IN email_creatore VARCHAR(30))
BEGIN
    -- Restituisce tutte le candidature legate ai progetti del creatore
    SELECT C.Id AS IdCandidatura, P.NomeProgetto, P.Nome AS NomeProfilo, C.EmailUtente, C.Stato
    FROM Candidatura C
    JOIN Profili P ON C.IdProfilo = P.Id
    JOIN Progetto PR ON P.NomeProgetto = PR.Nome
    WHERE PR.EmailCreatore = email_creatore;
END //

--Visualizza Finanziamenti Avvenuti su un progetto
CREATE PROCEDURE VisualizzaFinanziamenti(IN Nome_Progetto VARCHAR(30))
BEGIN
    SELECT F.Codice, F.EmailUtente, F.Importo, F.DataFinanziamento,F.CodiceReward,R.Descrizione
    FROM Finanziamento AS F 
    JOIN Rewards AS R ON R.Codice=F.CodiceReward
    WHERE F.NomeProgetto = Nome_Progetto; 
END //
--Visualizza Finanziamenti Fatti da un utente
CREATE PROCEDURE VisualizzaFinanziamentiPropri(IN Email_Utente VARCHAR(30))
BEGIN 
    SELECT F.NomeProgetto, F.Codice, F.Importo, F.DataFinanziamento, F.CodiceReward, R.Descrizione
    FROM Finanziamento AS F 
    LEFT JOIN Rewards AS R ON R.Codice = F.CodiceReward
    WHERE F.EmailUtente = Email_Utente;
END //

--Restituisce budget Progetto
CREATE PROCEDURE OttieneProjectBudget(IN p_nomeProgetto VARCHAR(30))
BEGIN
    SELECT Budget FROM Progetto WHERE Nome = p_nomeProgetto;
END //

--restituisce progetti totale finanziamenti
CREATE PROCEDURE OttieneProjectTotaleFinanziamenti(IN p_nomeProgetto VARCHAR(30))
BEGIN
    SELECT SUM(Importo) AS TotaleFinanziato
    FROM Finanziamento
    WHERE NomeProgetto = p_nomeProgetto;
END //

--visualizza le candidature con relativo esito effettuate da un utente
CREATE PROCEDURE VisualizzaCandidatureUtente(IN p_emailUtente VARCHAR(30))
BEGIN
    -- Controlla se l'utente esiste
    IF NOT EXISTS(SELECT 1 FROM Utente WHERE Email = p_emailUtente) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Utente con l''email specificata non trovato.';
    END IF;

    SELECT P.Nome AS NomeProgetto,PROF.Nome AS NomeProfilo,C.Stato AS StatoCandidatura
    FROM Candidatura C
    JOIN Profili PROF ON C.IdProfilo = PROF.Id
    JOIN Progetto P ON PROF.NomeProgetto = P.Nome
    WHERE C.EmailUtente = p_emailUtente
    ORDER BY P.Nome, PROF.Nome;
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
    
    INSERT INTO Finanziamento (EmailUtente,Importo,DataFinanziamento,NomeProgetto,CodiceReward) 
    VALUES (Email_utente,Importo_finanziamento,CURDATE(),Nome_progetto,Codice_reward);   
END //

-- Aggiunta di un commento
CREATE PROCEDURE InserimentoCommento(Email_utente VARCHAR(30),IN Testo_inserito  VARCHAR(500), IN NomeProgetto_Scelto VARCHAR(30))
	BEGIN
    DECLARE ControlloEsistenzaProgetto BOOLEAN;
    SELECT EXISTS (SELECT 1
					FROM Progetto AS P
                    WHERE NomeProgetto_Scelto = P.Nome)INTO ControlloEsistenzaProgetto;
                    
    IF ControlloEsistenzaProgetto=TRUE THEN
		INSERT INTO Commento(EmailUtente, DataCommento,Testo ,NomeProgetto)
        VALUES (Email_utente,CURDATE(), Testo_inserito, NomeProgetto_scelto);
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
    DECLARE ProgettoAperto BOOLEAN;
    DECLARE CandidaturaEsiste BOOLEAN;
    DECLARE ProfiloAssegnato BOOLEAN;
    -- Recupera nome progetto associato al profilo
    SELECT NomeProgetto INTO Nome_Progetto
    FROM Profili
    WHERE Id = Id_Profilo;

    -- Controlla progetto
    SELECT EXISTS(
        SELECT 1 FROM Progetto
        WHERE Nome = Nome_Progetto AND Stato = 'aperto'
    ) INTO ProgettoAperto;

    IF ProgettoAperto = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non valido o non aperto';
    END IF;

    -- Controlla se profilo è già stato assegnato
    SELECT Assegnato INTO ProfiloAssegnato
    FROM Profili
    WHERE Id = Id_Profilo;

    IF ProfiloAssegnato = TRUE THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Profilo è già stato assegnato';
    END IF;

    -- Controlla se utente si era già candidato precedentemente
    SELECT EXISTS(
        SELECT 1 FROM Candidatura
        WHERE EmailUtente = Email_Utente AND IdProfilo = Id_Profilo AND stato IN ('in_attesa', 'accettata')
    ) INTO CandidaturaEsiste;

    IF CandidaturaEsiste = 1 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Candidatura già presente per questo profilo';
    END IF;

    -- Controllo skill utente
    IF EXISTS(
        SELECT 1 
        FROM SkillRichieste AS Sr
        WHERE Sr.IdProfilo = Id_Profilo
          AND NOT EXISTS(
            SELECT 1 
            FROM SkillUtente AS Su
            WHERE Su.EmailUtente = Email_Utente
              AND Su.CompetenzaUtente = Sr.CompetenzaRichiesta
              AND Su.Livello >= Sr.Livello
          )
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Skill insufficienti per la candidatura';
    END IF;

    -- Inserisce la candidatura con stato 'in_attesa'
    INSERT INTO Candidatura (EmailUtente, IdProfilo, stato)
    VALUES (Email_Utente, Id_Profilo, 'in_attesa');
END //

-- Operazioni degli Amministratori----------------------------------------------------------

--Autenticazione Ulteriore per gli admin
CREATE PROCEDURE AutenticazioneAdmin(IN Email_inserita VARCHAR(30),Codice_inserito INT,OUT Esito BOOLEAN)
BEGIN
    DECLARE CodiceRegistrato INT;
    SET Esito=FALSE;
        SELECT CodiceSicurezza
        INTO CodiceRegistrato
        FROM Amministratore
        WHERE EmailUtente = Email_inserita
        LIMIT 1;
    IF Codice_inserito IS NOT NULL AND CodiceRegistrato=Codice_inserito THEN
        SET Esito=TRUE;
	END IF;
END //     

-- Inserimento di una nuova skill
CREATE PROCEDURE InserimentoCompetenza (IN Nome_competenza VARCHAR(30), IN Codice_sicurezza INT)
BEGIN 
	DECLARE ControlloEsistenzaCompetenza BOOLEAN;
    DECLARE ControlloAmministratore BOOLEAN;
    
    SELECT EXISTS (SELECT 1
					FROM Amministratore AS A
                    WHERE A.CodiceSicurezza = Codice_sicurezza)INTO ControlloAmministratore;
	SELECT EXISTS (SELECT 1
					FROM Skills AS S
                    WHERE S.Competenza=Nome_competenza)INTO ControlloEsistenzaCompetenza;
	
    IF ControlloEsistenzaCompetenza=FALSE AND ControlloAmministratore=TRUE THEN
		INSERT INTO Skills(Competenza)
        VALUES (Nome_competenza);
	ELSE SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Competenza già inserita o atenticazione come amministratore non andata a buon fine';
	END IF;
END //

-- Operazioni dei Creatori------------------------------------------------------------------

-- Controllare se si possiede effettivamente il progetto
CREATE PROCEDURE ControlloProgetto(IN Email_inserita VARCHAR(30),IN Progetto_inserito VARCHAR(30),OUT Esito BOOLEAN)
BEGIN
    DECLARE Esiste BOOLEAN;
    SET Esito = FALSE;
    SELECT EXISTS(
        SELECT 1
        FROM Progetto
        WHERE EmailCreatore = Email_inserita AND Nome = Progetto_inserito
    ) INTO Esiste;

    IF Esiste THEN
        SET Esito = TRUE;
    END IF;
END //

-- Inserire un nuovo progetto
CREATE PROCEDURE AggiungiProgetto(
    IN Email_Creatore VARCHAR(30),
    IN Nome_Progetto VARCHAR(30),
    IN Descrizione_Progetto VARCHAR(255),
    IN Budget DECIMAL(10,2),
    IN Limite DATE,
    IN Tipologia ENUM('hardware','software')
)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Creatore WHERE EmailUtente = Email_Creatore) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Creatore non valido';
    END IF;
            
    IF EXISTS (SELECT 1 FROM Progetto WHERE Nome = Nome_Progetto) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Nome progetto già esistente';
    END IF;
            
    INSERT INTO Progetto (EmailCreatore, Nome, Descrizione, DataInserimento, Budget, DataLimite, Stato, Tipologia) 
    VALUES (Email_Creatore, Nome_Progetto, Descrizione_Progetto, CURDATE(), Budget, Limite, 'aperto', Tipologia);
END //

-- Inserimento foto nel Progetto
CREATE PROCEDURE AggiungiFotoProgetto( IN NomeProgetto_inserito VARCHAR(30), IN Percorsofoto_inserito VARCHAR(255),
									IN EmailCreatore_inserito VARCHAR(30))
BEGIN
    DECLARE Controllo BOOLEAN;
    SELECT EXISTS (
        SELECT 1
        FROM Progetto
        WHERE Nome = NomeProgetto_inserito AND EmailCreatore = EmailCreatore_inserito
    ) INTO Controllo;

    IF Controllo = TRUE THEN
        INSERT INTO FotoProgetto( NomeProgetto, PercorsoFoto)
        VALUES (NomeProgetto_inserito, Percorsofoto_inserito);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Non sei il creatore';
    END IF;
END //

-- inserimento Reward 
CREATE PROCEDURE InserimentoReward( IN Descrizione_reward VARCHAR(300), IN Nome_progetto VARCHAR(30),
									IN Email_creatore VARCHAR(30), IN  Percorso_Foto VARCHAR(255))
BEGIN
	DECLARE Controllo BOOLEAN;
    SELECT EXISTS (
        SELECT 1
        FROM Progetto
        WHERE Nome = Nome_progetto AND EmailCreatore = Email_creatore AND Stato = 'aperto'
    ) INTO Controllo;

    IF Controllo = TRUE THEN
        INSERT INTO Rewards(Descrizione, NomeProgetto, PercorsoFoto)
        VALUES (Descrizione_reward, Nome_progetto, Percorso_foto);
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato o chiuso';
    END IF;
END //

-- inserimento di una risposta
CREATE PROCEDURE InserimentoRisposta (IN Email_creatore VARCHAR(30),IN Cod_commento INT,IN Testo_comm VARCHAR(500))
BEGIN
    DECLARE ControlloCodiceCommento BOOLEAN;
    DECLARE ControlloCreatore BOOLEAN;
    DECLARE ControlloRisposta BOOLEAN ; 
    -- controllo esistenza del codice commento
    SELECT EXISTS (SELECT 1
					FROM Commento AS C
                    WHERE C.CodiceCommento = Cod_commento)
                    INTO ControlloCodiceCommento;
	-- controllo associazione tra creatore e progetto
    SELECT EXISTS (
        SELECT 1
        FROM Commento C
        JOIN Progetto P ON C.NomeProgetto = P.Nome
        WHERE C.CodiceCommento = Cod_commento AND P.EmailCreatore = Email_creatore
    ) INTO ControlloCreatore;

    -- controllo che non sia già stata inserita la risposta    
     SELECT EXISTS (SELECT 1 FROM Risposta WHERE CodCommento = Cod_commento)  INTO ControlloRisposta;
                    
    IF ControlloCodiceCommento = TRUE AND ControlloCreatore=TRUE AND ControlloRisposta=FALSE THEN
		INSERT INTO Risposta(EmailCreatore, CodCommento, DataRisposta, Testo)
        VALUES (Email_creatore, Cod_commento, CURDATE(), Testo_comm);
	ELSE SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Commento non trovato o risposta già inserita';
	END IF;
END //

-- Inserimento nuovo profilo per un progetto software
Create Procedure AggiungiProfiloSoftware(IN Email_creatore VARCHAR(30),in Nome_Progetto varchar(30),in Nome_Profilo varchar(30)) 
BEGIN
	IF NOT EXISTS (
        SELECT 1 FROM Creatore WHERE EmailUtente= Email_creatore
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Id non valido';
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM Progetto 
        WHERE Nome = Nome_Progetto AND Tipologia = 'software' AND Stato = 'aperto'
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non trovato';
    END IF;

    INSERT INTO Profili (Nome, NomeProgetto)
    VALUES (Nome_Profilo, Nome_Progetto);

    SELECT LAST_INSERT_ID() AS IdNuovoProfilo;
END //

-- Inserimento skill richieste nei profili per un progetto software
CREATE PROCEDURE AggiungiSkillProfilo (IN Id_profilo INT,IN Skill VARCHAR(30),IN Livello INT)
BEGIN
    IF NOT EXISTS (SELECT 1 FROM Profili WHERE Id = Id_profilo) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Profilo non esistente';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM Skills WHERE Competenza = Skill) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Skill non valida';
    END IF;

    IF EXISTS (
        SELECT 1 FROM SkillRichieste
        WHERE IdProfilo = Id_profilo AND CompetenzaRichiesta = Skill
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Skill già inserita per questo profilo';
    END IF;

    INSERT INTO SkillRichieste (IdProfilo, CompetenzaRichiesta, Livello)
    VALUES (Id_profilo, Skill, Livello);
END //
     
-- Inserimento Componente per progetto hardware
CREATE PROCEDURE AggiungiComponente(IN NomeComponente VARCHAR(30), IN DescrizioneComponente VARCHAR(255),
    IN Quantita INT,IN Prezzo DECIMAL(10,2),IN NomeProgetto VARCHAR(30))
BEGIN
    DECLARE Controllo BOOLEAN;
    -- verifica esistenza progetto
    SELECT EXISTS (
        SELECT 1 FROM Progetto 
        WHERE Nome = NomeProgetto AND Tipologia = 'hardware' AND Stato = 'aperto'
    ) INTO Controllo;

    IF Controllo = FALSE THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Progetto non valido o non aperto';
    END IF;

    INSERT INTO Componenti(Nome, Descrizione, Quantita, Prezzo, NomeProgetto)
    VALUES (NomeComponente, DescrizioneComponente, Quantita, Prezzo, NomeProgetto);
END //


-- Accettazione o meno di una candidatura
CREATE PROCEDURE AccettazioneCandidatura (IN Id_Candidatura INT, 
										IN Email_creatore VARCHAR(30),  
                                        IN Esito_Candidatura ENUM('accettata', 'rifiutata'))
BEGIN
	DECLARE controlloCandidatura BOOLEAN DEFAULT FALSE;
    DECLARE id_profilo INT;

    
    -- controllo esistenza candidatura e appartenenza progetto a creatore
    SELECT EXISTS (
        SELECT 1
        FROM Candidatura C
        JOIN Profili PR ON C.IdProfilo = PR.Id
        JOIN Progetto P ON PR.NomeProgetto = P.Nome
        WHERE C.Id = Id_candidatura AND P.EmailCreatore = Email_creatore
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

    -- se esito=accettata recupera id_profilo e aggiorna Assegnato = TRUE
    IF Esito_Candidatura = 'accettata' THEN
        SELECT C.IdProfilo
        INTO id_profilo
        FROM Candidatura C
        JOIN Profili PR ON C.IdProfilo = PR.Id
        JOIN Progetto P ON PR.NomeProgetto = P.Nome
        WHERE C.Id = Id_Candidatura AND P.EmailCreatore = Email_creatore;

        UPDATE Profili
        SET Assegnato = TRUE
        WHERE Id = id_profilo;
    END IF;
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
Select p.Nome,p.Descrizione,p.Budget, IFNULL((p.Budget - SUM(F.Importo)), p.Budget) as Differenza
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
    WHERE EmailCreatore = NEW.EmailCreatore;
    
    SELECT COUNT(DISTINCT p.Nome) INTO progetti_finanziati
    FROM Progetto p
    JOIN Finanziamento f ON p.Nome = f.NomeProgetto
    WHERE p.EmailCreatore = NEW.EmailCreatore;
    
    IF progetti_totali > 0 THEN
        SET nuova_affidabilita = (progetti_finanziati * 100) / progetti_totali;
    ELSE
        SET nuova_affidabilita = 0;
    END IF;
        -- Aggiorna l'affidabilità del creatore
    UPDATE Creatore
    SET Affidabilita = nuova_affidabilita,
        nr_progetti = progetti_totali
    WHERE EmailUtente = NEW.EmailCreatore;
END //

-- Aggiornare l'affidabilità dopo un finanziamento (Da rivedere)
CREATE TRIGGER Affidabilità_finanziamento
AFTER INSERT ON Finanziamento
FOR EACH ROW
BEGIN
    DECLARE progetti_totali INT;
    DECLARE progetti_finanziati INT;
    DECLARE nuova_affidabilita INT;
    DECLARE email_creatore_progetto VARCHAR(30);
    
    -- Trova l'email del creatore del progetto finanziato
    SELECT EmailCreatore INTO email_creatore_progetto
    FROM Progetto
    WHERE Nome = NEW.NomeProgetto;
    
    -- Conta il numero totale di progetti del creatore
    SELECT COUNT(*) INTO progetti_totali
    FROM Progetto
    WHERE EmailCreatore = email_creatore_progetto;
    
    -- Conta il numero di progetti finanziati del creatore (almeno un finanziamento)
    SELECT COUNT(DISTINCT p.Nome) INTO progetti_finanziati
    FROM Progetto p
    JOIN Finanziamento f ON p.Nome = f.NomeProgetto
    WHERE p.EmailCreatore = email_creatore_progetto;
    
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
    WHERE EmailUtente = email_creatore_progetto;
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
    WHERE EmailUtente = NEW.EmailCreatore;
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
END // 

DELIMITER ;

USE Bostarter;

--Test 
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