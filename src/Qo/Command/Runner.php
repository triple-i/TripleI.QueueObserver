<?php


namespace Qo\Command;

class Runner
{

    /**
     * @param  string $command
     * @return string
     **/
    public function execute ($command)
    {
        try {
            exec($command, $output);
        } catch (\Exception $e) {
            throw $e;
        }

        return implode(PHP_EOL, $output);
    }
}

