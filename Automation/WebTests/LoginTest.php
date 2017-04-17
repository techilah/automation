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
        $capabilities = DesiredCapabilities::firefox();
        $this->webDriver = RemoteWebDriver::create($host, $capabilities, 5000);
    }
    
    public function tearDown() {
        
        if($this->hasFailed()){
            $imageName = "screenshot_" . strtotime(date('Y-m-d h:i:s')) . '.png';
            $this->webDriver->takeScreenshot('C:\Results\screenshots\\' . $imageName);
        }
        $this->webDriver->quit();
        parent::tearDown();
        
    }

    public function testLoginFail(){
        
        $this->webDriver->get($this->url);
        $elements = $this->webDriver->findElements(WebDriverBy::id('username'));        
        $this->assertTrue(count($elements) > 0, 'Username field not found'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::id('password'));        
        $this->assertTrue(count($elements) > 0, 'Password field not found'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::id('login'));        
        $this->assertTrue(count($elements) > 0, 'Login button not found');  
        
        $elements = $this->webDriver->findElement(WebDriverBy::id('username'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('wrongpassword');
        $elements = $this->webDriver->findElement(WebDriverBy::id('login'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::id('username')));
        
        $elements = $this->webDriver->findElements(WebDriverBy::id('username'));        
        $this->assertTrue(count($elements) > 0, 'Username field not found after failed login'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::id('password'));        
        $this->assertTrue(count($elements) > 0, 'Password field not found after failed login'); 
        
        $elements = $this->webDriver->findElements(WebDriverBy::id('login'));        
        $this->assertTrue(count($elements) > 0, 'Login button not found after failed login'); 
        
    }    
    
    public function testLoginSuccess(){
        
        $this->webDriver->get($this->url);
        
        $elements = $this->webDriver->findElement(WebDriverBy::id('username'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::id('password'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::id('login'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::linkText('Logout')));
        
        $elements = $this->webDriver->findElements(WebDriverBy::linkText('Logout'));        
        $this->assertTrue(count($elements) > 0, 'Logout link not found'); 
        
    }   

}



