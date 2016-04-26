<?php

namespace JiraServiceDesk\Service;


use GuzzleHttp\Client;

class Service
{
    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_PUT = 'PUT';
    const REQUEST_METHOD_DELETE = 'DELETE';

    const METHOD_URL = 'rest/servicedeskapi/';

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $host;

    /**
     * @var string
     */
    public $url;

    /**
     * @var Client
     */
    private $client;

    private $options = [];

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    /**
     * @param mixed $username
     * @return Service
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $password
     * @return Service
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $type
     * @return Service
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param mixed $url
     * @return Service
     */
    public function setUrl($url)
    {
        $this->url = $this->host . self::METHOD_URL . $url;
        return $this;
    }

    /**
     * @param $post_data
     * @return Service
     */
    public function setPostData($post_data)
    {
        $this->options['body'] = $post_data;
        return $this;
    }


    /**
     * @return array
     */
    public function request()
    {
        $this->options['auth'] = [$this->username, $this->password];
        return new Response($this->client->request($this->type, $this->url, $this->options));

    }

    public static function dump($die, $variable, $desc = false, $noHtml = false)
    {
        if (is_string($variable)) {
            $variable = str_replace("<_new_line_>", "<BR>", $variable);
        }

        if ($noHtml) {
            echo "\n";
        } else {
            echo "<pre>";
        }

        if ($desc) {
            echo $desc . ": ";
        }

        print_r($variable);

        if ($noHtml) {
            echo "";
        } else {
            echo "</pre>";
        }

        if ($die) {
            die();
        }
    }

}