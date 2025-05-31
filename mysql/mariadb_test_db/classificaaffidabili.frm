TYPE=VIEW
query=select `U`.`Nickname` AS `Nickname` from (`mariadb_test_db`.`creatore` `C` join `mariadb_test_db`.`utente` `U` on(`C`.`EmailUtente` = `U`.`Email`)) order by `C`.`Affidabilita` desc limit 3
md5=5996da53c67c715e28f70ba3d19ebb96
updatable=0
algorithm=0
definer_user=username
definer_host=%
suid=2
with_check_option=0
timestamp=0001748549629235135
create-version=2
source=SELECT Nickname\nFROM Creatore C join Utente U on C.EmailUtente = U.Email\nORDER BY affidabilita DESC\nLIMIT 3
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `U`.`Nickname` AS `Nickname` from (`mariadb_test_db`.`creatore` `C` join `mariadb_test_db`.`utente` `U` on(`C`.`EmailUtente` = `U`.`Email`)) order by `C`.`Affidabilita` desc limit 3
mariadb-version=110702
