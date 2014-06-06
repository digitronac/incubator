<?php
namespace Phalcon\Test\Pdo;

/**
 * Class FunctionalTestCase.
 * Intended to be used with Phalcon MVC Application as helper for integration/controller testing.
 * Protected $application property should be defined by classes which override this class.
 * DI component should be set up by implementing initDi method and setting up $di property.
 *
 * @package Phalcon\Test\Pdo
 */
abstract class FunctionalTestCase extends \Phalcon\Test\Pdo\ModelTestCase
{
    /**
     * @var \Phalcon\Mvc\Application
     */
    protected $application;


    /**
     * SetUp method.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Ensures that each test has it's own DI and all globals are purged
     *
     * @return void
     */
    protected function tearDown()
    {
        parent::tearDown();

        // reset superglobals
        $_SESSION = array();
        $_GET = array();
        $_POST = array();
        $_COOKIE = array();
        $_REQUEST = array();
    }

    /**
     * Dispatches a given url and sets the response object accordingly.
     *
     * @param string $url The request url
     *
     * @throws \Phalcon\Test\Pdo\Exception in case $this->application is not Phalcon app
     *
     * @return void
     */
    protected function dispatch($url)
    {
        if (!$this->application instanceof \Phalcon\Mvc\Application) {
            throw new \Phalcon\Test\Pdo\Exception(
                '$application property must be set as instance of Phalcon\Mvc\Application.'
            );
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd
        $this->getDI()->set('response', $this->application->handle($url), true);
    // buggy xdebug coverage?
    // @codeCoverageIgnoreStart
    }
    // @codeCoverageIgnoreEnd

    /**
     * Assert that the last dispatched controller matches the given controller class name.
     *
     * @param string $expected The expected controller name
     *
     * @throws \PHPUnit_Framework_ExpectationFailedException on failure
     *
     * @return void
     */
    public function assertController($expected)
    {
        $actual = $this->getDI()->get('dispatcher')->getControllerName();
        if ($actual != $expected) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                sprintf(
                    'Failed asserting Controller name "%s", actual Controller name is "%s".',
                    $expected,
                    $actual
                )
            );
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd

        $this->assertEquals($expected, $actual);
    }

    /**
     * Assert that the last dispatched action matches the given action name.
     *
     * @param string $expected The expected action name
     *
     * @throws \PHPUnit_Framework_ExpectationFailedException on failure
     *
     * @return void
     */
    public function assertAction($expected)
    {
        $actual = $this->getDI()->get('dispatcher')->getActionName();
        if ($actual != $expected) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                sprintf(
                    'Failed asserting Action name "%s", actual Action name is "%s".',
                    $expected,
                    $actual
                )
            );
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd
        $this->assertEquals($expected, $actual);
    }

    /**
     * Assert that the response headers contains the given array.
     * <code>
     * $expected = array('Content-Type' => 'application/json')
     * </code>
     *
     * @param array $expected The expected headers
     *
     * @throws \PHPUnit_Framework_ExpectationFailedException on failure
     *
     * @return void
     */
    public function assertHeader(array $expected)
    {
        foreach ($expected as $expectedField => $expectedValue) {
            $actualValue = $this->getDI()->get('response')->getHeaders()->get($expectedField);
            if ($actualValue != $expectedValue) {
                throw new \PHPUnit_Framework_ExpectationFailedException(
                    sprintf(
                        'Failed asserting "%s" has a value of "%s", actual "%s" header value is "%s".',
                        $expectedField,
                        $expectedValue,
                        $expectedField,
                        $actualValue
                    )
                );
            // dead code!
            // @codeCoverageIgnoreStart
            }
            // @codeCoverageIgnoreEnd
            $this->assertEquals($expectedValue, $actualValue);
        }
    }

    /**
     * Asserts that the response code matches the given one.
     *
     * @param string $expected the expected response code
     *
     * @throws \PHPUnit_Framework_ExpectationFailedException on failure
     *
     * @return void
     */
    public function assertResponseCode($expected)
    {
        // convert to string if int
        if (is_integer($expected)) {
            $expected = (string) $expected;
        }

        $actualValue = $this->getDI()->get('response')->getHeaders()->get('Status');

        if (!$actualValue || (stristr($actualValue, $expected) === false)) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                sprintf(
                    'Failed asserting response code is "%s", actual response status is "%s".',
                    $expected,
                    $actualValue
                )
            );
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd
        $this->assertContains($expected, $actualValue);
    }

    /**
     * Asserts that the dispatch is forwarded.
     *
     * @throws \PHPUnit_Framework_ExpectationFailedException on failure
     *
     * @return void
     */
    public function assertDispatchIsForwarded()
    {
        /* @var $dispatcher \Phalcon\Mvc\Dispatcher */
        $dispatcher = $this->getDI()->get('dispatcher');
        $actual = $dispatcher->wasForwarded();

        if (!$actual) {
            throw new \PHPUnit_Framework_ExpectationFailedException('Failed asserting dispatch was forwarded.');
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd

        $this->assertTrue($actual);
    }

    /**
     * Assert location redirect.
     *
     * @param string $location location
     *
     * @throws \PHPUnit_Framework_ExpectationFailedException on failure
     *
     * @return void
     */
    public function assertRedirectTo($location)
    {
        $actualLocation = $this->getDI()->get('response')->getHeaders()->get('Location');

        if (!$actualLocation) {
            throw new \PHPUnit_Framework_ExpectationFailedException('Failed asserting response caused a redirect.');
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd

        if ($actualLocation !== $location) {
            throw new \PHPUnit_Framework_ExpectationFailedException(
                sprintf(
                    'Failed asserting response redirects to "%s". It redirects to "%s".',
                    $location,
                    $actualLocation
                )
            );
        // dead code!
        // @codeCoverageIgnoreStart
        }
        // @codeCoverageIgnoreEnd

        $this->assertEquals($location, $actualLocation);
    }

    /**
     * Convenience method to retrieve response content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->getDI()->get('response')->getContent();
    }

    /**
     * Assert response content contains string.
     *
     * @param string $string content
     *
     * @return void
     */
    public function assertResponseContentContains($string)
    {
        $this->assertContains($string, $this->getContent());
    }
}
