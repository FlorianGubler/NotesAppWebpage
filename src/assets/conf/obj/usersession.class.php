<?php
    class UserSession {
        public $id;
        public $token;
        public $code;
        public $del_date;

        public function __construct($id, $token, $code, $del_date){
            $this->id = $id;
            $this->token = $token;
            $this->code = $code;
            $this->del_date = $del_date;
        }
    }
?>