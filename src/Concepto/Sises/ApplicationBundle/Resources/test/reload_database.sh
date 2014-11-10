#!/bin/sh

database="concepto_sises"
base=$(dirname $(readlink -f $0))
file="${base}/../files/database.sql"


if [ ! -f "${file}" ];then
    mysqldump -u root --add-drop-database --databases "${database}_dev" -r ${file}
    sed -i ${file} -e "s/${database}_dev/${database}_test/g"
fi

echo "source ${file}" | mysql -u root "${database}_test"