<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   magento-snippets
 * @package    AbstractTest.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-snippets/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-snippets
 * @since      0.1.0
 */

/**
 * Class AbstractTest.
 *
 * @category   magento-snippets
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-snippets/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-snippets
 * @since      0.1.0
 */
abstract class LeMike_Skeleton_Test_AbstractCase extends EcomDev_PHPUnit_Test_Case
{
    const FRONTEND_CLASS = '';


    public function getFrontend()
    {
        $frontend = static::FRONTEND_CLASS;

        return new $frontend;
    }

    public function testSelf()
    {
        $this->assert
    }


    public function testBlackbox()
    {
        $class = new ReflectionClass('Some_Class');
        $protected = $class->getProperty('_someProtected');
        $protected->setAccessible(true);
        $this->assertInternalType('string', $protected->getValue(new Some_Class));
    }
}
