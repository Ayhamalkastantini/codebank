<?php


namespace Models;


class CodeUsers
{
    private $id;
    private $codeId;
    private $userId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCodeId()
    {
        return $this->codeId;
    }

    /**
     * @param mixed $codeId
     */
    public function setCodeId($codeId)
    {
        $this->codeId = $codeId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}