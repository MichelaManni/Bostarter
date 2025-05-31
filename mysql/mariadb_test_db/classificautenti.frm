TYPE=VIEW
query=select `U`.`Nickname` AS `Nickname` from (`mariadb_test_db`.`finanziamento` `F` join `mariadb_test_db`.`utente` `U` on(`U`.`Email` = `F`.`EmailUtente`)) group by `F`.`EmailUtente`,`U`.`Nickname` order by sum(`F`.`Importo`) desc limit 3
md5=a70102b886f134cee35fe93a870a8617
updatable=0
algorithm=0
definer_user=username
definer_host=%
suid=2
with_check_option=0
timestamp=0001748549629276744
create-version=2
source=SELECT U.Nickname\nFROM FINANZIAMENTO AS F\nJOIN UTENTE AS U ON U.Email = F.EmailUtente\nGROUP BY F.EmailUtente, U.Nickname\nORDER BY SUM(F.Importo) DESC\nLIMIT 3
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `U`.`Nickname` AS `Nickname` from (`mariadb_test_db`.`finanziamento` `F` join `mariadb_test_db`.`utente` `U` on(`U`.`Email` = `F`.`EmailUtente`)) group by `F`.`EmailUtente`,`U`.`Nickname` order by sum(`F`.`Importo`) desc limit 3
mariadb-version=110702
