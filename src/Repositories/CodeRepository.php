<?php


namespace Repositories;


use App\BaseRepository;
use App\Database;
use App\Helper;
use App\UserInfo;

class CodeRepository extends BaseRepository
{

    /**
     * @var UserInfo
     */
    private $userInfo;

    public function __construct()
    {
        $this->userInfo = new UserInfo();
    }

    /**
     * READ all
     *
     * @param integer $count
     * @return array
     */
    public function index($count = 0)
    {
        if ($count === 0) {
            $this->query("
                SELECT DISTINCT code.id, code.category,code.slug, code.title, code.subtitle, code.body, code.createdAt, code.updatedAt, users.id
                FROM code 
                LEFT JOIN code_users ON code.id = code_users.codeId
                LEFT JOIN users ON users.id = code_users.userId
                WHERE code_users.kind != 1
                ORDER BY code.createdAt DESC");
        } else {
            $this->query("SELECT * FROM code ORDER BY id DESC LIMIT :count");
            $this->bind(':count', $count);
        }

        return  $this->fetchAll();
    }

    public function myIndex($userId)
    {
        $this->query("
                SELECT DISTINCT code.id, code.category,code.slug, code.title, code.subtitle, code.body, code.createdAt, code.updatedAt, users.id
                FROM code 
                LEFT JOIN code_users ON code.id = code_users.codeId
                LEFT JOIN users ON users.id = code_users.userId
                WHERE code_users.kind != 1
                ORDER BY code.createdAt DESC");
        $this->bind(':userId', $userId);

        return  $this->fetchAll();
    }


    /**
     * READ one
     *
     * @param string $slug
     * @return array
     */
    public function show($slug)
    {
        $this->query("SELECT code.id, code.category,code.slug, code.title, code.subtitle, code.body, code.createdAt, code.updatedAt, code_users.userId
                FROM code 
                INNER JOIN code_users ON code.id = code_users.codeId
                INNER JOIN users ON users.id = code_users.userId
                WHERE slug = :slug
                ORDER BY id DESC");
        $this->bind(':slug', $slug);

        return $this->fetch();
    }


    public function showCodeContributors($codeId)
    {
        $this->query("SELECT users.id, users.email
                FROM users 
                INNER JOIN code_users ON users.id = code_users.userId
                WHERE code_users.codeId = :codeId AND code_users.kind = 1");
        $this->bind(':codeId', $codeId);

        return $this->fetch();
    }

    public function showCodeContributorsRights($codeId, $userId)
    {
        $this->query("SELECT kind
                FROM code_users
                WHERE code_users.codeId = :codeId AND code_users.userId = :userId");
        $this->bind(':codeId', $codeId);
        $this->bind(':userId', $userId);

        return $this->fetch();
    }


    /**
     * READ one on id
     *
     * @param string $id
     * @return array
     */
    public function readOnId($id)
    {
        $this->query("SELECT * FROM code WHERE id = :id");
        $this->bind(':id', $id);

        return $this->fetch();
    }
    /**
     * STORE
     *
     * @param object $request
     * @return bool
     */
    public function store($request)
    {

        $userCurrentInfo = $this->userInfo->current();
        $status = false;
        $lastId = 0;
        $this->query("INSERT INTO code (
            `category`,
            `title`,
            `slug`,
            `subtitle`,
            `body`
        ) VALUES (:category, :title, :slug, :subtitle, :body)");

        $this->bind(':category', $request->category ?? DEFAULT_CATEGORY);
        $this->bind(':title', $request->title);
        $this->bind(':slug', Helper::slug($request->title));
        $this->bind(':subtitle', $request->subtitle);
        $this->bind(':body', $request->body);

        if ($this->execute()){
            $status = true;
            $lastId = $this->dbHandler->lastInsertId();
        }
        $this->query("INSERT INTO code_users (
            `codeId`,
            `userId`,
            `kind`
        ) VALUES (:codeId, :userId, :kind)");

        $this->bind(':codeId',  $lastId);
        $this->bind(':userId', $userCurrentInfo['id']);
        $this->bind(':kind', 0);
        if ($status == true && $this->execute() && $request->contributors != ''){
            $this->query("INSERT INTO code_users (
            `codeId`,
            `userId`,
            `kind`
        ) VALUES (:codeId, :userId, :kind)");

            $this->bind(':codeId',  $lastId);
            $this->bind(':userId', $request->contributors);
            $this->bind(':kind', 1);

            if ($this->execute())return true;

        }else if($status == true && $this->execute())return true;

        return false;
    }


    /**
     * EDIT
     *
     * @param string $slug
     * @return array
     */
    public function edit($slug)
    {
        $this->query("SELECT * FROM code WHERE title = :slug");
        $this->bind(':slug', $slug);

        return $this->fetch();
    }


    /**
     * UPDATE
     *
     * @param object $request
     * @return bool
     */
    public function update($request)
    {
        $this->query("UPDATE code SET
            category = :category,
            title = :title,
            subtitle = :subtitle,
            body = :body
        WHERE id = :id");
        $this->bind(':category', $request->category ?? DEFAULT_CATEGORY);
        $this->bind(':title', $request->title);
        $this->bind(':subtitle', $request->subtitle);
        $this->bind(':body', $request->body);
        $this->bind(':id', $request->id);


        if ($this->execute() && $request->contributors == ''){


            // Check if contributor existed and delete it
            $this->query("SELECT * FROM code_users WHERE codeId = :codeId AND kind = 1");
            $this->bind(':codeId', $request->id);
            $recordId = $this->fetch()['id'];
            if (!is_null($recordId)) {
                // Delete it
                $this->query("DELETE FROM code_users WHERE codeId = :codeId AND kind = 1");
                $this->bind(':codeId', $request->id);
                if ($this->fetch()) return true;
            }
            return true;

        } else if($this->execute()){
            // Check if contributor existed
            $this->query("SELECT * FROM code_users WHERE codeId = :codeId AND kind = 1");
            $this->bind(':codeId', $request->id);
            $recordId = $this->fetch()['id'];
            if (!is_null($recordId)) {
                // Update it
                $this->query("UPDATE code_users SET
                    userId = :contributor,
                WHERE id = :id AND kind = 1");
                $this->bind(':id', $recordId);
                $this->bind(':contributor', $request->contributors);
                if ($this->execute()) return true;
            }else{
            //Insert it
            $this->query("INSERT INTO code_users (
                `codeId`,
                `userId`,
                `kind`
            ) VALUES (:codeId, :userId, :kind)");

            $this->bind(':codeId',  $request->id);
            $this->bind(':userId', $request->contributors);
            $this->bind(':kind', 1);

            if ($this->execute())return true;
            }
        }
        return false;
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return bool
     */
    public function delete($slug)
    {

        $this->query("SELECT * FROM code WHERE slug = :slug");
        $this->bind(':slug', $slug);

        $codeId = $this->fetch()['id'];

        $this->query("DELETE FROM code_users WHERE codeId = :codeId");
        $this->bind(':codeId', $codeId);
        $this->fetch();

        $this->query("DELETE FROM code WHERE slug = :slug");
        $this->bind(':slug', $slug);

        if ($this->execute()) return true;
        return false;
    }
}