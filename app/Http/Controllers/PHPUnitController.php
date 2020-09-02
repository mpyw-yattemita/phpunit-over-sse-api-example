<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Process\Process;
use Throwable;

class PHPUnitController extends Controller
{
    public function test()
    {
        return new StreamedResponse(function () {
            while (ob_get_level()) {
                ob_end_flush();
            }

            $process = new Process([
                PHP_BINARY,
                base_path('vendor/bin/phpunit'),
                '--configuration',
                base_path('phpunit.xml'),
            ]);
            $process->start();

            echo "event: phpunit:start\n";
            echo "data: null\n";
            echo "\n";
            flush();

            try {
                while ($process->isRunning()) {
                    if ('' !== $output = $process->getOutput()) {
                        echo "event: phpunit:stdout\n";
                        echo 'data: ' . json_encode(['output' => $output]) . "\n";
                        echo "\n";
                        flush();
                    }
                    if ('' !== $output = $process->getErrorOutput()) {
                        echo "event: phpunit:stderr\n";
                        echo 'data: ' . json_encode(['output' => $output]) . "\n";
                        echo "\n";
                        flush();
                    }
                }
                echo "event: phpunit:done\n";
                echo "data: null\n";
                echo "\n";
                flush();
            } catch (Throwable $e) {
                echo "event: phpunit:error\n";
                echo 'data: ' . json_encode([
                    'class' => get_class($e),
                    'message' => $e->getMessage(),
                ]) . "\n";
                echo "\n";
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
            'Cache-Control' => 'no-store',
        ]);
    }
}
