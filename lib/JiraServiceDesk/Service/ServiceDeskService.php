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

    /**
     * Returns all service desks in the JIRA Service Desk application.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk-getServiceDesks
     * @param int $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param int $limit The maximum number of items to return per page. Default: 100. See the Pagination section for more details.
     * @return array
     */
    public function getServiceDesks($start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/?' . http_build_query($data))
            ->request();
    }

    /**
     * Returns the service desk for a given service desk Id.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk-getServiceDeskById
     * @param integer $serviceDeskId
     * @return array
     */
    public function getServiceDeskById($serviceDeskId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId)
            ->request();
    }

    /**
     * Returns all request types from a service desk, for a given service desk Id.
     * @param $serviceDeskId
     * @param int $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param int $limit The maximum number of items to return per page. Default: 100. See the Pagination section for more details.
     * @return array
     */
    public function getRequestTypes($serviceDeskId, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype?' . http_build_query($data))
            ->request();
    }

    /**
     * Returns a request type for a given request type Id.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk/{serviceDeskId}/requesttype-getRequestTypeById
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @return array
     */
    public function getRequestTypeById($serviceDeskId, $requestTypeId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId)
            ->request();
    }
}