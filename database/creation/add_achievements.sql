USE iron_arena;

INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, unique_opponent) VALUES ("Played New Opponent", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, unique_opponent_locations) VALUES ("New Opponent Location", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, played_theme_force) VALUES ("Played Theme Force", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, fully_painted) VALUES ("Played Fully Painted", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, fully_painted_battle) VALUES ("Fully Painted Battle", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, played_scenario) VALUES ("Scenario Table", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, multiplayer) VALUES ("Multiplayer Game", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, vs_vip) VALUES ("Play vs VIP", 1, 1, 0, 1, 1);

INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 5 New Opponents", 1, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (9, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 10 New Opponents", 2, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (10, 1, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 15 New Opponents", 3, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (11, 1, 15);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 20 New Opponents", 4, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (12, 1, 20);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played 25 New Opponents", 5, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (13, 1, 25);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("5 Unique Opponent Locations", 1, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (14, 2, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("10 Unique Opponent Locations", 2, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (15, 2, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("15 Unique Opponent Locations", 3, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (16, 2, 15);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("20 Unique Opponent Locations", 4, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (17, 2, 20);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("25 Unique Opponent Locations", 5, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (18, 2, 25);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 15pt Game", 1, 1, 0, 1, 1);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 25pt Game", 1, 1, 0, 1, 2);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 35pt Game", 2, 1, 0, 1, 3);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_size_id) VALUES ("Played 50pt Game", 3, 1, 0, 1, 4);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id) VALUES ("Played All Game Sizes", 3, 0, 1, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (23, 19, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (23, 20, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (23, 21, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (23, 22, 1);


INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 5 Games", 1, 0, 0, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 10 Games", 2, 0, 0, 1, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 15 Games", 3, 0, 0, 1, 15);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 20 Games", 4, 0, 0, 1, 20);
INSERT INTO achievements (name, points, per_game, is_meta, game_system_id, game_count) VALUES ("Played 25 Games", 5, 0, 0, 1, 25);


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
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 29, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 30, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 31, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 32, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 33, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 34, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 35, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 36, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 37, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 38, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 39, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (41, 40, 1);

