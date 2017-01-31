# OPPen Project
Zarządzanie Projektami i Księgowość dla organizacji OPP
ver. 2.1

#### Autor:
Michał Janczak
mrjanczak@gmail.com

# Spis treści
1 Wstęp
1.1 Instalacja
1.2 Struktura aplikacji
1.3 Obiekty aplikacji
2 Projekty
2.1 Opis
2.2 Przychody
2.3 Koszty
2.4 Umowy
2.5 Dokumenty
3 Dokumenty
4 Kartoteki
5 Raporty
5.1 Deklaracje PIT11/PIT4
5.2 Rachunek Zysków i Strat/Bilans
5.3 Obroty/zapisy kontaktowe
5.4 Dziennik księgowych
6 Ustawienia
6.1 Okresy
6.2 Plan Kont
6.3 Kategorie
6.4 Raporty
6.5 Szablony
6.5.1 Szablony Umów
6.5.2 Szablon Raportu
6.5.3 Szablon księgowania
6.6 Parametry
7 Załącznik

# 1 Wstęp

Program OPPen Project to aplikacja Open Source pozwalająca zarządzać budżetem
projektu i automatycznie tworzyć zapisy księgowe według zdefiniowanych przez
użytkownika szablonów. Program pozwala również generować raporty takie, jak
deklaracje podatkowe i sprawozdania.

### 1.1 Instalacja

Do uruchomienia aplikacji wymagane jest:
 - zainstalowanie serwera PHP (wersja min. 5.4)
 - zainstalowanie serwera MySQL (wersja min. 5.5)
 - uruchomienie framework-a Symfony wersja 2.4. (www.symfony.com)
 - zainstalowanie modułu aplikacji we framework-u (jako tzw. Bundle)
 - skonfigurowanie serwera pod kątem zadań TRON (automatyczny backup i powiadomienia)

Aplikacja dostępna jest jako [repozytorium github] (https://github.com/mrjanczak/OPPEN2).

### 1.2 Struktura aplikacji

Program składa się z 5-ciu modułów dostępnych w menu głównym. Część z nich posiada zakładki widoczne poniżej Głównego Menu:
 - Projekty (Opis, Przychody, Koszty, Umowy, Dokumenty)
 - Dokumenty
 - Kartoteki
 - Raporty
 - Ustawienia (Okresy, Plan Kont, Kategorie, Raporty, Szablony, Parametry, Użytkownicy)

Aplikacja działa w oknie przeglądarki. Można ją otwierać równocześnie w dowolnej liczbie okien. Nawigacja odbywa się poprzez klikanie w linki i przyciski:
 - (+) - dodanie nowego obiektu
 - ( x ) – usunięcie istniejącego obiektu
 - ( - ) – odłączenie przypisanego obiektu (nie usunięcie)
 - (↑) (↓) - zmiana kolejności obiektu
 - ( c ) - skopiowanie kwoty lub daty z/do innego pola

### 1.3 Obiekty aplikacji

Aplikacja pozwala zarządzać następującymi obiektami:
 - Projekty oraz ich budżety z wydzielonymi kategoriami przychodów i kosztów
 - Umowy ze zleceniobiorcami przypisane do projektów
 - Kartoteki przypisane do swoich kategorii
 - Dokumenty księgowe przypisane do swoich kategorii
 - Konta księgowe
 - Raporty (np. RZiS, Bilans, Deklaracje podatkowe)
 - Szablony umów, raportów, księgowania i przelewów bankowych

Aplikacja zorientowana jest na zarządzanie projektem, śledzenie i dokumentowanie jego przychodów i kosztów. Dlatego wiele z powyższych obiektów uporządkowanych jest według tego podziału – na grupę przychodów i kosztów. Przypisane są do nich kategorie w budżecie projektu, kategorie Kartotek i Dokumentów a także Konta księgowe (patrz pkt. 6). Pozwala to w łatwy sposób skonfigurować projekt pod kątem późniejszego automatycznego księgowania zapisów.
| Kategorie budżetu |             Przychody             |                  Koszty                 |
|:-----------------:|:---------------------------------:|:---------------------------------------:|
| Dokumenty         | Rach. Własny, Umowa, Wyciąg Bank. | Fakt. Vat, Rachunek, Wyciąg Bank.       |
| Kartoteki         | Sponsorzy, Odbiorcy               | Koszty administracyjne Koszty statutowe |
| Konta księgowe    | Konta przychodów                  | Konta kosztów                           |
# 2 Projekty

Moduł zawiera listę projektów z danego roku. Aby dodać nowy projekt, kliknij znak (+). Aby otworzyć już istniejący, kliknij w jego nazwę. Po wejściu w widok projektu dostępnych jest 5 zakładek pozwalających zarządzać budżetem projektu oraz związanymi z projektem dokumentami, w tym Umowami.

### 2.1 Opis

Zakładka Opis zawiera następujące informacje o projekcie:
 - Nazwa
 - Status (otwarty, w trakcie, zamknięty)
 - Miejsce
 - Zakres czasowy projektu
 - Kartoteka projektu (reprezentuje projekt w module księgowości)
 - Kategoria Kartoteki z grupy kosztów (np. koszty statutowe lub administracyjne – patrz pkt. 6.3)
 - Konto z grupy przychodów (domyślne dla całego projektu)
 - Konto z grupy kosztów (domyślne dla całego projektu)
 - Konto z grupy rachunków bankowych (domyślne dla całego projektu)
 - Opis
 - Komentarz

Zakładka zawiera również listę zadań przypisanych do projektu. Po kliknięciu w pole Opis pojawi się okno dialogowe, w którym można dodać opis zadania, komentarz, wybrać użytkownika i włączyć powiadomienie wysyłane e-mailem każdego dnia w okresie trwania zadania (wymaga to dodatkowych ustawień serwera – patrz załącznik Konfiguracja). Strzałki obok zadania pozwalają zmieniać kolejność zadań. Kliknięcie w znak (x) powoduje usunięcie zadania.

Uwaga! Usunięcie projektu możliwe jest tylko w zakładce Opis.:
 - powoduje usunięcie całego budżetu projektu
 - powoduje usunięcie wszystkich umów ze zleceniobiorcami w ramach projektu
 - nie powoduje usunięcia zapisów księgowych dotyczących projektu

### 2.2 Przychody

Zakładka Przychody zawiera kategorie budżetowe z grupy przychodów. Do każdej pozycji można przypisywać dokumenty (np. Umowę sponsorską, Rachunek własny itd.) i określać dla nich kwotę (np. wyciąg bankowy z kwotą konkretnej darowizny). Wybierając odpowiednie dokumenty i szablony księgowania można wygenerować dla nich zapisy księgowe. Dla każdej kategorii przychodów pokazany jest stosunek faktycznych wpływów do zaplanowanych.

Każda kategoria przychodu zawiera następujące informacje:
 - Nazwa
 - Skrót – widoczny w nagłówkach kolumn w zakładce Koszty
 - Wartość – planowana kwota przychodów
 - Opis
 - Kartoteka (wybór spośród kategorii przypisanych do grupy przychodów)
 - Konto z grupy rachunków bankowych – patrz pkt. 6.2
 - Konto z grupy przychodów – patrz pkt. 6.2

### 2.3 Koszty

Zakładka Koszty zawiera kategorie budżetowe z grupy kosztów. Podobnie jak w Przychodach, do każdej pozycji można przypisywać dokumenty (np. Rachunek lub Fakt.VAT). Kwotę każdego kosztu należy wpisać do jednej lub kilku kolumn odpowiadających kategoriom przychodów, z których zostaną pokryte.

Dostępne operacje na wybranych dokumentach:
 - Utwórz dekretacje – należy wybrać jeden lub więcej szablonów księgowania, aby na ich podstawie wygenerować zapisy księgowe dla wybranych dokumentów.
 - Koszty – opcja pozwala wyeksportować plik .csv z kosztami projektu
 - Przelewy – opcja pozwala wygenerować plik .csv z przelewami. Opisy przelewów tworzone są na podstawie wybranego szablonu księgowania (takie same , jak opisy dekretacji) oraz szablonu przelewu (patrz pkt. 6.5)

Każda kategoria kosztu zawiera następujące informacje:
 - Nazwa
 - Wartość
 - Opis
 - Kartoteka kosztów (kartoteka z kategorii kosztów statutowych lub administracyjnych – wybranej w zakładce Opis)
 - Konto z grupy rachunków bankowych – patrz pkt. 6.2
 - Konto z grupy przychodów – patrz pkt. 6.2

### 2.4 Umowy

Zakładka Umowy zawiera umowy ze zleceniobiorcami przypisane do kategorii budżetowych z grupy kosztów. Umowy można stworzyć (+), kopiować (c) lub generować wiele jednocześnie (cc) na bazie istniejącej umowy i wybranych zleceniobiorców. Dla wybranych umów można wygenerować właściwe dokumenty księgowe (np. Rachunki) widoczne w zakładce Koszty. Umowy będą z nimi powiązane (informacje zawarte w Umowie mogą być wykorzystane przy późniejszym generowaniu dekretacji dla Rachunku). Wybrane umowy można wydrukować wykorzystując szablon przypisany do umowy (patrz pkt. 6.5.1).

Dla każdej umowy należy określić:
 - Zleceniobiorcę
 - Miesiąc (okres) zawarcia umowy
 - Nr umowy (proponowany jest automatycznie kolejny wolny nr w miesiącu, ale można go ręcznie zmienić)
 - Data, Miejsce zawarcia i Przedmiot umowy
 - opcjonalnie można określić datę, miejsce i nazwę imprezy oraz rolę w niej zleceniobiorcy (informacje te mogą być zawarte w wydruku umowy – patrz 6.5.1)
 - szablon umowy
 - grupa kosztów
 - kwota Brutto
 - procent PDOF
 - procent kosztów uzyskania przychodu
 - Okres płatności (np. +30 days, +2 weeks, 1 month)
 - metoda płatności

### 2.5 Dokumenty

Zakładka Dokumenty zawiera wszystkie dokumenty przypisane do Projektu, tzn.:
 - wygenerowane na podstawie umów;
 - dodane przez użytkownika do grupy przychodów;
 - dodane przez użytkownika do grupy kosztów.
Edycja dokumentu opisana jest w Rozdziale 3.

# 3 Dokumenty

Moduł Dokumenty pozwala przeglądać dokumenty z wybranego miesiąca i kategorii, zawierające w opisie szukany tekst. Dla każdego dokumentu można wyświetlić dekretacje (żadne, wszystkie, zatwierdzone lub niezatwierdzone). Wybrane dekretacje można zbiorowo zatwierdzić przyciskiem Zatwierdź poniżej listy dokumentów. Nowy dokument można utworzyć tylko po wybraniu konkretnego miesiąca. Link (+) nie pojawi się, gdy wybierzemy „wszystkie” miesiące.

Kliknięcie w numer dokumentu otwiera formularz dokumentu. Można w nim określić:
 - Datę księgowania
 - Datę dokumentu
 - Datę operacji
 - Datę wpłynięcia dokumentu
 - Kategorię dokumentu i związaną z nim kartotekę
 - Numer, opis i komentarz dokumentu

Formularz pozwala również tworzyć i zatwierdzać dekretacje. Kliknij (+) aby dodać nowy zapis. Linki (WN+) i (MA+) pozwalają dodać zapis po stronie Winien i Ma. Po kliknięciu na pole Opis pojawi się okno dialogowe dekretacji, w którym można ustawić: 
 - datę księgowania (pozwalającą sortować dekretacje w ramach jednego dokumentu),
 - opis dekretacji
 - projekt, do którego przypisany jest dokument

# 4 Kartoteki

Moduł Kartoteki pozwala tworzyć i edytować kartoteki w ramach poszczególnych kategorii. Kartoteki można wyszukać po roku, kategorii i tekście zawartym w nazwie. Kliknięcie w nazwę kartoteki pozwala na jej edycję. W zależności od kategorii dostępne są różne pola formularza takie, jak:
 - dane osobowe (nazwa, imię, nazwisko, NIP, PESEL, data ur.),
 - adres (kraj woj., powiat, gmina, miasto, ul., bud., lokal, kod),
 - rachunek bankowy,
 - urząd skarbowy (jako pod-kartoteka),
 - dane kontaktowe (telefon, e-mail).

# 5 Raporty

Moduł Raporty pozwala wygenerować raporty takie, jak deklaracje podatkowe, sprawozdania, dzienniki księgowań itd. Dla raportów z pierwszej grupy możliwe jest wybranie Metody rozliczenia (Kasowa/Memoriałowa). Raporty księgowe generowane są dla wybranego okresu i/lub konta.

### 5.1 Deklaracje PIT11/PIT4

Raport pozwala wygenerować dane w formie plików .xml do deklaracji podatkowych PIT11 i PIT4R. Dane można zaimportować np. w programie Adobe Reader.

### 5.2 Rachunek Zysków i Strat/Bilans

### 5.3 Obroty/zapisy kontaktowe

### 5.4 Dziennik księgowych

# 6 Ustawienia

Moduł Ustawienia pozwala tworzyć i edytować:
 - Okresy (miesiące, lata)
 - Plan Kont
 - Kartoteki
 - Raporty
 - Szablony
 - Parametry aplikacji

### 6.1 Okresy

Zakładka Okresy pozwala dodać kolejny lub poprzedni Rok Obrotowy oraz skopiować do niego z bieżącego roku:
 - Plan Kont
 - Raporty
 - Kategorie Kartotek
 - Kategorie Dokumentów

Kliknięcie w Rok Obrotowy pozwala edytować daty rozpoczęcia i zakończenia poszczególnych Okresów oraz ustawiać je jako:
 - Aktywne (umożliwia to księgowanie w tych okresach)
 - Zamknięte ( uniemożliwia to dalsze księgowanie w tych okresach))

### 6.2 Plan Kont

Zakładka Ustawienia > Konta pozwala edytować Plan kont. W celu dodania nowego konta należy kliknąć (+) w wierszu konta nadrzędnego (dla nowego konta syntetycznego kliknij (+) w pierwszym wierszu „Plan kont”).

W celu edycji istniejącego konta należy kliknąć w jego nazwę. W formularzu zdefiniować do 3 poziomów kartotek oraz przypisać konto do następujących grup:
 - Konto rach. Bankowego (zalecane do kont analitycznych) przypisywane do projektów lub grup przychodów
 - Konto przychodów – przypisywane do projektu lub kategorii przychodów
 - Konto kosztów – przypisywane do projektu lub kategorii kosztów

### 6.3 Kategorie

Zakładka Ustawienia > Kategorie pozwala tworzyć i edytować kategorie Dokumetów i Kartotek. Kliknięcie w nazwę otwiera formularz edycji kategorii. Kategorii z gwiazdką (*) nie można usunąć ani zmienić ich symbolu. Kategorię Kartoteki można przypisać do następujących grup:
 - Przychody – kartoteki z tej grupy przypisywane są do kategorii przychodów projektu
 - Koszty – kartoteki z tej grupy przypisywane są do kategorii kosztów projektu
 - Zleceniobiorca – kartoteki z tej grupy wykorzystywane są do tworzenia umów w ramach projektu

Kategorię Dokumentu można przypisać do następujących grup:
 - Przychody – dokumenty z kategorii należącej do tej grupy mogą być przypisywane do kategorii przychodów projektu
 - Koszty – dokumenty z kategorii należącej do tej grupy mogą być przypisywane do kategorii kosztów projektu
 - Umowa – nowo tworzone umowy (dokumenty) będą należały do pierwszej kategorii z tej grupy

### 6.4 Raporty

Zakładka Ustawienia > Raporty pozwala utworzyć lub skonfigurować istniejące raporty. W celu edycji raportu należy kliknąć jego nazwę. Formularz raportu zawiera:
 - nazwę
 - skrót
 - szablon dla raportu (patrz zakładka Ustawienia > Szablony)
 - pozycje raportu

W celu edycji pozycji raportu należy kliknąć jego nazwę.
Formularz pozycji raportu zawiera:
 - Numer – pozwala uporządkować pozycje w formie konspektu
 - Nazwę
 - Symbol - unikalny w ramach raportu. jeśli raport ma być wygenerowany w formacie .pdf, symbol musi odpowiadać nazwie pola w formularzu .pdf.
 - Formuła - zawiera instrukcję, jak obliczyć wartość pozycji.

Składnia formuły ma następującą postać:
[WARUNEK] ( =[FUNKCJA] : [ARG1] ) lub
([ZNAK] [TYP DANYCH] : [ARG1] : [ARG2] : [ARG3] [SEPARATOR] )+

Dostępne WARUNKI:
 - „>” - jeśli wartość jest dodatnia, zostanie pokazana w danej pozycji
 - „<” - jeśli wartość jest ujemna, zostanie pokazana w danej pozycji

Dostępne FUNKCJE (poprzedzone znakiem „=”):
 - sum - suma; argumenty:
   - ARG1: „children” - suma wartości podrzędnych pozycji

Dostępne ZNAKI:
 - „+” - wartość pomnożona jest przez +1
 - „-” - wartość pomnożona jest przez -1

Dostępne TYPY DANYCH:
 - account - wartość księgowań; argumenty:
   - ARG1: nr konta, np. 201-001
   - ARG2: strona księgowania (1 – WN, 2 – MA, 0 – obie strony)
   - ARG3: miesiąc księgowania
   - ARG4: tekst w opisie księgowania
 - item - wartość innej pozycji raportu o symbolu z ARG1
 - data - dane właściwe dla danego rodzaju raportu; argumenty – patrz lista poniżej
 - form - wartość z formularza raportu; argumenty – patrz lista poniżej
 - param - wartość parametrów aplikacji; argumenty – patrz zakładka Ustawienia > Parametry
 - text – wartość tekstowa podana w ARG1

Dostępne argumenty (ARG1) dla typu „item” i raportu PIT11:
 - year – rok raportu
 - first_name – imię podatnika
 - last_name – nazwisko podatnika
 - street - ulica
 - house – nr budynku
 - flat – nr mieszkania
 - code – kod pocztowy
 - city - miasto
 - district2 – gmina
 - district - powiat
 - province - województwo
 - country – kraj
 - post_office - poczta
 - address1 – adres opcjonalny 1
 - address2 – adres opcjonalny 1
 - birth_date – data ur.
 - PESEL
 - gross – przychód (brutto)
 - netto - przychód (netto)
 - income_cost – koszty uzyskania przychodu
 - tax - podatek
 - US_name – nazwa Urzędu Skarbowego
 - US_address1 – adres US (ulica, budynek)
 - US_address2 - adres US (kod, miasto)

Dostępne argumenty (ARG1) dla typu „form”:
 - objection – cel złożenia – deklaracja/korekta
 - report_date – data sporządzenia raportu

### 6.5 Szablony

Aplikacja umożliwia tworzenie szablonów dla:
 - umów
 - raportów
 - księgowań
 - przelewów
 - pozostałych dokumentów

Aby edytować szablon, należy kliknąć jego nazwę.
Formularz szablonu zawiera:
 - Nazwa i symbol (musi być unikalny)
 - Typ szablonu (umowa, księgowanie, raport)
 - Dane – informacje specyficzne dla danego typu szablonu (patrz opis poniżej)
 - Treść – tekst w formacie .html zawierający pola uzupełniane podczas generowania docelowego dokumentu

### 6.5.1 Szablony Umów

Szablon umowy wykorzystywany jest podczas generowania umów w projekcie. Zawarte w Treści pola są automatycznie wypełniane. Dostępne pola dla szablonu umowy:
 - __contract_no__ - numer umowy
 - __contract_date__ - data umowy
 - __contract_place__ - miejsce zawarcia umowy
 - __organization_name__ - nazwa organizacji
 - __organization_address1__ - adres organizacji (ulica, nr bud. I mieszkania)
 - __organization_address2__ - adres organizacji (kod pocztowy, miejscowość)
 - __organization_KRS__ - KRS organizacji
 - __organization_REGON__ - REGON organizacji
 - __approver_name__ - Imię i nazwisko osoby podpisującej umowę z ramienia organizacji ( wykorzystywana jest wartość parametru organization_board1 lub organization_board2, jeśli pierwsza wartość jest identyczna, co pole __contractor_name__)
 - __contractor_name__ - imię i nazwisko osoby będącej stroną umowy (np. zleceniobiorcy)
 - __contractor_address1__ - adres zleceniobiorcy (ulica, nr bud. I mieszkania)
 - __contractor_address2__ - adres zleceniobiorcy (kod pocztowy, miejscowość)
 - __contractor_PESEL__ - PESEL zleceniobiorcy
 - __project_name__ - nazwa projektu
 - __event_name__ - nazwa wydarzenia w ramach projektu
 - __event_place__ - miejsce wydarzenia
 - __event_date__ - data wydarzenia
 - __event_role__ - rola zleceniobiorcy w wydarzeniu
 - __bank_name__ - nazwa banku zleceniobiorcy
 - __bank_account__ - nr rachunku bankowego zleceniobiorcy
 - __document_no__ - nr Rachunku przypisanego do umowy
 - __document_date__ - data Rachunku
 - __gross__ - przychód brutto
 - __income_cost__ - koszt uzyskania przychodu
 - __tax__ - podatek
 - __netto__ - przychód netto
 - __gross_text__ - przychód brutto słownie

Dostępne są również znaczniki pozwalające określić, czy treść i pola w nich zawarte
mają być widoczne:
 - --event_desc2-- (...) --event_desc2-- - jeżeli dla umowy zdefiniowano jej zakres, treść i pola w tym znaczniku nie będą widoczne
 - --document-- (...) --document-- - jeżeli do umowy nie jest przypisany dokument księgowy w postaci np. Rachunku, treść i pola w tym znaczniku nie będą widoczne.

#### Przykład:
__event_desc__--event_desc2-- artystycznego wykonania koncertu pt. __event_name__ w ramach
projektu __project_name__ w __event_place__ w terminie __event_date__ w charakterze __event_role__
--event_desc2--

W powyższym przykładzie treść i pola zaznaczone kolorem zostaną usunięte, jeżeli pole __event_desc__ nie jest puste.

### 6.5.2 Szablon Raportu

### 6.5.3 Szablon księgowania

Szablony księgowania pozwalają zdefiniować automatyczne księgowanie dokumentów załączonych do projektów.
Definicja szablonu księgowania tworzona jest w języku YML i zawiera następujące pola:
 - group: - grupa budżetowa; argumenty:
   - Income - przychody
   - Cost - koszty
 - for: - sposób tworzenia dekretacji, argumenty:
   - EACH – dekretacje tworzone są oddzielnie dla kwot z każdego zaznaczonego dokumentu
   - SUM - dekretacja tworzona jest dla sumarycznej kwoty ze wszystkich zaznaczonych dokumentów
 - Doc: - dokument do zaksięgowania
   - SelectedDoc
   - PaymentDoc
 - desc: -
   - __Doc_desc__ - opis dokumentu
   - __Doc_no__ - nr dokumentu
   - __Doc_bookking_no__ - nr ewidencyjny dokumentu
   - __SelDocFile_Name__ - nazwa kartoteki przypisanej do zaznaczonego dokumentu (w przypadku Rachunku, imię i nazwisko)
   - __GroupFile_Name__ - nazwa kartoteki przypisanej do kategorii budżetowej, w której znajduje się zaznaczony dokument
   - __YY__ - rok księgowania
   - __MM__ - miesiąc księgowania
   - __Project_Name__ - nazwa projektu
 - BEs: - definicje zapisów księgowych; każdy zapis definiowany jest w nowej linii w nawiasie klamrowym poprzedzonym myślnikiem „ - {...}”. 

Definicja zapisów księgowych zawiera następujące pola:
 - for: - sposób tworzenia zapisów, argumenty:
   - SUM – tworzony jest jeden zapis dla łącznej kwoty zaznaczonego dokumentu
   - IBA – (tylko dla Grupy Kosztów) – tworzone są odrębne zapisy dla kwot cząstkowych z kategorii przychodów mających wspólne konto bankowe
   - IF - (tylko dla Grupy Kosztów) – tworzone są odrębne zapisy dla kwot 	cząstkowych z kategorii przychodów mających wspólną kartotekę
 - side – strona księgowania:
   - 1 – WN
   - 2 - MA
 - value – wartość, argument:
   - gross – kwota brutto
   - netto – kwota netto
   - tax – podatek PDOF (dostępny dla Rachunków)
 - Account – konto księgowe, argumenty:
   - GroupAcc – konto kategorii budżetowej (przychodów lub kosztów), do której należy zaznaczony dokument
   - IncomeBankAcc – konto rachunku bankowego kategorii przychodów (dostępne dla kombinacji „for:IBA”)
   - CommitAcc – konto zobowiązań zdefiniowane dla kategorii zaznaczonego dokumentu
   - TaxCommitAcc - konto zobowiązań podatkowych zdefiniowane dla kategorii zaznaczonego dokumentu
 - FileLev1, FileLev2, FileLev3 – kartoteki (analityka), argumenty:
   - GroupFile – kartoteka przypisana do kategorii budżetowej
   - SelDocFile - kartoteka przypisana do zaznaczonego dokumentu
	
### 6.6 Parametry

Dostępne parametry aplikacji:
 - organization_name - Nazwa organizacji
 - organization_KRS - KRS organizacji
 - organization_REGON - REGON organizacji
 - organization_NIP - NIP organizacji
 - organization_address1 - Adres (ulica, bud., lok.) organizacji
 - organization_address2 - Adres (kod pocztowy, miejscowość) organizacji
 - organization_bank_account - Rachunek bankowy organizacji
 - organization_bank - Nazwa Banku organizacji
 - organization_email - E-mail organizacji
 - organization_phone - Telefon do organizacji
 - organization_create_date - Data utworzenia organizacji
 - organization_board1 - Prezes organizacji
 - organization_board2 - Z-ca Prezesa organizacji
 - organization_board3 - Członek Zarządu organizacji
 - default_approver_first_name - Domyślna osoba podpisująca raport - imię
 - default_approver_last_name - Domyślna osoba podpisująca raport - nazwisko
 - default_tax_coef - Domyślny podatek PDOF [%]
 - default_cost_coef - Domyślne koszty uzyskania przychodu [%]
 - dec_point - Domyślny separator dziesiętny
 - thousands_sep - Domyślny separator tysięcy
 - default_path - Domyślna ścieżka do plików .pdf
 - default_sufix - Domyślny sufix tworzonych lików .pdf
 - default_font - Domyślny font
 - organization_tax_office - Urząd Skarbowy organizacji

