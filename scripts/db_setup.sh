stty -echo
printf "Mysql root password: "
read PASSWORD
stty echo
echo ""
mysql -u root "-p$PASSWORD" < "../config/db_setup.sql"
#mysql -u root "-p$PASSWORD" livestock < "../config/db_tables.sql"
echo "finished"
