<?php

namespace App\Command;

// Assurez-vous de bien imporater vos namespace
use App\Entity\Station;
use App\Repository\StationRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'ImportStationsCommand',
    description: "Import de données en provenance d'un fichier csv",
)]
class ImportStationsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StationRepository $stationRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription("Import de données en provenance d'un fichier csv");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $this->createStations($io);
        $io->success('Import réussi.');

        return Command::SUCCESS;
    }

    private function getDataFromFile(): Reader
    {
        $reader = Reader::createFromPath('public/data/stations.csv', 'r');
        $reader->setHeaderOffset(0);
        $reader->setDelimiter(';');

        return $reader;
    }

    private function createStations(SymfonyStyle $io): void
    {
        $io->title('Importation des stations');

        $reader = $this->getDataFromFile();
        $io->progressStart($reader->count());

        foreach ($reader as $row) {
            $io->progressAdvance();
            $station = $this->createOrUpdateStation($row);
            $this->entityManager->persist($station);
        }

        $this->entityManager->flush();
        $io->progressFinish();
    }

    private function createOrUpdateStation(array $data): Station
    {
        $station = new Station();
        $station->setName($data['nom_gares'] ?? 'Inconnu');
        $station->setLigneLogo($data['picto'] ?? '');
        $station->setTerminus($data['principal'] === '1');

        return $station;
    }
}
