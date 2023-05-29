<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Rankings extends CI_Controller {

	/*
	* @see https://codeigniter.com/user_guide/general/urls.html
	*/

	function __construct()
	{
		parent::__construct();

		/* Standard Libraries of codeigniter are required */
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('table');

	}

	public function index()
	{
		$output = array(
			'static' => 'n'
		);
		$this->load->view('rankingsindex.php', $output);
	}

	public function static()
	{
		$output = array(
			'static' => 'y'
		);
		$this->load->view('rankingsindex.php', $output);
	}


	//
	// ok, the points table is by ages within levels.  so [U11 club, U13 club, u11 State, U13 state] and so on
	// so we need a base age, applicable age and level to extract the points.  Can get them from the comp table.
	//
	//  select * from comp where category = 'Open' and date within the last 12 months of the last comp

	public function u9($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U9',1,8, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function u11($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U11',1,10, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function u13($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U13',1,12, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function u15($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U15',1,14, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function u17($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U17',1,16, $g, $w, 'N', $s);
		//	   $this->_rankings_output($output);
	}

	public function u20($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U20',1,19, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function u23($g, $w, $s)
	{
		$output['output'] = $this->procRanking('U23',1,22, $g, $w, 'N', $s);
		//		$this->_rankings_output($output);
	}

	public function veteran($g, $w, $s)
	{
		$output['output'] = $this->procRanking('Veteran',40,99, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function novice($g, $w, $s)
	{
		$output['output'] = $this->procRanking('Novice',1,99, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function open($g, $w, $s)
	{
		$output['output'] = $this->procRanking('Open',1,99, $g, $w, 'N', $s);
		//		 $this->_rankings_output($output);
	}

	public function collectResults($s)
	{

		global $colRankArray;
		global $lastdate;

		if ($s != 'y') {
			$s = 'n';
		}
		foreach (array('m', 'f') as $g)
		{
			foreach (array('foil','epee','sabre') as $w)
			{
				$ignore = $this->procRanking('U9',1,8, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('U11',1,10, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('U13',1,12, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('U15',1,14, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('U17',1,16, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('U20',1,19, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('U23',1,22, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('Veteran',40,99, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('Novice',1,99, $g, $w, 'Y', $s);
				$ignore = $this->procRanking('Open',1,99, $g, $w, 'Y', $s);
			}
		}

		function colSort($a, $b)
		{
			$leftRank = str_replace("=", "", $a[3]);
			$left = $a[0] . sprintf("%'.02d", $leftRank) . $a[1];

			$rightRank = str_replace("=", "", $b[3]);
			$right = $b[0] . sprintf("%'.02d", $rightRank) . $b[1];

			if ($left == $right)
			{
				return 0;
			}
			return ($left > $right) ? +1 : -1;

		}

		usort($colRankArray,"colSort");

		// Count how many fencers there are in each category.
		$trophyValid = array();
		foreach ($colRankArray as $row)
		{
			if (array_key_exists($row[2], $trophyValid))
			{
				$trophyValid[$row[2]] += 1;
			} else {
				$trophyValid[$row[2]] = 1;
			}
		}

		foreach ($trophyValid as $k => $v)
		{
			$trophyValidArray[] = array($k, $v);
		}
		sort($trophyValidArray);

		// Sorting function to sort based on the value of the second element of the array
		function valSort($a, $b)
		{

			if ($a[1] == $b[1])
			{
				return 0;
			}
			return ($a[1] > $b[1]) ? -1 : +1;

		}


		// Participation Points.
		// This basically the number of competitors for each group grouped by club.
		$participateCadet = array();
		$participateJunior = array();
		$participateCadetArray = array();

		foreach ($colRankArray as $row)
		{
			if ((substr_count($row[2], "U20") > 0)
			|| (substr_count($row[2], "U23") > 0)
			|| (substr_count($row[2], "Open") > 0)
			|| (substr_count($row[2], "Veteran") > 0)
			|| (substr_count($row[2], "Novice") > 0))
			{
				if (array_key_exists(rtrim($row[1]), $participateJunior))
				{
					$participateJunior[rtrim($row[1])] += 1;
				} else {
					$participateJunior[rtrim($row[1])] = 1;
				}
			} else {
				if (array_key_exists(rtrim($row[1]), $participateCadet))
				{
					$participateCadet[rtrim($row[1])] += 1;
				} else {
					$participateCadet[rtrim($row[1])] = 1;
				}
			}
		}

		foreach ($participateJunior as $k => $v)
		{
			$participateJuniorArray[] = array($k, $v);
		}
		usort($participateJuniorArray, "valSort");

		foreach ($participateCadet as $k => $v)
		{
			$participateCadetArray[] = array($k, $v);
		}
		usort($participateCadetArray, "valSort");


		// Competition Points.
		// Top 25% of fencers in each category, points are added together and highest
		// number of points per club in the two categories wins each.
		$competeCadet = array();
		$competeJunior = array();
		$competeCadetUsed = array();
		$competeJuniorUsed = array();
		$competeCadetArray = array();

		foreach ($colRankArray as $row)
		{
			// colRankArray -> Name, Club, Category, Rank, Rank Status, Points
			// trophyValid -> {Category}->"value"

			$thisrank = str_replace("=", "", $row[3]);
			$rankcompare = (($trophyValid[$row[2]]/4)+1);
			if ($thisrank < $rankcompare)
			{
				$newrow = $row;
				$newrow[] = $rankcompare;
				$newrow[] = $trophyValid[$row[2]];
				if ((substr_count($row[2], "U20") > 0)
				|| (substr_count($row[2], "U23") > 0)
				|| (substr_count($row[2], "Open") > 0)
				|| (substr_count($row[2], "Veteran") > 0)
				|| (substr_count($row[2], "Novice") > 0))
				{
					if (array_key_exists(rtrim($row[1]), $competeJunior))
					{
						$competeJunior[rtrim($row[1])] += $row[5];
						$competeJuniorUsed[] = $newrow;
					} else {
						$competeJunior[rtrim($row[1])] = $row[5];
						$competeJuniorUsed[] = $newrow;
					}
				} else {
					if (array_key_exists(rtrim($row[1]), $competeCadet))
					{
						$competeCadet[rtrim($row[1])] += $row[5];
						$competeCadetUsed[] = $newrow;
					} else {
						$competeCadet[rtrim($row[1])] = $row[5];
						$competeCadetUsed[] = $newrow;
					}
				}
			}
		}

		foreach ($competeJunior as $k => $v)
		{
			$competeJuniorArray[] = array($k, $v);
		}
		usort($competeJuniorArray, "valSort");

		foreach ($competeCadet as $k => $v)
		{
			$competeCadetArray[] = array($k, $v);
		}
		usort($competeCadetArray, "valSort");


		// Calculate individual Trophys
		$trophyFencers = array();
		foreach ($colRankArray as $row)
		{
			if ($row[4] <> "")
			{
				if ((substr_count($row[4], "Champion") > 0) && ($trophyValid[$row[2]] > 2))
				{
					$trophyFencers[] = $row;
				}
				if ((substr_count($row[4], "Runner") > 0) && ($trophyValid[$row[2]] > 4))
				{
					$trophyFencers[] = $row;
				}
			}
		}

		// Start outputting the results
		$template = array(
			'table_open' => '<table class="liste">',
			'heading_cell_start' => '<th class="HBD" align="left">'
		);

		$output['output'] = "";


		$output['output'] = "<h1>Trophies</h1>\n<h2>as at " . $lastdate . "</h2>\n";

		if ($s == "n") {
			$output['output'] .= "<h3>Previous 12 Months Included (Rolling Rankings)</h3>\n";
		} else {
			$output['output'] .= "<h3>Current Year Results Only (Static Rankings)</h3>\n";
		}

		$output['output'] .= "<p>This takes into account a minimum number of fencers for each category/award.</p>\n";

		// List of valid trophies
		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Name',  'Club', 'Category', 'Rank', 'Status', 'Points']);
		$output['output'] .= $this->table->generate($trophyFencers);

		// Participation
		$output['output'] .= "<br /><h2>Pennants</h2><h3>Junior and Above Participation</h3>\n";

		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Club', 'Fenced competitions']);
		$output['output'] .= $this->table->generate($participateJuniorArray);

		$output['output'] .= "<br /><h3>Cadet and Below Participation</h3>\n";

		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Club', 'Fenced competitions']);
		$output['output'] .= $this->table->generate($participateCadetArray);

		// Competitive
		$output['output'] .= "<br /><h2>Pennants</h2><h3>Junior and Above Competitive</h3>\n";

		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Club', 'Points']);
		$output['output'] .= $this->table->generate($competeJuniorArray);

		$output['output'] .= "<br /><h3>Cadet and Below Competitive</h3>\n";

		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Club', 'Points']);
		$output['output'] .= $this->table->generate($competeCadetArray);

		$output['output'] .= "<hr /><h2>All Rankings</h2>\n";

		// All Results
		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Name',  'Club', 'Category', 'Rank', 'Status', 'Points']);
		$output['output'] .= $this->table->generate($colRankArray);

		$output['output'] .= "<br /><br />\n";

		$output['output'] .= "<h2>Fencers per Category</h2>\n";
		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Category', 'Fencers']);
		$output['output'] .= $this->table->generate($trophyValidArray);

		$output['output'] .= "<br /><h2>Results Used in Junior and Above Competitive Pennant</h2>\n";
		$output['output'] .= "<p>Calculated by adding the points of results where the fencer is selected by rank < (No. of fencers in category / 4)+1. </p>\n";

		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Name',  'Club', 'Category', 'Rank', 'Status', 'Points', 'MaxRank Used', 'Fencers in Category']);
		$output['output'] .= $this->table->generate($competeJuniorUsed);

		$output['output'] .= "<br /><h2>Results Used in Cadet and Below Competitive Pennant</h2>\n";
		$output['output'] .= "<p>Calculated by adding the points of results where the fencer is selected by rank < (No. of fencers in category / 4)+1. </p>\n";
		$this->table->clear();
		$this->table->set_template($template);
		$this->table->set_heading(['Name',  'Club', 'Category', 'Rank', 'Status', 'Points', 'MaxRank Used', 'Fencers in Category']);
		$output['output'] .= $this->table->generate($competeCadetUsed);


		$this->load->view('viewrankings.php', $output);

	}
	// -------------------------------------------------------------------------


	// -------------------------------------------------------------------------

	function procRanking($thiscategory, $minage, $maxage, $gender, $weapon, $collect, $isstatic)
	{
		// Grab the points tables and make them avaialble in the function
		include 'assets/points_table.php';
		global $colRankArray;
		global $lastdate;


		//		 $browser = $_SERVER['HTTP_USER_AGENT'];
		//		 echo "<pre>$browser</pre><br />\n";

		if ($isstatic == "y") {
			$lastdate = getdate()['year'] . "-12-30";
//			echo "<pre>lastdate: " . $lastdate . "</pre><br />\n";
		} else {
			// When was the latest comp in the database
			$query = $this->db->query('SELECT date FROM comp ORDER BY date DESC LIMIT 1');
			$answer = $query->row();
			$lastdate = $answer->date;
		}


		$lastunixdate = DateTime::createFromFormat('Y-m-d', $lastdate);
		$lastyear = date_format($lastunixdate, 'Y');
		//		 $today = getdate();
		//		 $maxyear = ($today['year'] - $maxage - 1) . "-01-01";
		//		 $minyear = ($today['year'] - $minage + 1) . "-01-01";
		$maxyear = ($lastyear - $maxage - 1) . "-01-01";
		$minyear = ($lastyear - $minage + 1) . "-01-01";
		//		 echo "Minyear: " . $minyear . " Maxyear: " . $maxyear . "<br />\n";
		if ($gender == "x")
		{
			$gender = "%";
		}

		$listOfCategories = implode("','", $validages[$thiscategory]);
		$queryFormat = 'SELECT 	comp.level as level,
		comp.category as category,
		result.place as place,
		fencer.name as name,
		comp.idcomp as compid,
		comp.name as competition,
		fencer.club as club,
		result.points as intpoints,
		result.points2 as intpoints2,
		fencer.actfamember as actfamember
		from result, comp, fencer
		where result.comp_id=comp.idcomp
		and result.fencer_id=fencer.idfencer
		and fencer.dateofbirth BETWEEN CAST(\'%s\' AS DATE)
		and CAST(\'%s\' AS DATE)
		and comp.date>date_sub(\'%s\', interval 364 day)
		and comp.category IN (\'%s\')
		and comp.weapon = \'%s\'
		and fencer.gender LIKE \'%s\'
		;
		';

		$queryString = sprintf($queryFormat, $maxyear, $minyear, $lastdate, $listOfCategories, $weapon, $gender);
		//		 echo $queryString . "<br />\n\n";
		$query = $this->db->query($queryString);
		if ($query->num_rows() == 0)
		{
			if ($collect == "N")
			{
				$ptable = "<h2>No rankings for " . $thiscategory . " ";
				$ptable .= ($gender == 'm') ? 'Mens' : 'Womens';
				$ptable .= " $weapon</h2>\n";
				$output['output'] = $ptable;
				$this->load->view('viewrankings.php', $output);
			}

			return 0;
		}
		// Now w need to iterate over the results and matching the placing to the points gained.
		$qresult = $query->result_array();
		//		 echo "result table -> " . $this->table->generate($query);
		foreach ($qresult as $row)
		{
			// we work out the offset into the array holding the points for this result

			//				echo "thiscategory = " . $row['level'] . "<br />\n";
//			echo "Row level " . $row['level'] . "<br />\n";
			if ($row['level'] != 'International')
			{
				$catsearch = array_search($row['category'], $validages[$thiscategory]);
				// echo "catsearch $catsearch<br />\n\n";
				$levelsearch = array_search($row['level'], $validlevels[$thiscategory]);
				// echo "levelsearch $levelsearch<br />---<br />\n\n";

				$pointsOffset = ($levelsearch * count($validages[$thiscategory])) + $catsearch;
				// echo "pointsOffset = $pointsOffset <br />\n" . "count of levels in $thiscategory = " . count($validlevels[$thiscategory]) . "<br />\n";

				// Then we fetch the points for the category, place and level/category mix
				if ($pointsOffset < count($points[$thiscategory][$row['place']]))
				{

					$compPoints = $points[$thiscategory][$row['place']][$pointsOffset];
					//			 echo "comp points -> $compPoints <br />\n\n";
					// We save each points into an array of results points so we can later sort it
					// to pluck out the highest points talley for the comps listed.
					// The compid index here is solely to keep the results separate so we can sort it later.
					$newrow['points'] = $compPoints;
					$pointsTable[] = array_merge($row, $newrow);
				}
			} else {
//				echo "International Event" . $row['competition'] . " for " . $row['name'] . "<br />\n";
				if ($row['category'] == $thiscategory)
				{
					$newrow['points'] = $row['intpoints'];
//					echo "Adding International Points 1" . $row['intpoints'] . "<br />\n";
				} else {
					$newrow['points'] = $row['intpoints2'];
//					echo "Adding International Points 2" . $row['intpoints2'] . "<br />\n";
				}
				$pointsTable[] = array_merge($row, $newrow);
			}

			//			 $pointsTable[$row->weapon][$row->name][$row->compid] = [$compPoints, $row->level];
		}
		//		 echo var_dump($pointsTable);

		//	 sort the points table so the highest points results are at the top so we can
		// 	 pick off the top 3/2/1 as required.
		usort($pointsTable, function($a, $b)
		{
			if ($a['points'] == $b['points'])
			{
				return 0;
			}
			return ($a['points'] < $b['points']) ? +1 : -1;
		});

		$totals = array();

		// initialise the levels table
		foreach ($pointsTable as $value)
		{
			$countresults[$value['name']][$value['level']] = 0;
			$countresults[$value['name']]['Club'] = 0;
			$countresults[$value['name']]['State'] = 0;
			$countresults[$value['name']]['National'] = 0;
			$countresults[$value['name']]['International'] = 0;
			$totals[$value['name']] = 0;
			$resultlevels[$value['name']]['Club'] = 0;
			$resultlevels[$value['name']]['State'] = 0;
			$resultlevels[$value['name']]['National'] = 0;
			$resultlevels[$value['name']]['International'] = 0;
		}

		$resultsused = array();
		$resultsnotused = array();

		// Here is where we add up the points, applying the level limitations
		// ie 'state', 'club', etc.

		foreach ($pointsTable as $value)
		{
			/*			 echo "fencer: " . $value['name']
			. " - Level: " . $value['level']
			. " - Place: " . $value['place']
			. " - Points: " . $value['points']
			. "\n<br />\n"; */

			$fencerclub[$value['name']] = $value['club']; // Store the club for output later
			if ($value['level'] == 'International')
			{
				$uselevel = 'National';
			} else {
				$uselevel = $value['level'];
			}
//			echo "<pre>countresults - " . $value['name'] . " - " . $uselevel . "</pre><br />\n";
			$countresults[$value['name']][$uselevel]++;
			$memberdate = new DateTime($value['actfamember']);
			$cmplastdate = new DateTime($lastdate);
			//			 echo "memberdate: " . date_format($memberdate, 'Y-m-d') . " - cmplastdate: " . date_format($cmplastdate, 'Y-m-d') . " <br>\n";
			if ($memberdate < $cmplastdate)
			{
				$reason = 'Fencer is not an ACTFA Member';
				//				 echo "not a member<br />\n";
			} else {
				$reason = 'Result in excess of ' . $uselevel . ' level competition limit of ' . $numlevel[$uselevel];
			}
			if (($countresults[$value['name']][$uselevel] <= $numlevel[$uselevel]) and ($memberdate > $cmplastdate))
			{
				$totals[$value['name']] += $value['points'];
				$resultlevels[$value['name']][$value['level']] += $value['points'];
				$resultsused[] = [$value['name'], $value['competition'], $value['category'], $value['level'], $value['place'], $value['points']];

			} else {
				$resultsnotused[] = [$value['name'], $value['competition'], $value['category'], $value['level'], $value['place'], $value['points'], $reason];
			}
		}

		$lastpoints = 0;
		$count = 1;
		$equalrank = 0;
		$lastcount = 0;
		$last = "";

		// We sort the results into rank order and generate the 'Rank' level
		array_multisort($totals, SORT_DESC);
		foreach ($totals as $key => $value)
		{
			//			 echo "fencer: $key, lastcount: $lastcount, count: $count, points: $value<br />\n";
			if ($value > 0) {
				if ($lastpoints == $value)
				{
					if ($equalrank == 0)
					{
						$results[$lastcount-1] = $last;
						$results[] = ["=" . $lastcount, $key, $fencerclub[$key], $value];
					} else {
						$equalrank = 1;
						$results[] = [ "=" . $lastcount, $key, $fencerclub[$key], $value];
					}
				} else {
					$results[] = [$count, $key, $fencerclub[$key], $value];
					$last = [ "=" . $count, $key, $fencerclub[$key], $value];
					$lastcount = $count;
					$equalrank = 0;
				}
				$lastpoints = $value;
				$count++;
			}
		}

		if (!isset($results))
		{
//			$results[] = ["", "", "", 0];
			$results = array();
		}

		if ($collect == "Y")
		{
			$individualCategory = $thiscategory . " ";
			$individualCategory .= ($gender == 'm') ? 'Mens' : 'Womens';
			$individualCategory .= " " . ucfirst($weapon);

			foreach ($results as $key => $gtab)
			{
				switch ($gtab[0]) {
					case "1":
					$rankStatus = "Champion";
					break;

					case "=1":
					$rankStatus = "Equal Champion";
					break;
					case "2":
					$rankStatus = "Runner Up";
					break;
					case "=2":
					$rankStatus = "Equal Runner Up";
					break;
					default:
					$rankStatus = "";
				}
				// Name, Club, Category, Rank, Rank Status
				$colRankArray[] = array($gtab[1], $gtab[2], $individualCategory, $gtab[0], $rankStatus, $gtab[3]);
			}
		} else {

			$xmloutput = array();
			if ($gender <> "%")
			{

				// This starts formatting the output
				$ptable = "<h2>" . $thiscategory . " ";
				$ptable .= ($gender == 'm') ? 'Mens' : 'Womens';
				$ptable .= " " . ucfirst($weapon) . "</h2>\n";

				if ($isstatic == "y") {
					$ptable .= "<h3>Current Year Results Only (Static Rankings)</h3>\n";
				} else {
					$ptable .= "<h3>Previous 12 Months Included (Rolling Rankings)</h3>";
				}

				$ptable .= "<table  class=\"liste\">\n";
				$ptable .= "<tr align=\"left\">";
				$ptable .= "<th class=\"HBD\" align=\"left\">Rank</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">Fencer</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">Club</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">Points</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">Club</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">State</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">National</th>";
				$ptable .= "<th class=\"HBD\" align=\"left\">International</th>";
				$ptable .= "</tr>\n";
				
				if (count($results) > 0) {

					foreach ($results as $key => $gtab)
					{
						//			 echo "key: $key<br />\n";
						$ptable .= "<tr align=\"left\">";
						$ptable .= "<td align=\"center\" bgcolor=\"#ffff33\">" . $gtab[0] . "</td>";
						$ptable .= "<td align=\"left\">" . $gtab[1] . "</td>";
						$ptable .= "<td align=\"left\">". $gtab[2] . "</td>";
						$ptable .= "<td align=\"center\" bgcolor=\"#ffff33\">". $gtab[3] . "</td>";
						$ptable .= "<td align=\"center\" bgcolor=\"#82fa58\">". $resultlevels[$gtab[1]]['Club'] . "</td>";
						$ptable .= "<td align=\"center\" bgcolor=\"#fe9a2e\">". $resultlevels[$gtab[1]]['State'] . "</td>";
						$ptable .= "<td align=\"center\" bgcolor=\"#e55e6c\">". $resultlevels[$gtab[1]]['National'] . "</td>";
						$ptable .= "<td align=\"center\" bgcolor=\"#2196f3\">". $resultlevels[$gtab[1]]['International'] . "</td>";
						$ptable .= "</tr>\n";

						$xmloutput[] = [$gtab[1], $gender,  $gtab[2], $gtab[3]];
					}
				}

				$ptable .= "</table>\n";

				$ptable .= "<p>as at $lastdate</p>\n";


				usort($resultsused, function($a, $b)
				{
					if ($a[0] == $b[0])
					{
						return 0;
					}
					return ($a[0] > $b[0]) ? +1 : -1;
				});

				usort($resultsnotused, function($a, $b)
				{
					if ($a[0] == $b[0])
					{
						return 0;
					}
					return ($a[0] > $b[0]) ? +1 : -1;
				});


				$template = array(
					'table_open' => '<table class="liste">',
					'heading_cell_start' => '<th class="HBD" align="left">'
				);


				$ptable .= "<hr /><div>\n";
				$ptable .= "<h2>Rankings were calculated using the following results</h2>\n";

				$this->table->set_template($template);
				$this->table->set_heading(['Name', 'Competition', 'Category', 'Level', 'Place', 'Points']);
				$ptable .= $this->table->generate($resultsused);

				$ptable .= "</div><hr /><div>\n";
				$ptable .= "<h2>The following results were NOT used</h2>\n";

				$this->table->set_template($template);
				$this->table->set_heading(['Name', 'Competition', 'Category', 'Level', 'Place', 'Points', 'Reason']);
				$ptable .= $this->table->generate($resultsnotused);
				$ptable .= "</div>\n";
				$output['output'] = $ptable;
				$this->load->view('viewrankings.php', $output);
			} else {

				$newquerystring = 'SELECT fencer.name as name,
				fencer.gender as gender,
				fencer.club as club
				from fencer
				;
				';

				$newquery = $this->db->query($newquerystring);
				// Now w need to iterate over the results and matching the placing to the points gained.
				$newqresult = $newquery->result_array();
				//		 echo "result table -> " . $this->table->generate($query);

				$allfencers = array();

				foreach ($newqresult as $newrow)
				{
					//				 echo var_dump(array_keys($newrow));
					//				 echo $newrow['name'] . " - " . $newrow['gender'] . " - " . $newrow['club'] . "\n";
					$allfencers[$newrow['name']] = [$newrow['gender'], $newrow['club'], 0];
				}

				$xmlout = "";
				$csvout = "";
				foreach ($results as $key => $gtab)
				{
					$thisfencer = $allfencers[$gtab[1]];
					$allfencers[$gtab[1]] = [$thisfencer[0], $thisfencer[1], $gtab[3]];
					// <Tireur ID="6" Nom="Ford" Prenom="Amanda" DateNaissance="" Sexe="F" Nation="AUS" Ligue="ACT" Club="ANU" points="60"/>
				}

				$count = 0;
				foreach ($allfencers as $key => $line)
				{
					$count++;
					$name = explode(" ", $key, 2);
					//$xmlstring = '     <Tireur ID="%s" Nom="%s" Prenom="%s" DateNaissance="" Sexe="%s" Nation="AUS" Ligue="ACT" Club="%s" points="%s"/>';
					//$xmlout .= sprintf($xmlstring, $count, trim($name[1]), trim($name[0]), $line[0], $line[1], $line[2]);
					//$xmlout .= "\n";
					if ($line[2] > 0) {
						$csvstring = '%s,%s,%s,%s,%s,%s';
						$csvout .= sprintf($csvstring, $line[2], trim($name[0]), trim($name[1]),
							$count , trim($name[1]), trim($name[0]), $line[0], $line[1], $line[2]);
						$csvout .= "\n";
				}

				}

				//$output['category'] = $thiscategory;
				//$output['weapon'] = $weapon;
				//$output['date'] = $lastdate;
				//$output['xmlout'] = $xmlout;
				$output['csvout'] = $csvout;
				//			$this->output->set_header('Content-Type: application/xml');
				$this->output->set_header('Content-Type: text/plain');
				// $this->output->set_header('Content-Disposition: filename="' . $thiscategory . $weapon . $lastdate . '.xml"');
				$this->output->set_header('Content-Disposition: filename="' . $thiscategory . $weapon . $lastdate . '.csv"');
				// $this->load->view('xmlout.php', $output);
				$this->load->view('csvout.php', $output);
			}
		}


		return 0;
	}

	function _rankings_output($output = null)
	{
		$this->load->view('viewrankings.php', $output);
	}

}
