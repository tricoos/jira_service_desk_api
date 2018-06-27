<?php

namespace JiraServiceDesk\Service;

class KnowledgebaseService
{
    private $service;

    /**
     * KnowledgebaseService constructor.
     * @param Service $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    /**
     * Returns articles which match the given query string across all service desks.
     * @see https://developer.atlassian.com/cloud/jira/service-desk/rest/#api-knowledgebase-article-get
     * @param string $query
     * @param bool $highlight If set to true matching query term in the title and excerpt will be highlighted using the {@code @@@hl@@@term@@@endhl@@@} syntax.
     * @param integer $start The starting index of the returned comments. Base index: 0. See the Pagination section for more details.
     * @param integer $limit The maximum number of comments to return per page. Default: 100. See the Pagination section for more details.
     * @return Response
     */
    public function getArticles($query, $highlight = false, $start = 0, $limit = 100)
    {
        $data = [];
        $data['query'] = $query;
        $data['highlight'] = $highlight;
        $data['start'] = $start;
        $data['limit'] = $limit;

        return $this->service
            ->setType(Service::REQUEST_METHOD_GET)
            ->setUrl('knowledgebase/article?' . http_build_query($data))
            ->setExperimentalApi()
            ->request();
    }
}