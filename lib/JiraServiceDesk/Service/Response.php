<?php

namespace JiraServiceDesk\Service;

class Response
{
    public $body, $message, $status;

    /**
     * Response constructor.
     * @param \GuzzleHttp\Psr7\Response $response
     */
    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->status = $response->getStatusCode();
        $this->message = $response->getReasonPhrase();
        $this->body = json_decode($response->getBody()->getContents());
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        return $this->message;
    }

}