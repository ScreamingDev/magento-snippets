* auto-gen TOC:
{:toc}

# Catalog

Whatever could be useful for Mage Catalog and it's known childs can be found here.

## Product

### Return product attributes

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


### Get configurable products child products

```php
    <?php

    // input is $_product and result is iterating child products
    $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);

    ?>
```

### Get configurable products children (simple product) custom attributes

```php
    <?php

    // input is $_product and result is iterating child products
    $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($_product);
    $col = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();

    foreach($col as $simple_product)
    {
        var_dump($simple_product->getId());
    }

    ?>
```

### Get associated products

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

### Get simple products of a configurable product

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


### Getting Configurable Product from Simple Product ID in Magento 1.5+

```php
    <?php

    $simpleProductId = 465;
    $parentIds = Mage::getResourceSingleton('catalog/product_type_configurable')
        ->getParentIdsByChild($simpleProductId);
    $product = Mage::getModel('catalog/product')->load($parentIds[0]);
    echo $product->getId(); // ID = 462 (aka, Parent of 465)

    ?>
```


## Category

### Load category by id

```php
    <?php

    $_category = Mage::getModel('catalog/category')->load(89);
    $_category_url = $_category->getUrl();

    ?>
```

