<?php

namespace JiraServiceDesk\Model;

class AdditionalCommentModel
{
    /**
     * @var string
     */
    public $id;

    public $additionalComment;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return AdditionalCommentModel
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdditionalComment()
    {
        return $this->additionalComment;
    }

    /**
     * @param mixed $comment
     * @return AdditionalCommentModel
     */
    public function setAdditionalComment($comment)
    {
        $this->additionalComment['body'] = $comment;
        return $this;
    }

}