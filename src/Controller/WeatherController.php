<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\ForecastRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{id}', name: 'app_weather', requirements: ['id' => '\d+'])]
    public function city(City $city, ForecastRepository $forecastRepository): Response
    {
        $forecasts = $forecastRepository->findByLocation($city);

        return $this->render('weather/city.html.twig', [
            'location' => $city,
            'forecasts' => $forecasts,
        ]);
    }

    #[Route('/weather/{name}', name: 'app_weather')]
    public function cityName(City $city, ForecastRepository $forecastRepository): Response
    {
        $forecasts = $forecastRepository->findByLocation($city);

        return $this->render('weather/city.html.twig', [
            'location' => $city,
            'forecasts' => $forecasts,
        ]);
    }
}
