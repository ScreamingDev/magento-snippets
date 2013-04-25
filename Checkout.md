# Checkout

Some snippets for the Cart, Multishipping, Onepage / Checkout and Agreements.

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

# Add product (by id)

```php
<?php

$productId = 98;

/* @var $cart Mage_Checkout_Model_Cart */
$cart = Mage::getModel('checkout/cart');

if ( in_array($productId, $cart->getQuoteProductIds()) )
{ // in cart: do something different
    // ...
}

// add
$cart->addProduct($productId);

?>
```