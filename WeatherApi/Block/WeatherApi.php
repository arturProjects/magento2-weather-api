<?php

namespace AB\WeatherApi\Block;

use Magento\Framework\View\Element\Template;
use AB\WeatherApi\Api\WeatherApiInterface;

class WeatherApi extends  Template
{
    protected WeatherApiInterface $weatherApiModel;

    /**
     * @param Template\Context $context
     * @param WeatherApiInterface $weatherApiModel
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        WeatherApiInterface $weatherApiModel,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->weatherApiModel = $weatherApiModel;
    }

    /**
     * @return array|null
     */
   public function getWeather(): ?array
   {
        return $this->weatherApiModel->getWeatherData();
   }
}
