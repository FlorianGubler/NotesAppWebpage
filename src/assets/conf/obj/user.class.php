<?php
class User
{
    public $id;
    public $username;
    public $email;
    public $email_is_verified;
    public $profilepicture;
    public $admin;

    function __construct($_id, $_username, $_email, $_email_is_verified, $_profilepicture, $_admin)
    {
        $this->id = $_id;
        $this->username = $_username;
        $this->email = $_email;
        $this->email_is_verified = $_email_is_verified;
        $this->profilepicture = $_profilepicture;
        $this->admin = $_admin;
    }
}
