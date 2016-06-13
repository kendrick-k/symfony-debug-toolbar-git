<?php
namespace Kendrick\SymfonyDebugToolbarGit\Git;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Git
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * verifies that the directory exists
     * @return bool
     */
    public function getGitDir()
    {
        $gitDir = $this->container->get('kernel')->getRootDir()."/../";
        $gitDir .= $this->container->getParameter('symfony_debug_toolbar_git.repository_local_dir').'.git';

        return $gitDir;
    }

    /**
     * @param $command
     * @return string
     */
    public function shellExec($command)
    {
        $command = sprintf('cd %s && %s', $this->getGitDir(), $command);
        $resultCommand = shell_exec($command);

        return (string)trim($resultCommand);
    }

    /**
     * @param $command
     * @return array
     */
    public function exec($command)
    {
        $command = sprintf('cd %s && %s', $this->getGitDir(), $command);
        exec($command, $resultCommand);

        return $resultCommand;
    }
}
