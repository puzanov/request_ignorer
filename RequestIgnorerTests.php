<?
require_once "PHPUnit/Framework.php";
require_once "Cache.php";
require_once "RequestIgnorerController.php";
require_once "RequestIgnorerModel.php";
class RequestIgnorerTests extends PHPUnit_Framework_TestCase {
  public function test() {
    $memcache_obj = new Memcache;
    $memcache_obj->connect('localhost', 11211);
    $memcache_obj->delete("oleg_createComment");
    $cache = new Cache($memcache_obj);
    $ri = new RequestIgnorerController($cache);
    $this->assertTrue($ri->reactOnRequest("oleg", "createComment"));      
    $this->assertTrue($ri->reactOnRequest("oleg", "createComment"));      
    $this->assertTrue($ri->reactOnRequest("oleg", "createComment"));      
    $this->assertFalse($ri->reactOnRequest("oleg", "createComment"));

    # usage example
    # if ($ri->reactOnRequest($username, "createComment")) {
    #   // user can create a comment
    # } else {
    #   // hey %username%! you're posting too much comments! wait a little...
    # }     
  }
}

?>
