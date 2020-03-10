<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class FencerDetails extends CI_Controller {

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


	// -------------------------------------------------------------------------

	function details($fencerName)
	{
	//	global $colRankArray;
	//	global $lastdate;

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
		}


		return 0;
	}

	function _rankings_output($output = null)
	{
		$this->load->view('viewrankings.php', $output);
	}

}
