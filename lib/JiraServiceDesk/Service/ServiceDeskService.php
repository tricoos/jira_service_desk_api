<?php

namespace JiraServiceDesk\Service;

use JiraServiceDesk\Model\RequestTypeModel;

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
     * This method returns all the service desks in the Jira Service Desk instance
     * that the user has permission to access.
     * Use this method where you need a list of service desks or need to locate a service desk by name or keyword.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-get
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 100. See the Pagination section for more details.
     * @return Response
     */
    public function getServiceDesks($start = 0, $limit = 100)
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
     * This method returns a service desk. Use this method to get service desk details whenever
     * your application component is passed a service desk ID but needs to display other service desk details.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-get
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
     * This method adds one or more temporary attachments to a service desk, which can then be
     * permanently attached to a customer request using servicedeskapi/request/{issueIdOrKey}/attachment.
     * Note: It is possible for a service desk administrator to turn off the ability to add attachments to a service desk.
     * This method expects a multipart request. The media-type multipart/form-data is defined in RFC 1867.
     * Most client libraries have classes that make dealing with multipart posts simple.
     * For instance, in Java the Apache HTTP Components library provides MultiPartEntity.
     * Because this method accepts multipart/form-data, it has XSRF protection on it.
     * This means you must submit a header of X-Atlassian-Token: no-check with the request or it will be blocked.
     * The name of the multipart/form-data parameter that contains the attachments must be file.
     * For example, to upload a file called myfile.txt in the Service Desk with ID 10001 use
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-attachTemporaryFile-post
     * @param $serviceDeskId
     * @param $file
     * @param $filename
     * @return Response
     */
    public function attachTemporaryFile($serviceDeskId, $file, $filename = null)
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
                        'contents' => is_string($file) ? fopen($file, 'r') : $file,
                        'filename' => $filename
                    ]
                ]
            )
            ->request();
    }

    /**
     * This method returns all the service desks in the Jira Service Desk instance
     * that the user has permission to access.
     * Use this method where you need a list of service desks or need to locate a service desk by name or keyword.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-customer-get
     * @param $serviceDeskId
     * @param $query
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getCustomers($serviceDeskId, $query = '', $start = 0, $limit = 50)
    {
        $data = [];
        $data['query'] = $query;
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/customer?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Adds one or more customers to a service desk.
     * If any of the passed customers are associated with the service desk,
     * no changes will be made for those customers and the resource returns a 204 success code.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-customer-post
     * @param string $serviceDeskId
     * @param string[] $usernames
     * @return Response
     */
    public function addCustomers($serviceDeskId, $usernames = [])
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData(['usernames' => $usernames])
            ->setUrl('servicedesk/' . $serviceDeskId . '/customer')
            ->request();
    }

    /**
     * This method removes one or more customers from a service desk. The service desk must have closed access.
     * If any of the passed customers are not associated with the service desk,
     * no changes will be made for those customers and the resource returns a 204 success code.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-customer-delete
     * @param string $serviceDeskId
     * @param string[] $usernames
     * @return Response
     */
    public function removeCustomers($serviceDeskId, $usernames = [])
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setDeleteData(['usernames' => $usernames])
            ->setUrl('servicedesk/' . $serviceDeskId . '/customer')
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Returns articles which match the given query and belong to the knowledge base linked to the service desk.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-knowledgebase-article-get
     * @param $serviceDeskId
     * @param $highlight
     * @param $query
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getArticles($serviceDeskId, $highlight = false, $query = '', $start = 0, $limit = 50)
    {
        $data = [];
        $data['highlight'] = $highlight;
        $data['query'] = $query;
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/knowledgebase/article?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }

    /**
     * This method returns a list of all organizations associated with a service desk.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-organization-get
     * @param $serviceDeskId
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getOrganizations($serviceDeskId, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/organization?' . http_build_query($data))
            ->request();
    }

    /**
     * This method adds an organization to a service desk.
     * If the organization ID is already associated with the service desk,
     * no change is made and the resource returns a 204 success code.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-organization-post
     * @param string $serviceDeskId
     * @param integer $organizationId
     * @return Response
     */
    public function addOrganization($serviceDeskId, $organizationId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData(['organizationId' => $organizationId])
            ->setUrl('servicedesk/' . $serviceDeskId . '/organization')
            ->request();
    }

    /**
     * This method removes an organization from a service desk.
     * If the organization ID does not match an organization associated with the service desk,
     * no change is made and the resource returns a 204 success code.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-organization-delete
     * @param string $serviceDeskId
     * @param integer $organizationId
     * @return Response
     */
    public function removeOrganization($serviceDeskId, $organizationId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setDeleteData(['organizationId' => $organizationId])
            ->setUrl('servicedesk/' . $serviceDeskId . '/organization')
            ->request();
    }

    /**
     * This method returns the queues in a service desk.
     * To include a customer request count for each queue (in the issueCount field) in the response,
     * set the query parameter includeCount to true (its default is false).
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-queue-get
     * @param $serviceDeskId
     * @param $includeCount
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getQueues($serviceDeskId, $includeCount = false, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;
        $data['includeCount'] = $includeCount;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/queue?' . http_build_query($data))
            ->request();
    }

    /**
     * This method returns the customer requests in a queue.
     * Only fields that the queue is configured to show are returned.
     * For example, if a queue is configured to show description and due date,
     * then only those two fields are returned for each customer request in the queue.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-queue-queueId-issue-get
     * @param $serviceDeskId
     * @param $queueId
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 50. See the Pagination section for more details.
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
            ->request();
    }

    /**
     * This method returns all customer request types from a service desk.
     * There are two parameters for filtering the returned list
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-get
     * @param $serviceDeskId
     * @param $expand
     * @param $groupId
     * @param $searchQuery
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 100. See the Pagination section for more details.
     * @return Response
     */
    public function getRequestTypes($serviceDeskId, $expand = [], $groupId = null, $searchQuery = null, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;
        if ($expand)
            $data['expand'] = $expand;
        if ($groupId)
            $data['groupId'] = $groupId;
        if ($searchQuery)
            $data['searchQuery'] = $searchQuery;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype?' . http_build_query($data))
            ->request();
    }

    /**
     * This method enables a customer request type to be added to a service desk based on an issue type.
     * Note that not all customer request type fields can be specified in the request
     * and these fields are given the following default values:
     * Request type icon is given the question mark icon.
     * Request type groups is left empty, which means this customerrequest type willnot be visible on the customer portal.
     * Request type status mapping is left empty, so the request type has no custom status mapping but inherits the status map from the issue type upon which it is based.
     * Request type field mapping is set to show the required fields as specified by the issue type used to create the customer request type.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-post
     * @param string $serviceDeskId
     * @param RequestTypeModel $requestType
     * @return Response
     */
    public function createRequestType($serviceDeskId, RequestTypeModel $requestType)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData((array)$requestType)
            ->setUrl('servicedesk/' . $serviceDeskId . '/organization')
            ->setExperimentalApi()
            ->request();
    }

    /**
     * This method returns a customer request type from a service desk.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-requestTypeId-get
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @param string[] $expand
     * @return Response
     */
    public function getRequestTypeById($serviceDeskId, $requestTypeId, $expand = [])
    {
        $data = [];
        $data['expand'] = $expand;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId . '?' . http_build_query($data))
            ->request();
    }

    /**
     * This method returns the fields for a service desk’s customer request type.
     * Also, the following information about the user’s permissions for the request type is returned:
     * canRaiseOnBehalfOf returns true if the user has permission to raise customer requests on behalf of other customers. Otherwise, returns false.
     * canAddRequestParticipants returns true if the user can add customer request participants. Otherwise, returns false.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-requestTypeId-field-get
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
     * Returns the keys of all properties for a request type.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-requestTypeId-property-get
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @return Response
     */
    public function getPropertiesKeys($serviceDeskId, $requestTypeId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId . '/property')
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Returns the value of the property from a request type.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-requestTypeId-property-propertyKey-get
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @param string $propertyKey
     * @return Response
     */
    public function getProperty($serviceDeskId, $requestTypeId, $propertyKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId . '/property/' . $propertyKey)
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Sets the value of a request type property. Use this resource to store custom data against a request type.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-requestTypeId-property-propertyKey-put
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @param string $propertyKey
     * @return Response
     */
    public function setProperty($serviceDeskId, $requestTypeId, $propertyKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_PUT)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId . '/property/' . $propertyKey)
            ->setExperimentalApi()
            ->request();
    }

    /**
     * Removes a property from a request type.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttype-requestTypeId-property-propertyKey-delete
     * @param integer $serviceDeskId
     * @param integer $requestTypeId
     * @param string $propertyKey
     * @return Response
     */
    public function deleteProperty($serviceDeskId, $requestTypeId, $propertyKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttype/' . $requestTypeId . '/property/' . $propertyKey)
            ->setExperimentalApi()
            ->request();
    }

    /**
     * This method returns a service desk’s customer request type groups.
     * Jira Service Desk administrators can arrange the customer request type groups in an arbitrary
     * order for display on the customer portal; the groups are returned in this order.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-servicedesk-serviceDeskId-requesttypegroup-get
     * @param $serviceDeskId
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of items to return per page. Default: 100. See the Pagination section for more details.
     * @return Response
     */
    public function getRequestTypeGroups($serviceDeskId, $start = 0, $limit = 100)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('servicedesk/' . $serviceDeskId . '/requesttypegroup?' . http_build_query($data))
            ->request();
    }
}