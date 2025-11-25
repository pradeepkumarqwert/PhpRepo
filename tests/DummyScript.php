<?php
require __DIR__ . '/../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverDimension;

// --------------------------
// Selenium Hub URL
// --------------------------
$hubUrl = 'http://103.182.210.84:4444/'; // replace with your hub URL if needed

// --------------------------
// Chrome options
// --------------------------
$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--start-maximized']); // add other Chrome args if needed

// --------------------------
// W3C capabilities
// --------------------------
$capabilities = [
    'browserName' => 'chrome',
    'browserVersion' => '123', // optional, remove if you want latest on node
    'latformName' => 'Windows', // optional
    'goog:chromeOptions' => $chromeOptions
];

// --------------------------
// Create RemoteWebDriver
// --------------------------
$driver = RemoteWebDriver::create($hubUrl, $capabilities, 60000, 60000);

// --------------------------
// Set browser window size (optional)
// --------------------------
$driver->manage()->window()->setSize(new WebDriverDimension(1024, 768));

// --------------------------
// Example test: Google search
// --------------------------
$driver->get("https://www.google.com");
$driver->findElement(WebDriverBy::name("q"))
       ->sendKeys("Selenium Grid PHP Chrome")
       ->sendKeys(WebDriverKeys::ENTER);

echo "Chrome test executed successfully" . PHP_EOL;

// --------------------------
// Quit browser
// --------------------------
sleep(5);
$driver->quit();
