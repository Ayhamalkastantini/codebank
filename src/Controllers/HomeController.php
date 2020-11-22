<?php

namespace Controllers;

use App\Helper;
use App\UserInfo;
use Models\Blog;
use Repositories\CodeRepository;

class HomeController
{


    /**
     * @var CodeRepository
     */
    private $codeRepository;

    public function __construct()
    {
        $this->codeRepository = new CodeRepository();
        $this->userInfo = new UserInfo();
    }
    /**
     * Home page rendering to show recent posts
     *
     * @return void
     */
    public function index()
    {
        Helper::render(
            'Home/home',
            [
                'page_title' => 'Home',
                'page_subtitle' => 'CodeBank',

                'codes' => $this->codeRepository->index(0),
                'currentUser' => $this->userInfo->current(),
                'userInfo' => $this->userInfo->info($this->userInfo->current()['id']),
            ]
        );
    }
}
