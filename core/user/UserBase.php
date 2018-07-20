<?php
namespace core\user;

use core\App;

class UserBase implements User
{
    protected $parentApp;

    protected $logged = false;
    protected $id = 0;
    protected $login = '';
    protected $role = [];
    protected $rights = [];
    protected $isAdmin = false;
    protected $additionalInfo = [];

    protected $ip;
    protected $ua;
    protected $hash;
    protected $isMobile = false;

    public function __construct(App $app)
    {
        $this->parentApp = $app;
    }

    public function getUserStamp() : string
    {
        if ($this->hash === null) {
            $this->hash = md5($this->getUserIp() . $this->getUserAgent());
        }

        return $this->hash;
    }

    public function getUserIp() : string
    {
        if ($this->ip === null) {
            $this->ip = $this->parentApp->helpers->getRealIp();
        }

        return $this->ip;
    }

    public function getUserAgent() : string
    {
        if ($this->ua === null) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        }

        return $this->ua;
    }

    public function getUserBrowser() : string
    {
        if ($this->ua === null) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        }

        return $this->ua;
    }

    /**
     * @return bool
     */
    public function isMobile() : bool
    {
        if ($this->isMobile === null) {
            require_once $this->parentApp->rootPath . 'libs' . DIRECTORY_SEPARATOR . 'Mobile_Detect.php';
            $detect = new Mobile_Detect();
            $this->isMobile = $detect->isMobile();
        }

        return $this->isMobile;
    }

    public function isLogged() : bool
    {
        return $this->logged;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getLogin() : string
    {
        return $this->login;
    }

    public function getRole() : array
    {
        return $this->role;
    }

    public function getRights() : array
    {
        return $this->rights;
    }

    public function isAdmin() : bool
    {
        return $this->isAdmin;
    }

    public function setAdditionalInfo($info)
    {
        $this->additionalInfo = $info;
    }

    public function getAdditionalInfo() : array
    {
        return $this->additionalInfo;
    }

    public function logIn($login, $password) : bool
    {
        return false;
    }

    public function logOut() : bool
    {
        return false;
    }

}