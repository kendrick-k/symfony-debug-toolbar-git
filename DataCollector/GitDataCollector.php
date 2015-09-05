<?php

namespace Kendrick\SymfonyDebugToolbarGit\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class GitDataCollector
 * @package Kendrick\SymfonyDebugToolbarGit\DataCollector
 */
class GitDataCollector extends DataCollector
{

	/**
	 * @param $repositoryCommitUrl
	 */
	public function __construct($repositoryCommitUrl)
	{

		$this->data['repositoryCommitUrl'] = $repositoryCommitUrl;

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

		$fs = new Filesystem();

		// is there a web directory ?

		if ($fs->exists('../web')) {
			$gitPath = '../.git';
		} else {
			// unit tests
			$gitPath = '.git';
		}

		// if there is no .git directory
		if (!$fs->exists($gitPath)) {
			$this->data['gitData'] = false;
			return;
		}

		// get latest commit information
		exec("git log -1", $data);

		if (isset($data) && $data) {

			// there is some information
			$this->data['gitData'] = true;

			foreach ($data as $d) {

				if (strpos($d, 'commit') === 0) {

					// commit Id

					$this->data['commit'] = substr($d, 7);

				} elseif (strpos($d, 'Author') === 0) {

					// author and email

					preg_match('$Author: ([^<]+)<(.+)>$', $d, $author);

					if (isset($author[1])) $this->data['author'] = trim($author[1]);
					if (isset($author[2])) $this->data['email'] = $author[2];

				} elseif (strpos($d, 'Date') === 0) {

					// date, ex : Fri Mar 6 16:56:25 2015 +0100

					$date = trim(substr($d, 5));

					// date of commit
					$dateCommit = date_create($date);

					// actual date at runtime
					$dateRuntime = new \DateTime();
					$dateNow = date_create($dateRuntime->format('Y-m-d H:i:s'));

					// difference
					$time = date_diff($dateCommit,$dateNow);

					// static time difference : minutes and seconds
					$this->data['timeCommitIntervalMinutes'] = $time->format('%y')*365*24*60+$time->format('%m')*30*24*60+$time->format('%d')*24*60+$time->format('%h')*60+$time->format('%i');
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

			exec("git status", $data);

			if (isset($data[0])) {

				if (strstr($data[0], 'On branch')) {

					// branch name

					$this->data['branch'] = trim(substr($data[0], strpos($data[0],'On branch')+9));

				}
			}

		}
		else {

			// no git data

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
	 * @param $data
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

}
