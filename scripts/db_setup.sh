stty -echo
printf "Mysql root password: "
read PASSWORD
stty echo
echo ""
db="LivestockTracker"

echo "setup:\n"
mysql -u root "-p$PASSWORD" < "../config/db_setup.sql"

cd ../src/private/models

for dir in ./*/
do
  #get directory names
  dir=${dir%*/}
  dir=${dir##*/}

  #if Table.sql exists
  if ls "$dir"/Table.sql 1> /dev/null 2>&1; then
    echo "$dir Table:\n"
    mysql -u root "-p$PASSWORD" $db < "$dir"/Table.sql
  fi
done

echo "Finished\n";
