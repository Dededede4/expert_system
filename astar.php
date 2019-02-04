<?php
/*
class Case
{
	public $val;

	public Case $top;
	public Case $left;
	public Case $right;
	public Case $down;
}*/

class Map
{
	private $pos;
	private $len;
	public $map;
	private $step;

	public function __construct($goal, $current, $step = 0, $parent = NULL)
	{
		$this->parent = $parent;
		$this->step = $step;
		$this->len = sqrt(count($goal));
		$this->pos = array_search('0', $current);
		$this->goal = $goal;
		$this->map = $current;
		//echo $this->map.' '.$this->pos."\n";
	}

	public function getLeft()
	{
		if (0 == $this->pos % $this->len)
			return (NULL);
		$map = $this->map;
		$a = $map[$this->pos];
		$b = $map[$this->pos - 1];
		$map[$this->pos - 1] = $a;
		$map[$this->pos] = $b;

		return new Map($this->goal, $map, $this->step + 1, $this);
	}

	public function getRight()
	{
		if (0 == ($this->pos + 1) % $this->len || $this->pos >= $this->len * $this->len)
			return (NULL);
		$map = $this->map;
		$a = $map[$this->pos];
		$b = $map[$this->pos + 1];
		$map[$this->pos + 1] = $a;
		$map[$this->pos] = $b;

		return new Map($this->goal, $map, $this->step + 1, $this);
	}

	public function getTop()
	{
		if (0 > $this->pos - $this->len)
			return (NULL);
		$map = $this->map;
		$a = $map[$this->pos];
		$b = $map[$this->pos - $this->len];
		$map[$this->pos - $this->len] = $a;
		$map[$this->pos] = $b;

		return new Map($this->goal, $map, $this->step + 1, $this);
	}

	public function getDown()
	{
		if ($this->pos + $this->len >= $this->len * $this->len)
			return (NULL);
		$map = $this->map;
		$a = $map[$this->pos];
		$b = $map[$this->pos + $this->len];
		$map[$this->pos + $this->len] = $a;
		$map[$this->pos] = $b;

		return new Map($this->goal, $map, $this->step + 1, $this);
	}

	public function getHeuristic()
	{
		$cur = $this->map;
		$heuristic = 0;
		foreach ($cur as $currentPos => $val) {
			$wantedPos = array_search($val, $this->goal);

			$wantedX = $wantedPos % $this->len;
			$wantedY = intval($wantedPos / $this->len);

			$currentX = $currentPos % $this->len;
			$currentY = intval($currentPos / $this->len);
			$heuristic += abs($wantedX - $currentX) + abs($wantedY - $currentY);
		}
		
		return intval($heuristic);
	}

	public function getGH()
	{
		return $this->getHeuristic() + $this->step;
	}

	public function explore()
	{
		$find = NULL;
		$vals = [];
		$i = 0;
		$current = $this;
		$ignoreStack = [];
		while (is_null($find) && $i++ < 999)
		{
			$vals = array_merge($vals, [
				$current->getLeft(),
				$current->getRight(),
				$current->getTop(),
				$current->getDown(),
			]);
			$vals = array_filter($vals);
			$vals = array_filter($vals, function($k) use ($ignoreStack) {
				    return !in_array(implode(' ', $k->map), $ignoreStack);
				});
			if (0 === count($vals))
			{
				echo 'impossible';
				return ;
			}
			usort($vals, function($a, $b){return $a->getHeuristic() - $b->getHeuristic();});

			if (0 == $vals[0]->getHeuristic())
			{
				$find = $vals[0];
			}
			$current = $vals[0];
			$ignoreStack[] = implode(' ', $vals[0]->map);
		}
		if ($find)
			$find->remote();
	}

	public function remote()
	{
		if ($this->parent)
			$this->parent->remote();
		echo implode(' ', $this->map).' GH:'.$this->getHeuristic() . ' '.$this->step."\n";
	}
}



$handle = fopen("php://stdin", "r");
$size = null;
$fulltable = [];
if ($handle) {
    while (($line = fgets($handle)) !== false) {
    	$commentLine = explode('#', $line);
    	$line = trim($commentLine[0]);
    	if(strlen($line))
    	{
	    	if (ctype_digit($line) && is_null($size))
			{
					$size = $line;
			}
			else if (is_null($size))
			{
				echo "error1"."\n";
				die;
			}
			else
			{
				$tab = explode(' ', $line);
				if (count($tab) != $size)
				{
					echo 'error2'."\n"; die;
				}
				$fulltable = array_merge($fulltable, $tab);
			}
		}

    }

    fclose($handle);
}

var_dump($fulltable);

// $goal = "0123456789ABCDEFGHIJKLMNO";
$goal = str_split("012345678");
// $goal = "3120";
$map = new Map($goal, $fulltable);
$map->explore();
