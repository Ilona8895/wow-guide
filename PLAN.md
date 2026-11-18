# Azeroth Armory – kontekst i plan do dokończenia

Plik dla mnie i dla AI (Cursor / Claude Code). Wczytuj go na początku pracy: `@PLAN.md`.

## Po co ten projekt

Mini-projekt do nauki Symfony przed rozmową o pracę (Confronter, Gliwice – Full Stack PHP/Symfony/React + AI, rozmowa w piątek o 10:00).
Na co dzień pracuję w NestJS + Prisma + Zod + Expo, uczę się Symfony.
Fanowskie API w stylu World of Warcraft: rasy, klasy, postacie. Projekt do nauki, niezwiązany z Blizzardem.

## Jak AI ma mi pomagać

- Małe kroki. Po każdym kroku powiedz, jak sprawdzić, że działa.
- Przy każdym nowym pojęciu Symfony/Doctrine podaj odpowiednik z NestJS / Prismy / Zoda.
- Tłumacz prostym językiem. Nie dodawaj funkcji, o które nie proszę.
- Wszystkie komendy PHP/Composer/Symfony uruchamiam w kontenerze.
  Lokalne PHP na Macu to 8.4.12, a projekt wymaga 8.5.12.

## Środowisko

- Szablon `dunglas/symfony-docker` (FrankenPHP), Symfony 8.1, PHP 8.5, Doctrine ORM 3, PostgreSQL 16.
- Start: `docker compose up --wait`. Aplikacja: https://localhost
- Adminer (podgląd bazy): http://localhost:8081 – System PostgreSQL, Server `database`, user `app`, hasło `!ChangeMe!`, baza `app`.
- Aliasy w `~/.zshrc`:
  - `sf` = `docker compose exec php bin/console` (np. `sf make:entity`)
  - `dc` = `docker compose exec php` (np. `dc composer require ...`)
- Po zmianie Dockerfile (np. nowe rozszerzenie PHP) trzeba `docker compose build`, samo `up` nie wystarczy.
- Repo: https://github.com/Ilona8895/wow-guide

## Co już jest zrobione

- Encje:
  - `Race`: name, faction (enum `Faction`: alliance/horde), allowedClasses (ManyToMany → CharacterClass, strona właścicielska).
  - `CharacterClass`: name, armorType (lista enumów `ArmorType`, `SIMPLE_ARRAY`), races (strona odwrotna).
  - `Character` (tabela `characters`): name, surname (obowiązkowe), level, race i characterClass (ManyToOne, wymagane), playerEmail, createdAt (ustawiane w konstruktorze), `getFullName()`.
    Unikalna para imię + nazwisko: `UniqueConstraint(name, surname)`.
- Migracja wykonana.
- Fixtures (`src/DataFixtures/AppFixtures.php`): 9 klas, 10 ras wg tabeli WoW Forever, 5 postaci.
  Skyborne jest w obu frakcjach z innymi klasami, więc to dwa osobne rekordy `Race`.
  Wgrywanie: `sf doctrine:fixtures:load`.
- `GET /api/characters` z filtrami `?faction=horde&class=druid`.
  Repozytorium `findWithFilters()` z JOIN + addSelect (bez N+1). Odpowiedź przez DTO `CharacterResponse` (bez e-maila gracza).
- `POST /api/characters`: DTO `CreateCharacterRequest` + `#[MapRequestPayload]`, walidacja `#[Assert\...]`.
  Własny walidator klasy `AllowedRaceClass` sprawdza, czy rasa może mieć daną klasę.
  Kody: 201 utworzono, 422 błędy walidacji, 409 duplikat imienia i nazwiska.
- Świadome decyzje (do opowiedzenia na rozmowie):
  - `armorType` jako `simple_array`, bo to krótka, stała lista bez filtrowania. Do wyszukiwania: JSON albo osobna tabela.
  - Brak gildii i wysyłki maili w tle (Messenger), bo zabrakłoby czasu. Wiem, jak bym to zrobiła.
  - Flagi „nowa kombinacja” wymagałyby zamiany ManyToMany na encję pośrednią `RaceClass` z polem `isNew`.
  - EasyAdmin usunięty, do podglądu bazy wystarcza Adminer.

## Do zrobienia (w tej kolejności)

1. [ ] Commit obecnych zmian (POST + walidator). Plik DTO przemianowany na `CreateCharacterRequest.php`, żeby zgadzał się z nazwą klasy (PSR-4).
2. [ ] Testy (`dc composer require --dev symfony/test-pack`):
   - przygotować bazę testową (env `test`): utworzyć bazę, zrobić migracje i wgrać fixtures w środowisku test,
   - `WebTestCase`: GET lista = 200; POST poprawny = 201; POST Orc + Paladin = 422; POST duplikat = 409,
   - uruchamianie: `dc bin/phpunit`.
3. [ ] Dokumentacja API: `dc composer require nelmio/api-doc-bundle` + `dc composer require scalar/symfony`.
   Scalar ma czytać OpenAPI z Nelmio. Sprawdzić `/scalar` i wysłać z niego zapytanie.
4. [ ] (opcjonalnie) `GET /api/characters/{id}` (404 gdy brak) oraz `GET /api/races` i `GET /api/classes`.
5. [ ] README: co robi projekt, jak uruchomić, endpointy, link do `/scalar`,
   sekcja „Jak użyłam AI” (2–3 zdania), zdanie o fanowskim charakterze projektu.
6. [ ] Push na GitHuba.
7. [ ] (jeśli zostanie czas) GitHub Actions z testami; gildie z regułą frakcji; Messenger.

Czwartek wieczorem: max ~1,5 h na projekt, potem powtórka teorii i historie STAR (dokument przygotowania do rozmowy w Claude).
