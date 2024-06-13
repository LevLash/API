<?php

namespace App\Services;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class RobotsService
{
    public static $refreshTokenKey = 'X-AUTH-REFRESH-TOKEN';
    public static $tokenKey = 'X-AUTH-ACCESS-TOKEN';

    public static $sessionRefreshTokenKey = 'api_refresh_token';
    public static $sessionTokenKey = 'api_token';

    private int $attemp = 0;


    /**
     * @param  array  $data
     * @return bool|null
     */
    public function register(array $data): ?bool
    {
        try {
            $response = Http::post($this->_getFullUrl('api.routes.register'), [
                'email' => $data['email'],
                'password' => $data['password'],
                'first_name' => $data['name'],
                'last_name' => $data['last_name'],
            ]);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }

        return (isset($response) && in_array($response->status(), [200, 201]))
            ? self::saveTokenFromResponse($response, $data['password']) : null;
    }

    /**
     * @param $response
     * @param $password
     * @return mixed
     */
    private static function saveTokenFromResponse($response, $password)
    {
        session()->put(self::$sessionTokenKey, $token = $response->header(self::$tokenKey));
        session()->put(self::$sessionRefreshTokenKey, $response->header(self::$refreshTokenKey));
        session()->put('p', Crypt::encryptString($password));

        return $token;
    }

    /**
     * @param $email
     * @param $password
     * @return bool|null
     */
    public function login($email, $password): ?bool
    {
        try {
            $response = Http::post($this->_getFullUrl('api.routes.login'), [
                'email' => $email,
                'password' => $password,
            ]);
            var_dump($response);
        } catch (Throwable $e) {
            Log::error($e->getMessage());
        }

        return (isset($response) && in_array($response->status(), [200, 201]))
            ? self::saveTokenFromResponse($response, $password) : null;
    }

    /**
     * @return false|PromiseInterface|Response
     */
    public function getRobots()
    {
        return $this->_sendAuthGetRequest($this->_getFullUrl('api.routes.list'));
    }

    /**
     * @param $url
     * @return false
     */
    private function _sendAuthGetRequest($url)
    {
        if (session()->has(self::$sessionTokenKey)) {
            try {
                $response = Http::withHeaders(self::_getHeaders())->get($url);

                if ($this->attemp === 0 && $response->status() === 403) {
                    $this->attemp++;
                    $result = $this->login(auth()->user()->email, Crypt::decryptString(session()->get('p')));

                    if ($result) {
                        $this->attemp = 0;

                        return $this->_sendAuthGetRequest($url);
                    }
                }
                return $response->json();
            } catch (Throwable $e) {
            }
        }
        return false;

    }

    /**
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private static function _getHeaders()
    {
        return [
            self::$tokenKey => session()->get(self::$sessionTokenKey),
            self::$refreshTokenKey => session()->get(self::$sessionRefreshTokenKey),
        ];
    }

    /**
     * @return false
     */
    public function createRobot()
    {
        return $this->_sendAuthPostRequest($this->_getFullUrl('api.routes.create'));
    }

    /**
     * @param $id
     * @param $task
     * @return false
     */
    public function turnOnRobot($id, $task)
    {
        return $this->_sendAuthPostRequest($this->_getFullUrl('api.routes.on', $id), ['task' => $task]);
    }

    /**
     * @param $id
     * @return false
     */
    public function turnOffRobot($id)
    {
        return $this->_sendAuthPostRequest($this->_getFullUrl('api.routes.off', $id));
    }

    /**
     * @param $id
     * @return false
     */
    public function getRobotStatus($id)
    {
        return $this->_sendAuthPostRequest($this->_getFullUrl('api.routes.get_feedback', $id));
    }

    /**
     * @param $id
     * @param  string  $feedback
     * @param  int  $rating
     * @return false
     */
    public function addFeedback($id, string $feedback, int $rating)
    {
        return $this->_sendAuthPostRequest($this->_getFullUrl('api.routes.add_feedback', $id), [
            'feedback' => $feedback,
            'rating' => $rating,
        ]);
    }

    /**
     * @param $id
     * @return false
     */
    public function getFeedbacks($id)
    {
        return $this->_sendAuthGetRequest($this->_getFullUrl('api.routes.get_feedback', $id));
    }

    /**
     * @param $url
     * @param $data
     * @return false
     */
    private function _sendAuthPostRequest($url, $data = [])
    {
        if (session()->has(self::$sessionTokenKey)) {

            try {
                $response = Http::withHeaders(self::_getHeaders())->post($url, $data);
                if (in_array($response->status(), [200, 201])) {
                    return $response->json();
                }
                if ($this->attemp < 3 && $response->status() === 403) {
                    $this->attemp++;
                    $result = $this->login(auth()->user()->email, Crypt::decryptString(session()->get('p')));
                    if ($result) {
                        $this->attemp = 0;
                        $response = $this->_sendAuthPostRequest($url, $data);
                    }
                }

            } catch (Throwable $e) {
                var_dump($e);exit;
            }
        }
        return false;
    }


    /**
     * @param $url
     * @param  string  $id
     * @return string
     */
    private function _getFullUrl($url, $id = '')
    {
        return config('api.host').(str_ireplace('{id}', $id, config($url)));
    }
}
