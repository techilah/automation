<?php

namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use \PHPUnit\Framework\TestCase;
require_once(__DIR__ .  '/../vendor/autoload.php');

class LoginTest extends TestCase{
    
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;
    protected $url = 'http://shoppingcart.com/admin/';

    public function setUp(){
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $capabilities = DesiredCapabilities::firefox();
        $this->webDriver = RemoteWebDriver::create($host, $capabilities, 5000);
    }

    public function testGitHubHome()
    {
        $this->webDriver->get($this->url);
        $elements = $this->webDriver->findElements(WebDriverBy::id('login'));
        sleep(10);
        
        $this->assertTrue(count($elements) > 0, 'Login button not found');    
    }    
}



