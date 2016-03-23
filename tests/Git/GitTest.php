<?php
/**
 * Created by PhpStorm.
 * User: rafaelfirmino
 * Date: 22/03/16
 * Time: 17:22
 */

namespace Kendrick\SymfonyDebugToolbarGit\tests\Git;


use Kendrick\SymfonyDebugToolbarGit\Git\Git;
use Kendrick\SymfonyDebugToolbarGit\Git\GitCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class GitTest extends \PHPUnit_Framework_TestCase
{

    public function testAssertsPreConditions()
    {
        $this->assertTrue(
            class_exists($class = 'Kendrick\SymfonyDebugToolbarGit\Git\Git'),
            'Class not found:'.$class
        );
    }

    /**
     * @depends testAssertsPreConditions
     */
    public function testGetGitDirMethodShouldReturnGitDirectory()
    {
        $kernel = $this->getMock(KernelInterface::class);
        $kernel->expects($this->any())
            ->method('getRootDir')
            ->will($this->returnValue('kernelDirectory'));

        $container = $this->getMock(ContainerInterface::class);
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($kernel));


        $git  = new Git($container);
        $expectedResult = "kernelDirectory/../.git";
        $result = $git->getGitDir();

        $this->assertEquals($expectedResult, $result);
    }

    /**
     * @depends testAssertsPreConditions
     */
    public function testShouldExecuteExecMethodAndReturnValue()
    {
        $gitMock = $this->getMockBuilder(Git::class)->disableOriginalConstructor()->getMock();
        $gitMock->method('exec')->willReturn('value');
        $this->assertEquals('value',$gitMock->exec(GitCommand::GIT_STATUS));
    }

    /**
     * @depends testAssertsPreConditions
     */
    public function testShouldExecuteShellExecMethodAndReturnValue()
    {
        $gitMock = $this->getMockBuilder(Git::class)->disableOriginalConstructor()->getMock();
        $gitMock->method('shellExec')->willReturn('value');
        $this->assertEquals('value',$gitMock->shellExec(GitCommand::GIT_STATUS));
    }



}
