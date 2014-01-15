SELECT 'Building database...' as '';
SOURCE ../creation/model.sql

SELECT 'Adding Admin account...' as '';
SOURCE ../creation/add_admin.sql

SELECT 'Adding PHP account...' as '';
SOURCE ../creation/allow_access.sql

SELECT 'Adding default Location info...' as '';
SOURCE ../creation/add_locations.sql

SELECT 'Adding default PP Data...' as '';
SOURCE ../creation/add_wm_hordes.sql
SOURCE ../creation/add_misc_pp.sql

/* 
SELECT 'Adding default Achievements...' as '';
SOURCE ../creation/add_achievements.sql
*/

SELECT 'Adding TempleCon 2014 Event and Achievement Setup...' as '';
SOURCE ../creation/templecon_2014_setup.sql
