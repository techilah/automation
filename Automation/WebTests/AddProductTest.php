<?php

namespace Facebook\WebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Firefox\FirefoxDriver;
use \PHPUnit\Framework\TestCase;
require_once(__DIR__ .  '/../../vendor/autoload.php');
require_once(__DIR__ . '/../../lib/ProductClass.php');

class AddProduct extends TestCase{
    
    /**
     * @var \RemoteWebDriver
     */
    protected $webDriver;
    protected $url = 'http://shoppingcart.com/admin/';
    protected $productClass;

    public function setUp(){
        $host = 'http://localhost:4444/wd/hub'; // this is the default
        $capabilities = DesiredCapabilities::chrome();
        //$capabilities->setCapability(FirefoxDriver::PROFILE, base64_encode(file_get_contents('Automation.zip')));
        $this->webDriver = RemoteWebDriver::create($host, $capabilities, 5000);
        
        $this->productClass = new \ProductClass();
    }
    
    public function tearDown() {
        $this->webDriver->quit();
        parent::tearDown();
        
    }

    public function testAddProductNoImage(){
        
        $this->webDriver->get($this->url); 
        
        $elements = $this->webDriver->findElement(WebDriverBy::name('username'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::name('password'))->sendKeys('admin');
        $elements = $this->webDriver->findElement(WebDriverBy::name('login'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::linkText('Logout')));
        $elements = $this->webDriver->findElement(WebDriverBy::linkText('Add product'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::name('productname')));
        
        $productName = "Automated_product_" . strtotime(date('Y-m-d h:i:s'));
        
        $elements = $this->webDriver->findElement(WebDriverBy::name('productname'))->sendKeys($productName);        
        $elements = $this->webDriver->findElement(WebDriverBy::name('unitprice'))->sendKeys("3.14");
        $elements = $this->webDriver->findElement(WebDriverBy::name('productdescription'))->sendKeys("Short product description");
        $elements = $this->webDriver->findElement(WebDriverBy::id('add'))->click();
        
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath("/html/body/div[2]/center/b")));
        
        $elements = $this->webDriver->findElements(WebDriverBy::linkText($productName));        
        $this->assertTrue(count($elements) > 0, 'Product was not added'); 
        
        $elements = $this->webDriver->findElement(WebDriverBy::linkText($productName))->click();
        $this->webDriver->wait(3, 200)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::name('unitprice')));
        sleep(3);
        $unitPrice = $this->webDriver->findElement(WebDriverBy::name('unitprice'))->getAttribute('value');
        
        $this->assertEquals(3.14, $unitPrice, "Unit price mismatch", 0.01);
        
        
        
        $allProducts = $this->productClass->getProducts();
        
        
        $found = false;
        foreach ($allProducts as $productFromDB){
            if ($productFromDB['productname'] === $productName){
                $found = true;
                $this->assertEquals(3.14, $productFromDB['unitprice'], 'Incorrect DB price');
                $this->assertEquals("Short product description", $productFromDB['productdescription'], 'Incorrect DB description');
                $this->assertEquals(DEFAULT_IMAGE_NAME, $productFromDB['productfilepath'], 'Incorrect DB price');
            }
        }
        
        $this->assertTrue($found, "Product not found in the DB");
        
    }

}



