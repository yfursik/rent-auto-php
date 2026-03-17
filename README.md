#Název projektu: RentAuto – Rezervační systém pro půjčovnu vozidel
#Autor: Yehor Fursik

#1. Anotace a cíl projektu
Cílem tohoto semestrálního projektu bylo vytvořit plně funkční webovou aplikaci pro půjčovnu luxusních aut. Aplikace umožňuje běžným uživatelům prohlížet katalog vozidel a odesílat žádosti o rezervaci. Pro majitele (administrátora) je k dispozici redakční systém (CRUD) pro správu vozového parku a vyřizování objednávek.

#2. Použité technologie

Front-end: HTML5, CSS3, Bootstrap 5 (pro responzivní a moderní design).

Back-end: PHP 8.

Databáze: MySQL (propojení přes objektové rozhraní mysqli).

#3. Funkcionalita a splnění požadavků (CRUD)
Projekt plně implementuje architekturu CRUD:

Create (Vytváření):

Klient může vytvořit novou rezervaci (formulář na webu).

Administrátor může přidat nové auto do katalogu (formulář v administraci).

Read (Čtení):

Výpis všech dostupných aut na hlavní stránce pro klienty.

Výpis všech aut a aktuálních rezervací v administračním panelu.

Update (Aktualizace):

Administrátor může upravovat detaily vozidel (cena, popis, fotka, model) přes speciální editační formulář.

Delete (Mazání):

Administrátor může odstranit auto z databáze.

Administrátor může smazat (vyřídit) rezervaci zákazníka.

#4. Struktura databáze
Databáze rent_auto obsahuje dvě propojené tabulky (relace 1:N):

cars (id, brand, model, price_per_day, description, image_url) – uchovává informace o autech.

bookings (id, car_id, client_name, client_phone, start_date, end_date) – uchovává informace o rezervacích a pomocí klíče car_id je propojena s konkrétním vozidlem (ON DELETE CASCADE).

#5. Návod na zprovoznění (pro vyučujícího)

Nakopírujte složku s projektem do kořenového adresáře lokálního serveru (např. C:\xampp\htdocs\project).

Spusťte Apache a MySQL v XAMPP Control Panel.

Otevřete phpMyAdmin a importujte přiložený soubor rent_auto.sql, který automaticky vytvoří databázi i potřebné tabulky.

Otevřete v prohlížeči adresu http://localhost/project/.
