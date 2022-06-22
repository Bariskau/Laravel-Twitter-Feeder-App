<?php

namespace App\Notifications\Messages;

class SmsMessage
{
    protected string $to;
    protected string $from;
    protected array $lines;

    /**
     * SmsMessage constructor.
     * @param array $lines
     */
    public function __construct(array $lines = [])
    {
        $this->lines = $lines;
    }

    /**
     * @param string $line
     * @return $this
     */
    public function line(string $line = ''): self
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * @param $to
     * @return $this
     */
    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param $from
     * @return $this
     */
    public function from($from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function send(): bool
    {
        if (!$this->from || !$this->to || !count($this->lines)) {
            throw new \Exception('SMS not correct.');
        }

        info(json_encode([
            'from'    => $this->from,
            'to'      => $this->to,
            'type'    => 'sms',
            'message' => implode('\n', $this->lines),
        ]));

        return true;
    }

}
