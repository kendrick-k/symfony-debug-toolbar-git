<?php
namespace Kendrick\SymfonyDebugToolbarGit\tests\DataCollector;


use Kendrick\SymfonyDebugToolbarGit\DataCollector\GitDataCollector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class GitDataCollectorTest extends \PHPUnit_Framework_TestCase
{

    public function testAssertsPreConditions()
    {
        $this->assertTrue(
            class_exists($class = GitDataCollector::class),
            'Class not found'.$class
        );
    }

    /**
     * @depends testAssertsPreConditions
     * @dataProvider collectionData
     */
    public function testeShouldReturnDataCollection($data)
    {
        $container = $this->getMock(ContainerInterface::class);

        $dataCollector = new GitDataCollector($container);

        $this->assertEquals('merge', $dataCollector->getMerge());

    }

    public function collectionData()
    {
        return array(
            "repositoryCommitUrl" => " ",
            "gitData" => true,
            "gitDir" => "/home/rafaelfirmino/projects/git/app/../src/Kendrick/SymfonyDebugToolbarGit/.git",
            "branch" => "allRespository",
            "commit" => "b1b41bb562fa7b6c76d6815b481f92a8380aebe4",
            "author" => "rafaelgfirmino@gmail.com",
            "email" => "Rafael Firmino",
            "message" => "Implement test for git class",
            "merge" => "eeba7b6b1b41bb562fa7b6c76d6815b481f92a8380aebe4",
            "timeCommitIntervalMinutes" => 77,
            "date" => "Wed Mar 23 15:52:09 2016 -0300"
        );
    }
}
