<?php
class RequestIgnorerController {
  private $cache;
  private $duration = 60;
  private $maxRequests = 3;

  public function __construct($cache) {
    $this->cache = $cache;
  }

  public function setDuration($duration) {
    $this->duration = $duration;
  }

  public function setMaxRequests($maxRequests) {
    $this->maxRequests = $maxRequests;
  }

  public function reactOnRequest($login, $component) {
    $key = $login.'_'.$component;
    $requestIgnorer = $this->getRequest($key);
    if (!$requestIgnorer) {
      $this->saveNewRequest($key);
      return true;
    }
    if ($this->checkTime($requestIgnorer)) {
      if ($requestIgnorer->tries >= $this->maxRequests) {
        return false; // too much requests
      } else {
        $this->saveExistingRequest($key, $requestIgnorer);
      }
    } else {
      $this->saveNewRequest($key);
    }
    return true;
  }

  public function isRequestIgnored($login, $component) {
    $key = $login.'_'.$component;
    $requestIgnorer = $this->getRequest($key);
    if ($requestIgnorer) {
      if ($this->checkTime($requestIgnorer)) {
        if ($requestIgnorer->tries >= $this->maxRequests) {
          return true;
        }
      }
    } else {
      return false;
    }
  }

  private function getRequest($key) {
    return $this->cache->getValue($key);
  }
  private function saveNewRequest($key) {
    $requestIgnorer = new RequestIgnorerModel();
    $requestIgnorer->tries = 1;
    $requestIgnorer->timestamp = time();
    $this->cache->setValue($key, $requestIgnorer, $this->duration);
  }
  private function saveExistingRequest($key, RequestIgnorerModel $requestIgnorer) {
    $requestIgnorer->tries += 1;
    $this->cache->setValue($key, $requestIgnorer, $this->duration);
  }
  private function checkTime($requestIgnorer) {
    return time() - $requestIgnorer->timestamp < $this->duration;
  }
}
