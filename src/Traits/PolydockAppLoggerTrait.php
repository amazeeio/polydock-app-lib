<?php

namespace FreedomtechHosting\PolydockApp\Traits;

use FreedomtechHosting\PolydockApp\Loggers\PolydockAppCacheLogger;
use FreedomtechHosting\PolydockApp\Loggers\PolydockAppEchoLogger;
use FreedomtechHosting\PolydockApp\PolydockAppLoggerInterface;

trait PolydockAppLoggerTrait
{
    /**
     * Set the logger instance
     *
     * @param  PolydockAppLoggerInterface  $logger  The logger instance
     * @return self Returns the instance for method chaining
     */
    public function setLogger(PolydockAppLoggerInterface $logger): self
    {
        if ($this->logger instanceof PolydockAppCacheLogger && ! ($logger instanceof PolydockAppEchoLogger)) {
            $logger->debug('Flushing cache logger...');
            foreach ($this->logger->getLogMessages() as $logMessage) {
                $logger->{$logMessage['level']}($logMessage['message'], $logMessage['context']);
            }
            $logger->debug('Cache logger flushed.');
        }

        $this->logger = $logger;

        return $this;
    }

    /**
     * Get the logger instance
     *
     * @return PolydockAppLoggerInterface The logger instance
     */
    public function getLogger(): PolydockAppLoggerInterface
    {
        return $this->logger;
    }

    /**
     * Log an informational message
     *
     * @param  string  $message  The message to log
     * @param  array  $context  Additional context data for the log entry
     * @return self Returns the instance for method chaining
     */
    public function info(string $message, array $context = []): self
    {
        $this->logger->info($message, $context);

        return $this;
    }

    /**
     * Log an error message
     *
     * @param  string  $message  The message to log
     * @param  array  $context  Additional context data for the log entry
     * @return self Returns the instance for method chaining
     */
    public function error(string $message, array $context = []): self
    {
        $this->logger->error($message, $context);

        return $this;
    }

    /**
     * Log a warning message
     *
     * @param  string  $message  The message to log
     * @param  array  $context  Additional context data for the log entry
     * @return self Returns the instance for method chaining
     */
    public function warning(string $message, array $context = []): self
    {
        $this->logger->warning($message, $context);

        return $this;
    }

    /**
     * Log a debug message
     *
     * @param  string  $message  The message to log
     * @param  array  $context  Additional context data for the log entry
     * @return self Returns the instance for method chaining
     */
    public function debug(string $message, array $context = []): self
    {
        $this->logger->debug($message, $context);

        return $this;
    }

    /**
     * Initialize the logger with a provided logger or default CacheLogger if not provided
     * The CacheLogger is used to cache log messages in a variable until an actual logger is set
     *
     * @param  PolydockAppLoggerInterface|null  $logger  Optional logger instance
     */
    protected function initializeLogger(?PolydockAppLoggerInterface $logger = null): void
    {
        $this->logger = $logger ?? new PolydockAppCacheLogger;
    }
}
