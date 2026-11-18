<?php

namespace App\DataFixtures;

use App\Entity\Character;
use App\Entity\CharacterClass;
use App\Entity\Race;
use App\Enum\ArmorType;
use App\Enum\Faction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 1. Klasy i typy pancerza, które mogą nosić
        $classesData = [
            'Warrior' => [ArmorType::Plate, ArmorType::Mail, ArmorType::Leather, ArmorType::Cloth],
            'Paladin' => [ArmorType::Plate, ArmorType::Mail, ArmorType::Leather, ArmorType::Cloth],
            'Hunter'  => [ArmorType::Mail, ArmorType::Leather, ArmorType::Cloth],
            'Shaman'  => [ArmorType::Mail, ArmorType::Leather, ArmorType::Cloth],
            'Rogue'   => [ArmorType::Leather, ArmorType::Cloth],
            'Druid'   => [ArmorType::Leather, ArmorType::Cloth],
            'Priest'  => [ArmorType::Cloth],
            'Mage'    => [ArmorType::Cloth],
            'Warlock' => [ArmorType::Cloth],
        ];

        $classes = [];
        foreach ($classesData as $name => $armorTypes) {
            $class = (new CharacterClass())
                ->setName($name)
                ->setArmorType($armorTypes);

            $manager->persist($class);
            $classes[$name] = $class; 
        }

        // 2. Rasy (WoW Forever), ich frakcje i dozwolone klasy
        // Klucz tablicy jest unikalny, bo Skyborne występuje w obu frakcjach
        // z innym zestawem klas.
        $racesData = [
            // Przymierze
            'human'             => ['Human', Faction::Alliance, ['Warrior', 'Paladin', 'Hunter', 'Rogue', 'Priest', 'Mage', 'Warlock']],
            'dwarf'             => ['Dwarf', Faction::Alliance, ['Warrior', 'Paladin', 'Hunter', 'Rogue', 'Priest', 'Shaman']],
            'night_elf'         => ['Night Elf', Faction::Alliance, ['Warrior', 'Hunter', 'Rogue', 'Priest', 'Druid']],
            'gnome'             => ['Gnome', Faction::Alliance, ['Warrior', 'Rogue', 'Priest', 'Mage', 'Warlock']],
            'skyborne_alliance' => ['Skyborne', Faction::Alliance, ['Warrior', 'Hunter', 'Rogue', 'Mage', 'Druid']],

            // Horda
            'orc'               => ['Orc', Faction::Horde, ['Warrior', 'Hunter', 'Rogue', 'Shaman', 'Mage', 'Warlock']],
            'undead'            => ['Undead', Faction::Horde, ['Warrior', 'Paladin', 'Rogue', 'Priest', 'Mage', 'Warlock']],
            'tauren'            => ['Tauren', Faction::Horde, ['Warrior', 'Hunter', 'Shaman', 'Druid']],
            'troll'             => ['Troll', Faction::Horde, ['Warrior', 'Hunter', 'Rogue', 'Priest', 'Shaman', 'Mage', 'Warlock']],
            'skyborne_horde'    => ['Skyborne', Faction::Horde, ['Warrior', 'Hunter', 'Rogue', 'Shaman', 'Druid']],
        ];

        $races = [];
        foreach ($racesData as $key => [$name, $faction, $allowedClassNames]) {
            $race = (new Race())
                ->setName($name)
                ->setFaction($faction);

            foreach ($allowedClassNames as $className) {
                $race->addAllowedClass($classes[$className]);
            }

            $manager->persist($race);
            $races[$key] = $race;
        }

        // 3. Kilka przykładowych postaci (rasa po kluczu z tablicy wyżej)
        $charactersData = [
            ['Ezabore', 'Ghastwane', 60, 'night_elf', 'Druid'],
            ['Fiznik', 'Doodlerattle', 42, 'human', 'Hunter'],
            ['Grom', 'Hellscream', 17, 'orc', 'Warrior'],
            ['Cairne', 'Bloodhoof', 23, 'tauren', 'Shaman'],
            ['Aerin', 'Cloudwing', 30, 'skyborne_horde', 'Druid'],
        ];

        foreach ($charactersData as [$name, $surname, $level, $raceKey, $className]) {
            $character = (new Character())
                ->setName($name)
                ->setSurname($surname)
                ->setLevel($level)
                ->setRace($races[$raceKey])
                ->setCharacterClass($classes[$className])
                ->setPlayerEmail(strtolower($name . '.' . $surname) . '@example.com');

            $manager->persist($character);
        }

        
        $manager->flush();
    }
}
