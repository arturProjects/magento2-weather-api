<?php

namespace AB\WeatherApi\Model;

use AB\WeatherApi\Api\WeatherApiInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\CacheInterface;
use Magento\Store\Model\ScopeInterface;


class WeatherApi implements WeatherApiInterface
{

    protected Curl $curlClient;
    protected ScopeConfigInterface $scopeConfig;
    protected CacheInterface $cache;
    protected mixed $apiKey;
    protected mixed $apiLocation;

    const string WEATHER_API_URL = 'https://api.openweathermap.org/data/2.5/weather';

    /**
     * Weather constructor.
     * @param Curl $curlClient
     * @param ScopeConfigInterface $scopeConfig
     * @param CacheInterface $cache
     */
    public function __construct(
        Curl                 $curlClient,
        ScopeConfigInterface $scopeConfig,
        CacheInterface     $cache
    )
    {
        $this->curlClient = $curlClient;
        $this->scopeConfig = $scopeConfig;
        $this->cache = $cache;
        $this->apiKey = $this->scopeConfig->getValue('general/weather_api_key_group/weather_api_key', ScopeInterface::SCOPE_STORE);
        $this->apiLocation = $this->scopeConfig->getValue('general/weather_api_key_group/weather_api_location', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getWeatherData(): mixed
    {
        $cacheKey = 'weather_data_' . md5($this->apiLocation);
        $cachedData = $this->cache->load($cacheKey);

        if ($cachedData) {
            return json_decode($cachedData, true);
        }

        $url = self::WEATHER_API_URL . '?q=' . urlencode($this->apiLocation) . '&appid=' . $this->apiKey . '&units=metric';

        try {
            $this->curlClient->get($url);
            $response = $this->curlClient->getBody();
            $weatherData = json_decode($response, true);

            if ($weatherData) {
                $this->cache->save(json_encode($weatherData), $cacheKey, [], 3600);
            }
            return $weatherData;
        } catch (\Exception $e) {
            return null;
        }
    }
}
