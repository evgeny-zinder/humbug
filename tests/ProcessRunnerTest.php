<?php

/**
 * Humbug
 *
 * @category   Humbug
 * @package    Humbug
 * @copyright  Copyright (c) 2015 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    https://github.com/padraic/humbug/blob/master/LICENSE New BSD License
 *
 * @author     rafal.wartalski@gmail.com
 */

namespace Humbug\Test;

use Humbug\Adapter\Phpunit;
use Humbug\ProcessRunner;
use Symfony\Component\Process\PhpProcess;

class ProcessRunnerTest extends \PHPUnit_Framework_TestCase
{
    public function testRunShouldNotFail()
    {
        $processRunner = new ProcessRunner();
        $testFrameworkAdapter = new Phpunit();

        $process = $this->createOkProcess();

        $result = $processRunner->run($process , $testFrameworkAdapter);

        $this->assertFalse($result);
    }

    public function testRunShouldFail()
    {
        $processRunner = new ProcessRunner();
        $testFrameworkAdapter = new Phpunit();

        $process = new PhpProcess('<?php
echo "TAP version 13\r\n";
echo "not ok 82 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo\r\n";
echo "not ok 78 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testReturnsTokenEquivalentToLessThanOrEqualTo\r\n";
echo "not ok 79 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo\r\n";
echo "not ok 80 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo\r\n";
echo "not ok 81 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo\r\n";
echo "ok 81 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo\r\n";
        ');

        $result = $processRunner->run($process , $testFrameworkAdapter);

        $this->assertTrue($result);
    }

    public function testShouldInvokeOnProgresCallback()
    {
        $processRunner = new ProcessRunner();
        $testFrameworkAdapter = new Phpunit();

        $executedCount = 0;

        $onProgressCallback = function() use (&$executedCount) {
            $executedCount++;
        };

        $process = $this->createOkProcess();

        $processRunner->run($process , $testFrameworkAdapter, $onProgressCallback);

        $this->assertGreaterThan(0, $executedCount);
    }

    /**
     * @return PhpProcess
     */
    private function createOkProcess()
    {
        $process = new PhpProcess('<?php
echo "TAP version 13\r\n";
echo "ok 78 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testReturnsTokenEquivalentToLessThanOrEqualTo\r\n";
echo "ok 79 - Humbug\Test\Mutator\ConditionalBoundary\LessThanOrEqualToTest::testMutatesLessThanToLessThanOrEqualTo\r\n";
echo "ok 80 - Humbug\Test\Mutator\ConditionalBoundary\LessThanTest::testReturnsTokenEquivalentToLessThanOrEqualTo\r\n";
        ');
        return $process;
    }
}
