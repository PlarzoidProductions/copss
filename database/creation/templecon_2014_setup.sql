USE iron_arena;

INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, unique_opponent) VALUES ("Played New Opponent", 0, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, unique_opponent_locations) VALUES ("New Opponent Location", 0, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, played_theme_force) VALUES ("Played Theme Force", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, fully_painted) VALUES ("Played Fully Painted", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, played_scenario) VALUES ("Scenario Table", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, multiplayer) VALUES ("Multiplayer Game", 2, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, vs_vip) VALUES ("Play vs VIP", 2, 1, 0, 1, 1);

INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 7 Scenario Tables", 2, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (8, 5, 7);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 5 New Opponents", 1, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (9, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 10 New Opponents", 2, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (10, 1, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 15 New Opponents", 5, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (11, 1, 15);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 20 New Opponents", 10, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (12, 1, 20);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("5 Unique Opponent Locations", 2, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (13, 2, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("10 Unique Opponent Locations", 5, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (14, 2, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("15 Unique Opponent Locations", 10, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (15, 2, 15);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 25pt Game", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 35pt Game", 1, 1, 0, 1, 2);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 50pt Game", 2, 1, 0, 1, 3);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 75pt Game", 3, 1, 0, 1, 4);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played All Game Sizes", 1, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (20, 16, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (20, 17, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (20, 18, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (20, 19, 1);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 5 Games", 1, 0, 0, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 10 Games", 1, 0, 0, 1, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 15 Games", 2, 0, 0, 1, 15);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 20 Games", 2, 0, 0, 1, 20);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Cyg", 0, 0, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Kha", 0, 0, 0, 1, 2);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against PoM", 0, 0, 0, 1, 3);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Crx", 0, 0, 0, 1, 4);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Mer", 0, 0, 0, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Ret", 0, 0, 0, 1, 6);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Con", 0, 0, 0, 1, 7);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against LoE", 0, 0, 0, 1, 8);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against CoO", 0, 0, 0, 1, 9);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Sko", 0, 0, 0, 1, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Tro", 0, 0, 0, 1, 11);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, faction_id) VALUES ("Played against Min", 0, 0, 0, 1, 12);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played against All Factions", 5, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 25, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 26, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 27, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 28, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 29, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 30, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 31, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 32, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 33, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 34, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 35, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (37, 36, 1);


INSERT INTO events (name) VALUES ('Iron Arena');
INSERT INTO events (name) VALUES ('Iron Gauntlet Preliminaries');
INSERT INTO events (name) VALUES ('Commander\'s Crucible');
INSERT INTO events (name) VALUES ('Midnight Madness (Flanks)');
INSERT INTO events (name) VALUES ('Team Tournament');
INSERT INTO events (name) VALUES ('Midnight Madness (Crucible)');
INSERT INTO events (name) VALUES ('HARDCORE');
INSERT INTO events (name) VALUES ('Special Forces');
INSERT INTO events (name) VALUES ('Midnight Madness (Death Race)');
INSERT INTO events (name) VALUES ('Blood, Sweat and Tiers');

INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Wore Midnight Madness 2014 T-shirt", 2, 0, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Displayed TempleCon 2014 patch", 2, 0, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Displayed TempleCon 2014 coin", 5, 0, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Iron Gauntlet Preliminaries", 10, 0, 0, 1, 2);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Commander\'s Crucible", 10, 0, 0, 1, 3);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Midnight Madness (Flanks)", 10, 0, 0, 1, 4);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Team Tournament", 10, 0, 0, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Midnight Madness (Crucible)", 10, 0, 0, 1, 6);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed HARDCORE", 10, 0, 0, 1, 7);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Special Forces", 10, 0, 0, 1, 8);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Midnight Madness (Death Race)", 10, 0, 0, 1, 9);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, event_id) VALUES ("Completed Blood, Sweat and Tiers", 10, 0, 0, 1, 10);
