<?php


use Qo\Command\Runner;
use Qo\Mock\MockTestCase;

class RunnerTest extends MockTestCase
{

    /**
     * @var Runner
     **/
    private $runner;


    /**
     * @return void
     **/
    public function setUp ()
    {
        $this->runner = new Runner();
    }


    /**
     * @test
     * @group runner-execute
     * @group runner
     **/
    public function 正常な処理 ()
    {
        $command = 'echo "test"';
        $result = $this->runner->execute($command);

        $this->expectOutputString('test'.PHP_EOL);
    }

}

