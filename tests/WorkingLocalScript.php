<?php
require __DIR__ . '/../vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;

// Chrome options
$chromeOptions = new ChromeOptions();
$chromeOptions->addArguments(['--start-maximized']);

// W3C capabilities
$capabilities = [
    'browserName' => 'chrome',
    ChromeOptions::CAPABILITY => $chromeOptions
];

// Selenium Hub URL
$serverUrl = 'http://localhost:4444';

// Create driver
$driver = RemoteWebDriver::create($serverUrl, $capabilities, 60000, 60000);

// Example test: Google search
$driver->get("https://www.google.com");
$driver->findElement(WebDriverBy::name("q"))
       ->sendKeys("Selenium Grid PHP")
       ->sendKeys(WebDriverKeys::ENTER);

echo "Test executed successfully" . PHP_EOL;

// Quit browser
sleep(5);
$driver->quit();
