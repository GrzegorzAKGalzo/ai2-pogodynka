<?php

namespace App\Command;

use App\Repository\CityRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:location',
    description: 'Find weather forecast for given city',
)]
class WeatherLocationCommand extends Command
{
    public function __construct(
        private readonly CityRepository $locationRepository,
        private WeatherUtil $weatherUtil,
    )
    {

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Citiy id that intrests you')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
{
    $io = new SymfonyStyle($input, $output);
    $locationId = $input->getArgument('id');
    $location = $this->locationRepository->find($locationId);

    $measurements = $this->weatherUtil->getWeatherForLocation($location);
    if(empty($measurements)){
        $io->info(sprintf('Weather Measurements for Location: %s', $location->getName()));
        $io->info(sprintf('Is Empty'));

        return Command::SUCCESS;

    }
    $io->success(sprintf('Weather Measurements for Location: %s', $location->getName()));

    $tableHeaders = ['Date', 'Temperature'];
    $tableRows = [];

    foreach ($measurements as $measurement) {
        $tableRows[] = [
            $measurement->getDate()->format('Y-m-d'),
            $measurement->getTemperature(),
        ];
    }

    $io->table($tableHeaders, $tableRows);

    return Command::SUCCESS;
}

}
