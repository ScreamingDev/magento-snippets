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

## Add JavaScript to page

First approach: page.xml - you can add something like

```xml
    <action method="addJs">
        <script>path/to/my/file.js</script>
    </action>
```

Second approach: Find `page/html/head.phtml` in your theme and add the code directly to `page.html`.

Third approach: If you look at the stock page.html mentioned above, you'll see this line

```php
    <?php

    echo $this->getChildHtml()

    ?>
```

Normally, the getChildHtml method is used to render a specific child block. However, if called with no paramater, getChildHtml will automatically render all the child blocks. That means you can add something like

```xml
<!-- existing line --> <block type="page/html_head" name="head" as="head">
	<!-- new sub-block you're adding --> <block type="core/template" name="mytemplate" as="mytemplate" template="page/mytemplate.phtml"/>
	...
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
// Run you code here

?>
```

## Get the current category/product/cms page

```php
<?php

$currentCmsPage = Mage::registry('cms_page');

?>
```

## Programmatically change Magento’s core config data

```php
<?php
// find 'path' in table 'core_config_data' e.g. 'design/head/demonotice'
$my_change_config = new Mage_Core_Model_Config();
// turns notice on
$my_change_config->saveConfig('design/head/demonotice', "1", 'default', 0);
// turns notice off
$my_change_config->saveConfig('design/head/demonotice', "0", 'default', 0);
?>
```

## Changing the Admin URL

Open up the `/app/etc/local.xml` file, locate the `<frontName>` tag, and change the ‘admin’ part it to something a lot more random, eg:

```xml
<frontName><![CDATA[supersecret-admin-name]]></frontName>
```

Clear your cache and sessions.

## Magento: Mass Exclude/Unexclude Images

By default, Magento will check the 'Exclude' box for you on all imported images, making them not show up as a thumbnail under the main product image on the product view.

```sql
    # Mass Unexclude
    UPDATE`catalog_product_entity_media_gallery_value` SET `disabled` = '0' WHERE `disabled` = '1';
    # Mass Exclude
    UPDATE`catalog_product_entity_media_gallery_value` SET `disabled` = '1' WHERE `disabled` = '0';
```

## getBaseUrl – Magento URL Path

```php
<?php
// http://example.com/
echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
// http://example.com/js/
echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_JS);
// http://example.com/index.php/
echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
// http://example.com/media/
echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
// http://example.com/skin/
echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
?>
```

## Get The Root Category In Magento

```php
<?php
$rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
$_category = Mage::getModel('catalog/category')->load($rootCategoryId);
// You can then get all of the top level categories using:
$_subcategories = $_category->getChildrenCategories();
?>
```

## Get The Current URL In Magento

```php
<?php echo Mage::helper('core/url')->getCurrentUrl(); ?>
```

## Category Navigation Listings in Magento

Make sure the block that you’re working is of the type catalog/navigation. If you’re editing catalog/navigation/left.phtml then you should be okay.

```php
<div id="leftnav">
	<?php $helper = $this->helper('catalog/category') ?>
	<?php $categories = $this->getStoreCategories() ?>
	<?php if (count($categories) > 0): ?>
		<ul id="leftnav-tree" class="level0">
			<?php foreach($categories as $category): ?>
				<li class="level0<?php if ($this->isCategoryActive($category)): ?> active<?php endif; ?>">
					<a href="<?php echo $helper->getCategoryUrl($category) ?>"><span><?php echo $this->escapeHtml($category->getName()) ?></span></a>
					<?php if ($this->isCategoryActive($category)): ?>
						<?php $subcategories = $category->getChildren() ?>
						<?php if (count($subcategories) > 0): ?>
							<ul id="leftnav-tree-<?php echo $category->getId() ?>" class="level1">
								<?php foreach($subcategories as $subcategory): ?>
									<li class="level1<?php if ($this->isCategoryActive($subcategory)): ?> active<?php endif; ?>">
										<a href="<?php echo $helper->getCategoryUrl($subcategory) ?>"><?php echo $this->escapeHtml(trim($subcategory->getName(), '- ')) ?></a>
									</li>
								<?php endforeach; ?>
							</ul>
							<script type="text/javascript">decorateList('leftnav-tree-<?php echo $category->getId() ?>', 'recursive')</script>
						<?php endif; ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<script type="text/javascript">decorateList('leftnav-tree', 'recursive')</script>
	<?php endif; ?>
</div>
```

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

## Is product purchasable?

```php
<?php if($_product->isSaleable()) { // do stuff } ?>
```

## Load Products by Category ID

```php
<?php
$_category = Mage::getModel('catalog/category')->load(47);
$_productCollection = $_category->getProductCollection();
if($_productCollection->count()) {
	foreach( $_productCollection as $_product ):
		echo $_product->getProductUrl();
		echo $this->getPriceHtml($_product, true);
		echo $this->htmlEscape($_product->getName());
	endforeach;
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

## Turn template hints on/off via database

```sql
UPDATE
`core_config_data`
SET
`value` = 0
WHERE
`path` = "dev/debug/template_hints"
OR
`path` = "dev/debug/template_hints_blocks";
```

## Delete all products

```sql
TRUNCATE TABLE `catalog_product_bundle_option`;
TRUNCATE TABLE `catalog_product_bundle_option_value`;
TRUNCATE TABLE `catalog_product_bundle_selection`;
TRUNCATE TABLE `catalog_product_entity_datetime`;
TRUNCATE TABLE `catalog_product_entity_decimal`;
TRUNCATE TABLE `catalog_product_entity_gallery`;
TRUNCATE TABLE `catalog_product_entity_int`;
TRUNCATE TABLE `catalog_product_entity_media_gallery`;
TRUNCATE TABLE `catalog_product_entity_media_gallery_value`;
TRUNCATE TABLE `catalog_product_entity_text`;
TRUNCATE TABLE `catalog_product_entity_tier_price`;
TRUNCATE TABLE `catalog_product_entity_varchar`;
TRUNCATE TABLE `catalog_product_link`;
TRUNCATE TABLE `catalog_product_link_attribute_decimal`;
TRUNCATE TABLE `catalog_product_link_attribute_int`;
TRUNCATE TABLE `catalog_product_link_attribute_varchar`;
TRUNCATE TABLE `catalog_product_option`;
TRUNCATE TABLE `catalog_product_option_price`;
TRUNCATE TABLE `catalog_product_option_title`;
TRUNCATE TABLE `catalog_product_option_type_price`;
TRUNCATE TABLE `catalog_product_option_type_title`;
TRUNCATE TABLE `catalog_product_option_type_value`;
TRUNCATE TABLE `catalog_product_super_attribute`;
TRUNCATE TABLE `catalog_product_super_attribute_label`;
TRUNCATE TABLE `catalog_product_super_attribute_pricing`;
TRUNCATE TABLE `catalog_product_super_link`;
TRUNCATE TABLE `catalog_product_enabled_index`;
TRUNCATE TABLE `catalog_product_website`;
TRUNCATE TABLE `catalog_product_entity`;
TRUNCATE TABLE `cataloginventory_stock_item`;
TRUNCATE TABLE `cataloginventory_stock_status`;
```