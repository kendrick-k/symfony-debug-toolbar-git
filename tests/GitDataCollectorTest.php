<?php

	use Kendrick\SymfonyDebugToolbarGit\DataCollector\GitDataCollector;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;

	/**
	 * Class GitDataCollectorTest
	 */
	class GitDataCollectorTest extends \PHPUnit_Framework_TestCase
	{

		/**
		 * gitRepositoryTest
		 */
		public function testGitRepository() {

			$request = Request::createFromGlobals();

			$response = Response::create();

			$repositoryCommitUrl = "https://github.com/kendrick-k/symfony-debug-toolbar-git/commit/";

			$gitDataCollector = new GitDataCollector($repositoryCommitUrl);

			$gitDataCollector->collect($request,$response);

			// check

			$this->assertTrue($gitDataCollector->getGitData(),"git repository doesn't exists");

			$this->assertTrue(null !== $gitDataCollector->getBranch(),"git : no branch set");

			$this->assertTrue(null !== $gitDataCollector->getCommit(),"git : no commit");

			$this->assertTrue(null !== $gitDataCollector->getMessage(),"git : no message");


		}

	}
