<?php

use \PHPUnit\Framework\TestCase;
require_once(__DIR__ .  '/../../vendor/autoload.php');
require_once(__DIR__ . '/../../lib/conf.php');

class ApiSOAPTest extends TestCase{
    
    protected $client;
    protected $sessionID;
    protected $subscriptionReference = '5EE3A8DFF4';

    public function setUp(){
        
        $host   = "https://api.avangate.com/subscription";
	$this->client = new \SoapClient($host . "/2.0/soap/?wsdl", array('location' => $host . "/2.0/soap/"));
        
        date_default_timezone_set('UTC');
        $now          = date('Y-m-d H:i:s'); 
	$string = strlen(MERCHANT_CODE) . MERCHANT_CODE . strlen($now) . $now;
	$hash   = hash_hmac('md5', $string, SECRET_KEY);
        
        $this->sessionID = $this->client->login(MERCHANT_CODE, $now, $hash);
    }
    
    public function testUpdateSubscription(){
        
        $subscription = $this->client->getSubscription($this->sessionID,$this->subscriptionReference);
        
        $newFirstName = "FirstName_" . strtotime(date('Y-m-d H:i:s'));
        $newLastName = "LastName_" . strtotime(date('Y-m-d H:i:s'));
        $subscription->EndUser->FirstName = $newFirstName;
        $subscription->EndUser->LastName = $newLastName;
        
        $this->client->updateSubscription($this->sessionID, $subscription);
        $subscription = $this->client->getSubscription($this->sessionID,$this->subscriptionReference);
        
        $this->assertEquals($newFirstName, $subscription->EndUser->FirstName, 'First Name was not updated');
        $this->assertEquals($newLastName, $subscription->EndUser->LastName, 'Last Name was not updated');
        
        $this->client->cancelSubscription($this->sessionID, $this->subscriptionReference);
        $subscription = $this->client->getSubscription($this->sessionID,$this->subscriptionReference);
        $this->assertFalse($subscription->SubscriptionEnabled, 'Subscription was not disabled');
        
        $this->client->enableSubscription($this->sessionID, $this->subscriptionReference);
        $subscription = $this->client->getSubscription($this->sessionID,$this->subscriptionReference);
        $this->assertTrue($subscription->SubscriptionEnabled, 'Subscription was not enabled');
        
    }    
    
}



