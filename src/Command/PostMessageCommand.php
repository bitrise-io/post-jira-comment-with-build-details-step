<?php
namespace DAG\JIRA\Post\Command;

use DAG\JIRA\Post\Client;
use DAG\JIRA\Post\IssueKeyResolver;
use Jira\JiraClient;
use Jira_Api;
use Jira_Api_Authentication_Basic;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PostMessageCommand
 */
final class PostMessageCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('post-message')
            ->setDescription('Post the message on JIRA')
            ->addArgument(
                'git-branch',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'jira-user',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'jira-password',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'jira-build-message',
                InputArgument::REQUIRED
            )
            ->addArgument(
                'jira-url',
                InputArgument::REQUIRED
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['variable', 'value']);
        $table->addRow(['git-branch', $input->getArgument('git-branch')]);
        $table->addRow(['jira-url', $input->getArgument('jira-url')]);
        $table->addRow(['jira-user', $input->getArgument('jira-user')]);
        $table->addRow(['jira-build-message', $input->getArgument('jira-build-message')]);

        $table->render();

        $issueKeyResolver = new IssueKeyResolver();
        $issueKey = $issueKeyResolver->resolveKeyFromBranchName($input->getArgument('git-branch'));
        $output->writeln(sprintf('The issue key is "%s"', $issueKey));

        $client = new Client(
            $input->getArgument('jira-user'),
            $input->getArgument('jira-password'),
            $input->getArgument('jira-url')
        );

        $message = $input->getArgument('jira-build-message');
        $client->postComment($issueKey, $message);

        // Set environment variable
        $out = $returnValue = null;
        exec('envman add --key JIRA_ISSUE_KEY --value "'.$issueKey.'"', $out, $returnValue);

        if ($returnValue != 0) {
            throw new \Exception("Can not export environment variable");
        }
    }
}
