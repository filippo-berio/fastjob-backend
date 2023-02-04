<?php

namespace App\Tests\SlowAcceptance;

use App\Tests\Acceptance\AcceptanceTest;

abstract class SlowAcceptanceTest extends AcceptanceTest
{
    protected function setUp(): void
    {
        parent::setUp();
        exec('bin/console doctrine:fixtures:load -n --purger my_purger');
    }
}
