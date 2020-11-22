<?php

namespace Controllers;

use App\HandleForm;
use App\Helper;
use App\Middleware;
use Repositories\UserRepository;

class AuthController
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }
    /**
     * Registeration from
     *
     * @return void
     */
    public function registerForm()
    {
        if (!is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT, true, 303);
            exit();
        }

        Helper::render(
            'Auth/register',
            [
                'page_title' => 'Register',
                'page_subtitle' => 'Register to send post in Blog'
            ]
        );
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        $request = json_decode(json_encode($_POST));
        $secret = md5(uniqid(rand(), true));
        $request->secret = $secret;
        $request->roleId = 2;
        $output = [];

        if (!HandleForm::validate($request->email, 'email')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Gelieve een geldig e-mailadres in te geven!';
        } elseif (!HandleForm::validate($request->password1, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer een wachtwoord in!';
        } elseif ($request->password1 !== $request->password2) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Herhaal het wachtwoord in het bevestigingsveld!';
        } elseif (!HandleForm::validate($request->tagline, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer een slogan in om jezelf voor te stellen!';
        } elseif ($this->userRepository->existed($request->email)) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Deze e-mail is eerder geregistreerd!';
        } elseif (Helper::csrf($request->token) && $this->userRepository->register($request)) {
            Helper::mailto($request->email, 'Welkom bij CodeBank! Uw geheime API-sleutel', '<p>Hallo beste vriend,</p><hr /><p>Dit is uw geheime API-sleutel om toegang te krijgen tot geverifieerde API-routes:</p><p><strong>' . $secret . '</strong></p><p>Bewaar het op een veilige plaats.</p><hr /><p>Succes,</p><p><a href="http://localhost:8080" target="_blank" rel="noopener">CodeBank</a></p>');

            $output['status'] = 'OK';
            $output['message'] = 'Proces succesvol afgerond!';
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Er is een fout! Probeer het opnieuw.';
        }

        unset($_POST);
        echo json_encode($output);
    }

    /**
     * Login from
     *
     * @return void
     */
    public function loginForm()
    {
        if (!is_null(Middleware::init(__METHOD__))) {
            header('location: ' . URL_ROOT, true, 303);
            exit();
        }

        Helper::render(
            'Auth/login',
            [
                'page_title' => 'Login',
                'page_subtitle' => 'Login '
            ]
        );
    }

    /**
     * Login
     *
     * @return void
     */
    public function login()
    {
        $request = json_decode(json_encode($_POST));

        $output = [];

        if (!HandleForm::validate($request->email, 'email')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Gelieve een geldig e-mailadres in te geven!';
        } elseif (!HandleForm::validate($request->password, 'required')) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Voer uw wachtwoord in!';
        } elseif (Helper::csrf($request->token) && $this->userRepository->login($request)) {
            $output['status'] = 'OK';
            $output['message'] = 'Proces succesvol afgerond!';
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Er is een fout! Probeer het opnieuw.';
        }

        unset($_POST);
        echo json_encode($output);
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $output = [];

        if (is_null(Middleware::init(__METHOD__))) {
            $output['status'] = 'ERROR';
            $output['message'] = 'Authentication failed!';
        } elseif ($this->userRepository->logout()) {
            header('location: ' . URL_ROOT, true, 303);
            exit();
        } else {
            $output['status'] = 'ERROR';
            $output['message'] = 'Logout Failed!';
        }

        echo json_encode($output);
    }
}
