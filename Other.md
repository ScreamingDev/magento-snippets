# Magento Snippets

## Download extension manually using pear/mage
Pear for 1.4, mage for 1.5. File downloaded into /downloader/.cache/community/

	./pear download magento-community/Shipping_Agent
	./mage download community Shipping_Agent

## Clear cache/reindex

```php
    <?php

    // clear cache
    Mage::app()->removeCache('catalog_rules_dirty');
    // reindex prices
    Mage::getModel('index/process')->load(2)->reindexEverything();
    /*
    1 = Product Attributes
    2 = Product Attributes
    3 = Catalog URL Rewrites
    4 = Product Flat Data
    5 = Category Flat Data
    6 = Category Products
    7 = Catalog Search Index
    8 = Tag Aggregation Data
    9 = Stock Status
    */

    ?>
```

## Log to custom file

```php
    <?php

    Mage::log('Your Log Message', Zend_Log::INFO, 'your_log_file.log');

    ?>
```

## Call Static Block

```php
    <?php

    echo $this->getLayout()->createBlock('cms/block')->setBlockId('block-name')->toHtml();

    ?>
```

to `page.xml`, and then add the `mytemplate.phtml` file. Any block added to the head block will be automatically rendered. (this automatic rendering doesn't apply for all layout blocks, only for blocks where getChildHtml is called without paramaters).

## Check if customer is logged in

```php
    <?php

    $logged_in = Mage::getSingleton('customer/session')->isLoggedIn(); // (boolean)

    ?>
```

## Run Magento Code Externally

```php
<?php

require_once('app/Mage.php'); //Path to Magento
umask(0);
Mage::app();
// Run your code here

?>
```

## Get the current category/product/cms page

```php
<?php

$currentCmsPage = Mage::registry('cms_page');

?>
```

## Changing the Admin URL

Open up the `/app/etc/local.xml` file, locate the `<frontName>` tag, and change the ‘admin’ part it to something a lot more random, eg:

```xml
<frontName><![CDATA[supersecret-admin-name]]></frontName>
```

Clear your cache and sessions.

## Debug using zend

```php
<?php echo Zend_Debug::dump($thing_to_debug, 'debug'); ?>
```

## $_GET, $_POST & $_REQUEST Variables

```php
<?php
// $_GET
$productId = Mage::app()->getRequest()->getParam('product_id');
// The second parameter to getParam allows you to set a default value which is returned if the GET value isn't set
$productId = Mage::app()->getRequest()->getParam('product_id', 44);
$postData = Mage::app()->getRequest()->getPost();
// You can access individual variables like...
$productId = $postData['product_id']);
?>
```

## Get methods of an object

First, use `get_class` to get the name of an object's class.

```php
<?php $class_name = get_class($object); ?>
```

Then, pass that `get_class_methods` to get a list of all the callable methods on an object

```php
<?php
$class_name = get_class($object);
$methods = get_class_methods($class_name);
foreach($methods as $method)
{
	var_dump($method);
}
?>
```

## Update all subscribers into a customer group (e.g. 5)

```sql
UPDATE
	customer_entity,
	newsletter_subscriber
SET
	customer_entity.`group_id` = 5
WHERE
	customer_entity.`entity_id` = newsletter_subscriber.`customer_id`
AND
	newsletter_subscriber.`subscriber_status` = 1;
```

## Cart Data

```php
<?php
$cart = Mage::getModel('checkout/cart')->getQuote()->getData();
print_r($cart);
$cart = Mage::helper('checkout/cart')->getCart()->getItemsCount();
print_r($cart);
$session = Mage::getSingleton('checkout/session');
foreach ($session->getQuote()->getAllItems() as $item) {
    echo $item->getName();
    Zend_Debug::dump($item->debug());
}
?>
```