<?php
namespace Kendrick\SymfonyDebugToolbarGit\Git;

 use Symfony\Component\Config\Definition\Exception\Exception;
 use Symfony\Component\DependencyInjection\ContainerInterface;

 class Git
{
     protected  $gitDir;
     protected  $container;

     public function __construct(ContainerInterface $container)
     {
         $this->container =  $container;
     }

     public function GitDirExist()
     {
         $this->gitDir =  $this->container->get('kernel')->getRootDir() . "/..";
         $this->gitDir.=  $this->container->getParameter('symfony_debug_toolbar_git.repository_local_dir') . '/.git';

         if (!file_exists($this->gitDir)) {
             throw new Exception ('The git directory does not exist. Check repository_local_dir parameter of SymfonyDebugToolbar extension git');
         }
         return true;
     }


     /**
      * @param $command
      * @return string
      */
     public final function shellExec($command)
     {
         $command  = sprintf('cd %s && %s', $this->gitDir,$command);
         $resultCommand = shell_exec($command);
         return (string) $resultCommand;
     }

     /**
      * @param $command
      * @return array
      */
     public final function exec($command){
         $command  = sprintf('cd %s && %s', $this->gitDir,$command);
          exec($command, $resultCommand);
         return  $resultCommand;
     }

}
