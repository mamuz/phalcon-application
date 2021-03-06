<?php

class CommandLineCest
{
    /** @var array */
    private $config = [
        'dispatcher' => [
            'taskDefaultNamespace' => 'StubProject\Task',
        ],
        'services'   => [
            'stdClass'     => 'StubProject\Service\StdClass',
            'stdClass2'    => 'StubProject\Service\StdClass2',
            'errorHandler' => 'StubProject\Service\ErrorHandler',
        ],
        'customKey'  => true,
    ];

    /** @var array */
    private $arguments;

    /** @var string */
    private $forcedExpection;

    /**
     * @param FunctionalTester $tester
     */
    public function _after(FunctionalTester $tester)
    {
        $task = $action = 'main';
        $helpDoc = $arguments = [];

        foreach ($this->arguments as $k => $arg) {
            if ($k == 0) {
                $helpDoc[] = $task = $arg;
            } elseif ($k == 1) {
                $helpDoc[] = $action = $arg;
            } elseif ($k >= 2) {
                $helpDoc[] = $arguments[] = $arg;
            }
        }

        $tester->comment('`> php index.php ' . implode(' ', $helpDoc) . '`');

        if ($this->forcedExpection) {
            $expected = $this->forcedExpection;
        } else {
            $expected = $this->config['dispatcher']['taskDefaultNamespace'] . '\\'
                . ucfirst($task) . '::' . $action . 'Action('
                . implode(', ', $arguments)
                . ')';
        }
        
        ob_start();
        $tester->execute(function () {
            try {
                \Phapp\Application\Bootstrap::init($this->config)->runApplicationOn(
                    ['argv' => array_merge([__FILE__], $this->arguments)]
                );
            } catch (\Throwable $e) {
                echo $e->getMessage();
            }
        });
        $tester->assertSame($expected, ob_get_clean());

        $this->arguments = [];
        $this->forcedExpection = null;
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callDefaultActionInDefaultTask(FunctionalTester $tester)
    {
        $this->arguments = [];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInDefaultTask(FunctionalTester $tester)
    {
        $this->arguments = ['main', 'custom'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInDefaultTaskWithArguments(FunctionalTester $tester)
    {
        $this->arguments = ['main', 'argument', 12, 'foo', 'bar'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInDefaultTaskWithServiceAccess(FunctionalTester $tester)
    {
        $this->arguments = ['main', 'service'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callDefaultActionInCustomTask(FunctionalTester $tester)
    {
        $this->arguments = ['custom'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTask(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'custom'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTaskWithArguments(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'argument', 12, 'foo', 'bar'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTaskWithServiceAccess(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'service'];
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionWichThrowsAnException(FunctionalTester $tester)
    {
        $this->forcedExpection = 'error';
        $this->arguments = ['main', 'error'];
    }


    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTaskWithNormalizedArguments(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'option', 'arg', '--first=12', '--second=foo'];
    }
}
