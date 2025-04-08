<?php namespace Eirbware\Protect;

class LoginResponse {
    public bool $success;
    public string $message;
    public $user;

    function __construct(bool $success, string $message, array $session) {
        $this->success = $success;
        $this->message = $message;
        if ($success) {
            $this->user = $session['cas_data'];
        }
    }
    
    function toString(): string {
        return json_encode($this);
    }
}

?>
