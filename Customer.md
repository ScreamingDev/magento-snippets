# Customer

Things about Account, Address, Attributes, Forms, Groups, Observer, Session and Widgets especially for customer.


## Check if customer is logged in

```php
    <?php

    $logged_in = Mage::getSingleton('customer/session')->isLoggedIn(); // (boolean)

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