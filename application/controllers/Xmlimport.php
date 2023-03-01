<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Xmlimport extends CI_Controller {

/*
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	 function __construct()
	 {
		 parent::__construct();

		 /* Standard Libraries of codeigniter are required */
		 $this->load->database(); $this->load->helper('url');
		 $this->load->helper('form');
		 $this->load->library('grocery_CRUD');
	 }

	 public function index()
	 {
		 echo "<h1>Welcome to the world of Codeigniter</h1>";//Just an example to ensure that we get into the function
		 die();
	 }

	// details/uploadResult
	public function uploadresult()
	{

		$upout = "<h2>Importing results for</h2>\n";

		$compweapon = [
			"F" => "Foil",
			"E" => "Epee",
			"S" => "Sabre"
		];
		$compcategory = [
			"V" => "Veteran",
			"VET" => "Veteran",
			"E" => "Veteran",
			"T" => "Veteran",
			"R" => "Veteran",
			"N" => "Novice",
			"S" => "Open", 	// Senior
			"U" => "U23",
			"J" => "U20", 	// Junior
			"C" => "U17", 	// Cadet
			"M" => "U15",		// Minime
			"B" => "U13",		// Benjamin
			"P" => "U11",		// Pupille
			"O" => "U9",			// Poussin
			"U23" => "U23",
			"M23" => "U23",
			"U20" => "U20", 	// Junior
			"M20" => "U20", 	// Junior
			"U17" => "U17", 	// Cadet
			"M17" => "U17",
			"U15" => "U15",		// Minime
			"M15" => "U15",		// Minime
			"U13" => "U13",		// Benjamin
			"M13" => "U13",		// Benjamin
			"U11" => "U11",		// Pupille
			"M11" => "U11",		// Pupille
			"U9" => "U9",			// Poussin
			"M9" => "U9",			// Poussin
			"" => "Novice"			// Default

		];
		$compdomain = [
			"I" => "International",
			"N" => "National",
			"R" => "State",
			"Z" => "State",
			"L" => "Club"
		];

		$compsex = [
			"M" => "Mens",
			"F" => "Womens",
			"X"	=> "Mixed"
		];

		// Load in the ACT fencers
		$query = $this->db->query('SELECT lower(name) as name, lower(name_alt) as name_alt, idfencer FROM fencer');
		$qresult = $query->result_array();
		foreach ($qresult as $row) {
			$actfencers[$row['name']] = $row['idfencer'];
			if (strlen($row['name_alt']) > 0) {
				$actfencers[$row['name_alt']] = $row['idfencer'];
			}
		}
//		var_dump($actfencers);

		// Load the uploaded XML file
		$tmpname = $_FILES['engardexml']['tmp_name'];
		$engardexml = simplexml_load_file($tmpname);
//		print_r($engardexml);

// var_dump($engardexml);

		$xCompName = (STRING)$engardexml['TitreLong'];
		$xCompCategory = (STRING)$engardexml['Categorie'];
		$xCompWeapon = (STRING)$engardexml['Arme'];
		$xCompDateTmp = date_parse((STRING)$engardexml['Date']);
		$xCompSex = (STRING)$engardexml['Sexe'];
		$xCompDate = $xCompDateTmp['year'] . "-" . $xCompDateTmp['month'] . "-" . $xCompDateTmp['day'];
		if (isset($engardexml['Domaine'])) {
			# $upout .= "<p>Domaine key found</p>";
			$xCompLevel = (STRING)$engardexml['Domaine'];
		} else {
			$xCompLevel = (STRING)$engardexml['Niveau'];
		}

//		$upout .= var_dump($_POST);

		if (array_key_exists('incompletetitle', $_POST)) {
			$xCompName .= " - " . $compcategory[$xCompCategory] . " " . $compsex[$xCompSex] . " " . $compweapon[$xCompWeapon];
		}

		if (array_key_exists('actfacomp', $_POST)) {
			$xactfacomp = 1;
		} else {
			$xactfacomp = 0;
		}

		$upout .= "<h1>$xCompName</h1>\n<h2>Category $compcategory[$xCompCategory], Weapon $compweapon[$xCompWeapon], Date $xCompDate, Domain $compdomain[$xCompLevel] </h2>\n";

		$this->load->library('table');
		$template = array(
			'table_open' => '<table class="liste">',
			'heading_cell_start' => '<th class="HBD" align="left">'
		);

		$this->table->set_template($template);
		$this->table->set_heading('ID', 'Name', 'Birthdate', 'Club/Nation', 'Place', 'ACTFA Member');

//		$upout .= "<table><tr><th>ID</th><th>Name</th><th>Birthdate</th><th>Club/Nation</th><th>Place</th><th>ACTFA Fencer</th></tr>\n";

		foreach ($engardexml->Tireurs->Tireur as $tireur)
		{
			$tID = $tireur['ID'];
			$tSurname = ucwords(strtolower($tireur['Nom']));
			$tFirstname = $tireur['Prenom'];
			$tBirthdate = $tireur['DateNaissance'];
			$tNation = $tireur['Club'] . "/" . $tireur['Nation'];
			$tPlace = $tireur['Classement'];

			$tSurname = str_replace(" (40+)", "", $tSurname);
			$tSurname = str_replace(" (50+)", "", $tSurname);
			$tSurname = str_replace(" (60+)", "", $tSurname);
			$tSurname = str_replace(" (70+)", "", $tSurname);

			$compareName = strtolower($tFirstname . " " . $tSurname);
			if (array_key_exists($compareName, $actfencers)) {
				$localfencer = "<b>Yes (" . $actfencers[$compareName] . ")</b>";
				$actfaresult[] = [$actfencers[$compareName], $tPlace];
			} elseif ($tNation == "ACT") {
				$localfencer = "<b>Perhaps</b>";
			} else {
				$localfencer = "";
			}

//			$upout .= "<tr><td>$tID</td><td>$tFirstname $tSurname</td><td>$tBirthdate</td><td>$tNation</td><td>$tPlace</td><td>$localfencer</td></tr>\n";
			$this->table->add_row($tID, $tFirstname . " " . $tSurname, $tBirthdate, $tNation, $tPlace, $localfencer);
		}
//		$upout .= "</table>\n";
		$upout .= $this->table->generate();

		$upout .= "<hr>\n";
		$upout .= "<p>\n";
// We have the results - now we have to update the database
// 1. Create the competition
// 2. Iterate over the results adding the individual results.

// Check to see if we have already created this competition and abort if we have
		$compupdate = "SELECT name from comp where name = " . $this->db->escape($xCompName) . ";";
		$query = $this->db->query($compupdate);
		if ($query->num_rows() > 0) {
			$upout .= "<h1>Competition already exists</h1>\n";
		} else {
			$insertdata = [
				'name' => $xCompName,
				'weapon' => $compweapon[$xCompWeapon],
				'category' => $compcategory[$xCompCategory],
				'level' => $compdomain[$xCompLevel],
				'date' => $xCompDate,
				'isact' => $xactfacomp
			];
			$compupdate = $this->db->insert_string('comp', $insertdata);
			$upout .= "comp update -> $compupdate<br />\n";
			if ($this->db->query($compupdate)) {
				$upout .= "Competition update Successfull<br />";
			} else {
				echo "<strong>Competition update FAILED!</strong>\n";
				exit;
			}
			$compupdate = "SELECT idcomp from comp where name = " . $this->db->escape($xCompName) . ";";
			$query = $this->db->query($compupdate);
			$row = $query->row();
			$xCompId = $row->idcomp;
			$upout .= "Competition ID is $xCompId<br />\n";

			if (isset($actfaresult)) {
				foreach ($actfaresult as $thisresult) {
					if ($thisresult[1] > 0) {
						$insertdata = [
							'fencer_id' => $thisresult[0],
							'comp_id' => $xCompId,
							'place' => $thisresult[1]
						];
						$compupdate = $this->db->insert_string('result', $insertdata);
						if ($this->db->query($compupdate)) {
							$upout .= "Inserted -> $compupdate <br />";
						} else {
							echo "<strong>Result insertion FAILED!</strong> - $compupdate\n";
							exit;
						}
					}

				}
			} else {
				$upout .= "<strong>No ACTFA fencers in this competition</strong>\n";
			}
			$upout .= "</p>\n";

//			$compupdate = "SELECT name from comp where name = '$xCompName';";
//			$query = $this->db->query($compupdate);

		}

//		$query = $this->db->query(

		$output['output'] = $upout;
		$this->load->view('viewrankings.php', $output);
	}
}
