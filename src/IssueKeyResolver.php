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
     * @param string $project
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function resolveKeyFromBranchName($branchName, $project = "")
    {
        $groups = [];
        if (preg_match('@^(feature|hotfix|bugfix|bug)/([\w]+\-[\d]+)@', $branchName, $groups)) {
            // Ex: feature/PROJECT-123
            return $groups[2];
        } else if (preg_match('@^([\w]+\-[\d]+)\-.*@', $branchName, $groups)) {
            // Ex: PROJECT-123-My-Message
            return $groups[1];
        } else if (preg_match('@^(release|hotfix)/[\w\.]+\-([\w]+\-[\d]+)\-.*@', $branchName, $groups)) {
            // Ex: release/v1.0.0-PROJECT-123-My-Message
            return $groups[2];
        } else if (preg_match('@^([\w]+\-[\d]+)@', $branchName, $groups)) {
            // Ex: PROJECT-123
            return $groups[1];
        }

        if ($project) {
            if (preg_match('@^(feature|hotfix|bugfix|bug)/([\d]+)@', $branchName, $groups)) {
                // Ex: feature/123
                return $project . "-" . $groups[2];
            } else if (preg_match('@^([\d]+)\-.*@', $branchName, $groups)) {
                // Ex: 123-My-Message
                return $project . "-" . $groups[1];
            } else if (preg_match('@^(release|hotfix)/[\w\.]+\-([\d]+)\-.*@', $branchName, $groups)) {
                // Ex: release/v1.0.0-123-My-Message
                return $project . "-" . $groups[2];
            } else if (preg_match('@^([\d]+)@', $branchName, $groups)) {
                // Ex: 123
                return $project . "-" . $groups[1];
            }
        }

        throw new InvalidArgumentException(
            sprintf('The branch name "%s" is not valid', $branchName)
        );
    }
}
