<?php

class MDL_Contest_Rank
{
	protected $contest;
	protected $users = array();

	public function __construct($contest)
	{
		$this->contest = $contest;
	}

	/**
	 * @return MDL_Contest
	 */
	protected function getContest()
	{
		return $this->contest;
	}

	protected function score_cmp($a, $b)
	{
		if ($a->getScore() > $b->getScore())
			return -1;
		if ($a->getScore() < $b->getScore())
			return 1;
		if ($a->getPenaltyTime() < $b->getPenaltyTime())
			return -1;
		if ($a->getPenaltyTime() > $b->getPenaltyTime())
			return 1;
		return 0;
	}

	protected function initializeScore()
	{
		$users = $this->getContest()->getContestUsers();
		usort($users, array($this,'score_cmp'));
		$this->users = $users;
	}

	public function getRank($rank)
	{
		if (!isset($this->scores[$rank]))
			$this->initializeScore();
		return $this->users[$rank];
	}

	public function getRankDisplay()
	{
		return $this->getContest()->getConfig()->getRankDisplay('during');
	}
}