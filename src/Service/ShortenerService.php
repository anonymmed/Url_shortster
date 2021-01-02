<?php

namespace App\Service;

use App\Entity\Urls;

class ShortenerService
{
  public function generateRandomAlphaNumericString(): string {
      $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      return substr(str_shuffle($str), 0, 6);
  }

  public function createNewShortUrl($originalUrl, $shortCode) {
      $url = new Urls();
      $url->setShortUrl($shortCode)->setOriginalUrl($originalUrl);
      return $url;
  }

  public function getUrlStatsResponse(Urls $url) {
      $response['originalUrl'] = $url->getOriginalUrl();
      $response['registration_date'] = $url->getCreatedAt();
      $response['last_accessed'] = $url->getLastVisited();
      $response['visit_count'] = $url->getVisitCount();
      $response['success'] = true;
      return $response;
  }
  public function getSuccessfulResponse(string $message) {
      $response['success'] = true;
      $response['message'] = $message;
      return $response;
  }
  public function getErrorResponse(string $message) {
      $response['success'] = false;
      $response['message'] = $message;
      return $response;
  }
}
