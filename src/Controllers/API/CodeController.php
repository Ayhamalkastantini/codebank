<?php

namespace Controllers\API;

/**
 * Required headers
 */
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: ' . URL_ROOT);
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Max-Age: 3600');

use App\Database;
use App\HandleForm;
use App\Middleware;
use App\UserInfo;
use App\XmlGenerator;
use Models\Blog;
use Models\Code;
use Models\User;
use Repositories\CodeRepository;
use Repositories\UserRepository;

class CodeController
{

    public function __construct()
    {
        $this->codeRepository = new CodeRepository();
        $this->codeModel = new Code();
        $this->userModel = new User();
        $this->userInfo = new UserInfo();
        $this->userRepository = new UserRepository();
    }
    /**
     * READ all
     *
     * @return void
     */
    public function index()
    {
        $response = $this->codeRepository->myIndex($this->userInfo->current()['id']);

        if (count($response) > 0) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No result!']);
        }
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return void
     */
    public function show($slug)
    {
        $response = Blog::show($slug);

        if (count($response) > 0) {
            http_response_code(200);
            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'No result!']);
        }
    }

    /**
     * STORE
     *
     * @return void
     */
    public function store()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            http_response_code(403);
            echo json_encode(["message" => "Authorization failed!"]);
            exit();
        }

        $request = json_decode(file_get_contents('php://input'));

        if (!HandleForm::validate($request->title, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a title for the code!']);
        } elseif (!HandleForm::validate($request->subtitle, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a subtitle for the code!']);
        } elseif (!HandleForm::validate($request->body, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a body for the code!']);
        } elseif ($this->codeRepository->store($request)) {
            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85, slug
                ($request->title, '-', false));
            }
            XmlGenerator::feed();

            http_response_code(201);
            echo json_encode(['message' => 'Data saved successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during saving data!']);
        }
    }

    /**
     * UPDATE
     *
     * @return void
     */
    public function update()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            http_response_code(403);
            echo json_encode(["message" => "Authorization failed!"]);
            exit();
        }

        $request = json_decode(file_get_contents('php://input'));

        if (!HandleForm::validate($request->title, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a title for the code!']);
        } elseif (!HandleForm::validate($request->subtitle, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a subtitle for the code!']);
        } elseif (!HandleForm::validate($request->body, 'required')) {
            http_response_code(422);
            echo json_encode(['message' => 'Please enter a body for the code!']);
        } elseif ($this->codeRepository->update($request)) {
            if (isset($_FILES['image']['type'])) {
                $currentPost = $this->codeRepository->readOnId($request->id);
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85,
                    substr($currentPost['slug'], 0, -11));
            }
            XmlGenerator::feed();

            http_response_code(200);
            echo json_encode(['message' => 'Data updated successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during updating data!']);
        }
    }

    /**
     * DELETE
     *
     * @param string $slug
     * @return void
     */
    public function delete($slug)
    {
        if (is_null(Middleware::init(__METHOD__))) {
            http_response_code(403);
            echo json_encode(["message" => "Authorization failed!"]);
            exit();
        }

        if ($this->codeRepository->delete($slug)) {
            http_response_code(200);
            echo json_encode(['message' => 'Data deleted successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Failed during deleting data!']);
        }
    }
}
