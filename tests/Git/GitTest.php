<?php
/**
 * Created by PhpStorm.
 * User: rafaelfirmino
 * Date: 22/03/16
 * Time: 17:22
 */

namespace Kendrick\SymfonyDebugToolbarGit\tests\Git;


use Kendrick\SymfonyDebugToolbarGit\Git\Git;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    public function testShoulReturnGitDirectory()
    {
        $containerMock = $this->getMock(ContainerInterface::class);
        $containerMock
            ->expects($this->once())
            ->method('getParameter')
            ->will($this->returnValue('teste'));

        $git  = new Git($containerMock);
        $expectedResult = "teste/.git";
        $result = $git->getGitDirInConfiguration();
        $this->assertEquals($expectedResult, $result);
    }

    public function testShouldVerifyGitDirExist()
    {
        $containerMock = $this->getMock(ContainerInterface::class);
        
    }
}
