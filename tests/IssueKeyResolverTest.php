<?php

use DAG\JIRA\Post\IssueKeyResolver;

class IssueKeyResolverTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['FOO-123', 'feature/FOO-123-Add-Something'],
            ['FOO-123', 'hotfix/FOO-123-Add-Something'],
            ['FOO-123', 'FOO-123-Add-Something'],
            ['FOO-123', 'release/v0.1.0-FOO-123-Add-Something'],
            ['FOO-123', 'hotfix/v0.1.0-FOO-123-Add-Something'],
            ['FOO-123', 'FOO-123-Add-Something'],
            ['FOO-123', 'FOO-123'],
        ];
    }

    /**
     * @param string $issueKey
     * @param string $branchName
     *
     * @dataProvider dataProvider
     */
    public function testIssueKeyResolved($expectedIssueKey, $branchName)
    {
        $resolver = new IssueKeyResolver();
        $actualIssueKey = $resolver->resolveKeyFromBranchName($branchName);
        $this->assertEquals($expectedIssueKey, $actualIssueKey);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExceptionThrownIfInvalidBranchName()
    {
        $invalidBranchName = "Fix something";
        $resolver = new IssueKeyResolver();
        $resolver->resolveKeyFromBranchName($invalidBranchName);
    }
}
