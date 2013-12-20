#!/bin/bash
rm ../../classes/db_*
./create_classes.php ../creation/model.sql ../../classes/

echo ""
echo "Populating Database with default values..."
mysql -u root -p < populate.sql
