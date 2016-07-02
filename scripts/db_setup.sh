stty -echo
printf "Mysql root password: "
read PASSWORD
stty echo
echo ""
db="LivestockTracker"
MODELS="../src/private/models/"
mysql -u root "-p$PASSWORD" < "../config/db_setup.sql"
mysql -u root "-p$PASSWORD" $db < "$MODELS/account/accountTable.sql"
mysql -u root "-p$PASSWORD" $db < "$MODELS/organization/organizationTable.sql"
echo "finished"
