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

class PQtest extends SplPriorityQueue
{
    public function compare($priority1, $priority2)
    {
        if ($priority1 === $priority2) return 0;
        return $priority1 > $priority2 ? -1 : 1;
    }
}

	function getLeft($mapobj)
	{
		if (0 == $mapobj->pos % $mapobj->len)
			return (NULL);
		$map = $mapobj->map;
		$a = $map[$mapobj->pos];
		$b = $map[$mapobj->pos - 1];
		$map[$mapobj->pos - 1] = $a;
		$map[$mapobj->pos] = $b;

		return new Map($mapobj->goal, $map, $mapobj->step + 1, $mapobj);
	}

	function getRight($mapobj)
	{
		if (0 == ($mapobj->pos + 1) % $mapobj->len || $mapobj->pos >= $mapobj->len * $mapobj->len)
			return (NULL);
		$map = $mapobj->map;
		$a = $map[$mapobj->pos];
		$b = $map[$mapobj->pos + 1];
		$map[$mapobj->pos + 1] = $a;
		$map[$mapobj->pos] = $b;

		return new Map($mapobj->goal, $map, $mapobj->step + 1, $mapobj);
	}

	function getTop($mapobj)
	{
		if (0 > $mapobj->pos - $mapobj->len)
			return (NULL);
		$map = $mapobj->map;
		$a = $map[$mapobj->pos];
		$b = $map[$mapobj->pos - $mapobj->len];
		$map[$mapobj->pos - $mapobj->len] = $a;
		$map[$mapobj->pos] = $b;

		return new Map($mapobj->goal, $map, $mapobj->step + 1, $mapobj);
	}

	function getDown($mapobj)
	{
		if ($mapobj->pos + $mapobj->len >= $mapobj->len * $mapobj->len)
			return (NULL);
		$map = $mapobj->map;
		$a = $map[$mapobj->pos];
		$b = $map[$mapobj->pos + $mapobj->len];
		$map[$mapobj->pos + $mapobj->len] = $a;
		$map[$mapobj->pos] = $b;

		return new Map($mapobj->goal, $map, $mapobj->step + 1, $mapobj);
	}

class Map
{
	public $pos;
	public $len;
	public $map;
	public $step;

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

	public $heuristic = null;

		public function getHeuristic()
	{
		if (!is_null($this->heuristic))
			return $this->heuristic;
		else if (isset($options['L']))
			return $this->get_Linear_conflict();
		else if (isset($options['H']))
			return $this->get_Hamming_distance();
		else
			return $this->get_Manhantan_distance();
	}

	public function get_Manhantan_distance()
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
		$this->heuristic = intval($heuristic);
		return intval($heuristic);
	}

	public function get_Hamming_distance()
	{
		$cur = $this->map;
		$heuristic = 0;
		foreach ($cur as $currentPos => $val) {
			$wantedPos = array_search($val, $this->goal);

			$wantedX = $wantedPos % $this->len;
			$wantedY = intval($wantedPos / $this->len);

			$currentX = $currentPos % $this->len;
			$currentY = intval($currentPos / $this->len);
			if ($wantedX != $currentX || $wantedY != $currentY)
				$heuristic++;
		}
		$this->heuristic = intval($heuristic);
		return intval($heuristic);
	}

	public function get_Linear_conflict()
	{
		$cur = $this->map;
		$heuristic = 0;
		foreach ($cur as $currentPos => $val) {
			$wantedPos = $this->getXY($val, $this->goal);

			$wantedX = $wantedPos['x'];
			$wantedY = $wantedPos['y'];

			$currentPos = $this->getXY($val, $this->map);
			$currentX = $currentPos['x'];
			$currentY = $currentPos['y'];
			if ($wantedX != $currentX || $wantedY != $currentY)
				$heuristic += 2;
		}
		$this->heuristic = intval($heuristic);
		return intval($heuristic);
	}

	public function getXY($val, $map)
	{
		$pos = array_search($val, $map);

		$x = $pos % $this->len;
		$y = intval($pos / $this->len);

		return ['x' => $x, 'y' => $y];
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
		$queue = new PQtest();

		$morebig = 0;
		while (is_null($find))
		{
			$vals = [
				getLeft($current),
				getRight($current),
				getTop($current),
				getDown($current),
			];

			$vals1 = array_filter($vals);
			$vals = null;
			$vals = $vals1;
			$vals1 = null;

			$vals1 = array_filter($vals, function($k) use ($ignoreStack) {
				    return !in_array(implode(' ', $k->map), $ignoreStack);
				});
			
			
			$vals = null;
			$vals = $vals1;
			$vals1 = null;

			$ignoreStack2 = array_slice($ignoreStack, -1000);
			$ignoreStack = null;
			$ignoreStack = $ignoreStack2;
			$ignoreStack2 = null;
			
			foreach ($vals as $value) {

				if ($queue->count() < 1000
				    || $morebig > $value->getGH()
			)
				{
					if ($value->getGH() > $morebig)
					{
						$morebig = $value->getGH();
					}
					$queue->insert($value, $value->getGH());
				}
			}
			$vals = null;

			if (0 === $queue->count())
			{
				echo 'impossible';
				return ;
			}
			
			$val = $queue->extract();
			if (0 == $val->getHeuristic())
			{
				$find = $val;
			}

			$current = $val;

			$ignoreStack[] = implode(' ', $val->map);
			$val = null;
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

$options = getopt("hMHL");
// var_dump($options);
if (isset($options['h']))
{
	echo "avable heuristique Manhattan Distance -M, Hamming Distance -H, Linear Conflict -L \n";
	die;
}
else if ((isset($options['M'])) && (isset($options['H'])) || (isset($options['M'])) && (isset($options['L'])) || (isset($options['H'])) && (isset($options['L'])))
{
	echo "ashhole one heuristic argument only ... \n";
	die;
}


$handle = fopen("php://stdin", "r");
$size = null;
$fulltable = [];
if ($handle) {
    while (($line = fgets($handle)) !== false) {
    	$commentLine = explode('#', $line);
    	if (isset($commentLine[1]))
    	{
    		echo $commentLine[1];	
    	}
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
				$tab = array_filter($tab, function($k) {return $k !== '';});
				if (count($tab) != $size )
				{
					echo count($tab);
					echo 'error2'."\n"; die;
				}
				$fulltable = array_merge($fulltable, $tab);
			}
		}

    }

    fclose($handle);
}

$goal = range(0, 8);
$goal = array_map('strval',$goal);
// /$goal = str_split("123804765");
// $goal = "3120";
$map = new Map($goal, $fulltable);
	echo $map->getHeuristic().PHP_EOL;
	$map->explore();
