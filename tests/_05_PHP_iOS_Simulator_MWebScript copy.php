<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer autoload

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Interactions\WebDriverActions;

// --------------------------
// 1. Set Desired Capabilities for iOS Safari
// --------------------------
$capabilities = DesiredCapabilities::safari();
$capabilities->setCapability('platformName', 'iOS');
$capabilities->setCapability('deviceName', 'iPhone 14');
$capabilities->setCapability('platformVersion', '18.5');

// --------------------------
// 2. RemoteWebDriver Server URL (Appium / FireFlink Cloud)
// --------------------------
$serverUrl = '';

// Create driver
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

try {
    // --------------------------
    // 3. Navigate to Wikipedia
    // --------------------------
    $driver->get("https://www.wikipedia.org/");
    sleep(2);

    $wait = new WebDriverWait($driver, 15);

    $searchInput = $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id("searchInput"))
    );
    sleep(2);
    $searchInput->sendKeys("iPhone");

    sleep(2);
    $driver->findElement(WebDriverBy::cssSelector("button[type='submit']"))->click();

    sleep(2);
   
    sleep(2);
    echo "Title after search: " . $driver->getTitle() . PHP_EOL;

    // --------------------------
    // 4. Scroll down using Actions
    // --------------------------
    $actions = new WebDriverActions($driver);
    $actions->sendKeys(WebDriverKeys::PAGE_DOWN)->perform();
    sleep(1);
    $actions->sendKeys(WebDriverKeys::PAGE_DOWN)->perform();
    sleep(2);

    // --------------------------
    // 5. Click first Apple-related link
    // --------------------------
    $link = $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::xpath("(//a[contains(@href,'Apple')])[1]"))
    );
    $link->click();
    sleep(2);

    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::tagName("h1")));
    $heading = $driver->findElement(WebDriverBy::tagName("h1"));
    echo "Opened page: " . $heading->getText() . PHP_EOL;

    // --------------------------
    // 6. Navigate back and refresh
    // --------------------------
    $driver->navigate()->back();
    echo "Now on: " . $driver->getCurrentURL() . PHP_EOL;

    sleep(2);
    $driver->navigate()->refresh();
    sleep(2);

} catch (Exception $e) {
    echo "Exception occurred: " . $e->getMessage() . PHP_EOL;
    takeScreenshot($driver, "99_Exception_Occurred");
} finally {
    if ($driver !== null) {
        $driver->quit();
    }
    echo "Driver closed." . PHP_EOL;
}
