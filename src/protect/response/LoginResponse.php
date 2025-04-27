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

    function send(?string $redirect = null) {
        if (isset($redirect)) {
            // TODO('transfer error message to client if login failed?')
            header('Location: ' . $redirect);
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo $this->toString();
        }
    }
}

?>
