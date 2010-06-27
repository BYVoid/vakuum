<?php

class MDL_Contest_Rank
{
	protected $contest;
	protected $scores = array();

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
		$scores = array();
		foreach ($this->getContest()->getSignUpUsers() as $user)
		{
			$scores[] = new MDL_Contest_Score($this->getContest(), $user);
		}
		usort($scores, array($this,'score_cmp'));
		$this->scores = $scores;
	}

	public function getRank($rank)
	{
		if (!isset($this->scores[$rank]))
			$this->initializeScore();
		return $this->scores[$rank];
	}
}