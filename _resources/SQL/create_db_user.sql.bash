#!/bin/bash

database_server="localhost"

# create new password
# thanks vivek@nixCraft
# http://www.cyberciti.biz/faq/linux-random-password-generator/
genpasswd() {
        local l=$1
        [ "$l" == "" ] && l=16
        tr -dc A-Za-z0-9 < /dev/urandom | head -c ${l} | xargs
}
new_id="$(genpasswd 5)"
new_username="movs_user_$new_id"
new_password="$(genpasswd)"
test_dbo_username=""

# default to dev
if [ -z "$1" ]; then

  new_db_name="movs_dev_$new_id"
  sql_privileges="GRANT ALL PRIVILEGES ON $new_db_name.* TO '$new_username'@'$database_server';"

else

  if [ "$1" = "-test" ]; then

    new_db_name="movs_test_$new_id"
    test_dbo_username="$new_username""_"
    sql_privileges="
      CREATE USER '$test_dbo_username'@'$database_server' IDENTIFIED BY '$new_password';
      GRANT ALL PRIVILEGES ON $new_db_name.* TO '$test_dbo_username'@'$database_server';
      GRANT EXECUTE ON $new_db_name.* TO '$new_username'@'$database_server';
    "
    test_dbo_username="database_user=\"$test_dbo_username\";"

  fi

  if [ "$1" = "-prod" ]; then

    new_db_name="movs_prod_$new_id"
    new_password="$(genpasswd 32)"
    sql_privileges="GRANT EXECUTE ON $new_db_name.* TO '$new_username'@'$database_server';"

  fi

fi

# get MySQL root user password
echo "Please Enter the MySQL root user password: "
stty_orig=`stty -g`
stty -echo
read mysql_root_pw
stty $stty_orig

mysql --host="$database_server" --user=root --password="$mysql_root_pw" << EOF

CREATE DATABASE $new_db_name;

CREATE USER '$new_username'@'$database_server' IDENTIFIED BY '$new_password';

$sql_privileges

EOF

cat << EOF >> credentials.local.bash

database_server="$database_server"
database_user="$new_username"
database_password="$new_password"
database_name="$new_db_name"
$test_dbo_username
include_fake_data=false

EOF

cat << EOF >> credentials.local.inc.php

<?php
\$database_server = "$database_server";
\$database_username = "$new_username";
\$database_password = "$new_password";
\$database_name = "$new_db_name";
?>

EOF

# move to working directory
cd $( dirname "${BASH_SOURCE[0]}" )

database_user="root"
database_password="$mysql_root_pw"
database_name="$new_db_name"

source ddl.sql.inc.bash
