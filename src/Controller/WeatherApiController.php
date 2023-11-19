<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherUtil;
use App\Entity\Forecast;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpFoundation\Response;


class WeatherApiController extends AbstractController
{
    #[Route('/api/v1/weather', name: 'app_weather_api')]
    public function index(
        WeatherUtil $util,
        #[MapQueryParameter('code')] string $code,
        #[MapQueryParameter('name')] string $name,
        #[MapQueryParameter('format')] string $format = 'json',
        #[MapQueryParameter('twig')] bool $twig = false,
    ): Response
    {
        $forecasts = $util->getWeatherForCountryAndCity($code, $name);

        if ($format === 'csv') {
            if($twig == true){
                return $this->render('weather_api/index.csv.twig', [
                    'name' => $name,
                    'code' => $code,
                    'forecasts' => $forecasts,
                ]);
            }
            $csv = "name,code,date,celsius,fahrenheit\n";
            $csv .= implode(
                "\n",
                array_map(fn(Forecast $m) => sprintf(
                    '%s,%s,%s,%s,%s',
                    $name,
                    $code,
                    $m->getDate()->format('Y-m-d'),
                    $m->getTemperature(),
                    $m->getFahrenheit(),
                ), $forecasts)
            );

            return new Response($csv, 200, [
            //    'Content-Type' => 'text/csv',
            ]);
        }
        if($twig == true){
            return $this->render('weather_api/index.json.twig', [
                'name' => $name,
                'code' => $code,
                'forecasts' => $forecasts,
            ]);
        }
        // JSON response
        return $this->json([
            'name' => $name,
            'code' => $code,
            'forecasts' => array_map(function (Forecast $m) {
                return [
                    'date' => $m->getDate()->format('Y-m-d'),
                    'celsius' => $m->getTemperature(),
                    'farenheit' => $m->getFahrenheit(),
                ];
            }, $forecasts),
        ]);
    }
}

