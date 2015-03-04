<?php

namespace Kendrick\SymfonyDebugToolbarGit\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GitDataCollector extends DataCollector
{

	private $data;

	public function collect(Request $request, Response $response, \Exception $exception = null) {

		$commitLogLast = shell_exec("git log -1");

		// regex : extract commit,merge,user,email,date

		$this->data = [
			'comit' =>  'fixture',
			'merge' =>  'fixture',
			'user'  =>  'fixture',
			'email' =>  'fixture',
			'date'  =>  'fixture'
		];

	}

	public function getCommit() {

		return $this->data['commit'];

	}

	public function getMerge() {

		return $this->data['merge'];

	}

	public function getUser() {

		return $this->data['user'];

	}

	public function getEmail() {

		return $this->data['email'];

	}

	public function getDate() {

		return $this->data['date'];

	}

	public function getName() {

		// name used into service

		return 'datacollector_git';

	}

}