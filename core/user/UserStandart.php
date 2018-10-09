<?php
namespace core\user;

use core\App;

class UserStandart extends UserBase implements User
{
    const TABLE_MAIN = 'user';
    const TABLE_ROLE = 'user_role_list';
    const TABLE_USER_ROLE = 'user_role';
    const TABLE_AUTH_HASH = 'user_hash';
    const TABLE_RIGHTS = 'user_rights_list';
    const TABLE_USER_RIGHTS = 'user_rights';
    const TABLE_ROLE_RIGHTS = 'user_role_rights';

    const COOKIE_HASH_NAME = 'aa_hash';

    public function __construct(App $app)
    {
        parent::__construct($app);

        $localUser = $app->session->get('user');
        if ($localUser !== null)
        {
            if ($localUser['logged'])
            {
                $this->setLogIn($localUser);
            }
        }
        else
        {
            $this->logged = false;
            $app->session->set('user', ['logged' => false]);
        }

        if (!$this->logged && isset($_COOKIE[self::COOKIE_HASH_NAME]))
        {
            $ip = ip2long($app->helpers->getRealIp());
            $hash = $_COOKIE[self::COOKIE_HASH_NAME];
            $app->db->exec('SELECT id, id_user FROM ' . $this::TABLE_AUTH_HASH . ' WHERE ip_int = :ip AND hash = :hash', ['ip' => $ip, 'hash' => $hash]);

            if ($app->db->rows() > 0)
            {
                $cookieData = $app->db->fetch();
                $app->db->exec('SELECT * FROM ' . $this::TABLE_MAIN . ' WHERE id = :id', ['id' => $cookieData['id_user']]);
                if ($app->db->rows() > 0)
                {
                    $userData = $app->db->fetch();
                    $userData['logged'] = true;
                    $userData['login'] = $userData['user_login'];
                    $userData['rights'] = [];
                    $userData['isAdmin'] = $userData['is_admin'];
                    $this->setLogIn($userData);
                    $app->db->exec('UPDATE ' . $this::TABLE_AUTH_HASH . ' SET dt = ' . time() . ' WHERE id = :id', ['id' => $cookieData['id']]);
                }
            }
        }
    }

    private function setLogIn($userData)
    {
        $this->logged = $userData['logged'];
        if ($this->logged)
        {
            $this->id = $userData['id'];
            $this->login = $userData['login'];
            $this->rights = $userData['rights'];
            $this->isAdmin = $userData['isAdmin'];
        }
        else
        {
            $this->id = 0;
            $this->login = '';
            $this->rights = [];
            $this->isAdmin = false;
        }
    }

    public function logIn($login, $password) : bool
    {
        $this->parentApp->db->exec('SELECT * FROM ' . $this::TABLE_MAIN . ' WHERE user_login = :login AND user_pass = :pass',
                                   ['login' => $login, 'pass' => md5($password)]);
        if ($this->parentApp->db->rows() == 0)
        {
            return false;
        }

        $userData = $this->parentApp->db->fetch();
        $userData['logged'] = true;
        $userData['login'] = $userData['user_login'];
        $userData['rights'] = [];
        $userData['isAdmin'] = $userData['is_admin'];
        $this->setLogIn($userData);

        $ipInt = ip2long($this->getUserIp());
        $hash = md5($ipInt . $this->getUserAgent() . time());

        $this->parentApp->db->exec('DELETE FROM ' . $this::TABLE_AUTH_HASH . ' WHERE id_user = :id_user AND ip_int = :ip_int',
                                   ['id_user' => $userData['id'], 'ip_int' => $ipInt]);

        $this->parentApp->db->exec('INSERT INTO ' . $this::TABLE_AUTH_HASH . ' (id_user, ip_int, dt, `hash`) VALUES (:id_user, :ip_int, :dt, :hash)',
            ['id_user' => $userData['id'],
             'ip_int'  => $ipInt,
             'dt'      => time(),
             'hash'    => $hash,
            ]);

        setcookie(self::COOKIE_HASH_NAME, $hash,time() + 31536000);

        return true;
    }

    public function logOut() : bool
    {
        $this->setLogIn(['logged' => false]);
        setcookie(self::COOKIE_HASH_NAME);
        return true;
    }

}