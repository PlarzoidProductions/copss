USE copss;

INSERT INTO achievements (name, points, per_game, is_meta, unique_opponent) VALUES ("Played New Opponent", 1, 1, 0, 1);
INSERT INTO achievements (name, points, per_game, is_meta, unique_opponent_locations) VALUES ("New Opponent Location", 1, 1, 0, 1);

INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("Played 5 New Opponents", 1, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (3, 1, 5);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("Played 10 New Opponents", 2, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (4, 1, 10);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("Played 15 New Opponents", 3, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (5, 1, 15);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("Played 20 New Opponents", 4, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (6, 1, 20);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("Played 25 New Opponents", 5, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (7, 1, 25);

INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("5 Unique Opponent Locations", 1, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (8, 2, 5);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("10 Unique Opponent Locations", 2, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (9, 2, 10);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("15 Unique Opponent Locations", 3, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (10, 2, 15);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("20 Unique Opponent Locations", 4, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (11, 2, 20);
INSERT INTO achievements (name, points, per_game, is_meta) VALUES ("25 Unique Opponent Locations", 5, 0, 1);
INSERT INTO meta_achievement_criteria (parent_achievement, child_achievement, count) VALUES (12, 2, 25);

INSERT INTO achievements (name, points, per_game, is_meta, game_count) VALUES ("Played 5 Games", 1, 0, 0, 5);
INSERT INTO achievements (name, points, per_game, is_meta, game_count) VALUES ("Played 10 Games", 2, 0, 0, 10);
INSERT INTO achievements (name, points, per_game, is_meta, game_count) VALUES ("Played 15 Games", 3, 0, 0, 15);
INSERT INTO achievements (name, points, per_game, is_meta, game_count) VALUES ("Played 20 Games", 4, 0, 0, 20);
INSERT INTO achievements (name, points, per_game, is_meta, game_count) VALUES ("Played 25 Games", 5, 0, 0, 25);

