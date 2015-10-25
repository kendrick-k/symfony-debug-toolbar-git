<?php

namespace Kendrick\SymfonyDebugToolbarGit\DataCollector;

use Kendrick\SymfonyDebugToolbarGit\Git\Git;
use Kendrick\SymfonyDebugToolbarGit\Git\GitLastCommit;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;
use Kendrick\SymfonyDebugToolbarGit\Git\GitCommand;
use Symfony\Component\Process\Process;

/**
 * Class GitDataCollector
 * @package Kendrick\SymfonyDebugToolbarGit\DataCollector
 */
class GitDataCollector extends DataCollector
{
    private $container;
    private $gitService;

    /**
     * @param $repositoryCommitUrl
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->gitService = $this->container->get('debug.toolbar.git');
        $this->data['repositoryCommitUrl'] = $this->container->getParameter('symfony_debug_toolbar_git.repository_commit_url');
        $this->data['gitData'] = true;
    }

    /**
     * Collect Git data for DebugBar (branch,commit,author,email,merge,date,message)
     *
     * @param Request $request
     * @param Response $response
     * @param \Exception $exception
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        if (!$this->gitService->GitDirExist()) {
            $this->data['gitData'] = false;
            return;
        }

        $this->data['branch'] = $this->gitService->shellExec(GitCommand::GIT_CURRENT_BRANCH);
        $this->data['commit'] = $this->gitService->shellExec(GitCommand::GIT_HASH_LAST_COMMIT);
        $this->data['author'] = $this->gitService->shellExec(GitCommand::GIT_AUTHOR_LAST_COMMIT);
        $this->data['email'] = $this->gitService->shellExec(GitCommand::GIT_EMAIL_LAST_COMMIT);
        $this->data['message'] = $this->gitService->shellExec(GitCommand::GIT_MESSAGE_LAST_COMMIT);
        $this->data['merge'] = $this->gitService->shellExec(GitCommand::GIT_ABBREVIATED_PARENT_HASHES. $this->data['commit']);
        $this->getDateCommit();
    }


    /**
     * true if there is some data : used by the view
     *
     * @return string
     */
    public function getGitData()
    {
        return $this->getData('gitData');
    }

    /**
     * Actual branch name
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->getData('branch');
    }

    /**
     * Commit ID
     *
     * @return string
     */
    public function getCommit()
    {
        return $this->getData('commit');
    }

    /**
     * Merge information
     *
     * @return string
     */
    public function getMerge()
    {
        return $this->getData('merge');
    }

    /**
     * Author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->getData('author');
    }

    /**
     * Author's email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getData('email');
    }

    /**
     * Commit date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->getData('date');
    }

    /**
     * Minutes since last commit
     *
     * @return string
     */
    public function getTimeCommitIntervalMinutes()
    {
        return $this->getData('timeCommitIntervalMinutes');
    }

    /**
     * Seconds since latest commit
     *
     * @return string
     */
    public function getTimeCommitIntervalSeconds()
    {
        return $this->getData('timeCommitIntervalSeconds');
    }

    /**
     * Commit message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->getData('message');
    }

    /**
     * Commit URL
     *
     * @return string
     */
    public function getCommitUrl()
    {
        return $this->data['repositoryCommitUrl'];
    }

    /**
     * Checks and returns the data
     *
     * @param string $data
     * @return string
     */
    private function getData($data)
    {
        return (isset($this->data[$data])) ? $this->data[$data] : '';
    }

    /**
     * DataCollector name : used by service declaration into container.yml
     *
     * @return string
     */
    public function getName()
    {
        return 'datacollector_git';
    }

    /**
     * Change icons color according to the version of symfony
     *
     * #3f3f3f < 2.8
     * #AAAAAA >= 2.8
     *
     * @return string
     */
    public final function getIconColor()
    {
        if ((float)$this->getSymfonyVersion() >= 2.8) {
            return $this->data['iconColor'] = '#AAAAAA';
        }
        return $this->data['iconColor'] = '#3F3F3F';#3F3F3F
    }

    /**
     * @return string
     */
    private function getSymfonyVersion()
    {
        $symfonyVersion = \Symfony\Component\HttpKernel\Kernel::VERSION;
        $symfonyVersion = explode('.', $symfonyVersion, -1);
        $symfonyMajorMinorVersion = implode('.', $symfonyVersion);
        return $symfonyMajorMinorVersion;
    }

    private function verifyExistMergeInCommit()
    {
        $lastCommit = $this->gitService->exec(GitCommand::GIT_LOG_MINUS_ONE);
        if (strpos($lastCommit, 'Merge') === 0) {
            // merge information
            $this->data['merge'] = trim(substr($lastCommit, 6));
        }
    }

    private function getDateCommit()
    {
        $date = $this->gitService->shellExec(GitCommand::GIT_COMMIT_DATE);
        $dateCommit = date_create($date);

        // actual date at runtime
        $dateRuntime = new \DateTime();
        $dateNow = date_create($dateRuntime->format('Y-m-d H:i:s'));
        // difference
        $time = date_diff($dateCommit, $dateNow);

        // static time difference : minutes and seconds
        $this->data['timeCommitIntervalMinutes'] = $time->format('%y') * 365 * 24 * 60 + $time->format('%m') * 30 * 24 * 60 + $time->format('%d') * 24 * 60 + $time->format('%h') * 60 + $time->format('%i');
        // full readable date
        $this->data['date'] = $date;
    }
}

