<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer autoload

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Chrome\ChromeOptions;

// --------------------------
// 1. Set Desired Capabilities for Android Chrome
// --------------------------
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability('platformName', 'Android');
$capabilities->setCapability('deviceName', 'Samsung Galaxy A12');
$capabilities->setCapability('platformVersion', '12');
$capabilities->setCapability('browserName', 'Chrome');

// --------------------------
// 2. RemoteWebDriver Server URL (Appium / Selenium Grid for mobile)
// --------------------------
$serverUrl = 'http://localhost:4723/wd/hub'; // Replace with your Appium server URL

$driver = RemoteWebDriver::create($serverUrl, $capabilities, 60000, 60000);

// --------------------------
// Helper function: Screenshot
// --------------------------
function takeScreenshot($driver, $fileName) {
    $path = "C:\\SimpleRunScreenshots\\$fileName.png";
    if (!file_exists(dirname($path))) {
        mkdir(dirname($path), 0777, true);
    }
    $driver->takeScreenshot($path);
    echo "Screenshot saved: $path" . PHP_EOL;
}

// --------------------------
// 3. Open Pantaloons website
// --------------------------
$driver->get("https://www.pantaloons.com");
takeScreenshot($driver, "01_HomePage");
sleep(3);

// --------------------------
// 4. Click search icon
// --------------------------
$driver->findElement(WebDriverBy::cssSelector("div.mobilesearchbox"))->click();
takeScreenshot($driver, "02_After_Click_Search_Icon");
sleep(2);

// --------------------------
// 5. Enter search text
// --------------------------
$driver->findElement(WebDriverBy::xpath("//input[@placeholder='Search for products,brands and more...']"))
       ->sendKeys("Shirt");
takeScreenshot($driver, "03_After_Entering_Search");
sleep(2);

// --------------------------
// 6. Click first search result
// --------------------------
$driver->findElement(WebDriverBy::xpath("(//mark[text()='Shirt'])[1]"))->click();
takeScreenshot($driver, "04_After_Search_Result_Click");
sleep(4);

// --------------------------
// 7. Open cart
// --------------------------
$driver->findElement(WebDriverBy::cssSelector("span.cartSpriteIcon"))->click();
takeScreenshot($driver, "05_Cart_Page");

// --------------------------
// 8. Page title
// --------------------------
echo "Page Title: " . $driver->getTitle() . PHP_EOL;

// --------------------------
// 9. Quit driver
// --------------------------
$driver->quit();
echo "Mobile browser test execution completed." . PHP_EOL;
