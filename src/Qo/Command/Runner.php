<?php


namespace Qo\Command;

class Runner
{

    /**
     * @param  string $command
     * @return boolean
     **/
    public function execute ($command)
    {
        try {
            passthru($command);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }
}

