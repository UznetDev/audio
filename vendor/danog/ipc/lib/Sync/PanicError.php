<?php declare(strict_types=1);

namespace Amp\Ipc\Sync;

final class PanicError extends \Error
{
    /** @var string Class name of uncaught exception. */
    private $name;

    /** @var string Stack trace of the panic. */
    private $trace;

    /**
     * Creates a new panic error.
     *
     * @param string          $name     The uncaught exception class.
     * @param string          $message  The panic message.
     * @param string          $trace    The panic stack trace.
     * @param \Throwable|null $previous Previous exception.
     */
    public function __construct(string $name, string $message = '', string $trace = '', \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->name = $name;
        $this->trace = $trace;
    }

    /**
     * Returns the class name of the uncaught exception.
     *
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Gets the stack trace at the point the panic occurred.
     *
     */
    public function getPanicTrace(): string
    {
        return $this->trace;
    }
}
