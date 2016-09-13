<?php

class ActionDomainResponseCest
{
    /** @var array */
    private $config = [
        'dispatcher' => [
            'controllerDefaultNamespace' => 'StubProject\Controller',
        ],
        'routes'     => [
            'default'           => [
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
            'static'            => [
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

    /** @var string */
    private $expectedController;

    /** @var string */
    private $expectedAction;

    /** @var string */
    private $request;

    /**
     * @param FunctionalTester $tester
     */
    public function _after(FunctionalTester $tester)
    {
        $requestInfo = explode(' ', $this->request);
        $_SERVER['REQUEST_METHOD'] = $requestInfo[0];
        $_SERVER['REQUEST_URI'] = isset($requestInfo[1]) ? $requestInfo[1] : '';

        $tester->comment('`HTTP ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI'] . '`');

        $expected = $this->config['dispatcher']['controllerDefaultNamespace'] . '\\'
            . ucfirst($this->expectedController) . '::' . lcfirst($this->expectedAction) . 'Action()';

        ob_start();
        $tester->execute(function () {
            $bootstrapper = new \Phapp\Application\Bootstrap($this->config, false);
            $bootstrapper->runApplicationOn([]);
        });
        $tester->assertSame($expected, ob_get_clean());

        $this->request = null;
        unset($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestDefaultActionInDefaultController(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'index';
        $this->request = 'GET /';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestDefaultActionInDefaultControllerWihtoutTrailingSlashInRequestUri(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'index';
        $this->request = 'GET';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInDefaultController(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'custom';
        $this->request = 'GET /index/custom';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInDefaultControllerWithTrailingSlashInRequestUri(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'custom';
        $this->request = 'GET /index/custom/';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInDefaultControllerWithServiceAccess(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'service';
        $this->request = 'GET /index/service';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestDefaultActionInCustomController(FunctionalTester $tester)
    {
        $this->expectedController = 'custom';
        $this->expectedAction = 'index';
        $this->request = 'GET /custom';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestCustomActionInCustomController(FunctionalTester $tester)
    {
        $this->expectedController = 'custom';
        $this->expectedAction = 'custom';
        $this->request = 'GET /custom/custom';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function requestWithInvalidMethod(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'index';
        $this->request = 'POST /custom/custom';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function postToStaticRoute(FunctionalTester $tester)
    {
        $this->expectedController = 'custom';
        $this->expectedAction = 'post';
        $this->request = 'POST /custompost';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function forwardAction(FunctionalTester $tester)
    {
        $this->expectedController = 'index';
        $this->expectedAction = 'custom';
        $this->request = 'GET /index/forward';
    }
}
