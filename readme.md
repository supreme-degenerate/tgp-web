TGP WEB - by Filip Heyduk
=================

Připravil jsem řešení dle zadání Invoice Management API.

Technologie - PHP 8.4+, Nette Framework, Doctrine ORM (Nettrine SDK), MySQL (8.0+)

AI při vývoji - aktivně používám inteligentní našeptávání pro ulehčení práce

Napsal jsem si vlastní API presenter, který handluje jednoduché chování pro RESTové endpointy. Některé věci jsou záměrně napsány zjednodušenou formou.

Projekt používá environment proměnné, které je potřeba mít v rootu (přiložím v rámci ZIPu).

Spuštění:
------------

1) Stačí zadat v příkazové řádce `docker compose up` v rootu projektu.

*Kdyby z nějakého důvodu nefungovaly automatické migrace z entrypointu, tak se musí změnit v .env `DB_HOST` na `localhost` a spustit `php bin/console migrations:migrate`. Po migraci vrátit `DB_HOST` na `mysql`.*

*Pro less storage usage posílám projekt bez `node_modules` a `vendor`.*

Povinné úkoly:
------------

------------
1) Číselná řada faktur
------------

Každá faktura si generuje nový `invoiceNumber` dle sekvence `InvoiceNumberSequence` a to ve formátu `2026000001`. Poslední záznam si bere ze sekvenční tabulky `invoice_number_sequence`.

------------
2) REST API endpointy
------------

`GET http://localhost/api/invoice` - vrátí list faktur

`GET http://localhost/api/invoice/{id}` - vrátí detail faktury

`POST http://localhost/api/invoice` - vytvoří novou fakturu (všechny properties Invoice)

JSON raw input:

```
{
  "shippingAddress": {
    "addressLine1": "Slavíkova",
    "addressLine2": "555",
    "city": "Ostrava",
    "postalCode": "70800",
    "country": "Česká republika"
  },
  "billingAddress": {
    "addressLine1": "Slavíkova",
    "addressLine2": "666",
    "city": "Praha",
    "postalCode": "70800",
    "country": "Česká republika"
  },
  "dueDate": "2026-12-04",
  "items": [
    {
      "name": "Produkt 1",
      "pricePerUnit": 120.0,
      "currency": "CZK",
      "quantity": 1
    },
    {
      "name": "Produkt 2",
      "pricePerUnit": 50.0,
      "currency": "CZK",
      "quantity": 2
    }
  ]
}
```

`PUT http://localhost/api/invoice/{id}` - upraví stávající fakturu (pouze dueDate)

JSON raw input:

```
{
  "dueDate": "2026-11-06"
}
```

`PUT http://localhost/api/invoice/{id}/status` - změní stav faktury

JSON raw input:

```
{
  "status": 3
}
```

`GET http://localhost/api/invoice/{id}/logs` - vrátí list logů pro danou fakturu

Změny na faktuře se automaticky logují při každém flushi `Invoice` (na faktuře lze upravovat dueDate a status, takže lze spatřit pouze tyto změny). Loguje se v `InvoiceSubscriber`.

------------
3) Stavy faktury a povolené přechody
------------

Logika povolených přechodů stavů faktury lze najít přímo v Enumu `InvoiceStatus`.

Přechod stavu se kontroluje automaticky před flushem `Invoice`.

------------
4) Externí služby
------------

Přidal jsem do tohoto řešení služby:

a) `mysql` - klasická relační databáze

b) `rabbitmq` (částečná implementace) - ukázka o povědomí async front

c) `tgp-worker` (částečná implementace) - worker zpracovávájící queue (invoice_queue) pro invoice service a elasticsearch

d) `elasticsearch` (částečná implementace) - ukázka o povědomí fulltext vyhledávání a automatické indexace

e) `kibana` - náhled do elasticu

f) `redis` - kdyby bylo potřeba zrychlit

Nepovinné úkoly:
------------

------------
1) Endpoint pro changelog faktury
------------

`GET http://localhost/api/invoice/{id}/logs` - vrátí list logů pro danou fakturu

Seřazený od poslední změny. Automaticky generovaná property `message` pro lepší čitelnost změny.

------------
2) Unit testy
------------

Použil jsem zabudovaný Tester od Nette pro tyto dva jednodušší testy.

`Unit\InvoiceNumberGeneratorTest` - test správného generování čísla faktur

`Unit\InvoiceStatusChangeTest` - test logiky přechodů stavů faktur

------------
3) Docker
------------

Celý projekt běží v Docker prostředí a je tak ready pro Kubernetes nasazování a škálování.

------------
4) Implementace napojení služeb
------------

Všechny služby, které v řešení používám, jsou plně integrované jako services v Dockeru a připravené na použití v aplikaci.
