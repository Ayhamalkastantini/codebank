<?php


namespace Repositories;


use App\BaseRepository;
use App\Database;
use App\UserInfo;

class UserRepository extends BaseRepository
{


    public function __construct()
    {
        $this->userInfo = new UserInfo();
    }
    /**
     * Register
     *
     * @param object $request
     * @return bool
     */

    public function register($request)
    {
        $this->query("INSERT INTO users (
            `roleId`,
            `email`,
            `password`,
            `secret`,
            `tagline`
        ) VALUES (:roleId, :email, :password, :secret, :tagline)");
        $this->bind(':roleId', $request->roleId);
        $this->bind(':email', $request->email);
        $this->bind(':password', password_hash($request->password1, PASSWORD_DEFAULT));
        $this->bind(':secret', $request->secret);
        $this->bind(':tagline', $request->tagline);

        if ($this->execute() && setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS))) return true;
        return false;
    }

    /**
     * Check for existed Email
     *
     * @param string $email
     * @return bool
     */
    public function existed($email)
    {
        $this->query("SELECT * FROM users WHERE email = :email");
        $this->bind(':email', $email);

        if (!is_null($this->fetch()['id'])) return true;
        return false;
    }

    /**
     * Get all users
     *
     * @param string $email
     * @return bool
     */
    public function getAllUsers()
    {
        $this->query("SELECT * FROM users WHERE id != :id");
        $this->bind(':id', $this->userInfo->current()['id']);

        return $this->fetchAll_class("Models\User");
    }

    /**
     * Login
     *
     * @param object $request
     * @return bool
     */
    public function login($request)
    {
        $this->query("SELECT * FROM users WHERE email = :email");
        $this->bind(':email', $request->email);

        if (password_verify($request->password, $this->fetch()['password']) && setcookie('loggedin', base64_encode($request->email), time() + (86400 * COOKIE_DAYS))) return true;
        return false;
    }

    /**
     * Logout
     *
     * @return bool
     */
    public function logout()
    {
        if (setcookie('loggedin', '', time() - (86400 * COOKIE_DAYS))) {
            unset($_COOKIE['loggedin']);
            return true;
        }
        return false;
    }
}