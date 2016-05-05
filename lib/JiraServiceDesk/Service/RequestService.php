<?php

namespace JiraServiceDesk\Service;

use JiraServiceDesk\Model\AttachmentModel;
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
     * Creates a customer request in a service desk. The service desk and request type are required. The fields that are mandatory for the request type are also required.
     * If you need the list of the fields required for the request type, you can get it via this resource: servicedesk/{serviceDeskId}/requesttype/{requestTypeId}/field
     * Notes:
     * Adding attachments via this method is currently not supported.
     * The fields for a request type may vary based on the permissions of the currently authenticated user:
     * raiseOnBehalfOf field - Not available to users who only have the Service Desk customer permission.
     * requestParticipants field - Not available to users who only have the Service Desk customer permission or if the feature is turned off for customers.
     * Schema of requestFieldValues field is a map of JIRA's field's ID and its value, which are JSON ready objects. The object value will be interpreted with JSON semantics according to the specific field requirements.
     * So a simple field like summary or number customer field might take String / Integer while other fields like Multi User Picker will take a more complex object that has JSON semantics.
     * Refer to Field input formats reference on what field types take what values.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request-createCustomerRequest
     * @param RequestModel $request
     * @return Response
     */
    public function createCustomerRequest(RequestModel $request)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData((array)$request)
            ->setUrl('request')
            ->request();
    }

    /**
     * Returns all customer requests for the user that is executing the query.
     * That is, the customer requests where the user is the creator of the customer request or has participated in the customer request.
     * Returned customer requests are ordered chronologically by the latest activity on each customer request.
     * For example, the latest status transition or comment.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request-getMyCustomerRequests
     * @param bool $searchTerm
     * @param bool $requestOwnership
     * @param bool $requestStatus
     * @param bool $serviceDeskId
     * @param bool $requestTypeId
     * @param bool $expand
     * @param int $start
     * @param int $limit
     * @return Response
     */
    public function getMyCustomerRequests(
        $searchTerm = false,
        $requestOwnership = false,
        $requestStatus = false,
        $serviceDeskId = false,
        $requestTypeId = false,
        $expand = false,
        $start = 0,
        $limit = 50
    )
    {
        $data = [];
        if ($searchTerm)
            $data['searchTerm'] = $searchTerm;

        if ($requestOwnership)
            $data['requestOwnership'] = $requestOwnership;

        if ($requestStatus)
            $data['requestStatus'] = $requestStatus;

        if ($serviceDeskId)
            $data['serviceDeskId'] = $serviceDeskId;

        if ($requestTypeId)
            $data['requestTypeId'] = $requestTypeId;

        if ($expand)
            $data['expand'] = $expand;

        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request?' . http_build_query($data))
            ->request();
    }

    /**
     * Returns the customer request for a given request Id/key.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request-getCustomerRequestByIdOrKey
     * @param string $issueIdOrKey
     * @param string|bool $expand
     * @return Response
     */
    public function getCustomerRequestByIdOrKey($issueIdOrKey, $expand = false)
    {
        if ($expand)
            $expand = '/' . $expand;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . $expand)
            ->request();
    }

    /**
     * Creates a public or internal comment on an existing customer request.
     * The currently logged-in user will be the author of the comment.
     * The comment visibility is set by the public field.
     * Permissions:
     * Setting comment visibility depends on the calling user's permissions.
     * For example, Agents can create either public or internal comments, Unlicensed users can only create internal comments, and Customers can only create public comments.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/comment-createRequestComment
     * @param string $issueIdOrKey
     * @param string $comment
     * @param bool $public
     * @return Response
     */
    public function createRequestComment($issueIdOrKey, $comment, $public = false)
    {
        $data = [];
        $data['body'] = $comment;
        $data['public'] = $public;

        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->setUrl('request/' . $issueIdOrKey . '/comment')
            ->request();
    }

    /**
     * Returns all comments on a customer request, for a given request Id/key.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/comment-getRequestComments
     * @param string $issueIdOrKey
     * @param bool $public Specifies whether to return public comments or not. Default: true.
     * @param bool $internal Specifies whether to return internal comments or not. Default: true.
     * @param integer $start The starting index of the returned comments. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of comments to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getRequestComments($issueIdOrKey, $public = true, $internal = true, $start = 0, $limit = 50)
    {
        $data = [];
        $data['public'] = $public;
        $data['internal'] = $internal;
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/comment?' . http_build_query($data))
            ->request();
    }

    /**
     * Returns a specific comment of a specific customer request based on the provided comment ID.
     * Permissions:
     * The calling user must have permission to view the comment.
     * For example, customers can only view public comments on requests where they are the reporter or a participant whereas agents can see both internal and public comments.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/comment-getRequestCommentById
     * @param $issueIdOrKey
     * @param $commentId
     * @return Response
     */
    public function getRequestCommentById($issueIdOrKey, $commentId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/comment/' . $commentId)
            ->request();
    }

    /**
     * Removes participants from an existing customer request.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/participant-removeRequestParticipants
     * @param string $issueIdOrKey
     * @param array $users
     * @return Response
     */
    public function removeRequestParticipants($issueIdOrKey, $users = [])
    {
        if (!empty($users))
            $users = '/' . http_build_query(['usernames' => $users]);

        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setUrl('request/' . $issueIdOrKey . '/participant' . $users)
            ->request();
    }

    /**
     * Returns all users participating in a customer request, for a given request Id/key.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/participant-getRequestParticipants
     * @param string $issueIdOrKey
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of request types to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getRequestParticipants($issueIdOrKey, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/participant?' . http_build_query($data))
            ->request();
    }

    /**
     * Adds users as participants to an existing customer request.
     * Note, you can also add participants when creating a request via the request resource, by using the requestParticipants field.
     * Permissions:
     * The calling user must have permission to manage participants for this customer request.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/participant-addRequestParticipants
     * @param string $issueIdOrKey
     * @param array $users
     * @return Response
     */
    public function addRequestParticipants($issueIdOrKey, $users = [])
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData(['usernames' => $users])
            ->setUrl('request/' . $issueIdOrKey . '/participant' . $users)
            ->request();
    }

    /**
     * Returns the SLA information for a customer request for a given request Id/key.
     * A request can have zero or more SLA values.
     * Each SLA value can have zero or more "completed cycles" and zero or 1 "ongoing cycles".
     * Each cycle has information on when it started and stopped, and whether it breached the SLA goal.
     * Permissions:
     * The calling user must be an agent.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/sla-getSlaInformation
     * @param string $issueIdOrKey
     * @param int $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param int $limit The maximum number of request types to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getSlaInformation($issueIdOrKey, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/sla?' . http_build_query($data))
            ->request();
    }

    /**
     * Returns the SLA information for a customer request for a given request Id/key and SLA metric Id.
     * A request can have zero or more SLA values.
     * Each SLA value can have zero or more "completed cycles" and zero or 1 "ongoing cycles".
     * Each cycle has information on when it started and stopped, and whether it breached the SLA goal.
     * Permissions:
     * The calling user must be an agent.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/sla-getSlaInformationById
     * @param $issueIdOrKey
     * @param $slaMetricId
     * @return Response
     */
    public function getSlaInformationById($issueIdOrKey, $slaMetricId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/sla/' . $slaMetricId)
            ->request();
    }

    /**
     * Adds one or more temporary attachments that were created using Attach temporary file to a customer request.
     * The attachment visibility is set by the public field.
     * Setting attachment visibility is dependent on the user's permission.
     * For example, Agents can create either public or internal attachments, while Unlicensed users can only create internal attachments, and Customers can only create public attachments.
     * An additional comment may be provided which will be prepended to the attachments.
     * @see https://docs.atlassian.com/jira-servicedesk/REST/cloud/#servicedeskapi/request/{issueIdOrKey}/attachment-createAttachment
     * @param $issueIdOrKey
     * @param AttachmentModel $attachmentModel
     * @return Response
     */
    public function createAttachment($issueIdOrKey, AttachmentModel $attachmentModel)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData((array)$attachmentModel)
            ->setUrl('request/' . $issueIdOrKey . '/attachment')
            ->setExperimentalApi()
            ->request();
    }
}