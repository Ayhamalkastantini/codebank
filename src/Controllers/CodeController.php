<?php

namespace Controllers;

use App\HandleForm;
use App\Helper;
use App\Middleware;
use App\UserInfo;
use App\XmlGenerator;
use Models\Code;
use Models\User;
use Repositories\CodeRepository;
use Repositories\UserRepository;

class CodeController
{

    /**
     * @var CodeRepository
     */
    private $codeRepository;

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
        Helper::render(
            'Code/index',
            [
                'page_title' => 'Code',
                'page_subtitle' => 'CodeBank | Mijn code',

                'codes' => $this->codeRepository->myIndex($this->userInfo->current()['id']),
                'currentUser' => $this->userInfo->current(),
                'userInfo' => $this->userInfo->info($this->userInfo->current()['id']),
            ]
        );
    }

    /**
     * READ one
     *
     * @param string $slug
     * @return void
     */
    public function show($slug)
    {
        $post = $this->codeRepository->show($slug);

        Helper::render(
            'Code/show',
            [
                'page_title' => $post['title'],
                'page_subtitle' => $post['subtitle'],

                'post' => $post,
                'currentUser' => $this->userInfo->current(),
                'userInfo' => $this->userInfo->info($this->userInfo->current()['id']),
            ]
        );
    }

    /**
     * CREATE
     *
     * @return void
     */
    public function create()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $allUsers = $this->userRepository->getAllUsers();

        Helper::render(
            'Code/create',
            [
                'page_title' => 'Code aanmaken',
                'page_subtitle' => 'Code aanmaken',
                'users'         => $allUsers
            ]
        );
    }

    /**
     * STORE
     *
     * @return void
     */
    public function store()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $request = json_decode(json_encode($_POST));
        $this->codeModel = $request;
        $output = [];

        if (!HandleForm::validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer een titel in voor je code!';
        } elseif (!HandleForm::validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer een ondertitle in voor je code!';
        } elseif (!HandleForm::validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer de code in!';
        } elseif (Helper::csrf($request->token) && $this->codeRepository->store($this->codeModel)) {
            if (isset($_FILES['image']['type'])) {
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/',
                    85, Helper::slug($request->title, '-', false));
            }
            $output['status'] = 'OK';
            $output['message'] = 'Proces succesvol afgerond!';
            unset($_POST);
            XmlGenerator::feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Er is een fout! Probeer het opnieuw.';
        }

        echo json_encode($output);

    }

    /**
     * EDIT
     *
     * @param string $slug
     * @return void
     */
    public function edit($slug)
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $code = $this->codeRepository->show($slug);
        $contributors = $this->codeRepository->showCodeContributors($code['id']);
        $allUsers = $this->userRepository->getAllUsers();
        $codeRights = $this->codeRepository->showCodeContributorsRights($code['id'], $this->userInfo->current()['id']);
        Helper::render(
            'Code/edit',
            [
                'page_title' => 'Edit ' . $code['title'],
                'page_subtitle' => $code['subtitle'],

                'post' => $code,
                'contributors' => $contributors,
                'codeRights' => $codeRights,
                'users' => $allUsers,
                'slug' => $slug
            ]
        );
    }

    /**
     * UPDATE
     *
     * @return void
     */
    public function update()
    {
        if (is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $request = json_decode(json_encode($_POST));
        $this->codeModel = $request;
        $output = [];

        if (!HandleForm::validate($request->title, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer een titel in voor je code!';
        } elseif (!HandleForm::validate($request->subtitle, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer een ondertitle in voor je code!';
        } elseif (!HandleForm::validate($request->body, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer de code in!';
        } elseif (Helper::csrf($request->token) && $this->codeRepository->update($this->codeModel)) {
            if (isset($_FILES['image']['type'])) {
                $currentPost = $this->codeRepository->readOnId($request->id);
                HandleForm::upload($_FILES['image'], ['jpeg', 'jpg','png'], 5000000, '../public/assets/images/', 85,
                    substr($currentPost['slug'], 0, -11));
            }
            $output['status'] = 'OK';
            $output['message'] = 'Proces succesvol afgerond!';
            XmlGenerator::feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Er is een fout! Probeer het opnieuw.';
        }

        echo json_encode($output);
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
            header('location: ' . URL_ROOT . '/login', true, 303);
            exit();
        }

        $output = [];

        if ($this->codeRepository->delete($slug)) {
            $output['status'] = 'OK';
            $output['message'] = 'Process complete successfully!';
            XmlGenerator::feed();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'There is an error! Please try again.';
        }

        echo json_encode($output);
    }
}
