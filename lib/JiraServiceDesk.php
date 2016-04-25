<?php

use JiraServiceDesk\Service\InfoService;
use JiraServiceDesk\Service\RequestService;
use JiraServiceDesk\Service\ServiceDeskService;
use JiraServiceDesk\Service\Service;

class JiraServiceDesk
{
    public $info;
    public $request;
    private $service;
    public $servicedesk;

    public function __construct()
    {
        $this->service = new Service();
        $this->info = new InfoService($this->service);
        $this->request = new RequestService($this->service);
        $this->servicedesk = new ServiceDeskService($this->service);
    }

    /**
     * @param string $password
     * @return JiraServiceDesk
     */
    public function setPassword($password)
    {
        $this->service->password = $password;
        return $this;
    }

    /**
     * @param string $username
     * @return JiraServiceDesk
     */
    public function setUsername($username)
    {
        $this->service->username = $username;
        return $this;
    }

    /**
     * @param string $host
     * @return JiraServiceDesk
     */
    public function setHost($host)
    {
        $this->service->host = $host;
        return $this;
    }
}