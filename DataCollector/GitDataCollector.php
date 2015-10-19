<?php

namespace Kendrick\SymfonyDebugToolbarGit\DataCollector;

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
    private $gitRootDir;

    /**
     * @param $repositoryCommitUrl
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->data['repositoryCommitUrl'] = $this->container->getParameter('symfony_debug_toolbar_git.repository_commit_url');

        $this->data['gitDir'] = $this->container->get('kernel')->getRootDir() . "/..";
        $this->data['gitDir'] .= $this->container->getParameter('symfony_debug_toolbar_git.repository_local_dir') . '/.git';

        $this->data['branch'] = $this->shellGit(GitCommand::GIT_CURRENT_BRANCH)[0];

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
        // if there is no .git directory
        if (!$this->gitRepositoryExist()) {
            $this->data['gitData'] = false;
            return;
        }

        $data = $this->shellGit(GitCommand::GIT_LOG_MINUS_ONE);

        if (isset($data) && !empty($data)) {

            // there is some information
            $this->data['gitData'] = true;

            foreach ($data as $d) {

                if (strpos($d, 'commit') === 0) {

                    // commit Id

                    $this->data['commit'] = substr($d, 7);

                } elseif (strpos($d, 'Author') === 0) {

                    // author and email

                    preg_match('$Author: ([^<]+)<(.+)>$', $d, $author);

                    if (isset($author[1])) {
                        $this->data['author'] = trim($author[1]);
                    }
                    if (isset($author[2])) {
                        $this->data['email'] = $author[2];
                    }

                } elseif (strpos($d, 'Date') === 0) {

                    $date = trim(substr($d, 5)); // ddd mmm n hh:mm:ss yyyy +gmt

                    // date of commit
                    $dateCommit = date_create($date);

                    // actual date at runtime
                    $dateRuntime = new \DateTime();
                    $dateNow = date_create($dateRuntime->format('Y-m-d H:i:s'));

                    // difference
                    $time = date_diff($dateCommit, $dateNow);

                    // static time difference : minutes and seconds
                    $this->data['timeCommitIntervalMinutes'] = $time->format('%y') * 365 * 24 * 60 + $time->format('%m') * 30 * 24 * 60 + $time->format('%d') * 24 * 60 + $time->format('%h') * 60 + $time->format('%i');
                    $this->data['timeCommitIntervalSeconds'] = $time->format('%s');

                    // full readable date
                    $this->data['date'] = $date;

                } elseif (strpos($d, 'Merge') === 0) {

                    // merge information
                    $this->data['merge'] = trim(substr($d, 6));

                } else {

                    // commit message
                    $this->data['message'] = trim($d);
                }
            }
            unset($data);

        } else {
            $this->data['gitData'] = false;
        }
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

    /**
     * @return mixed gitDir
     */
    private function getGitDir()
    {
        return $this->data['gitDir'];
    }

    /**
     * @return bool
     */
   final private function gitRepositoryExist()
    {
        if (file_exists($this->getGitDir())) {
            return true;
        }
        return false;
    }

    final private function shellGit($gitCommad)
    {
        exec('cd '.$this->getGitDir().' && '.$gitCommad,$data);
        return $data;
    }

}
