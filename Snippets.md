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

## Load category by id

```php
<?php
$_category = Mage::getModel('catalog/category')->load(89);
$_category_url = $_category->getUrl();
?>
```

## Load product by id or sku

```php
<?php
$_product_1 = Mage::getModel('catalog/product')->load(12);
$_product_2 = Mage::getModel('catalog/product')->loadByAttribute('sku','cordoba-classic-6-String-guitar');
?>
```


## Get Configurable product's Child products

```php
<?php
// input is $_product and result is iterating child products
$childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);
?>
```

## Get Configurable product's Children's (simple product) custom attributes

```php
<?php
// input is $_product and result is iterating child products
$conf = Mage::getModel('catalog/product_type_configurable')->setProduct($_product);
$col = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
foreach($col as $simple_product){
	var_dump($simple_product->getId());
}
?>
```

## Log to custom file

```php
<?php Mage::log('Your Log Message', Zend_Log::INFO, 'your_log_file.log'); ?>
```

## Call Static Block

```php
<?php echo $this->getLayout()->createBlock('cms/block')->setBlockId('block-name')->toHtml(); ?>
```

## Add JavaScript to page

First approach: page.xml - you can add something like

```xml
<action method="addJs"><script>path/to/my/file.js</script></action>
```

Second approach: Find `page/html/head.phtml` in your theme and add the code directly to `page.html`.

Third approach: If you look at the stock page.html mentioned above, you'll see this line

```php
<?php echo $this->getChildHtml() ?>
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
<?php $logged_in = Mage::getSingleton('customer/session')->isLoggedIn(); // (boolean) ?>
```

## Get the current category/product/cms page

```php
<?php
$currentCategory = Mage::registry('current_category');
$currentProduct = Mage::registry('current_product');
$currentCmsPage = Mage::registry('cms_page');
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

## Get associated products

In /app/design/frontend/default/site/template/catalog/product/view/type/

``` php
<?php $_helper = $this->helper('catalog/output'); ?>
<?php $_associatedProducts = $this->getAllowProducts() ?>
<?php //var_dump($_associatedProducts); ?>
<br />
<br />
<?php if (count($_associatedProducts)): ?>
	<?php foreach ($_associatedProducts as $_item): ?>
		<a href="<?php echo $_item->getProductUrl() ?>"><?php echo $_helper->productAttribute($_item, $_item->getName(), 'name') ?> | <?php echo $_item->getName() ?> | <?php echo $_item->getPrice() ?></a>
		<br />
		<br />
	<?php endforeach; ?>
<?php endif; ?>
```

## Get An Array of Country Names/Codes in Magento

```php
<?php
$countryList = Mage::getResourceModel('directory/country_collection')
                    ->loadData()
                    ->toOptionArray(false);

    echo '<pre>';
    print_r( $countryList);
    exit('</pre>');
?>
```

## Create a Country Drop Down in the Frontend of Magento

```php
<?php
$_countries = Mage::getResourceModel('directory/country_collection')
                                    ->loadData()
                                    ->toOptionArray(false) ?>
<?php if (count($_countries) > 0): ?>
    <select name="country" id="country">
        <option value="">-- Please Select --</option>
        <?php foreach($_countries as $_country): ?>
            <option value="<?php echo $_country['value'] ?>">
                <?php echo $_country['label'] ?>
            </option>
        <?php endforeach; ?>
    </select>
<?php endif; ?>
```

## Create a Country Drop Down in the Magento Admin

```php
<?php
    $fieldset->addField('country', 'select', array(
        'name'  => 'country',
        'label'     => 'Country',
        'values'    => Mage::getModel('adminhtml/system_config_source_country')->toOptionArray(),
    ));
?>
```

## Return Product Attributes

```php
<?php
$_product->getThisattribute();
$_product->getAttributeText('thisattribute');
$_product->getResource()->getAttribute('thisattribute')->getFrontend()->getValue($_product);
$_product->getData('thisattribute');
// The following returns the option IDs for an attribute that is a multiple-select field:
$_product->getData('color'); // i.e. 456,499
// The following returns the attribute object, and instance of Mage_Catalog_Model_Resource_Eav_Attribute:
$_product->getResource()->getAttribute('color'); // instance of Mage_Catalog_Model_Resource_Eav_Attribute
// The following returns an array of the text values for the attribute:
$_product->getAttributeText('color') // Array([0]=>'red', [1]=>'green')
// The following returns the text for the attribute
if ($attr = $_product->getResource()->getAttribute('color')):
    echo $attr->getFrontend()->getValue($_product); // will display: red, green
endif;
?>
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

## Get Simple Products of a Configurable Product

```php
<?php
if($_product->getTypeId() == "configurable") {
    $ids = $_product->getTypeInstance()->getUsedProductIds();
?>
<ul>
    <?php
    foreach ($ids as $id) {
        $simpleproduct = Mage::getModel('catalog/product')->load($id);
    ?>
        <li>
        	<?php
        	echo $simpleproduct->getName() . " - " . (int)Mage::getModel('cataloginventory/stock_item')->loadByProduct($simpleproduct)->getQty();
        	?>
        </li>
    <?php
    }
    ?>
</ul>
<?php
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

## Getting Configurable Product from Simple Product ID in Magento 1.5+

```php
<?php
$simpleProductId = 465;
$parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
    ->getParentIdsByChild($simpleProductId);
$product = Mage::getModel('catalog/product')->load($parentIds[0]);
echo $product->getId(); // ID = 462 (aka, Parent of 465)
?>
```