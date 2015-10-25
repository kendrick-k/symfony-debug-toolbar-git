<?php
namespace Kendrick\SymfonyDebugToolbarGit\Git;

use Symfony\Component\DependencyInjection\ContainerInterface;
class Git
{
    protected $gitDir;
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     *
     * @return string  The Git direcotry especificated in config_dev
     */
    public function getGitDirInConfiguration()
    {
        $gitDir = $this->container->getParameter('symfony_debug_toolbar_git.repository_local_dir') . '/.git';
        return $gitDir;
    }

    /**
     * verifies that the directory exists
     * @return bool
     */
    public function GitDirExist()
    {
        $this->gitDir = $this->container->get('kernel')->getRootDir() . "/..";
        $this->gitDir .= $this->getGitDirInConfiguration();

        if (!file_exists($this->gitDir)) {
            return false;
        }

        return true;
    }

    /**
     * @param $command
     * @return string
     */
    public final function shellExec($command)
    {
        $command = sprintf('cd %s && %s', $this->gitDir, $command);
        $resultCommand = shell_exec($command);

        return (string)trim($resultCommand);
    }

    /**
     * @param $command
     * @return array
     */
    public final function exec($command)
    {
        $command = sprintf('cd %s && %s', $this->gitDir, $command);
        exec($command, $resultCommand);

        return $resultCommand;
    }
}

