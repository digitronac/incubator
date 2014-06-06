<?php
namespace Phalcon\Test\Pdo;

/**
 * Class ModelTestCase.
 *
 * Intended to be used to test Phalcon model classes.
 * Test classes which utilize this component should set up DI component by overriding
 * initDi method and setting $di property.
 *
 * @package Phalcon\Test\Pdo
 */
abstract class ModelTestCase extends \Phalcon\Test\UnitTestCase
{
    /**
     * Prepares database for testing.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
    }

    /**
     * Setup test database and load fixture
     *
     * @throws \Phalcon\Test\Pdo\Exception if PDO is not used.
     *
     * @throws \PHPUnit_Extensions_Database_Operation_Exception rethrow on db error,
     *                                                          usually when previous truncate operation failed
     * @return void
     */
    protected function setupDatabase()
    {
        /* @var $pdo \Phalcon\Db\Adapter\Pdo */
        $pdo = $this->getDI()->get('db');
        if (!$pdo instanceof \Phalcon\Db\Adapter\Pdo) {
            throw new \Phalcon\Test\Pdo\Exception(
                'Only db objects which inherit Phalcon\Db\Adapter\Pdo are supported.'
            );
        }
        $connection = new \PHPUnit_Extensions_Database_DB_DefaultDatabaseConnection($pdo->getInternalHandler());

        $dataSet = $this->getDataSet();

        if ($dataSet instanceof \PHPUnit_Extensions_Database_DataSet_IDataSet) {
            if ($this->isMysql()) {
                $connection->getConnection()->query("SET FOREIGN_KEY_CHECKS = 0");
            }
            $setupOperation = \PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT();
            $setupOperation->execute($connection, $dataSet);
            if ($this->isMysql()) {
                $connection->getConnection()->query("SET FOREIGN_KEY_CHECKS = 1");
            }
        }
    }

    // @codeCoverageIgnoreStart
    /**
     * Returns the test dataset.
     *
     * @return \PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {

    }
    // @codeCoverageIgnoreEnd

    /**
     * Db service is mysql?
     *
     * @return bool
     */
    protected function isMysql()
    {
        return ($this->getDI()->get('db') instanceof \Phalcon\Db\Adapter\Pdo\Mysql);
    }
}
