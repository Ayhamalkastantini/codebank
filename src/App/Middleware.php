<?php

namespace App;

use App\BaseRepository;

class Middleware
{
    /**
     * Define your methods' custom middlewares for the web routes
     *
     * @var array
     */
    private static $WEBmiddlewares = [
        'CodeController@create' => 'WEBauthentication',
        'CodeController@store' => 'WEBauthentication',
        'CodeController@edit' => 'WEBauthentication',
        'CodeController@update' => 'WEBauthentication',
        'CodeController@delete' => 'WEBauthentication',

        'AuthController@logout' => 'WEBauthentication',
    ];

    /**
     * Define your methods' custom middlewares for the api routes
     *
     * @var array
     */
    private static $APImiddlewares = [
        'CodeController@store' => 'APIauthentication',
        'CodeController@update' => 'APIauthentication',
        'CodeController@delete' => 'APIauthentication',

        'AuthController@logout' => 'APIauthentication',
    ];

    /**
     * Assign related middleware method to the each controller's method
     *
     * @param string class method name $classMethod
     * @return void
     */
    public static function init($classMethod)
    {
        $classMethod = str_replace('Controllers\\', '', $classMethod);
        $classMethod = str_replace('::', '@', $classMethod);
        if (strpos($classMethod, 'API\\') !== false) {
            $classMethod = str_replace('API\\', '', $classMethod);
            if (array_key_exists($classMethod, self::$APImiddlewares)) return self::{self::$APImiddlewares[$classMethod]}();
        } else {
            if (array_key_exists($classMethod, self::$WEBmiddlewares)) return self::{self::$WEBmiddlewares[$classMethod]}();
        }
    }

    /**
     * Check loggedin user
     *
     * @return bool
     */
    private static function WEBauthentication()
    {
        $baseRepository = new BaseRepository();
        if (isset($_COOKIE['loggedin'])) {
            $email = base64_decode($_COOKIE['loggedin']);

            $baseRepository->query("SELECT * FROM users WHERE email = :email");
            $baseRepository->bind(':email', $email);

            if (!is_null($baseRepository->fetch()['id'])) return $baseRepository->fetch()['id'];
        }
        return null;
    }

    /**
     * Check access token from header
     * Client should use this `Bearer qwaeszrdxtfcygvuhbijnokmpl0987654321` for Authorization in header
     * That APP_SECRET can be set in ENV or generate after a login or ...
     *
     * @return void
     */
    private static function APIauthentication()
    {
        $header = self::getAuthorizationHeader();
        if (!empty($header)) {
            if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                Database::query("SELECT * FROM users WHERE secret = :secret");
                Database::bind(':secret', $matches[1]);

                if (!is_null(Database::fetch()['id'])) {
                    setcookie('loggedin', base64_encode(Database::fetch()['email']), time() + (86400 * COOKIE_DAYS));
                    return Database::fetch()['id'];
                }
            }
        }
        return null;
    }

    /**
     * Get access token from header
     *
     * @return string
     */
    private static function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            /**
             * Nginx or fast CGI
             */
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            /**
             * @var array
             */
            $requestHeaders = apache_request_headers();

            /**
             * Server-side fix for bug in old Android versions
             * A nice side-effect of this fix means we don't
             * care about capitalization for Authorization
             */
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
}
