SELECT 'Building database...' as '';
SOURCE ../creation/model.sql

SELECT 'Adding Admin account...' as '';
SOURCE ../creation/add_admin.sql

SELECT 'Adding PHP account...' as '';
SOURCE ../creation/create_access.sql

SELECT 'Adding default Location info...' as '';
SOURCE ../creation/add_locations.sql

SELECT 'Adding CaptainCon Captains\'s Log 2014 Achievements...' as '';
SOURCE ../creation/captaincon_config.sql
