<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\Forecast;
use App\Repository\CityRepository;
use App\Repository\ForecastRepository;

class WeatherUtil
{
    public function __construct(
        private readonly CityRepository $locationRepository,
        private readonly ForecastRepository $forecastRepository,
    )
    {
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForLocation(City $location): array
    {
        $forecast = $this->forecastRepository->findByLocation($location);
        return $forecast;
    }

    /**
     * @return Forecast[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'code' => $countryCode,
            'name' => $city,
        ]);

        $forecast = $this->getWeatherForLocation($location);

        return $forecast;
    }
}