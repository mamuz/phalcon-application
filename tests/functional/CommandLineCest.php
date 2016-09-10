<?php

class CommandLineCest
{
    /** @var array */
    private $config = [
        'dispatcher' => [
            'taskDefaultNamespace' => 'StubProject\Task',
        ],
        'services'   => [
            'stdClass'  => 'StubProject\Service\StdClass',
            'stdClass2' => 'StubProject\Service\StdClass2',
        ],
        'customKey'  => true,
    ];

    /** @var array */
    private $arguments;

    /**
     * @param FunctionalTester $tester
     */
    public function _before(FunctionalTester $tester)
    {
        $this->arguments = [];
        ob_start();
    }

    /**
     * @param FunctionalTester $tester
     */
    public function _after(FunctionalTester $tester)
    {
        $task = $action = 'main';
        $arguments = [];

        foreach ($this->arguments as $k => $arg) {
            if ($k == 0) {
                $task = $arg;
            } elseif ($k == 1) {
                $action = $arg;
            } elseif ($k >= 2) {
                $arguments[] = $arg;
            }
        }

        $expected = $this->config['dispatcher']['taskDefaultNamespace'] . '\\'
            . ucfirst($task) . '::' . $action . 'Action('
            . implode(', ', $arguments)
            . ')';

        $tester->assertSame($expected, ob_get_clean());
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callDefaultActionInDefaultTask(FunctionalTester $tester)
    {
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInDefaultTask(FunctionalTester $tester)
    {
        $this->arguments = ['main', 'foo'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInDefaultTaskWithArguments(FunctionalTester $tester)
    {
        $this->arguments = ['main', 'bar', 12, 'bar', 'baz'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInDefaultTaskWithServiceAccess(FunctionalTester $tester)
    {
        $this->arguments = ['main', 'baz'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callDefaultActionInCustomTask(FunctionalTester $tester)
    {
        $this->arguments = ['custom'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTask(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'foo'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTaskWithArguments(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'bar', 12, 'bar', 'baz'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    public function callCustomActionInCustomTaskWithServiceAccess(FunctionalTester $tester)
    {
        $this->arguments = ['custom', 'baz'];
        $this->execute($tester);
    }

    /**
     * @param FunctionalTester $tester
     */
    private function execute(FunctionalTester $tester)
    {
        $tester->execute(function () {
            \Phapp\Application\Bootstrap::init($this->config)->runApplicationOn($this->server());
        });
    }

    /**
     * @return array
     */
    private function server()
    {
        return ['argv' => array_merge([__FILE__], $this->arguments)];
    }
}
