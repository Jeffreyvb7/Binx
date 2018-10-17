<?php

class Alert {

    private static $sessionKeyName = 'alerts';

    private $title = null;
    private $message = null;
    private $type = '';

    public function __construct(string $message, $title = null)
    {
        $this->message = $message;
        $this->title = $title;
    }

    public function __destruct()
    {
        $alerts = session(self::$sessionKeyName) ?: [];

        $alerts[] = $this->toArray();

        session()->flash(self::$sessionKeyName, $alerts);
    }

    public function success()
    {
        $this->type = 'success';
    }

    public function warning()
    {
        $this->type = 'warning';
    }

    public function danger()
    {
        $this->type = 'danger';
    }

    public function primary()
    {
        $this->type = 'primary';
    }

    private function toArray()
    {
        return [
            'title' => $this->title ?: ucfirst($this->type),
            'message' => $this->message,
            'type' => $this->type
        ];
    }

    public static function getSessionKey()
    {
        return self::$sessionKeyName;
    }
}

function alert(string $message, $title = null) {
    return new Alert($message, $title);
}