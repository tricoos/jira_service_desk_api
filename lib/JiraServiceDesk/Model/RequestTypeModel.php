<?php

namespace JiraServiceDesk\Model;

class RequestTypeModel
{
    /**
     * @var string
     */
    public $issueTypeId;

    public $name;

    public $description;

    public $helpText;

    /**
     * @return string
     */
    public function getIssueTypeId()
    {
        return $this->issueTypeId;
    }

    /**
     * @param string $issueTypeId
     * @return RequestTypeModel
     */
    public function setIssueTypeId($issueTypeId)
    {
        $this->issueTypeId = $issueTypeId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return RequestTypeModel
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return RequestTypeModel
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @param mixed $helpText
     * @return RequestTypeModel
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
        return $this;
    }

}