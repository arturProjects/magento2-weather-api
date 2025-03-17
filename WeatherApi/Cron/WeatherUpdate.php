<?php

namespace AB\WeatherApi\Cron;

use AB\WeatherApi\Api\WeatherApiInterface;

class WeatherUpdate
{
    protected WeatherApiInterface $weatherModel;

    /**
     * @param WeatherApiInterface $weatherModel
     */
    public function __construct(WeatherApiInterface $weatherModel)
    {
        $this->weatherModel = $weatherModel;
    }

    /**
     * @return void
     */
    public function execute(): void
    {
        $this->weatherModel->getWeatherData();
    }
}
