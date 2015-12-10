INSERT INTO `doc_cat` ( `name`, `symbol`, `doc_no_tmp`, `as_income`, `as_cost`, `as_bill`, `is_locked`, `year_id`, `file_cat_id`, `commitment_acc_id`, `tax_commitment_acc_id`) VALUES
('Umowa',                 'Um', NULL, 1, 0, 0, 0, 1, 7, 26, NULL),
('Rachunek',              'Ra', NULL, 0, 1, 1, 1, 1, 4, 39, 32),
('Rachunek własny',       'RaW', 'byYear|#S #i/#Y', 1, 0, 0, 0, 1, 6, 24, 18),
('Faktura VAT',           'FV', NULL, 0, 1, 0, 1, 1, 5, 24, NULL),
('Wyciąg bankowy',        'WB', NULL, 1, 1, 0, 1, 1, NULL, NULL, NULL),
('Polecenie księgowania', 'PK', NULL, 1, 1, 0, 0, 1, NULL, NULL, NULL),
('Bilans otwarcia',       'BO', NULL, 0, 0, 0, 1, 1, NULL, NULL, NULL),
('Uchwała',               'Uch', NULL, 1, 0, 0, 0, 1, NULL, NULL, NULL),
('Raport Kasowy',         'RK', NULL, 0, 0, 0, 1, 1, NULL, NULL, NULL);
