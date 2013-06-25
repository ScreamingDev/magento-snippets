<?php
/**
 * Class DoubleTest.
 *
 * @category   UnitTest
 * @since      0.1.0
 */
class Acme_UnitTest_Test_Double_ProductTest extends Acme_UnitTest_Test_AbstractCase
{
    public function testRandom()
    {
        $product1 = new Acme_UnitTest_Test_Double_Product_Random();
        $product2 = new Acme_UnitTest_Test_Double_Product_Random();

        $this->assertNotNull($product1->getId());
        $this->assertNotNull($product2->getId());

    }
}
