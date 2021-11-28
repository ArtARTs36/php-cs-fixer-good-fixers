<?php

use Illuminate\Console\Command;

class RestartQueueCommand extends Command
{
    protected $signature = 'queue:restart';

    protected $description = 'Restart queue';
}
