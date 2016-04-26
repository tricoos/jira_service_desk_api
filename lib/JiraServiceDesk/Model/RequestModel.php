<?php

namespace JiraServiceDesk\Model;

class RequestModel
{

    /**
     * VARS
     */
    const VAR_COMPONENTS = 'components';
    const VAR_DESCRIPTION = 'description';
    const VAR_DUE_DATE = 'duedate';
    const VAR_LABELS = 'labels';
    const VAR_SUMMARY = 'summary';

    /**
     * @var integer
     */
    public $serviceDeskId;

    /**
     * @var integer
     */
    public $requestTypeId;

    /**
     * Schema of requestFieldValues field is a map of JIRA's field's ID and its value, which are JSON ready objects.
     * The object value will be interpreted with JSON semantics according to the specific field requirements.
     * So a simple field like summary or number customer field might take String / Integer while other fields like Multi User Picker will take a more complex object that has JSON semantics.
     * Refer to Field input formats reference on what field types take what values.
     * @var array
     */
    public $requestFieldValues;

    /**
     * Not available to users who only have the Service Desk customer permission or if the feature is turned off for customers.
     * @var array
     */
    public $requestParticipants;

    /**
     * @return int
     */
    public function getServiceDeskId()
    {
        return $this->serviceDeskId;
    }

    /**
     * @param int $serviceDeskId
     * @return RequestModel
     */
    public function setServiceDeskId($serviceDeskId)
    {
        $this->serviceDeskId = $serviceDeskId;
        return $this;
    }

    /**
     * @return int
     */
    public function getRequestTypeId()
    {
        return $this->requestTypeId;
    }

    /**
     * @param int $requestTypeId
     * @return RequestModel
     */
    public function setRequestTypeId($requestTypeId)
    {
        $this->requestTypeId = $requestTypeId;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequestFieldValues()
    {
        return $this->requestFieldValues;
    }

    /**
     * @param string $summary
     * @return RequestModel
     */
    public function setRequestSummary($summary)
    {
        $this->requestFieldValues[self::VAR_SUMMARY] = $summary;
        return $this;
    }

    /**
     * @param string $description
     * @return RequestModel
     */
    public function setRequestDescription($description)
    {
        $this->requestFieldValues[self::VAR_DESCRIPTION] = $description;
        return $this;
    }

    /**
     * @param string $due_date date('Y-m-d')
     * @return RequestModel
     */
    public function setRequestDueDate($due_date)
    {
        $this->requestFieldValues[self::VAR_DUE_DATE] = $due_date;
        return $this;
    }

    /**
     * @param array $labels
     * @return RequestModel
     */
    public function setRequestLabels($labels)
    {
        $this->requestFieldValues[self::VAR_LABELS] = $labels;
        return $this;
    }

    /**
     * @param array $components
     * @return RequestModel
     */
    public function setRequestComponents($components)
    {
        $this->requestFieldValues[self::VAR_COMPONENTS] = $components;
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return RequestModel
     */
    public function addRequestComponent($key, $value)
    {
        $this->requestFieldValues[self::VAR_COMPONENTS][] = [$key => $value];
        return $this;
    }

    /**
     * @param string $label
     * @return RequestModel
     */
    public function addRequestLabels($label)
    {
        $this->requestFieldValues[self::VAR_LABELS][] = $label;
        return $this;
    }

    /**
     * @param string $field_key
     * @param mixed $value
     * @return RequestModel
     */
    public function setRequestCustomField($field_key, $value)
    {
        $this->requestFieldValues[$field_key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getRequestParticipants()
    {
        return $this->requestParticipants;
    }

    /**
     * @param array $requestParticipants
     * @return RequestModel
     */
    public function setRequestParticipants($requestParticipants)
    {
        $this->requestParticipants = $requestParticipants;
        return $this;
    }

    /**
     * @param string $requestParticipant
     * @return RequestModel
     */
    public function addRequestParticipant($requestParticipant)
    {
        $this->requestParticipants[] = $requestParticipant;
        return $this;
    }


}