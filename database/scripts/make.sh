#!/bin/bash
echo "Building new PHP Class Files from model..."
rm ../../classes/db_*
./create_classes.php ../creation/model.sql ../../classes/ >> /dev/null
echo "Done!"
echo ""
echo "Ready to build the database"
mysql -u root -p < build.sql

echo "Done!"
