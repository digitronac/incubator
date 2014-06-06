<?php
namespace Phalcon\Test\Pdo;

class FunctionalTestCaseTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException \Phalcon\Test\Pdo\Exception
     * @expectedExceptionMessage $application property must be set as instance of Phalcon\Mvc\Application.
     */
    public function testFunctionalTestCaseWithoutApplicationSetShouldThrowException()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $stub->setInitDi(self::$testDi);
        $stub->initSetUp();
        $stub->doDispatch();
    }

    /**
     * @expectedException \Phalcon\DI\Exception
     * @expectedExceptionMessage Service 'view' was not found in the dependency injection container
     */
    public function testFunctionalTestCaseDispatchShouldThrowNoViewException()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $stub->setInitDi(self::$testDi);
        $stub->setApplication(new \Phalcon\Mvc\Application(self::$testDi));
        $stub->initSetUp();
        $stub->doDispatch();
    }

    public function testFunctionalTestCaseTearDownShouldResetDi()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $stub->setInitDi(self::$testDi);
        $stub->setApplication(new \Phalcon\Mvc\Application(self::$testDi));
        $stub->initSetUp();
        $stub->initTearDown();
        $this->assertNull(\Phalcon\DI::getDefault());
    }

    public function testAssertControllerMethodShouldReturnNullIfMatch()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->setMethods(array('getControllerName'))
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('getControllerName')
            ->will($this->returnValue('index'));
        $di->set('dispatcher', $dispatcher, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertController('index'));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting Controller name "index", actual Controller name is "index1".
     */
    public function testAssertControllerMethodShouldThrowExceptionIfFailed()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->setMethods(array('getControllerName'))
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('getControllerName')
            ->will($this->returnValue('index1'));
        $di->set('dispatcher', $dispatcher, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $stub->assertController('index');
    }

    public function testAssertActionMethodShouldReturnNullIfMatch()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->setMethods(array('getActionName'))
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('getActionName')
            ->will($this->returnValue('index'));
        $di->set('dispatcher', $dispatcher, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertAction('index'));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting Action name "index", actual Action name is "index1".
     */
    public function testAssertActionMethodShouldThrowExceptionIfFailed()
    {
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->setMethods(array('getActionName'))
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('getActionName')
            ->will($this->returnValue('index1'));
        $di->set('dispatcher', $dispatcher, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $stub->assertAction('index');
    }

    public function testAssertHeaderMethodShouldReturnNullIfOK()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue('testheader'));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertHeader(array('test' => 'testheader')));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting "test" has a value of "testheader", actual "test" header value is "testheader1".
     */
    public function testAssertHeaderMethodShouldThrowExceptionIfFailed()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue('testheader1'));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $stub->assertHeader(array('test' => 'testheader'));
    }

    public function testAssertResponseCodeMethodShouldReturnNullIfOK()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue('200'));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertResponseCode(200));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting response code is "201", actual response status is "200".
     */
    public function testAssertResponseCodeMethodShouldThrowExceptionIfFailed()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue('200'));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertResponseCode(201));
    }

    public function testAssertDispatchWasForwardedMethodShouldReturnNullIfOK()
    {
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->setMethods(array('wasForwarded'))
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('wasForwarded')
            ->will($this->returnValue(true));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('dispatcher', $dispatcher, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertDispatchIsForwarded());
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting dispatch was forwarded.
     */
    public function testAssertDispatchWasForwardedMethodShouldThrowExceptionIfFailed()
    {
        $dispatcher = $this->getMockBuilder('Phalcon\Mvc\Dispatcher')
            ->setMethods(array('wasForwarded'))
            ->getMock();
        $dispatcher->expects($this->once())
            ->method('wasForwarded')
            ->will($this->returnValue(false));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('dispatcher', $dispatcher, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $stub->assertDispatchIsForwarded();
    }

    public function testAssertRedirectToMethodShouldReturnNullIfOK()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue('location'));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertRedirectTo('location'));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting response redirects to "location". It redirects to "location1".
     */
    public function testAssertRedirectToMethodShouldThrowExceptionIfFailed()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue('location1'));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertRedirectTo('location'));
    }

    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting response caused a redirect.
     */
    public function testAssertRedirectToMethodShouldThrowExceptionIfNoRedirect()
    {
        $headers = $this->getMockBuilder('Phalcon\Http\Response\Headers')
            ->setMethods(array('get'))
            ->getMock();
        $headers->expects($this->once())
            ->method('get')
            ->will($this->returnValue(''));
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getHeaders'))
            ->getMock();
        $response->expects($this->once())
            ->method('getHeaders')
            ->will($this->returnValue($headers));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertNull($stub->assertRedirectTo('location'));
    }

    public function testGetContentMethodShouldNotThrowException()
    {
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getContent'))
            ->getMock();
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('content'));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $this->assertEquals('content', $stub->getContent());
    }

    public function testAssertResponseContentContainsMethodShouldNotThrowException()
    {
        $response = $this->getMockBuilder('Phalcon\Http\Response')
            ->setMethods(array('getContent'))
            ->getMock();
        $response->expects($this->once())
            ->method('getContent')
            ->will($this->returnValue('content'));
        include_once __DIR__ . '/FunctionalTestCaseStub.php';
        $stub = new \Phalcon\Test\Pdo\FunctionalTestCaseStub();
        $di = self::$testDi;
        $di->set('response', $response, true);
        $stub->setInitDi($di);
        $stub->initSetUp();
        $stub->assertResponseContentContains('ntent');
    }
} 