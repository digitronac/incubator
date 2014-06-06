<?php
namespace Phalcon\Test\Pdo;

class FunctionalTestCaseStub extends \Phalcon\Test\Pdo\FunctionalTestCase
{

    /**
     * This method should be overriden to setup $di property.
     *
     * @return mixed
     */
    protected function initDi()
    {

    }

    public function setApplication(\Phalcon\Mvc\Application $application)
    {
        $this->application = $application;
    }

    public function setInitDi($di)
    {
        $this->di = $di;
    }

    public function initSetUp()
    {
        $this->setUp();
    }

    public function initTearDown()
    {
        $this->tearDown();
    }

    public function doDispatch()
    {
        return $this->dispatch('/');
    }

    public function getApplicationDI()
    {
        return $this->application->getDI();
    }
}