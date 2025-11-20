<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer autoload

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\Chrome\ChromeOptions;

// --------------------------
// 1. Start Chrome Browser
// --------------------------
$chromeOptions = new ChromeOptions();
$capabilities = DesiredCapabilities::chrome();
$capabilities->setCapability(ChromeOptions::CAPABILITY, $chromeOptions);


$options = new ChromeOptions();
$driver = ChromeDriver::start($options);
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
// 2. Navigate to Google
// --------------------------
$driver->get("https://www.google.com");
takeScreenshot($driver, "01_Google_Page");

// --------------------------
// 3. Navigate to Pantaloons Landing Page
// --------------------------
$driver->navigate()->to("https://www.pantaloons.com");
takeScreenshot($driver, "02_Pantaloons_Landing");
sleep(2);

// --------------------------
// 4. Validate Pantaloons Logo
// --------------------------
$logo = $driver->findElement(WebDriverBy::xpath("//div[@class='nav-header-container']//img[@class='svgIconImg' and @alt='logoIcon']"));
if ($logo->isDisplayed()) {
    echo "Pantaloons logo is displayed" . PHP_EOL;
}
takeScreenshot($driver, "03_Logo_Visible");

// --------------------------
// 5. Search for Shirts
// --------------------------
$searchBar = $driver->findElement(WebDriverBy::xpath("//div[@class='nav-links']//input[@placeholder='Search']"));
$searchBar->click();
$searchBar->sendKeys("Shirts");
takeScreenshot($driver, "04_Typed_Search");
sleep(2);

$searchBar->sendKeys(WebDriverKeys::ENTER);
takeScreenshot($driver, "05_Search_Results");
sleep(4);

// --------------------------
// 6. Apply Gender Filter → Boys
// --------------------------
$filterGender = $driver->findElement(WebDriverBy::xpath("//p[text()='Gender']"));
$filterGender->click();
takeScreenshot($driver, "06_Gender_Filter_Clicked");

$boysCheckbox = $driver->findElement(WebDriverBy::xpath("//p[text()='Boys']//ancestor::div[contains(@class,'PlpWeb_filter-values')]//input"));
$boysCheckbox->click();
takeScreenshot($driver, "07_Boys_Filter_Clicked");
sleep(3);

// --------------------------
// 7. Clear / Select Filters
// --------------------------
$clearBtn = $driver->findElement(WebDriverBy::xpath("//button[@id=':r6:']"));
$clearBtn->click();
takeScreenshot($driver, "08_Filter_Clear");

echo "Test execution completed successfully." . PHP_EOL;

// --------------------------
// 8. Quit Browser
// --------------------------
$driver->quit();
