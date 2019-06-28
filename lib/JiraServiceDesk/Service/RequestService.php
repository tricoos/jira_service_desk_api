<?php

namespace JiraServiceDesk\Service;

use JiraServiceDesk\Model\AdditionalCommentModel;
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
     * This method returns all customer requests for the user executing the query.
     * The returned customer requests are ordered chronologically by the latest activity on each request. For example, the latest status transition or comment.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-get
     * @param string $approvalStatus
     * @param string[] $expand
     * @param string[] $requestOwnership
     * @param string $requestStatus
     * @param integer $organizationId
     * @param integer $requestTypeId
     * @param string $searchTerm
     * @param integer $serviceDeskId
     * @param integer $limit
     * @param integer $start
     * @return Response
     */
    public function getCustomerRequests(
        $approvalStatus = null,
        $expand = null,
        $organizationId = null,
        $requestOwnership = null,
        $requestStatus = null,
        $requestTypeId = null,
        $searchTerm = null,
        $serviceDeskId = null,
        $limit = 50,
        $start = 0
    )
    {
        $data = [];
        if ($approvalStatus)
            $data['approvalStatus'] = $approvalStatus;

        if ($expand)
            $data['expand'] = $expand;

        if ($organizationId)
            $data['organizationId'] = $organizationId;

        if ($requestOwnership)
            $data['requestOwnership'] = $requestOwnership;

        if ($requestStatus)
            $data['requestStatus'] = $requestStatus;

        if ($requestTypeId)
            $data['requestTypeId'] = $requestTypeId;

        if ($searchTerm)
            $data['searchTerm'] = $searchTerm;

        if ($serviceDeskId)
            $data['serviceDeskId'] = $serviceDeskId;

        $data['limit'] = $limit;
        $data['start'] = $start;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request?' . http_build_query($data))
            ->request();
    }

    /**
     * This method creates a customer request in a service desk.
     * The JSON request must include the service desk and customer request type,
     * as well as any fields that are required for the request type.
     * A list of the fields required by a customer request type
     * can be obtained using servicedesk/{serviceDeskId}/requesttype/{requestTypeId}/field.
     * The fields required for a customer request type depend on the user’s permissions:
     * raiseOnBehalfOf is not available to Users who have the customer permission only.
     * requestParticipants is not available to Users who have the customer permission only or if the feature is turned off for customers.
     * requestFieldValues is a map of Jira field IDs and their values. See Field input formats, for details of each field’s JSON semantics and the values they can take.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-post
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
     * This method returns a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-get
     * @param string $issueIdOrKey
     * @param string[] $expand
     * @return Response
     */
    public function getCustomerRequestByIdOrKey($issueIdOrKey, $expand = null)
    {
        $data = [];

        if ($expand)
            $data['expand'] = $expand;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '?' . http_build_query($data))
            ->request();
    }

    /**
     * This method returns all approvals on a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-approval-get
     * @param string $issueIdOrKey
     * @param integer $start
     * @param integer $limit
     * @return Response
     */
    public function getApprovals($issueIdOrKey, $start = 0, $limit = 50)
    {
        $data = [];

        if ($start)
            $data['start'] = $start;

        if ($limit)
            $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/approval?' . http_build_query($data))
            ->request();
    }

    /**
     * This method returns an approval. Use this method to determine the status of an approval and the list of approvers.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-approval-approvalId-get
     * @param integer $approvalId
     * @param string $issueIdOrKey
     * @return Response
     */
    public function getApprovalById($issueIdOrKey, $approvalId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/approval/' . $approvalId)
            ->request();
    }

    /**
     * This method enables a user to Approve or Decline an approval on a customer request.
     * The approval is assumed to be owned by the user making the call.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-approval-approvalId-post
     * @param integer $approvalId
     * @param string $issueIdOrKey
     * @param string $decision
     * @return Response
     */
    public function createAnswerApproval($issueIdOrKey, $approvalId, $decision)
    {
        $data = [];
        $data['decision'] = $decision;

        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData($data)
            ->setUrl('request/' . $issueIdOrKey . '/approval/' . $approvalId)
            ->request();
    }

    /**
     * This method returns all the attachments for a customer requests.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-attachment-get
     * @param string $issueIdOrKey
     * @param integer $start
     * @param integer $limit
     * @return Response
     */
    public function getAttachments($issueIdOrKey, $start = 0, $limit = 50)
    {
        $data = [];

        if ($start)
            $data['start'] = $start;

        if ($limit)
            $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/attachment?' . http_build_query($data))
            ->request();
    }

    /**
     * This method adds a temporary attachment. This is required for the createAttachment method below.
     *
     * @param int $serviceDeskId
     * @param string $filePath The local file path
     *
     * @return Response
     *
     * @see https://docs.atlassian.com/jira-servicedesk/REST/3.6.2/#servicedeskapi/servicedesk/{serviceDeskId}/attachTemporaryFile
     */
    public function createTemporaryAttachment($serviceDeskId, $filePath)
    {
        $multiPartOptions = [
            [
                'name'     => 'file',
                'contents' => file_get_contents($filePath),
                'filename' => basename($filePath)
            ]
        ];

        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setMultipart($multiPartOptions)
            ->setUrl('servicedesk/'.(int)$serviceDeskId.'/attachTemporaryFile')
            ->request();
    }
    
    /**
     * This method adds one or more temporary files (attached to the request’s service desk
     * using servicedesk/{serviceDeskId}/attachTemporaryFile) as attachments to a customer request and
     * set the attachment visibility using the public flag.
     * Also, it is possible to include a comment with the attachments.
     * To get a list of attachments for a comment on the request
     * use servicedeskapi/request/{issueIdOrKey}/comment/{commentId}/attachment.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-attachment-post
     * @param string $issueIdOrKey
     * @param AttachmentModel $attachmentModel
     * @return Response
     */
    public function createAttachment($issueIdOrKey, AttachmentModel $attachmentModel)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData((array)$attachmentModel)
            ->setUrl('request/' . $issueIdOrKey . '/attachment')
            ->request();
    }

    /**
     * This method returns all comments on a customer request. No permissions error is provided if, for example,
     * the user doesn’t have access to the service desk or request, the method simply returns an empty response.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-comment-get
     * @param string $issueIdOrKey
     * @param string[] $expand A multi-value parameter indicating which properties of the comment to expand
     * @param bool $internal Specifies whether to return internal comments or not. Default: true.
     * @param bool $public Specifies whether to return public comments or not. Default: true.
     * @param integer $start The starting index of the returned comments. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of comments to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getRequestComments($issueIdOrKey, $expand = [], $internal = true, $public = true, $start = 0, $limit = 50)
    {
        $data = [];
        $data['expand'] = $expand;
        $data['internal'] = $internal;
        $data['public'] = $public;
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/comment?' . http_build_query($data))
            ->request();
    }

    /**
     * This method creates a public or private (internal) comment on a customer request,
     * with the comment visibility set by public. The user recorded as the author of the comment.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-comment-post
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
     * This method returns details of a customer request’s comment.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-comment-commentId-get
     * @param $issueIdOrKey
     * @param $commentId
     * @param $expand
     * @return Response
     */
    public function getRequestCommentById($issueIdOrKey, $commentId, $expand = [])
    {
        $data = [];
        $data['expand'] = $expand;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/comment/' . $commentId . '?' . http_build_query($data))
            ->request();
    }

    /**
     * This method returns the attachments referenced in a comment.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-comment-commentId-attachment-get
     * @param string $issueIdOrKey
     * @param integer $commentId
     * @param integer $start The starting index of the returned comments. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of comments to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getCommentAttachments($issueIdOrKey, $commentId, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/comment/' . $commentId . '/attachment?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }

    /**
     * This method returns the notification subscription status of the user making the request.
     * Use this method to determine if the user is subscribed to a customer request’s notifications.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-notification-get
     * @param string $issueIdOrKey
     * @return Response
     */
    public function getSubscriptionStatus($issueIdOrKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/notification')
            ->request();
    }

    /**
     * This method subscribes the user to receiving notifications from a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-notification-put
     * @param string $issueIdOrKey
     * @return Response
     */
    public function subscribe($issueIdOrKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_PUT)
            ->setUrl('request/' . $issueIdOrKey . '/notification')
            ->request();
    }

    /**
     * This method unsubscribes the user from notifications from a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-notification-delete
     * @param string $issueIdOrKey
     * @return Response
     */
    public function unsubscribe($issueIdOrKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setUrl('request/' . $issueIdOrKey . '/notification')
            ->request();
    }

    /**
     * This method returns a list of all the participants on a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-participant-get
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
     * This method adds participants to a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-participant-post
     * @param string $issueIdOrKey
     * @param string[] $usernames
     * @return Response
     */
    public function addRequestParticipants($issueIdOrKey, $usernames = [])
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData(['usernames' => $usernames])
            ->setUrl('request/' . $issueIdOrKey . '/participant')
            ->request();
    }

    /**
     * This method removes participants from a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-participant-delete
     * @param string $issueIdOrKey
     * @param string[] $usernames
     * @return Response
     */
    public function removeRequestParticipants($issueIdOrKey, $usernames = [])
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setDeleteData(['usernames' => $usernames])
            ->setUrl('request/' . $issueIdOrKey . '/participant')
            ->request();
    }

    /**
     * This method returns all the SLA records on a customer request. A customer request can have zero or more SLAs.
     * Each SLA can have recordings for zero or more “completed cycles” and zero or 1 “ongoing cycle”.
     * Each cycle includes information on when it started and stopped, and whether it breached the SLA goal.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-sla-get
     * @param string $issueIdOrKey
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of request types to return per page. Default: 50. See the Pagination section for more details.
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
     * This method returns the details for an SLA on a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-sla-slaMetricId-get
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
     * This method returns a list of all the statuses a customer Request has achieved.
     * A status represents the state of an issue in its workflow. An issue can have one active status only.
     * The list returns the status history in chronological order, most recent (current) status first.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-status-get
     * @param string $issueIdOrKey
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of request types to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getCustomerRequestStatus($issueIdOrKey, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/status?' . http_build_query($data))
            ->request();
    }

    /**
     * This method returns a list of transitions, the workflow processes
     * that moves a customer request from one status to another, that the user can perform on a request.
     * Use this method to provide a user with a list if the actions they can take on a customer request.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-transition-get
     * @param string $issueIdOrKey
     * @param integer $start The starting index of the returned objects. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of request types to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getCustomerTransitions($issueIdOrKey, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('request/' . $issueIdOrKey . '/transition?' . http_build_query($data))
            ->request();
    }

    /**
     * This method performs a customer transition for a given request and transition.
     * An optional comment can be included to provide a reason for the transition.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-request-issueIdOrKey-transition-post
     * @param string $issueIdOrKey
     * @param AdditionalCommentModel $additionalComment
     * @return Response
     */
    public function performCustomerTransition($issueIdOrKey, AdditionalCommentModel $additionalComment)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData((array)$additionalComment)
            ->setUrl('request/' . $issueIdOrKey . '/transition')
            ->request();
    }
}
