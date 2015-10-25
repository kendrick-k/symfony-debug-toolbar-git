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
        $gitDir = $this->container->get('kernel')->getRootDir() . "/..";
        $gitDir .= $this->getGitDirInConfiguration();

        return file_exists($gitDir);
    }

    /**
     * @param $command
     * @return string
     */
    public final function shellExec($command)
    {
        $command = sprintf('cd %s && %s', $this->GitDirExist(), $command);
        $resultCommand = shell_exec($command);

        return (string)trim($resultCommand);
    }

    /**
     * @param $command
     * @return array
     */
    public final function exec($command)
    {
        $command = sprintf('cd %s && %s', $this->GitDirExist(), $command);
        exec($command, $resultCommand);

        return $resultCommand;
    }
}

