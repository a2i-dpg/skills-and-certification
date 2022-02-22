<?php
namespace App\Logging;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class UserNamedLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
//        $logger = new \Monolog\Logger('U');
//        // Configure Monolog to log on user named log files
//        $rotatingHandler = new \Monolog\Handler\RotatingFileHandler(storage_path('logs/' . 'email' . '.log'), 0, \Monolog\Logger::DEBUG, true, 0755);
//        $rotatingHandler->setFormatter(new \Monolog\Formatter\LineFormatter(null, null, true, true));
//        $rotatingHandler->setFilenameFormat('{date}-{filename}', 'Y');
//        $logger->pushHandler($rotatingHandler);

        $logger = new Logger('UserNamedLogger');
        // Configure Monolog to log on user named log files
        $filename = storage_path('logs/laravel-'.  posix_getpwuid(posix_geteuid())['name'] .'.log');
        $rotatingHandler = new RotatingFileHandler($filename);
        $logger->pushHandler($rotatingHandler);
        return $logger;
    }
}
