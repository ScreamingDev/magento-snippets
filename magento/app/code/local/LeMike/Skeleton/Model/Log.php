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
 * @package    Log.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-snippets/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-snippets
 * @since      0.1.0
 */

/**
 * Class Log.
 *
 * @category   Magento Snippets
 * @package
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/magento-snippets/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/magento-snippets
 * @since      0.1.0
 */
class LeMike_Skeleton_Model_Log
{
    /**
     * Name of the extension.
     *
     * Will be used for filename and logging prefix.
     *
     * @var string
     */
    protected static $_prefix = 'LeMike_Skeleton';

    /**
     * Decide when to print the error message (true) or not (false)
     * @var bool
     */
    protected static $_print = false;

    public static function setPrint ( $bool = true )
    {
        self::$_print = $bool;
    }

    /**
     * Send a message to log
     *
     * @param $message
     */
    public static function log ( $message )
    {
        self::_logAdapter($message, Zend_Log::INFO);
    }

    /**
     * Send an error message to the log
     *
     * @param $message
     */
    public static function error ( $message )
    {
        $message = "ERROR! " . $message;
        self::_logAdapter($message, Zend_Log::WARN);
    }

    /**
     * Send a debug message to the log
     *
     * @param string $message
     */
    public static function debug ( $message = "~~~" )
    {
        if ( !is_scalar($message) )
        {
            $message = serialize($message);
        }

        self::_logAdapter($message, Zend_Log::DEBUG);
    }

    protected static function _logAdapter ( $message, $level = null, $file = null, $forceLog = false )
    {
        static $firstInit = true;

        if (!$file)
        {
            $file = self::$_prefix . '.log';
        }

        $message = self::$_prefix . ": " . $message;
        if ( self::$_print )
        {
            echo "\n" . $message . "\n";
        }
        Mage::log($message, $level, $file, $forceLog);
    }
}
