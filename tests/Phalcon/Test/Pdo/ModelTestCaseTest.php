<?php
namespace Phalcon\Test;

class ModelTestCaseTest extends \PHPUnit_Framework_TestCase
{
    public static $testDi;

    protected function setUp()
    {
        $dbconfig = include __DIR__ . '/../../../db.mysql.config.php';
        self::$testDi = new \Phalcon\DI\FactoryDefault();

        self::$testDi->set('db', function () use ($dbconfig) {
            switch ($dbconfig->database->adapter) {
                case 'Mysql':
                    return new \Phalcon\Db\Adapter\Pdo\Mysql(
                        array
                        (
                            'host' => $dbconfig->database->host,
                            'username' => $dbconfig->database->username,
                            'password' => $dbconfig->database->password,
                            'dbname' => $dbconfig->database->dbname,
                        )
                    );
                    break;
            }
        }, true);
        parent::setUp();
    }

    /**
     * Test creation of model test case with yaml fixture.
     */
    public function testCanCreateModelTestCase()
    {
        require_once __DIR__ . '/ModelTestCaseStub.php';
        $modelTestCase = new \Phalcon\Test\Pdo\ModelTestCaseStub();
        $this->assertInstanceOf(
            'PHPUnit_Extensions_Database_DataSet_YamlDataSet',
            $modelTestCase->getTestCaseDataSet()
        );
    }

    public function testModelSetUpWithDbUnitShouldSetUpFixtures()
    {
        require_once __DIR__ . '/ModelTestCaseStub.php';
        $modelTestCase = new \Phalcon\Test\Pdo\ModelTestCaseStub();
        $modelTestCase->setDI(self::$testDi);
        $modelTestCase->initSetUp();
    }

    /**
     * @expectedException \Phalcon\Test\Pdo\Exception
     * @expectedExceptionMessage Only db objects which inherit Phalcon\Db\Adapter\Pdo are supported.
     */
    public function testUsingNonPdoDbAdapterWithModelTestCaseShouldThrowException()
    {
        $db = new \stdClass();
        self::$testDi = new \Phalcon\DI\FactoryDefault();
        self::$testDi->set('db', $db, true);
        require_once __DIR__ . '/ModelTestCaseStub.php';
        $modelTestCase = new \Phalcon\Test\Pdo\ModelTestCaseStub();
        $modelTestCase->setDI(self::$testDi);
        $modelTestCase->initSetUp();
    }

    /**
     * @expectedException \Phalcon\Test\Exception
     * @expectedExceptionMessage $di property must be setup as instance of \Phalcon\DI through overriding initDi method.
     */
    public function testCreatingModelTestWithoutInitDiShouldThrowException()
    {
        require_once __DIR__ . '/ModelTestCaseStub.php';
        $modelTestCase = new \Phalcon\Test\Pdo\ModelTestCaseStub();
        $modelTestCase->initSetUp();
    }
}
