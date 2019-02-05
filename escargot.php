<?php
function escargo($size)
{
	$tab = [$size][$size];

	$sens = 1;

	$i = 0;

	$pos = 0;
	$step = 1;
	$lastpos = null;


	$value = 1;
	$value_max = ($size * $size);

	$x = 0;
	$x_max = 0;
	$y = 0;
	$y_max = 0;

	echo $size.PHP_EOL;
	echo $value_max.PHP_EOL;;
	while($value < $value_max)
	{
		while ($x < $size - $x_max)
		{
			$tab[$x++][$y] = $value++;
		}
		while ($y < $size - $y_max)
		{
			$tab[$x][$y++] = $value++;
		}
		$y_max++;
		while ($x > (0 + $x_max))
		{
			$tab[$x--][$y] = $value++;
		}
		$x_max++;
		while ($y > (0 + $y_max))
		{
			$tab[$x][$y--] = $value++;
		}
	}
	echo "FIN\n";
	ksort($tab);
	return $tab;
}

function dump_map($map, $size)
{
	//$size = sqrt(count($map));
	$len = count($map);
	for ($i=0; $i < $len; $i++)
	{
		var_dump($map);
	}
	echo "\n";
}

function aff_amp($map, $size)
{
	$x = 0;
	$y = 0;
	while ()
}

$map = escargo(5);
aff_map($map);

// dump_map($map, 5);