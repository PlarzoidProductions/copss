#!/bin/bash
./create_classes.php ../creation/create_model.sql ../../classes/

echo ""
echo "Populating Database with default values..."
mysql -u root -p < populate.sql
