#!/bin/bash

database_server="localhost"
database_user="movs_dev_user"
database_password="p@55W0rd"
database_name="movs_dev"

include_fake_data=false


# must be in proper order for drop/add with key relationships
ddl_files=(
  "ddl.sql"
  "sp.sql"
)
fake_data_files=()

# move to working directory
cd $( dirname "${BASH_SOURCE[0]}" )

# trump credentials if external file exists
if [ -f credentials.local.bash ]; then
  source credentials.local.bash
fi

if $include_fake_data; then
  for sql in "${fake_data_files[@]}"
  do
    ddl_files+=($sql)
  done
fi

for sql in "${ddl_files[@]}"
do
  mysql --host=$database_server --user=$database_user --password=$database_password --database=$database_name < $sql
done
