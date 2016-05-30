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
