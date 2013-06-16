<?php

/**
 * Class Random.
 *
 * @category   UnitTest
 * @since      0.1.0
 */
class Acme_UnitTest_Test_Double_Product_Random extends Mage_Catalog_Model_Product
{
    public function __construct()
    {
        parent::__construct();

        /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = $this->getCollection();
        $collection->getSelect()->order('RAND()');
        $collection->load();

        $this->load($collection->getFirstItem()->getId());
    }
}
