<?php
class Cache {
  var $memcache;

  public function __construct($memcache) {
    $this->memcache = $memcache;
  }

  public function getValue($key) {
    $val = $this->memcache->get($key);
    return $val;
  }

  public function setValue($key, $value, $duration) {
    $this->memcache->set($key, $value, 0, $duration);
  }

  public function delete($key) {
    $this->memcache->delete($key);
  }

  public function increment($key, $def) {
    if ($this->memcache->increment($key) == null) {
      $this->memcache->set($key, $def);
    }
  }
}
