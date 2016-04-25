<?php

namespace JiraServiceDesk\Service;

use JiraServiceDesk\Model\RequestModel;

class RequestService
{
    private $service;

    /**
     * RequestService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * @param RequestModel $request
     * @return array
     */
    public function create(RequestModel $request)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request')
            ->request();
    }

    /**
     * @param bool|string $id
     * @return array
     */
    public function get($id = false)
    {
        if ($id)
            $id = '/' . $id;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request' . $id)
            ->request();
    }
}