<?php
namespace Phalcon\Test;

/**
 * Class UnitTestCase.
 * Base test helper class for Phalcon classes that utilize DI component.
 * DI should be set by implementing initDi method which should set $di property.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 *
 * @package Phalcon\Test
 */
abstract class UnitTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Phalcon\DI
     */
    protected $di;

    /**
     * Initial setUp method.
     *
     * @throws \Phalcon\Test\Exception if $di property not set through initDi method
     *
     * @return void
     */
    protected function setUp()
    {
        $this->initDi();
        if (!$this->di instanceof \Phalcon\DI) {
            throw new \Phalcon\Test\Exception(
                '$di property must be setup as instance of \Phalcon\DI through overriding initDi method.'
            );
        }
    }

    /**
     * This method should be overriden to setup $di property.
     *
     * @return mixed
     */
    abstract protected function initDi();

    /**
     * DI getter method.
     *
     * @return \Phalcon\DI
     */
    protected function getDI()
    {
        return $this->di;
    }

    /**
     * Reset di.
     *
     * @return void
     */
    protected function tearDown()
    {
        $this->getDI()->reset();
        // reset static DI too!
        \Phalcon\DI::reset();
    }
}
