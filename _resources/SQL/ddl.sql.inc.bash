# must be in proper order for drop/add with key relationships
ddl_files=(
  "ddl.sql"
  "sp.sql"
  "seed.sql"
)
fake_data_files=()

# move to working directory
cd $( dirname "${BASH_SOURCE[0]}" )

if $include_fake_data; then
  for sql in "${fake_data_files[@]}"
  do
    ddl_files+=($sql)
  done
fi

for sql in "${ddl_files[@]}"
do
  mysql --host="$database_server" --user="$database_user" --password="$database_password" --database="$database_name" < $sql
done
