USE iron_arena;

INSERT INTO game_systems (name) VALUES ('Warmachine / Hordes');

INSERT INTO game_system_factions (parent_game_system, name, acronym) VALUES 
(1, 'Cygnar', 'Cyg'),
(1, 'Khador', 'Kha'),
(1, 'Protectorate', 'PoM'),
(1, 'Cryx', 'Crx'),
(1, 'Mercenaries', 'Mer'),
(1, 'Retribution', 'Ret'),
(1, 'Convergence', 'Con'),

(1, 'Legion', 'LoE'),
(1, 'Circle', 'CoO'),
(1, 'Skorne', 'Sko'),
(1, 'Trollbloods', 'Tro'),
(1, 'Minions', 'Min');

INSERT INTO game_sizes (parent_game_system, size, name) VALUES 
(1, 15, NULL),
(1, 25, NULL),
(1, 50, NULL),
(1, 75, NULL),
(1, 100, 'Unbound');
