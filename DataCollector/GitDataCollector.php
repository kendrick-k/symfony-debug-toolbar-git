<?php

namespace Kendrick\SymfonyDebugToolbarGit\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

		exec("git log -1", $data);

		if (isset($data)) {

			foreach ($data as $d) {

				if (strpos($d, 'commit') === 0) {
					$this->data['commit'] = substr($d, 7);
				} elseif (strpos($d, 'Author') === 0) {

					preg_match('$Author: ([a-zA-Z ]+)<(.+)>$', $d, $author);

					$this->data['author'] = trim($author[1]);
					$this->data['email'] = $author[2];
				} elseif (strpos($d, 'Date') === 0) {

					// Fri Mar 6 16:56:25 2015 +0100
					$date = trim(substr($d, 5));

					$dateCommit = date_create($date);
					$dateNow = date_create((new \DateTime())->format('Y-m-d H:i:s'));

					$time = date_diff($dateCommit,$dateNow);

					// static time difference
					$this->data['timeCommitIntervalMinutes'] = $time->format('%y')*365*24*60+$time->format('%m')*30*24*60+$time->format('%d')*24*60+$time->format('%h')*60+$time->format('%i');
					$this->data['timeCommitIntervalSeconds'] = $time->format('%s');

					$this->data['date'] = $date;

				} elseif (strpos($d, 'Merge') === 0) {
					$this->data['merge'] = trim(substr($d, 6));
				} else {
					$this->data['message'] = trim($d);
				}

			}
		}

		unset($data);

		exec("git status", $data);

		if (isset($data[0])) {
			if (strpos($data[0], 'On branch') === 0) {
				$this->data['branch'] = trim(substr($data[0], 9));
			}
		}
	}

	/**
	 * @return string
	 */
	public function getBranch()
	{

		return $this->getData('branch');

	}

	/**
	 * @return string
	 */
	public function getCommit()
	{

		return $this->getData('commit');

	}

	/**
	 * @return string
	 */
	public function getMerge()
	{

		return $this->getData('merge');

	}

	/**
	 * @return string
	 */
	public function getAuthor()
	{

		return $this->getData('author');

	}

	/**
	 * @return string
	 */
	public function getEmail()
	{

		return $this->getData('email');

	}

	/**
	 * @return string
	 */
	public function getDate()
	{

		return $this->getData('date');

	}

	/**
	 * @return string
	 */
	public function getTimeCommitIntervalMinutes()
	{

		return $this->getData('timeCommitIntervalMinutes');

	}

	/**
	 * @return string
	 */
	public function getTimeCommitIntervalSeconds()
	{

		return $this->getData('timeCommitIntervalSeconds');

	}

	/**
	 * @return string
	 */
	public function getMessage()
	{

		return $this->getData('message');

	}

	/**
	 * @return string
	 */
	public function getCommitUrl()
	{

		return $this->data['repositoryCommitUrl'];

	}

	/**
	 * @param $data
	 * @return string
	 */
	private function getData($data)
	{

		return (isset($this->data[$data])) ? $this->data[$data] : '';

	}

	/**
	 * @return string
	 */
	public function getName()
	{

		return 'collector_commit';

	}

}