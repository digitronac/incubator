<?php
namespace Phalcon\Test\Pdo;

class ModelTestCaseStub extends \Phalcon\Test\Pdo\ModelTestCase
{
    protected $diInit;

    /**
     * Init setUp method.
     */
    public function initSetUp()
    {
        parent::setUp();
    }

    /**
     * Init tearDown.
     */
    public function initTearDown()
    {
        $this->tearDown();
    }

    /**
     * Returns the test dataset.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    protected function getDataSet()
    {
        return new \PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            __DIR__ . '/_files/yamlfixture.yaml.php'
        );
    }

    /**
     * Helper method used for testing.
     *
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getTestCaseDataSet()
    {
        return $this->getDataSet();
    }

    /**
     * This method should be overriden to setup $di property.
     *
     * @return mixed
     */
    protected function initDi()
    {
        $this->di = $this->diInit;
    }

    public function setDI($di) {
        $this->diInit = $di;
    }

    protected function tearDown()
    {
        parent::tearDown();
    }
}
