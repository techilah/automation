<?php

namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use \PHPUnit\Framework\TestCase;
require_once(__DIR__ .  '/../../vendor/autoload.php');

class LoginTest extends TestCase{
    
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;
    protected $url = 'http://shoppingcart.com/admin/';

    public function setUp(){
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $capabilities = DesiredCapabilities::chrome();
        $this->webDriver = RemoteWebDriver::create($host, $capabilities, 5000);
    }
    
    public function tearDown() {
        
        if($this->hasFailed()){
            $imageName = "screenshot_" . strtotime(date('Y-m-d h:i:s')) . '.png';
            $this->webDriver->takeScreenshot('G:\\' . $imageName);
        }
        $this->webDriver->quit();
        parent::tearDown();
        
    }

    public function testLoginFail(){
        
        $this->webDriver->get($this->url);
        $elements = $this->webDriver->findElements(WebDriverBy::name('username'));        
        $this->assertTrue(count($elements) > 0, 'Username field not found'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::name('password'));        
        $this->assertTrue(count($elements) > 0, 'Password field not found'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::name('login'));        
        $this->assertTrue(count($elements) > 0, 'Login button not found');  

        $elements = $this->webDriver->findElement(WebDriverBy::name('username'))->sendKeys('admin');

        $elements = $this->webDriver->findElement(WebDriverBy::name('password'))->sendKeys('wrongpassword');
        $elements = $this->webDriver->findElement(WebDriverBy::name('login'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::name('username')));
        
        $elements = $this->webDriver->findElements(WebDriverBy::name('username'));        
        $this->assertTrue(count($elements) > 0, 'Username field not found after failed login'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::name('password'));        
        $this->assertTrue(count($elements) > 0, 'Password field not found after failed login'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::name('login'));        
        $this->assertTrue(count($elements) > 0, 'Login button not found after failed login'); 
        
    }    
    
    /**
     * @depends testLoginFail
     */
    public function testLoginSuccess(){
      
        $this->webDriver->get($this->url);
        
        $elements = $this->webDriver->findElement(WebDriverBy::name('username'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::name('password'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::name('login'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::linkText('Logout')));
        
        $elements = $this->webDriver->findElements(WebDriverBy::linkText('Logout'));        
        $this->assertTrue(count($elements) > 0, 'Logout link not found'); 
        
    }   

}



