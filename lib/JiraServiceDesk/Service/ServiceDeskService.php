<?php

namespace JiraServiceDesk\Service;

class ServiceDeskService
{
    private $service;

    /**
     * ServiceDeskService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}