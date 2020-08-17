<?php


namespace WiQApp;


/**
 * Class CurlService
 *
 * Simple collection of functions to perform common curl calls.
 *
 *
 * @package WiQApp
 */
class CurlService
{
    /**
     * @var array request headers
     */
    private $headers = [];

    /**
     * Set Authorization: Bearer <access_token> header
     *
     * @param string|null $access_token
     *
     * @return CurlService
     */
    public function setAuthorizationBearerToken(?string $access_token): CurlService
    {
        if ( ! is_null($access_token)) {
            $headers[] = sprintf('Authorization: Bearer %1$s', $access_token);
        } else {
            die('No access token');
        }

        return $this;
    }

    /**
     * Make a curl GET request
     *
     * @param string $url
     * @param array $headers
     *
     * @return string
     * @throws \Exception
     */
    public function getRequest(string $url, array $headers = []): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, $this->headers));
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $errmsg = curl_error($ch);
        }
        curl_close($ch);

        if (isset($errmsg)) {
            throw new \Exception($errmsg, $httpcode);
        }

        return $output;
    }

    /**
     * Make a curl POST request
     *
     * @param string $url
     * @param array $headers
     * @param array $data
     *
     * @return string
     * @throws \Exception
     */
    public function postRequest(string $url, array $headers, array $data): string
    {
        $headers[] = "Content-Type:multipart/form-data";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, $this->headers));
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $errmsg = curl_error($ch);
        }
        curl_close($ch);

        if (isset($errmsg)) {
            throw new \Exception($errmsg, $httpcode);
        }

        return $output;
    }

    /**
     * Make a curl PUT request
     *
     * @param string $url
     * @param array $headers
     * @param array $data
     *
     * @return string
     * @throws \Exception
     */
    public function putRequest(string $url, array $headers, array $data): string
    {
        $headers[] = "Content-Type:multipart/form-data";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge($headers, $this->headers));
        $output   = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            $errmsg = curl_error($ch);
        }
        curl_close($ch);

        if (isset($errmsg)) {
            throw new \Exception($errmsg, $httpcode);
        }

        return $output;

    }
}