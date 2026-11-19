<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CharacterControllerTest extends WebTestCase
{
    public function testListReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/characters');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testListRacesReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/races');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testListClassesReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/classes');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testShowReturns200(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/characters/1');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testShowMissingReturns404(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/characters/99999');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testCreateValidCharacterReturns201(): void
    {
        $client = static::createClient();

       
        $client->jsonRequest('POST', '/api/characters', [
            'name' => 'Testowa',
            'surname' => 'Postac',
            'level' => 20,
            'raceId' => 6,
            'classId' => 4,
            'playerEmail' => 'testowa.postac@example.com',
        ]);

        $this->assertResponseStatusCodeSame(201);
    }

    public function testOrcPaladinReturns422(): void
    {
        $client = static::createClient();

        
        $client->jsonRequest('POST', '/api/characters', [
            'name' => 'Zly',
            'surname' => 'Orcpal',
            'level' => 10,
            'raceId' => 6,
            'classId' => 2,
            'playerEmail' => 'zly.orcpal@example.com',
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function testDuplicateReturns409(): void
    {
        $client = static::createClient();

        $payload = [
            'name' => 'Duplikat',
            'surname' => 'Imienia',
            'level' => 15,
            'raceId' => 6,
            'classId' => 4,
            'playerEmail' => 'duplikat@example.com',
        ];

        $client->jsonRequest('POST', '/api/characters', $payload);
        $this->assertResponseStatusCodeSame(201);

        $client->jsonRequest('POST', '/api/characters', $payload);
        $this->assertResponseStatusCodeSame(409);
    }
}