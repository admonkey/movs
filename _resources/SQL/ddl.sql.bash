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

# actual execution code
source ddl.sql.inc.bash
