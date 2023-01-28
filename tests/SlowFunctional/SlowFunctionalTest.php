<?php

namespace App\Tests\SlowFunctional;

use App\Tests\Functional\FunctionalTest;

abstract class SlowFunctionalTest extends FunctionalTest
{
    protected function setUp(): void
    {
        parent::setUp();
        exec('bin/console doctrine:fixtures:load -n --purger my_purger');
    }
}
