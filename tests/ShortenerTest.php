<?php
namespace App\Tests;

use App\Service\ShortenerService;
use phpDocumentor\Reflection\Types\Static_;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\Date;

class ShortenerTest extends WebTestCase {
    /**
     * @var $service ShortenerService
     */
    private $service;

    protected function setUp()
    {
        static::bootKernel();
        $this->service = static::$kernel->getContainer()->get('App\Service\ShortenerService');
    }

    public function testGenerateRandomAlphaNumericString() {
        $randomStr1 = $this->service->generateRandomAlphaNumericString();
        $randomStr2 = $this->service->generateRandomAlphaNumericString();
        $this->assertNotEquals($randomStr1, $randomStr2);
        $this->assertEquals(6, strlen($randomStr1));
        $this->assertNotTrue(null == $randomStr1);

    }

    public function testCreateNewShortUrl() {
        $originalUrl = 'http://example.com';
        $shortcode = 'anon';
        $createdAt = new \DateTime('NOW');
        $url = $this->service->createNewShortUrl($originalUrl, $shortcode);
        $this->assertEquals($createdAt->format('d/m/Y'), $url->getCreatedAt()->format('d/m/Y'));
        $this->assertEquals($originalUrl, $url->getOriginalUrl());
        $this->assertEquals($shortcode, $url->getShortUrl());
        $this->assertEquals(0, $url->getVisitCount());
    }

    public function testGetUrlStatsResponse() {
        $url = $this->service->createNewShortUrl('http://example.com', 'medabdh');
        $response = $this->service->getUrlStatsResponse($url);

        $this->assertEquals($url->getOriginalUrl(), $response['originalUrl']);
        $this->assertEquals($url->getCreatedAt(), $response['registration_date']);
        $this->assertEquals($url->getLastVisited(), $response['last_accessed']);
        $this->assertEquals($url->getVisitCount(), $response['visit_count']);
        $this->assertEquals(true, $response['success']);
    }

    public function testGetSuccessfulResponse() {
        $message = 'successful message';
        $successfulMessage = $this->service->getSuccessfulResponse($message);
        $this->assertEquals($message, $successfulMessage['message']);
        $this->assertEquals(true, $successfulMessage['success']);
    }

    public function testGetErrorResponse() {
        $message = 'error message';
        $errorMessage = $this->service->getErrorResponse($message);
        $this->assertEquals($message, $errorMessage['message']);
        $this->assertEquals(false, $errorMessage['success']);

    }
}