<?php
namespace core\user;

interface User
{
    public function getUserStamp() : string;
    public function getUserIp() : string;
    public function getUserAgent() : string;
    public function getUserBrowser() : string;
    public function isMobile() : bool;

    public function getId() : int;
    public function getLogin() : string;
    public function getRole() : array;
    public function getRights() : array;
    public function isLogged() : bool;
    public function isAdmin() : bool;
    public function logIn($login, $password) : bool;
    public function logOut();

    public function setAdditionalInfo($info);
    public function getAdditionalInfo() : array;
}