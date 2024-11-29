<?php

namespace Healthengine\LaravelLogging\Formatters;

use Monolog\Formatter\LogstashFormatter as BaseLogstashFormatter;
use Monolog\LogRecord;

class LogstashFormatter extends BaseLogstashFormatter
{
    /**
     * @inheritDoc
     */
    public function format(LogRecord $record): string
    {
        $recordData = parent::normalizeRecord($record);

        $message = [
            '@timestamp' => $recordData['datetime'],
            '@version' => 1,
            'host' => $this->systemName,
        ];
        if (isset($recordData['message'])) {
            $message['message'] = $recordData['message'];
        }
        if (isset($recordData['channel'])) {
            $message['type'] = $recordData['channel'];
            $message['channel'] = $recordData['channel'];
        }
        if (isset($recordData['level_name'])) {
            $message['level'] = $recordData['level_name'];
        }
        if (isset($recordData['level'])) {
            $message['monolog_level'] = $recordData['level'];
        }
        if ('' !== $this->applicationName) {
            $message['type'] = $this->applicationName;
        }
        if (\count($recordData['extra']) > 0) {
            $message[$this->extraKey] = $recordData['extra'];
        }
        if (\count($recordData['context']) > 0) {
            $message[$this->contextKey] = $recordData['context'];
        }

        $this->injectOtherData($message);

        return $this->toJson($message) . "\n";
    }

    public function injectOtherData(array &$message)
    {
        $message['X-Amzn-Trace-Id'] = $_SERVER['HTTP_X_AMZN_TRACE_ID'] ?? '';
    }
}
