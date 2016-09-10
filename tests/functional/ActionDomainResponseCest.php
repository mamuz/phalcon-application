<?php

class ActionDomainResponseCest
{
    /** @var array */
    private $config = [
        'dispatcher' => [
            'controllerDefaultNamespace' => 'StubProject\Controller',
        ],
        'routes'     => [
            'default' => [
                'pattern'     => '/:controller/:action',
                'paths'       => [
                    'controller' => 1,
                    'action'     => 2,
                ],
                'httpMethods' => ['GET'],
            ],
            'defaultController' => [
                'pattern'     => '/:controller',
                'paths'       => [
                    'controller' => 1,
                ],
                'httpMethods' => ['GET'],
            ],
            'custom' => [
                'pattern'     => '/custompost',
                'paths'       => [
                    'controller' => 'custom',
                    'action'     => 'post',
                ],
                'httpMethods' => ['POST'],
            ],
        ],
        'services'   => [
            'stdClass'  => 'StubProject\Service\StdClass',
            'stdClass2' => 'StubProject\Service\StdClass2',
        ],
        'customKey'  => true,
    ];

    /** @var \Phapp\Application\Bootstrap */
    private $bootstrapper;

    /** @var string */
    private $expectedController;

    /** @var string */
    private $expectedAction;

    /**
     * @param FunctionalTester $tester
     */
    public function _before(FunctionalTester $tester)
    {
        unset($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
        $this->bootstrapper = new \Phapp\Application\Bootstrap($this->config, false);
        ob_start();
    }

    /**
     * @param FunctionalTester $tester
     */
    public function _after(FunctionalTester $tester)
    {
        $expected = $this->config['dispatcher']['controllerDefaultNamespace'] . '\\'
            . ucfirst($this->expectedController) . '::' . lcfirst($this->expectedAction) . 'Action()';

        $tester->assertSame($expected, ob_get_clean());
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestDefaultActionInDefaultController(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'index';

        $this->prepareRequestTo('/');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestDefaultActionInDefaultControllerWihtoutTrailingSlashInRequestUri(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'index';

        $this->prepareRequestTo('');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInDefaultController(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'foo';

        $this->prepareRequestTo('/index/foo');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInDefaultControllerWithTrailingSlashInRequestUri(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'foo';

        $this->prepareRequestTo('/index/foo/');
        $this->sendRequestBy($tester);
    }
    
    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInDefaultControllerWithServiceAccess(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'foo';

        $this->prepareRequestTo('/index/foo');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestDefaultActionInCustomController(FunctionalTester $tester)
    {
        $this->expectedController = 'custom';
        $this->expectedAction = 'index';

        $this->prepareRequestTo('/custom/');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInCustomController(FunctionalTester $tester)
    {
        $this->expectedController = 'custom';
        $this->expectedAction = 'foo';

        $this->prepareRequestTo('/custom/foo');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestWithInvalidMethod(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'index';

        $this->prepareRequestTo('/custom/foo', 'POST');
        $this->sendRequestBy($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function postToStaticRoute(FunctionalTester $tester)
    {
        $this->expectedController = 'custom';
        $this->expectedAction = 'post';

        $this->prepareRequestTo('/custompost', 'POST');
        $this->sendRequestBy($tester);
    }

    /**
     * @param string $uri
     * @param string $method
     */
    private function prepareRequestTo($uri, $method = 'GET')
    {
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;
    }

    /**
     * @param FunctionalTester $tester
     */
    private function sendRequestBy(FunctionalTester $tester)
    {
        $tester->execute(function () {
            $this->bootstrapper->runApplicationOn([]);
        });
    }
}
