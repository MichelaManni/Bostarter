TYPE=TRIGGERS
triggers='CREATE DEFINER=`username`@`%` TRIGGER Affidabilità_progetto\nAFTER INSERT ON Progetto\nFOR EACH ROW\nBEGIN\n    DECLARE progetti_totali INT;\n    DECLARE progetti_finanziati INT;\n    DECLARE nuova_affidabilita INT;\n    \n	#Contrare il numero di progetti di un creatore -> contrare i progetti finanziati almeno una volta\n    SELECT COUNT(*) INTO progetti_totali\n    FROM Progetto\n    WHERE IdCreatore = NEW.IdCreatore;\n    \n    SELECT COUNT(DISTINCT p.Nome) INTO progetti_finanziati\n    FROM Progetto p\n    JOIN Finanziamento f ON p.Nome = f.NomeProgetto\n    WHERE p.IdCreatore = NEW.IdCreatore;\n    \n    IF progetti_totali > 0 THEN\n        SET nuova_affidabilita = (progetti_finanziati * 100) / progetti_totali;\n    ELSE\n        SET nuova_affidabilita = 0;\n    END IF;\n        -- Aggiorna l\'affidabilità del creatore\n    UPDATE Creatore\n    SET Affidabilita = nuova_affidabilita,\n        nr_progetti = progetti_totali\n    WHERE Id = NEW.IdCreatore;\nEND' 'CREATE DEFINER=`username`@`%` TRIGGER Incrementa_progetti\nAFTER INSERT ON Progetto\nFOR EACH ROW\nBEGIN\n    UPDATE Creatore\n    SET nr_progetti = nr_progetti + 1\n    WHERE Id = NEW.IdCreatore;\nEND'
sql_modes=524288 524288
definers='username@%' 'username@%'
client_cs_names='utf8mb4' 'utf8mb4'
connection_cl_names='utf8mb4_unicode_ci' 'utf8mb4_unicode_ci'
db_cl_names='utf8mb4_uca1400_ai_ci' 'utf8mb4_uca1400_ai_ci'
created=1748549629294792 1748549629416833
