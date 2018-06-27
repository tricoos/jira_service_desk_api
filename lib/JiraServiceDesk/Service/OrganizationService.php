<?php

namespace JiraServiceDesk\Service;

class OrganizationService
{
    private $service;

    /**
     * OrganizationService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * This method returns a list of organizations in the Jira Service Desk instance.
     * Use this method when you want to present a list of organizations or want to locate an organization by name.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-get
     * @param integer $start The starting index of the returned comments. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of comments to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getOrganizations($start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('organization?' . http_build_query($data))
            ->request();
    }

    /**
     * This method creates an organization by passing the name of the organization.
     * Permissions:
     * Service desk administrator or agent. Note: Permission to create organizations can be switched to
     * users with the Jira administrator permission, using the Organization management feature.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-post
     * @param string $name
     * @return Response
     */
    public function createOrganization($name)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData(['name' => $name])
            ->setUrl('organization')
            ->request();
    }

    /**
     * This method returns details of an organization. Use this method to get organization details
     * whenever your application component is passed an organization ID but needs to display other organization details.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-get
     * @param integer $organizationId The ID of the organization.
     * @return Response
     */
    public function getOrganization($organizationId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('organization/' . $organizationId)
            ->request();
    }

    /**
     * This method deletes an organization. Note that the organization is deleted regardless of other
     * associations it may have, for example, associations with service desks.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-delete
     * @param integer $organizationId
     * @return Response
     */
    public function deleteOrganization($organizationId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setUrl('organization/' . $organizationId)
            ->request();
    }

    /**
     * Returns the keys of all properties for an organization. Use this resource when you need to
     * find out what additional properties items have been added to an organization.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-property-get
     * @param integer $organizationId The ID of the organization from which keys will be returned.
     * @return Response
     */
    public function getPropertiesKeys($organizationId)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('organization/' . $organizationId . '/property')
            ->request();
    }

    /**
     * Returns the value of a property from an organization. Use this method to obtain the
     * JSON content for an organization’s property.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-property-propertyKey-get
     * @param integer $organizationId The ID of the organization from which the property will be returned.
     * @param string $propertyKey The key of the property to return.
     * @return Response
     */
    public function getProperty($organizationId, $propertyKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('organization/' . $organizationId . '/property/' . $propertyKey)
            ->request();
    }

    /**
     * Sets the value of a property for an organization.
     * Use this resource to store custom data against an organization.
     * Permissions:
     * Service Desk Administrator or Agent. Note: Permission to create organizations can be switched to
     * users with the Jira administrator permission, using the Organization management feature.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-property-propertyKey-put
     * @param integer $organizationId The ID of the organization from which the property will be returned.
     * @param string $propertyKey The key of the property to return.
     * @param array $data Data
     * @return Response
     */
    public function setProperty($organizationId, $propertyKey, $data)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_PUT)
            ->setPutData($data)
            ->setUrl('organization/' . $organizationId . '/property/' . $propertyKey)
            ->request();
    }

    /**
     * Removes a property from an organization.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-property-propertyKey-delete
     * @param integer $organizationId The ID of the organization from which the property will be returned.
     * @param string $propertyKey The key of the property to return.
     * @return Response
     */
    public function deleteProperty($organizationId, $propertyKey)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setUrl('organization/' . $organizationId . '/property/' . $propertyKey)
            ->request();
    }

    /**
     * This method returns all the users associated with an organization.
     * Use this method where you want to provide a list of users for an organization
     * or determine if a user is associated with an organization.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-user-get
     * @param integer $organizationId The ID of the organization from which the property will be returned.
     * @param integer $start The starting index of the returned comments. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of comments to return per page. Default: 50. See the Pagination section for more details.
     * @return Response
     */
    public function getUsers($organizationId, $start = 0, $limit = 50)
    {
        $data = [];
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('organization/' . $organizationId . '/user?' . http_build_query($data))
            ->request();
    }

    /**
     * This method adds users to an organization by passing a list of usernames (name from the customer record).
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-user-post
     * @param integer $organizationId The ID of the organization from which the property will be returned.
     * @param array $usernames List of customers, specified by ‘name’ field values, to add to or remove from the organization.
     * @return Response
     */
    public function addUsers($organizationId, $usernames)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData(['usernames' => $usernames])
            ->setUrl('organization/' . $organizationId . '/user')
            ->request();
    }

    /**
     * This method removes users from an organization by passing a list of usernames (name from the customer record).
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-organization-organizationId-user-delete
     * @param integer $organizationId The ID of the organization from which the property will be returned.
     * @param array $usernames List of customers, specified by ‘name’ field values, to add to or remove from the organization.
     * @return Response
     */
    public function removeUsers($organizationId, $usernames)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_DELETE)
            ->setDeleteData(['usernames' => $usernames])
            ->setUrl('organization/' . $organizationId . '/user')
            ->request();
    }
}