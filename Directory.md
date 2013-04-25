# Directory

## Get an array of country names/codes in Magento

```php
    <?php

    $countryList = Mage::getResourceModel('directory/country_collection')
                        ->loadData()
                        ->toOptionArray(false);

    print_r( $countryList);

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