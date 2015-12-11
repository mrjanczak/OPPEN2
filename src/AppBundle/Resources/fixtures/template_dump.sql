INSERT INTO `template` ( `name`, `symbol`, `type`, `as_contract`, `as_report`, `as_booking`, `as_transfer`, `data`, `contents`) VALUES

( 'Raport Zysków i Strat', NULL, NULL, 0, 1, 0, 0, NULL, '<p>__Param:organization_name__ __Param:organization_address1__ __Param:organization_address2__ NIP: __Param:organization_NIP__ Regon: __Param:organization_Regon__</p>\r\n<h1>Rachunek Zysk&oacute;w i Strat na dzień __form:report_date__</h1>\r\n<p>Rachunek wynik&oacute;w sporządzony zgodnie z załącznikiem do rozporządzenia Ministra Finans&oacute;w z 15.11.2001 (DZ. U. 137poz. 1539) __Entry:root:branch__ Zatwierdził: __Param:approver_first_name__ __Param:approver_last_name__</p>'),

( 'Bilans', NULL, NULL, 0, 1, 0, 0, NULL, '<p>Bilans __Entry:assets:branch__ __Entry:liabilities:branch__ Zatwierdził: __Param:approver_first_name__ __Param:approver_last_name__</p>'),

( 'PIT-11', 'pit11', NULL, 0, 1, 0, 0, '<?xml version="1.0" encoding="UTF-8"?>\r\n<Deklaracja xmlns="http://crd.gov.pl/wzor/2014/12/08/1887/"\r\n><Naglowek\r\n><KodFormularza kodSystemowy="PIT-11 (21)" kodPodatku="PIT" rodzajZobowiazania="Z" wersjaSchemy="1-0E"\r\n>PIT-11</KodFormularza\r\n><WariantFormularza\r\n>21</WariantFormularza\r\n><CelZlozenia poz="P_6"\r\n>{{ form.objective }}</CelZlozenia\r\n><Rok\r\n>{{ year }}</Rok\r\n><KodUrzedu\r\n>{{ data.US_accNo }}</KodUrzedu\r\n></Naglowek\r\n><Podmiot1 rola="Płatnik"\r\n><etd:OsobaNiefizyczna xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2011/06/21/eD/DefinicjeTypy/"\r\n><etd:NIP\r\n>{{ params.organization_NIP }}</etd:NIP\r\n><etd:PelnaNazwa\r\n>{{ params.organization_name|upper}}</etd:PelnaNazwa\r\n></etd:OsobaNiefizyczna\r\n></Podmiot1\r\n><Podmiot2 rola="Podatnik"\r\n><etd:OsobaFizyczna xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2011/06/21/eD/DefinicjeTypy/"\r\n><etd:PESEL\r\n>{{ data.PESEL }}</etd:PESEL\r\n><etd:ImiePierwsze\r\n>{{ data.first_name|upper }}</etd:ImiePierwsze\r\n><etd:Nazwisko\r\n>{{ data.last_name|upper }}</etd:Nazwisko\r\n><etd:DataUrodzenia\r\n>{{ data.birth_date|date("Y-m-d") }}</etd:DataUrodzenia\r\n></etd:OsobaFizyczna\r\n><etd:AdresZamieszkania xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2011/06/21/eD/DefinicjeTypy/" rodzajAdresu="RAD"\r\n><etd:AdresPol\r\n><etd:KodKraju\r\n>{{ data.country|upper }}</etd:KodKraju\r\n><etd:Wojewodztwo\r\n>{{ data.province|upper }}</etd:Wojewodztwo\r\n><etd:Powiat\r\n>{{ data.district|upper }}</etd:Powiat\r\n><etd:Gmina\r\n>{{ data.district2|upper }}</etd:Gmina\r\n><etd:Ulica\r\n>{{ data.street|upper }}</etd:Ulica\r\n><etd:NrDomu\r\n>{{ data.house }}</etd:NrDomu\r\n><etd:NrLokalu\r\n>{{ data.flat }}</etd:NrLokalu\r\n><etd:Miejscowosc\r\n>{{ data.city|upper }}</etd:Miejscowosc\r\n><etd:KodPocztowy\r\n>{{ data.code }}</etd:KodPocztowy\r\n><etd:Poczta\r\n>{{ data.post_office|upper }}</etd:Poczta\r\n></etd:AdresPol\r\n></etd:AdresZamieszkania\r\n></Podmiot2\r\n><PozycjeSzczegolowe\r\n><P_24\r\n>1</P_24\r\n><P_25\r\n>0.00</P_25\r\n><P_26\r\n>0.00</P_26\r\n><P_27\r\n>0.00</P_27\r\n><P_29\r\n>0</P_29\r\n><P_30\r\n>0.00</P_30\r\n><P_31\r\n>0.00</P_31\r\n><P_32\r\n>0.00</P_32\r\n><P_33\r\n>0.00</P_33\r\n><P_34\r\n>0</P_34\r\n><P_35\r\n>0.00</P_35\r\n><P_36\r\n>0.00</P_36\r\n><P_38\r\n>0</P_38\r\n><P_39\r\n>0.00</P_39\r\n><P_40\r\n>0.00</P_40\r\n><P_41\r\n>0</P_41\r\n><P_42\r\n>0.00</P_42\r\n><P_43\r\n>0.00</P_43\r\n><P_44\r\n>0</P_44\r\n><P_45\r\n>0.00</P_45\r\n><P_46\r\n>0.00</P_46\r\n><P_47\r\n>0.00</P_47\r\n><P_48\r\n>0</P_48\r\n><P_49\r\n>0.00</P_49\r\n><P_50\r\n>0.00</P_50\r\n><P_51\r\n>0.00</P_51\r\n><P_52\r\n>0</P_52\r\n><P_53\r\n>0.00</P_53\r\n><P_54\r\n>{{ data.gross - data.income_cost|round(2) }}</P_54\r\n><P_55\r\n>{{ data.tax|round(0) }}</P_55\r\n><P_56\r\n>{{ data.gross|round(2) }}</P_56\r\n><P_57\r\n>{{ data.income_cost|round(2) }}</P_57\r\n><P_58\r\n>0.00</P_58\r\n><P_59\r\n>0.00</P_59\r\n><P_60\r\n>0.00</P_60\r\n><P_61\r\n>0</P_61\r\n><P_62\r\n>0.00</P_62\r\n><P_63\r\n>0.00</P_63\r\n><P_64\r\n>0.00</P_64\r\n><P_65\r\n>0</P_65\r\n><P_66\r\n>0.00</P_66\r\n><P_67\r\n>0.00</P_67\r\n><P_68\r\n>0.00</P_68\r\n><P_69\r\n>0.00</P_69\r\n><P_70\r\n>0.00</P_70\r\n><P_71\r\n>0.00</P_71\r\n><P_72\r\n>2</P_72\r\n></PozycjeSzczegolowe\r\n><Pouczenie\r\n>Za uchybienie obowiązkom płatnika grozi odpowiedzialność przewidziana w Kodeksie karnym skarbowym.</Pouczenie\r\n></Deklaracja\r\n>', '<!--?xml version="1.0" encoding="UTF-8"?-->\r\n<p>&nbsp;</p>'),

('PIT-4R', 'pit4R', NULL, 0, 1, 0, 0, '<?xml version="1.0" encoding="UTF-8"?>\r\n<Deklaracja xmlns="http://crd.gov.pl/wzor/2013/10/11/1327/" xmlns:etd="http://crd.gov.pl/xml/schematy/dziedzinowe/mf/2011/06/21/eD/DefinicjeTypy/">\r\n	<Naglowek>\r\n		<KodFormularza kodSystemowy="PIT-4R" kodPodatku="PIT" rodzajZobowiazania="Z" wersjaSchemy="1-0E">PIT-4R</KodFormularza>\r\n		<WariantFormularza>20</WariantFormularza>\r\n		<CelZlozenia poz="P_6">{{ form.objective }}</CelZlozenia>\r\n		<Rok>{{ year }}</Rok>\r\n		<KodUrzedu>{{ data.US_accNo }}</KodUrzedu>\r\n	</Naglowek>\r\n	<Podmiot1 rola="Płatnik">\r\n		<etd:OsobaNiefizyczna>\r\n			<etd:NIP>{{ params.organization_NIP }}</etd:NIP>\r\n			<etd:PelnaNazwa>{{ params.organization_name}}</etd:PelnaNazwa>\r\n		</etd:OsobaNiefizyczna>\r\n	</Podmiot1>\r\n	<PozycjeSzczegolowe>\r\n	{% for item in items if  item.data.symbol is not null%}\r\n		<{{ item.data.symbol }}> {{ item.data.value }}</{{ item.data.symbol }}>\r\n	{% endfor %}\r\n	</PozycjeSzczegolowe>\r\n	<Pouczenie>Za uchybienie obowiązkom płatnika grozi odpowiedzialność przewidziana w Kodeksie karnym skarbowym.</Pouczenie>\r\n</Deklaracja>', NULL);

INSERT INTO `template` ( `name`, `symbol`, `type`, `as_contract`, `as_report`, `as_booking`, `as_transfer`, `data`, `contents`) VALUES

( 'Ra - koszt-zobow.', 'C500-231', NULL, 0, 0, 1, 0, 'group: Cost\r\nrows : EACH\r\nDoc : SelectedDoc\r\ndesc : __Doc_desc__ zg.z. __Doc_no__\r\nBEs : \r\n - {cols : IF,  side : 1, value : gross, Account : GroupAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile, FileLev3 : GroupFile}\r\n - {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : SelDocFile }\r\n - {cols : SUM, side : 1, value : tax, Account : CommitAcc, FileLev1 : SelDocFile }\r\n - {cols : SUM, side : 2, value : tax, Account : TaxCommitAcc }', NULL),

( 'Ra - zobow.-wypłata', 'C231-130', NULL, 0, 0, 1, 0, 'group: Cost\r\nrows : EACH\r\nDoc : PaymentDoc\r\ndesc : honorarium dla __SelDocFile_Name__ - projekt __Project_Name__\r\nBEs : \r\n - {cols : SUM, side : 1, value : netto, Account : CommitAcc, FileLev1 : SelDocFile }\r\n - {cols : IBA, side : 2, value : netto, Account : IncomeBankAcc }', NULL),

( 'Ra - wypłata PDOF', 'C225-130', NULL, 0, 0, 1, 0, 'group: Cost\r\nrows : SUM\r\nDoc : PaymentDoc\r\ndesc : __YY__M__MM__ podatek PDOF, projekt __Project_Name__ \r\nBEs : \r\n - {cols : SUM, side : 1, value : tax, Account : TaxCommitAcc }\r\n - {cols : IBA, side : 2, value : tax, Account : IncomeBankAcc }', NULL),

( 'Um - Zobow.-Przych.przysz.', 'I200-845', NULL, 0, 0, 1, 0, 'group: Income\r\nrows : EACH\r\nDoc : SelectedDoc\r\ndesc : __Doc_desc__ zg.z. __Doc_no__ \r\nBEs : \r\n - {cols : SUM, side : 1, value : gross, Account : CommitAcc, FileLev1 : GroupFile }\r\n - {cols : SUM, side : 2, value : gross, Account : #845, FileLev1 : ProjectFile, FileLev2 : GroupFile }', NULL),

( 'Um - Wpłata', 'I130-200', NULL, 0, 0, 1, 0, 'group: Income\r\nrows : EACH\r\nDoc : PaymentDoc\r\ndesc : płatność od __GroupFile_Name__ - projekt __Project_Name__\r\nBEs : \r\n - {cols : SUM, side : 1, value : gross, Account : IncomeBankAcc }\r\n - {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : GroupFile }', NULL),

( 'FV - koszt-zobow.', 'C500-201', NULL, 0, 0, 1, 0, 'group: Cost\r\nrows : EACH\r\nDoc : SelectedDoc\r\ndesc : __Doc_desc__ zg.z. __Doc_no__\r\nBEs : \r\n- {cols : IF, side : 1, value : gross, Account : GroupAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile, FileLev3 : GroupFile}\r\n- {cols : SUM, side : 2, value : gross, Account : CommitAcc, FileLev1 : SelDocFile }', NULL),

( 'FV - zobow.-zapłata', 'C201-131', NULL, 0, 0, 1, 0, 'group: Cost\r\nrows : EACH\r\nDoc : PaymentDoc\r\ndesc : __SelDocFile_Name__ zg. z  __SelDoc_No__ - projekt __Project_Name__\r\nBEs : \r\n- {cols : SUM, side : 1, value : netto, Account : CommitAcc, FileLev1 : SelDocFile }\r\n- {cols : IBA, side : 2, value : netto, Account : IncomeBankAcc }', NULL),

('WB - koszt-zapł.', 'C500-131', NULL, 0, 0, 1, 0, 'group: Cost\r\nfor : EACH\r\nDoc : SelectedDoc\r\ndesc : __GroupDoc_Desc__ \r\nBEs : \r\n- {for : IF, side : 1, value : gross, Account : GroupAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile, FileLev3 : GroupFile}\r\n- {for : IBA, side : 2, value : gross, Account :IncomeBankAcc }', NULL),

('WB - Darowizna os.praw.', 'I130-701-002', NULL, 0, 0, 1, 0, 'group: Income\r\nrows : EACH\r\nDoc : PaymentDoc\r\ndesc : Darowizna __ID_desc__ na projekt __Project_Name__\r\nBEs : \r\n- {cols : SUM, side : 1, value : gross, Account : IncomeBankAcc }\r\n- {cols : SUM, side : 2, value : gross, Account : #701-002 }', NULL),

('WB - Darowizna os.pryw.', 'I130-701-001', NULL, 0, 0, 1, 0, 'group: Income\r\nrows : EACH\r\nDoc : PaymentDoc\r\ndesc : Darowizna __ID_desc__ na projekt __Project_Name__\r\nBEs : \r\n- {cols : SUM, side : 1, value : gross, Account : IncomeBankAcc }\r\n- {cols : SUM, side : 2, value : gross, Account : #701-001 }', NULL),

('PDOF', 'do_US', NULL, 0, 0, 0, 1, 'for : SUM|IBA\r\nBGZ:  "{{param.TO_PDOF_bank_acc}};N;{{param.organization_NIP}};{{form.payment_date|date(''y'')}};M;{{form.payment_date|date(''m'')}};PIT-4R; - ;{{value.tax}}"\r\nVW:', NULL),

('PK - Przych.przyszłe - Przych.', 'I845-700', NULL, 0, 0, 1, 0, 'group: Cost\r\nrows : SUM\r\nDoc : DocByNo\r\ndesc : __Doc_desc__ zg.z. __Doc_no__ \r\nBEs : \r\n - {cols : IF, side : 1, value : gross, Account : #845, FileLev1 : ProjectFile, FileLev2 : IncomeFile } \r\n - {cols : IF, side : 2, value : gross, Account : IncomeAcc, FileLev1 : ProjectFile, FileLev2 : IncomeFile }', NULL);

INSERT INTO `template` ( `name`, `symbol`, `type`, `as_contract`, `as_report`, `as_booking`, `as_transfer`, `data`, `contents`) VALUES

( 'Krajowe', 'krajowy', NULL, 0, 0, 0, 1, 'for : EACH|IBA\r\nBGZ : "{{i}}; {{file.bankAccount}}; {{file.name}}; {{file.address1}}; {{file.code}}; {{file.city}}; honorarium dla {{file.name}} zg. z rach. {{doc.docNo}} - projekt {{project.name}} {% if FirstIF.IAAccNo == 700 %}, {{FirstIF.netto}} płatne ze śr. {{FirstIF.IName}} {% endif %}; {{IBA.netto|number_format(2, ''.'', '''')}}"\r\nVW :', NULL),

('Zagraniczne', 'Zagraniczne', NULL, 0, 0, 0, 1, 'for : EACH|IBA\r\nBGZ : "{% set FromAcc = IBA.IncomeBankAcc.name|split(''|'')%}{% set ToAcc = file.bankAccount|split(''|'')%}{{ToAcc[0]|replace({'' '':''''})}}; {{file.name}};;;;;{{file.bankAccount|slice(0, 2)}}; honorarium dla {{file.name}};zg. z rach. {{doc.docNo}}; projekt {{project.name}};{% if FirstIF.IAAccNo == 700 %} {{FirstIF.netto}} płatne ze śr. {{FirstIF.IName}} {% endif %}; {{file.bankName}};;;;{{ToAcc[1]}} ;;;;;;{{FromAcc[2]|replace({'' '':''''})}} ;B;Z;N;N;;;;;;;; {{IBA.netto|number_format(2, ''.'', '''')}} PLN"\r\nVW :', NULL);

INSERT INTO `template` ( `name`, `symbol`, `type`, `as_contract`, `as_report`, `as_booking`, `as_transfer`, `data`, `contents`) VALUES

('UoD_PA + Rach + Form', 'UoD_PA', NULL, 1, 0, 0, 0, NULL, '<h2 style="text-align: center;"><strong>Umowa o Dzieło Nr __contract_no__</strong></h2>\r\n<p>w&nbsp;dniu __contract_date__ w __contract_place__ pomiędzy:&nbsp;</p>\r\n<p><strong>__organization_name__</strong> z siedzibą w&nbsp;<span style="font-size: 11px;">__organization_address1__ ,&nbsp;</span><span style="font-size: 11px;">__organization_address2__, KRS __organization_KRS__</span><span style="font-size: 11px;">&nbsp;</span><span style="font-size: 11px;">zwanym dalej <strong>Zleceniodawcą</strong>,<strong>&nbsp;</strong></span><span style="font-size: 11px;">reprezentowanym przez __approver_name__,&nbsp;</span>a</p>\r\n<p><strong>__contractor_name__</strong>, adr.zam. __contractor_address1__, __contractor_address2__, __contractor_ID__, zwanym/zwaną dalej <strong>Zleceniobiorcą</strong></p>\r\n<p>zawarto umowę o treści następującej:</p>\r\n<p style="text-align: center;"><strong>&sect;1 </strong></p>\r\n<p><strong>Zleceniobiorca</strong> zobowiązuje się na zlecenie <strong>Zleceniodawcy</strong> do __event_desc__ &nbsp;__event_role__.</p>\r\n<p style="text-align: center;"><strong>&sect;2 </strong></p>\r\n<p><strong>Zleceniobiorca</strong> nie może powierzyć wykonania zobowiązań wynikających z niniejszej umowy innej osobie bez zgody <strong>Zleceniodawcy</strong>.</p>\r\n<p style="text-align: center;"><strong>&sect;3 </strong></p>\r\n<p><strong>Zleceniobiorcy</strong> za wykonanie czynności przewidzianych w &sect; 1 umowy przysługuje wynagrodzenie w wysokości <strong>__gross__</strong>&nbsp;PLN (słownie __gross_text__&nbsp;) brutto.</p>\r\n<p style="text-align: center;"><strong>&sect;4 </strong></p>\r\n<p>Wynagrodzenie będzie płatne nie p&oacute;źniej niż 14 dni po wystawieniu przez <strong>Zleceniobiorcę</strong> rachunku przelewem na konto __bank_account__ w banku __bank_name__.</p>\r\n<p style="text-align: center;"><strong>&sect;5 </strong></p>\r\n<p><strong>Zleceniobiorca</strong> niniejszym przenosi na <strong>Zleceniodawcę</strong> przysługujące mu prawa autorskie do rozporządzania i korzystania z artystycznego wykonania utwor&oacute;w w ramach projektu opisanego w &sect; 1, na wszystkich znanych w chwili zawarcia niniejszej umowy polach eksploatacji.</p>\r\n<p style="text-align: center;"><strong>&sect;6 </strong></p>\r\n<p>Zmiany umowy wymagają formy pisemnej w postaci aneksu.</p>\r\n<p style="text-align: center;"><strong>&sect;7 </strong></p>\r\n<p>W sprawach nieuregulowanych niniejszą umową mają zastosowanie przepisy Kodeksu Cywilnego.</p>\r\n<p style="text-align: center;"><strong>&sect;8 </strong></p>\r\n<p>Umowę spisano w dw&oacute;ch jednobrzmiących egzemplarzach po jednym dla każdej ze stron.&nbsp;</p>\r\n<table style="margin-left: auto; margin-right: auto;" width="706">\r\n<tbody>\r\n<tr>\r\n<td style="text-align: center;">\r\n<p>...............................</p>\r\n<p><strong>Zleceniodawca</strong></p>\r\n</td>\r\n<td style="text-align: center;">\r\n<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></p>\r\n</td>\r\n<td style="text-align: center;">\r\n<p>.................................</p>\r\n<p><strong>Zleceniobiorca</strong></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="text-align: left;">&nbsp;</p>\r\n<p style="text-align: left;">&nbsp;</p>\r\n<p style="text-align: left;">&nbsp;</p>\r\n<p style="text-align: left;">&nbsp;</p>\r\n<p style="text-align: left;">--document--</p>\r\n<h2 style="text-align: center;">Rachunek Nr &nbsp;__document_no__</h2>\r\n<p>za wykonanie prac zgodnie z umową nr __contract_no__ z dnia __contract_date__wystawił/a&nbsp;__contractor_name__&nbsp;dla __organization_name__&nbsp;na kwotę&nbsp;__gross__&nbsp;PLN.&nbsp;Wnoszę o przyjęcie niniejszego rachunku i dokonanie wypłaty wynagrodzenia na konto __bank_account__.</p>\r\n<p>&nbsp;</p>\r\n<table border="1" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr><th>\r\n<p>Wynagrodz. brutto (przych&oacute;d)</p>\r\n</th><th>\r\n<p>Koszty uzyskania przychodu</p>\r\n</th><th>\r\n<p>Doch&oacute;d</p>\r\n</th><th>\r\n<p>Podst. wymiaru składki ubezp. zdrow.</p>\r\n</th><th>\r\n<p>Podst. naliczania podatku doch.</p>\r\n</th><th>\r\n<p>Potrącona zalicz. na podatek doch.</p>\r\n</th><th>\r\n<p>Składka ubez. Zdrow. potrącona</p>\r\n</th><th>\r\n<p>Skład. Ubez. Zdrow. podleg. odliczeniu od podatku</p>\r\n</th><th>\r\n<p>Należna zalicz. na podatek doch.</p>\r\n</th><th>\r\n<p>Do wypłaty</p>\r\n</th></tr>\r\n<tr>\r\n<td>__gross__</td>\r\n<td>__income_cost__</td>\r\n<td>__income_cost__</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>__tax__</td>\r\n<td>&nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>__tax__</td>\r\n<td>__netto__</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style="text-align: center;">&nbsp;</p>\r\n<table style="margin-left: auto; margin-right: auto; height: 32px;" width="729">\r\n<tbody>\r\n<tr>\r\n<td style="text-align: center;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</td>\r\n<td style="text-align: center;">__document_date__&nbsp;.............................<br />Data i Podpis <strong>Zleceniobiorcy</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>--document--</p>\r\n<h2 style="text-align: center;"><strong>Formularz danych osobowych</strong></h2>\r\n<table style="height: 42px;" width="794">\r\n<tbody>\r\n<tr>\r\n<td>\r\n<p>Imię i Nazwisko: &nbsp;__contractor_name__</p>\r\n<p>__contractor_ID__</p>\r\n<p>Data ur.:&nbsp;__contractor_birth_date__</p>\r\n</td>\r\n<td>\r\n<p>Adres: &nbsp;</p>\r\n<p>__contractor_address1__</p>\r\n<p>__contractor_address2__</p>\r\n</td>\r\n<td>\r\n<p>Urząd Skarbowy:</p>\r\n<p>__contractor_US__</p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>\r\n<p><div class=''page-break''></div></p>\r\n<h2 style="text-align: center;"><strong>Umowa o Dzieło Nr __contract_no__</strong></h2>\r\n<p>w&nbsp;dniu __contract_date__ w __contract_place__ pomiędzy:&nbsp;</p>\r\n<p><strong>__organization_name__</strong> z siedzibą w&nbsp;<span style="font-size: 11px;">__organization_address1__ ,&nbsp;</span><span style="font-size: 11px;">__organization_address2__, KRS __organization_KRS__</span><span style="font-size: 11px;">&nbsp;</span><span style="font-size: 11px;">zwanym dalej <strong>Zleceniodawcą</strong>,<strong>&nbsp;</strong></span><span style="font-size: 11px;">reprezentowanym przez __approver_name__,&nbsp;</span>a</p>\r\n<p><strong>__contractor_name__</strong>, adr.zam. __contractor_address1__, __contractor_address2__, __contractor_ID__, zwanym/zwaną dalej <strong>Zleceniobiorcą</strong></p>\r\n<p>zawarto umowę o treści następującej:</p>\r\n<p style="text-align: center;"><strong>&sect;1 </strong></p>\r\n<p><strong>Zleceniobiorca</strong>&nbsp;zobowiązuje się na zlecenie&nbsp;<strong>Zleceniodawcy</strong>&nbsp;do __event_desc__ &nbsp;__event_role__.</p>\r\n<p style="text-align: center;"><strong>&sect;2 </strong></p>\r\n<p><strong>Zleceniobiorca</strong> nie może powierzyć wykonania zobowiązań wynikających z niniejszej umowy innej osobie bez zgody <strong>Zleceniodawcy</strong>.</p>\r\n<p style="text-align: center;"><strong>&sect;3 </strong></p>\r\n<p><strong>Zleceniobiorcy</strong> za wykonanie czynności przewidzianych w &sect; 1 umowy przysługuje wynagrodzenie w wysokości <strong>__gross__</strong>&nbsp;PLN (słownie __gross_text__&nbsp;) brutto.</p>\r\n<p style="text-align: center;"><strong>&sect;4 </strong></p>\r\n<p>Wynagrodzenie będzie płatne nie p&oacute;źniej niż 14 dni po wystawieniu przez <strong>Zleceniobiorcę</strong> rachunku przelewem na konto __bank_account__ w banku __bank_name__.</p>\r\n<p style="text-align: center;"><strong>&sect;5 </strong></p>\r\n<p><strong>Zleceniobiorca</strong> niniejszym przenosi na <strong>Zleceniodawcę</strong> przysługujące mu prawa autorskie do rozporządzania i korzystania z artystycznego wykonania utwor&oacute;w w ramach projektu opisanego w &sect; 1, na wszystkich znanych w chwili zawarcia niniejszej umowy polach eksploatacji.</p>\r\n<p style="text-align: center;"><strong>&sect;6 </strong></p>\r\n<p>Zmiany umowy wymagają formy pisemnej w postaci aneksu.</p>\r\n<p style="text-align: center;"><strong>&sect;7 </strong></p>\r\n<p>W sprawach nieuregulowanych niniejszą umową mają zastosowanie przepisy Kodeksu Cywilnego.</p>\r\n<p style="text-align: center;"><strong>&sect;8 </strong></p>\r\n<p>Umowę spisano w dw&oacute;ch jednobrzmiących egzemplarzach po jednym dla każdej ze stron.&nbsp;</p>\r\n<table style="margin-left: auto; margin-right: auto;" width="706">\r\n<tbody>\r\n<tr>\r\n<td style="text-align: center;">\r\n<p>...............................</p>\r\n<p><strong>Zleceniodawca</strong></p>\r\n</td>\r\n<td style="text-align: center;">\r\n<p><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</strong></p>\r\n</td>\r\n<td style="text-align: center;">\r\n<p>.................................</p>\r\n<p><strong>Zleceniobiorca</strong></p>\r\n</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p>&nbsp;</p>');
