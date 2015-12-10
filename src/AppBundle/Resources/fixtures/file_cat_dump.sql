INSERT INTO `file_cat` (`name`, `symbol`, `as_project`, `as_income`, `as_cost`, `as_contractor`, `is_locked`, `year_id`, `sub_file_cat_id`) VALUES
('Koszty statutowe',       'KS', 0, 0, 1, 0, 0, 1, NULL),
('Koszty administracyjne', 'KA', 0, 0, 1, 0, 0, 1, NULL),
('Urzędy Skarbowe',        'US', 0, 0, 0, 0, 1, 1, NULL),
('Zleceniobiorcy',         'ZB', 0, 0, 0, 1, 1, 1, 3),
('Dostawcy',               'DO', 0, 0, 1, 0, 0, 1, NULL),
('Odbiorcy',               'OD', 0, 1, 0, 0, 0, 1, NULL),
('Sponsorzy',              'SP', 0, 1, 0, 0, 0, 1, NULL),
('Projekty',               'PR', 1, 0, 0, 0, 1, 1, NULL);

