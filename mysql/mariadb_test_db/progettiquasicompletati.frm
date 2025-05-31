TYPE=VIEW
query=select `P`.`Nome` AS `Nome`,`P`.`Descrizione` AS `Descrizione`,`P`.`Budget` AS `Budget`,`P`.`Budget` - sum(`F`.`Importo`) AS `Differenza` from (`mariadb_test_db`.`progetto` `P` left join `mariadb_test_db`.`finanziamento` `F` on(`P`.`Nome` = `F`.`NomeProgetto`)) where `P`.`Stato` = \'Aperto\' group by `P`.`Nome` order by `P`.`Budget` - sum(`F`.`Importo`) limit 3
md5=5cc12b6ca6377b6040d38f3f360bb8a5
updatable=0
algorithm=0
definer_user=username
definer_host=%
suid=2
with_check_option=0
timestamp=0001748549629258547
create-version=2
source=Select p.Nome,p.Descrizione,p.Budget,(p.Budget - SUM(F.Importo)) as Differenza\nFrom Progetto P left join Finanziamento F on P.Nome = F.NomeProgetto\nwhere p.stato = \'Aperto\'\nGroup By p.Nome\norder by Differenza asc\nLimit 3
client_cs_name=utf8mb4
connection_cl_name=utf8mb4_unicode_ci
view_body_utf8=select `P`.`Nome` AS `Nome`,`P`.`Descrizione` AS `Descrizione`,`P`.`Budget` AS `Budget`,`P`.`Budget` - sum(`F`.`Importo`) AS `Differenza` from (`mariadb_test_db`.`progetto` `P` left join `mariadb_test_db`.`finanziamento` `F` on(`P`.`Nome` = `F`.`NomeProgetto`)) where `P`.`Stato` = \'Aperto\' group by `P`.`Nome` order by `P`.`Budget` - sum(`F`.`Importo`) limit 3
mariadb-version=110702
