<?php

namespace JiraServiceDesk\Model;

class AttachmentModel
{
    /**
     * @var array
     */
    public $temporaryAttachmentIds;

    /**
     * @var boolean
     */
    public $public = true;

    public $additionalComment;

    /**
     * @return mixed
     */
    public function getTemporaryAttachmentIds()
    {
        return $this->temporaryAttachmentIds;
    }

    /**
     * @param mixed $temporaryAttachmentIds
     * @return AttachmentModel
     */
    public function setTemporaryAttachmentIds($temporaryAttachmentIds)
    {
        $this->temporaryAttachmentIds = $temporaryAttachmentIds;
        return $this;
    }

    /**
     * @param string $temporaryAttachmentId
     * @return AttachmentModel
     */
    public function addTemporaryAttachmentId($temporaryAttachmentId)
    {
        $this->temporaryAttachmentIds[] = $temporaryAttachmentId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * @param mixed $public
     * @return AttachmentModel
     */
    public function setPublic($public)
    {
        $this->public = $public;
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
     * @return AttachmentModel
     */
    public function setAdditionalComment($comment)
    {
        $this->additionalComment['body'] = $comment;
        return $this;
    }

}