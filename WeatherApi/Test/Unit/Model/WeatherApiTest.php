<?php

namespace AB\WeatherApi\Test\Unit\Model;

use AB\WeatherApi\Model\WeatherApi;
use PHPUnit\Framework\TestCase;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\CacheInterface;


class WeatherApiTest extends TestCase
{
    protected WeatherApi $weatherModel;
    protected $curlClientMock;
    protected $scopeConfigMock;
    protected $cacheMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->curlClientMock = $this->createMock(Curl::class);
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->cacheMock = $this->createMock(CacheInterface::class);

        $this->scopeConfigMock->method('getValue')->willReturn('your-api-key-here');

        $this->weatherModel = new WeatherApi(
            $this->curlClientMock,
            $this->scopeConfigMock,
            $this->cacheMock
        );
    }

    /**
     * @return void
     */
    public function testGetWeatherDataReturnsValidData()
    {
        $city = 'Warsaw';
        $response = [
            'main' => ['temp' => 15],
            'weather' => [['description' => 'Clear sky']],
        ];

        $this->curlClientMock->method('get')->willReturn(true);
        $this->curlClientMock->method('getBody')->willReturn(json_encode($response));
        $weatherData = $this->weatherModel->getWeatherData();

        $this->assertIsArray($weatherData);
        $this->assertArrayHasKey('main', $weatherData);
        $this->assertEquals(15, $weatherData['main']['temp']);
        $this->assertEquals('Clear sky', $weatherData['weather'][0]['description']);
    }

    /**
     * @return void
     */
    public function testGetWeatherDataReturnsNullOnError()
    {
        $this->curlClientMock->method('get')->will($this->throwException(new \Exception('API Error')));
        $weatherData = $this->weatherModel->getWeatherData();
        $this->assertNull($weatherData);
    }
}
