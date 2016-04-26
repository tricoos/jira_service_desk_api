<?php

namespace JiraServiceDesk\Service;

class InfoService
{
    private $service;

    /**
     * InfoService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Returns runtime information about JIRA Service Desk. You do not need to be logged in to use this method.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/info-getInfo
     * @return array
     */
    public function getInfo()
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('info')
            ->request();
    }
}