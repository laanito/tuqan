<?php
/**
 * This class is used to create and manage Logger in the app
 *
 * It uses Monolog
 */

namespace Tuqan\Classes;

use Monolog\Logger;
use Monolog\Handler\SyslogHandler;
use Monolog\Formatter\LineFormatter;


class TuqanLogger
{
    private static $logger;

    private static $initialized = false;

    /**
     * Initializes the logger
     */
    private static function initialize()
    {
        if (self::$initialized) {
            return;
        } else {
            self::$logger = new Logger('TuqanLogger');
            $syslog = new SyslogHandler('Tuqan');
            $formatter = new LineFormatter();
            $syslog->setFormatter($formatter);
            self::$logger->pushHandler($syslog);
            self::$initialized = true;
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     *
     * @return void
     */
    public static function log($level, $message, array $context = array())
    {
        self::initialize();
        switch($level){
            case 'debug':
                self::debug($message, $context);
                break;
            case 'warning':
                self::warning($message, $context);
                break;
            case 'alert':
                self::alert($message, $context);
                break;
            case 'critical':
                self::critical($message, $context);
                break;
            case 'emergency':
                self::emergency($message, $context);
                break;
            case 'error':
                self::error($message, $context);
                break;
            case 'info':
                self::info($message, $context);
                break;
            case 'notice':
                self::notice($message, $context);
                break;
            default:
                self::debug($message, $context);
                break;
        }
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function debug($message, array $context = array())
    {
        self::initialize();
        self::$logger->addDebug($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function warning($message, array $context = array())
    {
        self::initialize();
        self::$logger->addWarning($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function alert($message, array $context = array())
    {
        self::initialize();
        self::$logger->addAlert($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function critical($message, array $context = array())
    {
        self::initialize();
        self::$logger->addCritical($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function emergency($message, array $context = array())
    {
        self::initialize();
        self::$logger->addEmergency($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function error($message, array $context = array())
    {
        self::initialize();
        self::$logger->addError($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function info($message, array $context = array())
    {
        self::initialize();
        self::$logger->addInfo($message, $context);
    }

    /**
     * @param string $message
     * @param array $context
     */
    public static function notice($message, array $context = array())
    {
        self::initialize();
        self::$logger->addNotice($message, $context);
    }
}
