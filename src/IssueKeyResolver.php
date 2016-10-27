<?php
namespace DAG\JIRA\Post;

use InvalidArgumentException;

/**
 * Class IssueKeyResolver
 */
final class IssueKeyResolver
{
    /**
     * @param string $branchName
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function resolveKeyFromBranchName($branchName)
    {
        $groups = [];
        if (preg_match('@^(feature|hotfix)/([\w]+\-[\w]+)@', $branchName, $groups)) {
            // Ex: feature/PROJECT-123
            return $groups[2];
        } else if (preg_match('@^([\w]+\-[\w]+)\-.*@', $branchName, $groups)) {
            // Ex: PROJECT-123-My-Message
            return $groups[1];
        } else if (preg_match('@^(release|hotfix)/[\w\.]+\-([\w]+\-[\w]+)\-.*@', $branchName, $groups)) {
            // Ex: release/v1.0.0-PROJECT-123-My-Message
            return $groups[2];
        } else if (preg_match('@^([\w]+\-[\w]+)@', $branchName, $groups)) {
            // Ex: PROJECT-123
            return $groups[1];
        }

        throw new InvalidArgumentException(
            sprintf('The branch name "%s" is not valid', $branchName)
        );
    }
}
