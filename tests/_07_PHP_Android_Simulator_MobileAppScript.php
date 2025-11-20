<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer autoload

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverWait;
use Facebook\WebDriver\WebDriverExpectedCondition;

// --------------------------
// 1. Desired Capabilities
// --------------------------
$capabilities = DesiredCapabilities::android();
$capabilities->setCapability('appium:deviceName', 'Vivo V40 Pro');  // Your real device
$capabilities->setCapability('platformName', 'Android');
$capabilities->setCapability('appium:platformVersion', '14');
$capabilities->setCapability('appium:app', 'General-Store-final (1).apk'); // APK path in FireFlink Cloud or URL

// --------------------------
// 2. Appium / Cloud URL
// --------------------------
$serverUrl = '';

// --------------------------
// 3. Start Driver
// --------------------------
$driver = RemoteWebDriver::create($serverUrl, $capabilities, 60000, 60000);

// --------------------------
// 4. Helper: Screenshot
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
    // 5. Wait helper
    // --------------------------
    $wait = new WebDriverWait($driver, 20);

    // --------------------------
    // 6. Open Country Dropdown
    // --------------------------
    $countryDropdown = $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::xpath("//android.widget.Spinner[@resource-id='com.androidsample.generalstore:id/spinnerCountry']")
        )
    );
    $countryDropdown->click();
    takeScreenshot($driver, "01_CountryDropdown_Click");

    // Select Country: Afghanistan
    $countryOption = $driver->findElement(
        WebDriverBy::xpath("//android.widget.TextView[@resource-id='android:id/text1' and @text='Afghanistan']")
    );
    $countryOption->click();
    takeScreenshot($driver, "02_Country_Selected");

    // --------------------------
    // 7. Enter Name
    // --------------------------
    $nameField = $driver->findElement(
        WebDriverBy::xpath("//android.widget.EditText[@resource-id='com.androidsample.generalstore:id/nameField']")
    );
    $nameField->click();
    $nameField->sendKeys("Tester1");
    takeScreenshot($driver, "03_Name_Entered");

    // Hide keyboard
    $driver->executeScript('mobile: hideKeyboard', []);

    // --------------------------
    // 8. Select Gender: Male
    // --------------------------
    $maleRadio = $driver->findElement(
        WebDriverBy::xpath("//android.widget.RadioButton[@resource-id='com.androidsample.generalstore:id/radioMale']")
    );
    $maleRadio->click();
    takeScreenshot($driver, "04_Gender_Selected");

    // --------------------------
    // 9. Click Lets Shop
    // --------------------------
    $letsShopBtn = $driver->findElement(
        WebDriverBy::xpath("//android.widget.Button[@resource-id='com.androidsample.generalstore:id/btnLetsShop']")
    );
    $letsShopBtn->click();
    takeScreenshot($driver, "05_LetsShop_Clicked");

    // --------------------------
    // 10. Add first product to cart
    // --------------------------
    $productAddBtn = $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::xpath("//android.widget.TextView[@text='Air Jordan 4 Retro']/following-sibling::android.widget.LinearLayout//android.widget.TextView[@resource-id='com.androidsample.generalstore:id/productAddCart']")
        )
    );
    $productAddBtn->click();
    takeScreenshot($driver, "06_Product_Added");

    // --------------------------
    // 11. Go to Cart
    // --------------------------
    $cartBtn = $driver->findElement(
        WebDriverBy::xpath("//android.widget.ImageButton[@resource-id='com.androidsample.generalstore:id/appbar_btn_cart']")
    );
    $cartBtn->click();
    takeScreenshot($driver, "07_Cart_Page");

    echo "Test execution completed successfully." . PHP_EOL;

} catch (Exception $e) {
    echo "Exception occurred: " . $e->getMessage() . PHP_EOL;
    takeScreenshot($driver, "99_Exception");
} finally {
    if ($driver !== null) {
        $driver->quit();
    }
    echo "Driver closed." . PHP_EOL;
}
