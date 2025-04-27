<?php namespace Eirbware\Protect;

class DataResponse {
    public $user;
    public array $protected;

    function __construct(array $session, array $protected) {
        $this->user = $session['cas_data'];
        $this->protected = $protected;
    }
    
    function toString(): string {
        return json_encode($this);
    }
}

?>
