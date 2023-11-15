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
    name: 'weather:locationCode',
    description: 'Find weather forecasts for given city by Country code and name',
)]
class WeatherLocationCodeCommand extends Command
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
            ->addArgument('code', InputArgument::REQUIRED, 'Country code')
            ->addArgument('name' , InputArgument::REQUIRED,'City name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $code = $input->getArgument('code');
        $name = $input->getArgument('name');
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($code, $name);

        $io->success(sprintf('Weather Measurements for Location: %s, %s', $name, $code));
        if(empty($measurements)){
            $io->info(sprintf('Is Empty'));
    
            return Command::SUCCESS;
    
        }
    
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
