<?php

class ViewCest
{
    /** @var string */
    private $outerframeViewTemplate = CODECEPT_DATA_DIR . '/StubViewProject/view/index.phtml';

    /** @var string */
    private $outerframeNullViewTemplate = CODECEPT_DATA_DIR . '/StubViewProject/view/null.phtml';

    /** @var array */
    private $config = [
        'dispatcher' => [
            'controllerDefaultNamespace' => 'StubViewProject\Controller',
        ],
        'routes'     => [
            'default' => [
                'pattern' => '/:controller/:action',
                'paths'   => [
                    'controller' => 1,
                    'action'     => 2,
                ],
            ],
            'module'  => [
                'pattern' => '/admin/:controller/:action',
                'paths'   => [
                    'namespace'  => 'StubViewProject\Controller\Admin',
                    'controller' => 1,
                    'action'     => 2,
                ],
            ],
            'moduleDefault'  => [
                'pattern' => '/admin',
                'paths'   => [
                    'namespace'  => 'StubViewProject\Controller\Admin',
                    'controller' => 'index',
                    'action'     => 'index',
                ],
            ],
        ],
        'view'       => [
            'templatePath' => CODECEPT_DATA_DIR . '/StubViewProject/view/',
        ],
        'services'   => [
            'stdClass' => 'StubProject\Service\StdClass',
        ],
    ];

    /** @var string */
    private $expectedOutput;

    /** @var string */
    private $request;

    /** @var bool */
    private $requestIsXhr = false;

    /**
     * @param FunctionalTester $tester
     */
    public function _after(FunctionalTester $tester)
    {
        $_SERVER['REQUEST_URI'] = $this->request;
        if ($this->requestIsXhr) {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        }

        $tester->comment('`HTTP GET ' . $_SERVER['REQUEST_URI'] . '`');

        ob_start();
        $tester->execute(function () {
            $bootstrapper = new \Phapp\Application\Bootstrap($this->config, false);
            $bootstrapper->runApplicationOn([]);
        });
        $tester->assertSame($this->expectedOutput, ob_get_clean());

        $this->requestIsXhr = false;
        $this->request = null;
        unset($_SERVER['REQUEST_URI']);

        if (file_exists($this->outerframeNullViewTemplate)) {
            rename($this->outerframeNullViewTemplate, $this->outerframeViewTemplate);
        }

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        }
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeStartpage(FunctionalTester $tester)
    {
        $this->request = '';
        $this->expectedOutput = '<outerframe><frame controller="index"><innerframe action="index">'
            . 'StubViewProject\Controller\Index::indexAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeStartpageWithoutOuterframe(FunctionalTester $tester)
    {
        $this->request = '';
        $this->expectedOutput = '<frame controller="index"><innerframe action="index">'
            . 'StubViewProject\Controller\Index::indexAction()'
            . "</innerframe>\n</frame>\n";

        rename($this->outerframeViewTemplate, $this->outerframeNullViewTemplate);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeStartpageAsAjaxRequest(FunctionalTester $tester)
    {
        $this->request = '';
        $this->requestIsXhr = true;
        $this->expectedOutput = '<innerframe action="index">'
            . 'StubViewProject\Controller\Index::indexAction()'
            . "</innerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeCustomPageWithIndexFrame(FunctionalTester $tester)
    {
        $this->request = '/index/custom';
        $this->expectedOutput = '<outerframe><frame controller="index"><innerframe action="custom">'
            . 'StubViewProject\Controller\Index::customAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seePickedPageWithIndexFrame(FunctionalTester $tester)
    {
        $this->request = '/index/pick';
        $this->expectedOutput = '<outerframe><frame controller="index"><innerframe action="custom">'
            . 'StubViewProject\Controller\Index::pickAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeNoPageWithIndexFrame(FunctionalTester $tester)
    {
        $this->request = '/index/noview';
        $this->expectedOutput = '<outerframe><frame controller="index">' . "</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeServicePageWithIndexFrame(FunctionalTester $tester)
    {
        $this->request = '/index/service';
        $this->expectedOutput = '<outerframe><frame controller="index"><innerframe action="service">'
            . 'StubViewProject\Controller\Index::serviceAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeReturnValueWithoutInnerFrame(FunctionalTester $tester)
    {
        $this->request = '/index/return';
        $this->expectedOutput = '<outerframe><frame controller="index">' . "</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeResponseContentWithoutAnyFrame(FunctionalTester $tester)
    {
        $this->request = '/index/response';
        $this->expectedOutput = 'StubViewProject\Controller\Index::responseAction()';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeEchoValueWithoutAnyFrame(FunctionalTester $tester)
    {
        $this->request = '/index/response';
        $this->expectedOutput = 'StubViewProject\Controller\Index::responseAction()';
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeDefaultPageWithCustomFrame(FunctionalTester $tester)
    {
        $this->request = '/custom/index';
        $this->expectedOutput = '<outerframe><frame controller="custom"><innerframe action="index">'
            . 'StubViewProject\Controller\Custom::indexAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeCustomPageWithCustomFrame(FunctionalTester $tester)
    {
        $this->request = '/custom/custom';
        $this->expectedOutput = '<outerframe><frame controller="custom"><innerframe action="custom">'
            . 'StubViewProject\Controller\Custom::customAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seePickedPageWithCustomFrame(FunctionalTester $tester)
    {
        $this->request = '/custom/pick';
        $this->expectedOutput = '<outerframe><frame controller="custom"><innerframe action="custom">'
            . 'StubViewProject\Controller\Custom::pickAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeNoPageWithCustomFrame(FunctionalTester $tester)
    {
        $this->request = '/custom/noview';
        $this->expectedOutput = '<outerframe><frame controller="custom">' . "</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seePageWithoutFrame(FunctionalTester $tester)
    {
        $this->request = '/non-layout/index';
        $this->expectedOutput = '<outerframe><innerframe action="index">'
            . 'StubViewProject\Controller\NonLayout::indexAction()'
            . "</innerframe>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeDefaultPageInAdminModule(FunctionalTester $tester)
    {
        $this->request = '/admin';
        $this->expectedOutput = '<outerframe><frame controller="admin"><innerframe action="index">'
            . 'StubViewProject\Controller\Admin\Index::indexAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeCustomPageInAdminModule(FunctionalTester $tester)
    {
        $this->request = '/admin/index/custom';
        $this->expectedOutput = '<outerframe><frame controller="admin"><innerframe action="custom">'
            . 'StubViewProject\Controller\Admin\Index::customAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function seeDefaultPageInCustomControllerOfAdminModule(FunctionalTester $tester)
    {
        $this->request = '/admin/custom/index';
        $this->expectedOutput = '<outerframe><frame controller="admin"><innerframe action="index">'
            . 'StubViewProject\Controller\Admin\Custom::indexAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }

    /**
     * @param FunctionalTester $tester
     */
    public function forwardAction(FunctionalTester $tester)
    {
        $this->request = '/index/forward';
        $this->expectedOutput = '<outerframe><frame controller="index"><innerframe action="custom">'
            . 'StubViewProject\Controller\Index::customAction()'
            . "</innerframe>\n</frame>\n</outerframe>\n";
    }
}
