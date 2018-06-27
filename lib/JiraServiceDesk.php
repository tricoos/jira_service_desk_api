<?php

use JiraServiceDesk\Service\CustomerService;
use JiraServiceDesk\Service\InfoService;
use JiraServiceDesk\Service\KnowledgebaseService;
use JiraServiceDesk\Service\OrganizationService;
use JiraServiceDesk\Service\RequestService;
use JiraServiceDesk\Service\RequestTypeService;
use JiraServiceDesk\Service\Service;
use JiraServiceDesk\Service\ServiceDeskService;

class JiraServiceDesk
{
    public $customer;
    public $info;
    public $knowledgebase;
    public $organization;
    public $request;
    public $requesttype;
    public $servicedesk;
    private $service;

    public function __construct()
    {
        $this->service = new Service();
        $this->customer = new CustomerService($this->service);
        $this->info = new InfoService($this->service);
        $this->knowledgebase = new KnowledgebaseService($this->service);
        $this->organization = new OrganizationService($this->service);
        $this->request = new RequestService($this->service);
        $this->requesttype = new RequestTypeService($this->service);
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