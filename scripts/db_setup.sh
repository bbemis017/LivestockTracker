#stty -echo
#printf "Mysql root password: "
#read PASSWORD
#stty echo
echo ""
db="LivestockTracker"

echo "setup:\n"
mysql -u local_access < "../config/db_setup.sql"

cd ../src/private/models

#migrate sql files twice in case one is dependent on another
for i in $(seq 1 2)
do
  echo "\nround $i"
  for dir in ./*/
  do
    #get directory names
    dir=${dir%*/}
    dir=${dir##*/}

    #if Table.sql exists
    if ls "$dir"/Table.sql 1> /dev/null 2>&1; then
      echo "$dir Table:"
      mysql -u root "-p$PASSWORD" $db < "$dir"/Table.sql
    fi
  done
done

echo "\nFinished\n";
