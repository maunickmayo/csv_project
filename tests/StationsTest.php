<?php

namespace App\Tests;

use App\Controller\StationController;
use App\Entity\Station;
use App\Repository\StationRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StationControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        // Création du mock du StationRepository
        $stationRepository = $this->createMock(StationRepository::class);

        //Création d'une station de test
        $station = new Station();
        $station->setName('Station 1');
        $station->setLigneLogo('/images/logo-ligne-1.png');
        $station->setTerminus(false);

        // Simulation des données de la station avec une correspondance
        $station->setConnections('logo-correspondance-1.png,logo-correspondance-2.png');

        // Configuartion du pour renvoyer notre station de test
        $stationRepository->method('findAll')->willReturn([$station]);

        //Instanciation du contrôleur
        $controller = new StationController();

        $client = static::createClient();

        $client->getContainer()->set(StationRepository::class, $stationRepository);

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h6.card-title', 'Station 1');

        $this->assertSelectorExists('img[src="/images/logo-ligne-1.png"]');
        $this->assertSelectorExists('img[src="logo-correspondance-1.png"]');
        $this->assertSelectorExists('img[src="logo-correspondance-2.png"]');

        $this->assertSelectorNotExists('span.badge.bg-success');
    }
}
