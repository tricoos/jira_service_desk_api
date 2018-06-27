<?php

namespace JiraServiceDesk\Service;

class CustomerService
{
    private $service;

    /**
     * CustomerService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * This method adds a customer to the Jira Service Desk instance by passing a JSON file
     * including an email address and display name. The display name does not need to be unique.
     * The record’s identifiers, name and key, are automatically generated from the request details.
     * Permissions:
     * JIRA administrator global permission is required to create a customer.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-customer-post
     * @param string $displayName
     * @param string $email
     * @return Response
     */
    public function createCustomer($displayName, $email)
    {
        return $this->service
            ->setType(Service::REQUEST_METHOD_POST)
            ->setPostData([
                'email' => $email,
                'displayName' => $displayName
            ])
            ->setUrl('customer')
            ->request();
    }
}