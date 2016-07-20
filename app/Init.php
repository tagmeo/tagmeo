<?php

namespace App;

use Tagmeo\Foundation\Boot;

class Init extends Boot
{
    public function __construct()
    {
        $this->loadClasses();
        $this->addFilters();
        $this->addThemeSupport();
    }
}
