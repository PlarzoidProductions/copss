#!/bin/bash
echo "Building new PHP Class Files from model..."
rm ../../classes/data_abstraction_layer/db_*
./create_classes.php ../creation/model.sql ../../classes/data_abstraction_layer/ >> /dev/null
echo "Done!"
echo ""
echo "Ready to build the database"
mysql -u root -p < build.sql

echo "Done!"
