<?php

namespace AB\WeatherApi\Api;

interface WeatherApiInterface
{
    /**
     * @return mixed
     */
    public function getWeatherData(): mixed;
}
