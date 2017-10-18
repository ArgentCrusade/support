<?php

namespace ArgentCrusade\Support\Console\Commands;

use Illuminate\Foundation\Console\ServeCommand;

class ArtisanServeCommand extends ServeCommand
{
    protected function host()
    {
        return config('support.dev-server.host', parent::host());
    }

    protected function port()
    {
        return config('support.dev-server.port', parent::port());
    }
}
