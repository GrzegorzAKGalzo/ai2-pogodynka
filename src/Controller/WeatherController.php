<?php

namespace App\Controller;

use App\Entity\City;
use App\Service\WeatherUtil;
use App\Repository\ForecastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class WeatherController extends AbstractController
{
    #[Route('/weather/{id}', name: 'app_weather_show', requirements: ['id' => '\d+'])]
    public function city(City $city, ForecastRepository $forecastRepository): Response
    {
        $forecasts = $forecastRepository->findByLocation($city);

        return $this->render('weather/city.html.twig', [
            'location' => $city,
            'forecasts' => $forecasts,
        ]);
    }

    #[Route('/weather/{name}')]
    public function cityName(City $city, ForecastRepository $forecastRepository): Response
    {
        $forecasts = $forecastRepository->findByLocation($city);

        return $this->render('weather/city.html.twig', [
            'location' => $city,
            'forecasts' => $forecasts,
        ]);
    }
    #[Route('/weather/{code}/{name}', name: 'app_weather')]
    public function cityWeather(
        #[MapEntity(mapping: ['code' => 'code', 'name' => 'name'])]
        City $location,
        WeatherUtil $util,
    ): Response
    {
        $forecasts = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'forecasts' => $forecasts,
        ]);
    }
}
