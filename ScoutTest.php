<?php
declare(strict_types=1);

namespace Asgrim;

require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Scoutapm\Agent;

class ScoutTest
{
    private const SCOUT_KEY = 'jU0TmCcDlJsnSew00S9U';

    public function main()
    {
        $agent = new Agent();
        $agent->getConfig()->set('name', 'Great test');
        $agent->getConfig()->set('key', self::SCOUT_KEY);
        $agent->getConfig()->set('monitor', true);

        $agent->setLogger(new Logger('foo', [new StreamHandler('php://stderr')]));

        $agent->connect();

        $agent->webTransaction('Yay', function () use ($agent) {
            $agent->instrument('test', 'foo', function () {
            });
            $agent->instrument('test', 'foo2', function () {
            });
            $agent->tagRequest('testtag', '1.23');
        });

        $agent->send();
    }
}

(new ScoutTest())->main();

// DB time in queries, rowcounts
// external HTTP calls
// any kind of I/O
