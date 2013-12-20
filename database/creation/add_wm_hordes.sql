USE iron_arena;

INSERT INTO game_systems (name) VALUES ('Warmachine / Hordes');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Cygnar', 'Cyg');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Khador', 'Kha');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Protectorate', 'PoM');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Cryx', 'Crx');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Mercenaries', 'Mer');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Retribution', 'Ret');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Convergence', 'Con');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Legion', 'LoE');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Circle', 'CoO');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Skorne', 'Sko');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Trollbloods', 'Tro');
INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES (1, 'Minions', 'Min');


INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 15, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 25, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 35, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 50, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 75, NULL);
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 100, 'Unbound');
INSERT INTO game_sizes (parent_game_system, size, name) VALUES (1, 150, 'Unbound');
