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
     * @return Response
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
     * @return Response
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
     * @return Response
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
     * @return Response
     */
    public function getRequestTypeById($serviceDeskId, $requestTypeId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId)
            ->request();
    }

    /**
     * Returns the fields for a request type, for a given request type Id and service desk Id.
     * These are the fields that are required to create a customer request of that particular request type.
     * In addition, the following information about the current user's permissions for the request type is returned:
     * canRaiseOnBehalfOf field - Returns true, if the user has permission to raise requests on behalf of customers. Otherwise, returns false.
     * canAddRequestParticipants field - Returns true, if the user can add request participants. Otherwise, returns false.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk/{serviceDeskId}/requesttype/{requestTypeId}/field-getRequestTypeFields
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @return Response
     */
    public function getRequestTypeFields($serviceDeskId, $requestTypeId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId . '/field')
            ->request();
    }

    /**
     * Returns a page of queues defined inside a service desk, for a given service desk ID. The returned queues will include an issue count for each queue (represented in issueCount field) if the query param includeCount is set to true (defaults to false).
     * Permissions:
     * The calling user must be an agent of the given service desk.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk/{serviceDeskId}/queue-getQueues
     * @param integer $serviceDeskId
     * @param boolean $includeCount
     * @param integer $start
     * @param integer $limit
     * @return Response
     */
    public function getQueues($serviceDeskId, $includeCount = false, $start = 0, $limit = 50)
    {
        $data = [];
        $data['includeCount'] = $includeCount;
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/queue?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Returns a page of issues inside a queue for a given queue ID.
     * Only fields that the queue is configured to show are returned.
     * For example, if a queue is configured to show only Description and Due Date, then only those two fields are returned for each issue in the queue.
     * Permissions:
     * The calling user must have permission to view the requested queue, i.e. they must be an agent of the service desk that the queue belongs to.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk/{serviceDeskId}/queue-getIssuesInQueue
     * @param $serviceDeskId
     * @param $queueId
     * @param int $start
     * @param int $limit
     * @return Response
     */
    public function getIssuesInQueue($serviceDeskId, $queueId, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/queue/' . $queueId . '/issue?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Create one or more temporary attachments, which can later be converted into permanent attachments on Create attachment.
     * On successful execution, this resource will return a list of temporary attachment IDs, which are used in subsequent calls to convert the attachments into permanent attachments.
     * This resource expects a multipart post. The media-type multipart/form-data is defined in RFC 1867.
     * In order to protect against XSRF attacks, because this method accepts multipart/form-data, it has XSRF protection on it.
     * This means you must submit a header of X-Atlassian-Token: no-check with the request, otherwise it will be blocked.
     * The name of the multipart/form-data parameter that contains attachments must be "file"
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/servicedesk/{serviceDeskId}/attachTemporaryFile-attachTemporaryFile
     * @param $serviceDeskId
     * @param string $fileUrl
     * @return Response
     */
    public function attachTemporaryFile($serviceDeskId, $fileUrl)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setUrl('servicedesk/' . $serviceDeskId . '/attachTemporaryFile')
            ->setHeaders(
                [
                    'X-ExperimentalApi' => 'opt-in',
                    'X-Atlassian-Token' => 'no-check'
                ]
            )
            ->setMultipart(
                [
                    [
                        'name' => 'file',
                        'contents' => fopen($fileUrl, 'r')
                    ]
                ]
            )
            ->request();
    }
}