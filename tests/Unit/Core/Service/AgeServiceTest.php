<?php

namespace App\Tests\Unit\Core\Service;

use App\Core\Service\AgeService;
use App\Tests\Unit\BaseUnitTest;
use DateTimeImmutable;

class AgeServiceTest extends BaseUnitTest
{
    /**
     * @dataProvider data
     */
    public function test(string $date1, string $date2, int $expected)
    {
        $service = new AgeService();
        $actual = $service->calculateDiffYears(
            new DateTimeImmutable($date1),
            new DateTimeImmutable($date2),
        );
        $this->assertEquals($expected, $actual);
    }

    private function data()
    {
        return [
            [
                '14.12.2000',
                '14.12.2022',
                22,
            ],
            [
                '14.12.2000',
                '13.12.2022',
                21,
            ],
            [
                '31.12.2000',
                '01.01.2022',
                21,
            ],
            [
                '13.12.2022',
                '14.12.2000',
                21,
            ],
            [
                '18.02.2002',
                '14.12.2000',
                1,
            ],
        ];
    }
}
