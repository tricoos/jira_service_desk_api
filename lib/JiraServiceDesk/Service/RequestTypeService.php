<?php

namespace JiraServiceDesk\Service;

class RequestTypeService
{
    private $service;

    /**
     * RequestTypeService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * This method returns all customer request types used in the Jira Service Desk instance,
     * optionally filtered by a query string.
     * Use servicedeskapi/servicedesk/{serviceDeskId}/requesttype to find
     * the customer request types supported by a specific service desk.
     * The returned list of customer request types can be filtered using the query parameter.
     * The parameter is matched against the customer request types’ name or description.
     * For example, searching for “Install”, “Inst”, “Equi”, or “Equipment” will match
     * a customer request type with the name “Equipment Installation Request”.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/Requesttype-getRequesttype
     * @param $searchQuery
     * @param $expand
     * @param $limit
     * @param $start
     * @return Response
     */
    public function getRequestType($searchQuery = null, $expand = [], $limit = 100, $start = 0)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;
        $data['expand'] = $expand;
        if ($searchQuery)
            $data['searchQuery'] = $searchQuery;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('requesttype?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }
}