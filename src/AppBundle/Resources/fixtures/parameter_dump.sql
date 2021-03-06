INSERT INTO `parameter` (`id`, `name`, `label`, `field_type`, `value_float`, `value_int`, `value_varchar`, `value_date`, `sortable_rank`) VALUES
(1, 'organization_name', 'Nazwa organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXX', NULL, 1),
(2, 'organization_REGON', 'REGON organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXX', NULL, 2),
(3, 'organization_NIP', 'NIP organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXX', NULL, 3),
(4, 'organization_KRS', 'KRS organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXX', NULL, 4),
(5, 'organization_address1', 'Adres (ulica, bud., lok.) organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXX X/X', NULL, 5),
(6, 'organization_address2', 'Adres (kod pocztowy, miejscowość) organizacji', 'varchar', NULL, NULL, 'XX-XXX Warszawa', NULL, 6),
(7, 'organization_email', 'E-mail organizacji', 'varchar', NULL, NULL, 'xxx@xx.xx', NULL, 7),
(8, 'organization_phone', 'Telefon do organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXXXX', NULL, 8),
(9, 'organization_board1', 'Prezes organizacji', 'varchar', NULL, NULL, 'XXXXXX XXXXXXX', NULL, 9),
(10, 'organization_board2', 'Z-ca Prezesa organizacji', 'varchar', NULL, NULL, 'XXXXXX XXXXXXX', NULL, 10),
(11, 'organization_board3', 'Członek Zarządu organizacji', 'varchar', NULL, NULL, 'XXXXXX XXXXXXX', NULL, 11),
(12, 'organization_create_date', 'Data utworzenia organizacji', 'date', NULL, NULL, NULL, 'XXXX-XX-XX', 12),
(13, 'organization_TO', 'Urząd Skarbowy organizacji', 'varchar', NULL, NULL, 'US XXXXXXXXXXX', NULL, 13),
(14, 'TO_PDOF_bank_acc', 'Rachunek bankowy US - PDOF', 'varchar', NULL, NULL, 'XX XXXX XXXX XXXX XXXX XXXX XXXX', NULL, 14),
(15, 'organization_bank', 'Nazwa Banku organizacji', 'varchar', NULL, NULL, 'XXXXXXXXXXXXXXXXX', NULL, 15),
(16, 'default_approver_first_name', 'Domyślna osoba podpisująca raport - imię', 'varchar', NULL, NULL, 'XXXX', NULL, 16),
(17, 'default_approver_last_name', 'Domyślna osoba podpisująca raport - nazwisko', 'varchar', NULL, NULL, 'XXXX', NULL, 17),
(18, 'default_reg_no_tmp', 'Domyślna ewidencja dokumentu w DK', 'varchar', NULL, NULL, 'byMonth|#i/#M-#Y', NULL, 17),
(19, 'default_doc_no_tmp', 'Domyślna numeracja dokumentu', 'varchar', NULL, NULL, 'byMonth|#S #i/#M/#Y', NULL, 18),
(20, 'default_tax_coef', 'Domyślny podatek PDOF [%]', 'float', 0.18, NULL, NULL, NULL, 19),
(21, 'default_cost_coef', 'Domyślne koszty uzyskania przychodu [%]', 'float', 0.5, NULL, NULL, NULL, 20),
(22, 'thousands_sep', 'Domyślny separator tysięcy', 'varchar', NULL, NULL, '_', NULL, 21),
(23, 'dec_point', 'Domyślny separator dziesiętny', 'varchar', NULL, NULL, ',', NULL, 22),
(24, 'default_path', 'Domyślna ścieżka do plików .pdf', 'varchar', NULL, NULL, 'pdf/', NULL, 23),
(25, 'default_sufix', 'Domyślny sufix tworzonych lików .pdf', 'varchar', NULL, NULL, NULL, NULL, 24),
(26, 'default_font', 'Domyślny font', 'varchar', NULL, NULL, 'arial.ttf', NULL, 25),
(27, 'organization_country', 'Adr. organizacji - kraj', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 26),
(28, 'organization_town', 'Adr. organizacji - miasto', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 27),
(29, 'organization_street', 'Adr. organizacji - ulica', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 28),
(30, 'organization_house', 'Adr. organizacji - bud.', 'varchar', NULL, NULL, 'X', NULL, 29),
(31, 'organization_flat', 'Adr. organizacji - lok.', 'varchar', NULL, NULL, 'X', NULL, 30),
(32, 'organization_code', 'Adr. organizacji - kod', 'varchar', NULL, NULL, 'XX-XXX', NULL, 31),
(33, 'organization_province', 'Adr. organizacji - woj.', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 32),
(34, 'organization_district2', 'Adr. organizacji - powiat', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 33),
(35, 'organization_district', 'Adr. organizacji - gmina', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 34),
(36, 'organization_post_office', 'Adr. organizacji - poczta', 'varchar', NULL, NULL, 'XXXXXXX', NULL, 35),
(37, 'disable_accepted_docs', 'Blokada zaakceptowanych operacji', 'float', 0, NULL, NULL, NULL, 36);

