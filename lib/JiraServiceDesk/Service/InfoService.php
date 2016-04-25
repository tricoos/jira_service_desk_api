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
     * @return array
     */
    public function get()
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('info')
            ->request();
    }
}