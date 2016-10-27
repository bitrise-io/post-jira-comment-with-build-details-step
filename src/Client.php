<?php
namespace DAG\JIRA\Post;

/**
 * Class Client
 */
final class Client
{
    /** @var string */
    private $jiraUser;

    /** @var string */
    private $jiraPassword;

    /** @var string */
    private $jiraURL;

    /**
     * Client constructor.
     *
     * @param string $jiraUser
     * @param string $jiraPassword
     * @param string $jiraURL
     */
    public function __construct($jiraUser, $jiraPassword, $jiraURL)
    {
        $this->jiraUser = $jiraUser;
        $this->jiraPassword = $jiraPassword;
        $this->jiraURL = $jiraURL;
    }

    public function postComment($issueKey, $comment)
    {
        $url = $this->getApiURL($issueKey);

        $payload = [
            'body' => $comment,
        ];

        $postdata = json_encode($payload);

        $auth = base64_encode($this->jiraUser.':'.$this->jiraPassword);

        $header = [
            "Authorization: Basic $auth",
            'Content-type: application/json',
        ];

        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => $header,
                'content' => $postdata,
            ],
        ];

        $context = stream_context_create($opts);
        file_get_contents($url, false, $context);

        if ($http_response_header[0] != 'HTTP/1.1 201 Created') {
            throw new \Exception(sprintf('Could not post comment. HTTP response : "%s"', $http_response_header[0]));
        }
    }

    /**
     * @param string $issueKey
     *
     * @return string
     */
    private function getApiURL($issueKey)
    {
        return $this->jiraURL.'/rest/api/2/issue/'.$issueKey.'/comment';
    }
}
