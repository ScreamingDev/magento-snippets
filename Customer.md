# Customer

Things about Account, Address, Attributes, Forms, Groups, Observer, Session and Widgets especially for customer.

## Session

Information about the current customer or visitor of this page.


### Check if customer is logged in

```php
    <?php

    $logged_in = Mage::getSingleton('customer/session')->isLoggedIn(); // (boolean)

    ?>
```

### Get current customer

```php
<?php

    public function getCustomer()
    {
        return Mage::getSingleton('customer/session')->getCustomer();
    }

?>
```

### Login as customer / user in Magento

```
<?php

/** @var $customer Mage_Customer_Model_Customer */
$customer = Mage::getModel('customer/customer');

// get the customer (by Mail)
$customer->loadByEmail('customer@example.org');

/** @var $session Mage_Customer_Model_Session */
$session = Mage::getModel('customer/session');
$session->loginById($customer->getId());

?>
```

## Customer

### Get customer by attribute

Sometimes you need to filter your customers by attribute.
An attribute can be a foreign customer number or other identities.
Take this method or it's body to search for such customer:

```php
<?php

    /**
     * Load customer by attribute
     *
     * @see Mage_Catalog_Model_Abstract::loadByAttribute()
     * 
     * @param Mage_Eav_Model_Entity_Attribute_Interface|integer|string|array $attribute Attribute to filter
     * @param null|string|array                                              $value     Value to meet
     *
     * @return array
     */
    public function loadByAttribute($attribute, $value)
    {
        // Get collection and filter by attribute value pair
        $collection = Mage::getModel('customer/customer')->getCollection()
            ->addAttributeToFilter($attribute, $value);

        $customerSet = array();
        foreach ($collection as $customer)
        {
            /** @var Mage_Customer_Model_Abstract $customer Customer that fits the pattern. */
            if (is_object($customer))
            {
            	// return $customer; // will instantly get the first found customer
            
                // append to array
                $customerSet[] = $customer;
            }
        }
        
        return $customerSet;
    }

```

You received an array with all customer that fit the attribute and value.

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
