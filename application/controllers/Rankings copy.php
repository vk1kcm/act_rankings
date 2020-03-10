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
		 $output = "";
		 $this->load->view('rankingsindex.php', $output);
	 }


//
// ok, the points table is by ages within levels.  so [U11 club, U13 club, u11 State, U13 state] and so on
// so we need a base age, applicable age and level to extract the points.  Can get them from the comp table.
//
//  select * from comp where category = 'Open' and date within the last 12 months of the last comp

	 public function u9($g, $w)
	 {
		 $output['output'] = $this->procRanking('U9',1,8, $g, $w);
//		 $this->_rankings_output($output);
   }

	 public function u11($g, $w)
	 {
		 $output['output'] = $this->procRanking('U11',1,10, $g, $w);
//		 $this->_rankings_output($output);
   }

	 public function u13($g, $w)
	 {
		 $output['output'] = $this->procRanking('U13',1,12, $g, $w);
//		 $this->_rankings_output($output);
   }

	 public function u15($g, $w)
	 {
		 $output['output'] = $this->procRanking('U15',1,14, $g, $w);
//		 $this->_rankings_output($output);
   }

	 public function u17($g, $w)
	 {
		 $output['output'] = $this->procRanking('U17',1,16, $g, $w);
//	   $this->_rankings_output($output);
	 }

	 public function u20($g, $w)
	 {
		 $output['output'] = $this->procRanking('U20',1,19, $g, $w);
//		 $this->_rankings_output($output);
	 }

	 public function u23($g, $w)
	 {
		$output['output'] = $this->procRanking('U23',1,22, $g, $w);
//		$this->_rankings_output($output);
	 }

	 public function veteran($g, $w)
	 {
		 $output['output'] = $this->procRanking('Veteran',40,99, $g, $w);
//		 $this->_rankings_output($output);
	 }

	 public function novice($g, $w)
	 {
		 $output['output'] = $this->procRanking('Novice',1,99, $g, $w);
//		 $this->_rankings_output($output);
   }

	 public function open($g, $w)
	 {
		 $output['output'] = $this->procRanking('Open',1,99, $g, $w);
//		 $this->_rankings_output($output);
	 }

// -------------------------------------------------------------------------


// -------------------------------------------------------------------------

	 function procRanking($thiscategory, $minage, $maxage, $gender, $weapon)
	 {
		 // Grab the points tables and make them avaialble in the function
		 include 'assets/points_table.php';


//		 $browser = $_SERVER['HTTP_USER_AGENT'];
//		 echo "<pre>$browser</pre><br />\n";

		 // When was the latest comp in the database
		 $query = $this->db->query('SELECT date FROM comp ORDER BY date DESC LIMIT 1');
		 $answer = $query->row();
		 $lastdate = $answer->date;
//		 echo "lastdate -> " . $lastdate . "\n<br />\n";
//		 $thiscategory = 'U20';
		 $today = getdate();
		 $maxyear = ($today['year'] - $maxage - 1) . "-01-01";
		 $minyear = ($today['year'] - $minage + 1) . "-01-01";
		 if ($gender == "x")
		 {
			 $gender = "%";
		 }

		 $listOfCategories = implode("','", $validages[$thiscategory]);
//		 echo "List of categories -> " . $listOfCategories . "\n</br />\n";
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
			 $ptable = "<h2>No rankings for " . $thiscategory . " ";
			 $ptable .= ($gender == 'm') ? 'Mens' : 'Womens';
			 $ptable .= " $weapon</h2>\n";
			 $output['output'] = $ptable;
			 $this->load->view('viewrankings.php', $output);

			 return 0;
		 }
		 // Now w need to iterate over the results and matching the placing to the points gained.
		 $qresult = $query->result_array();
//		 echo "result table -> " . $this->table->generate($query);
			foreach ($qresult as $row)
			{
				// we work out the offset into the array holding the points for this result

//				echo "thiscategory = " . $row['level'] . "<br />\n";
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
					if ($row['category'] == $thiscategory)
					{
						$newrow['points'] = $row['intpoints'];
					} else {
						$newrow['points'] = $row['intpoints2'];
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
//				 if (array_key_exists($value['name'], $totals))
//		 	 	 	{
			 	 		$totals[$value['name']] += $value['points'];
//		   		} else {
//			   	$totals[$value['name']] = $value['points'];
					  $resultlevels[$value['name']][$value['level']] += $value['points'];
						$resultsused[] = [$value['name'], $value['competition'], $value['category'], $value['level'], $value['place'], $value['points']];

//		   		}
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

		 $xmloutput = array();
		 if ($gender <> "%")
		 {


			 // This starts formatting the output
			 $ptable = "<h2>" . $thiscategory . " ";
			 $ptable .= ($gender == 'm') ? 'Mens' : 'Womens';
			 $ptable .= " " . ucfirst($weapon) . "</h2>\n";
			 //		 $ptable .= $this->table->generate($results);
			 //		 return $ptable;

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
				$xmlstring = '     <Tireur ID="%s" Nom="%s" Prenom="%s" DateNaissance="" Sexe="%s" Nation="AUS" Ligue="ACT" Club="%s" points="%s"/>';
				$xmlout .= sprintf($xmlstring, $count, trim($name[1]), trim($name[0]), $line[0], $line[1], $line[2]);
				$xmlout .= "\n";
			}

			$output['category'] = $thiscategory;
			$output['weapon'] = $weapon;
			$output['date'] = $lastdate;
			$output['xmlout'] = $xmlout;
//			$this->output->set_header('Content-Type: application/xml');
			$this->output->set_header('Content-Type: text/plain');
			$this->output->set_header('Content-Disposition: filename="' . $thiscategory . $weapon . $lastdate . '.xml"');
			$this->load->view('xmlout.php', $output);
		 }



		 return 0;
	 }

	function _rankings_output($output = null)
 	{
 		$this->load->view('viewrankings.php', $output);
	}

}
