<?php

namespace App;

use App\BaseRepository;

class UserInfo extends BaseRepository
{

    /**
     * Return current user information
     *
     * @return array
     */
    public function current()
    {
        if (isset($_COOKIE['loggedin'])) {
            $this->query("SELECT * FROM users WHERE email = :email");
            $this->bind(':email', base64_decode($_COOKIE['loggedin']));

            return $this->fetch();
        }
        return null;
    }

    /**
     * Return selected user information
     *
     * @param $id
     * @return array
     */
    public function info($id)
    {
        $this->query("SELECT * FROM users WHERE id = :id");
        $this->bind(':id', $id);

        return $this->fetch();
    }

}