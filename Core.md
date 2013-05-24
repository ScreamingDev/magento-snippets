# Core

Stuff like Blocks (Html, Store, Template, Text, Profiler),
Controller (Front Controller, Request, Response, Varien),
Helper (Cookie, File, Hint, Http, Js, String, Translate, Url),
and plenty Model (App, Cache, Calculate, Convert, Date, Locale, Observer, Store, Website etc.).

- [Get the current URL in Magento] (#get-the-current-url-in-magento)
- [getBaseUrl - Magento URL Path] (#getbaseurl--magento-url-path)
- [Add JavaScript to page] (#add-javascript-to-page)

## Get The Current URL In Magento

```php
<?php echo Mage::helper('core/url')->getCurrentUrl(); ?>
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

## See what event has been dispatched

If you want to see all events that has been thrown for the site you look at or until the point you are,
you need to core hack \Mage_Core_Model_App::dispatchEvent and add logging to the very start of the method:

```
function dispatchEvent($eventName, $args = array())
{
    echo $eventName;
    // ...
```

Or something like `var_dump` etc. to get all dispatched events.
Upcoming are not included.


## Cache

### Using Mage_Core_Model_Cache

Using possible options:

```php
<?php

// default options (no need to provide this array, just an example what is possible)
$options = array(
    'id_prefix'     => '',    // this_is_unique_42
    'prefix'        => '',    // some_prefix_
    'disallow_save' => false, // be volatile instead of persistent
    // 'request_processors' => ...
);

$cache = Mage::getModel('core/cache');

?>
```

#### CRUD

```php
<?php

$cache = Mage::getModel('core/cache');
$theData = 'foo';

/*
 * Create / Update
 */
$cache->save($theData, 'id_the_data');
// $cache->save($theData, 'id_the_data', array('some', 'tags'), $lifetime);

/*
 * Read
 */
$tmp = $cache->load('id_the_data');

/*
 * Delete
 */

// single
$cache->remove('id_the_data');

// multiple by tag
$cache->clean(array('some', 'tags'));

// all
$cache->clean();

?>

```

More possibilities by using `$cache->getFrontend()` which gives access to Zend_Cache.
