<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Details extends CI_Controller {

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

	 public function fencer()
	 {
		 $this->grocery_crud->set_table('fencer');
		 $this->grocery_crud->set_subject('Fencer');
		 $this->grocery_crud->columns('name', 'gender', 'dateofbirth', 'age', 'club','actfamember', 'name_alt');
		 $this->grocery_crud->callback_column('age', array($this,'age'));
		 $this->grocery_crud->display_as('dateofbirth', 'Date of Birth');
		 $this->grocery_crud->display_as('actfamember', 'ACTFA Member');

		 $output = $this->grocery_crud->render();
		 $this->_table_output($output);

	 }

	 public function age($value, $row)
	 {
		 $today = getdate();
		 $bday = date_parse($row->dateofbirth);
		 return $today['year'] - $bday['year'] - 1;
	 }

	 public function comp()
	 {
		 $this->grocery_crud->set_table('comp');
		 $this->grocery_crud->set_subject('Competition');
		 $this->grocery_crud->columns('name', 'weapon', 'category', 'level', 'date', 'competitors', 'isact');
		 $this->grocery_crud->fields('name', 'weapon', 'category', 'level', 'date', 'isact');
		 $this->grocery_crud->display_as('isact','Is ACT Comp');
		 $this->grocery_crud->callback_column('competitors', array($this,'competitors'));
		 $this->grocery_crud->callback_column('name', array($this,'_fix_name'));
		 $this->grocery_crud->callback_column('isact', array($this,'_fix_isact'));


		 $output = $this->grocery_crud->render();
		 $this->_table_output($output);
	 }

	 function competitors($value, $row)
	 {
		 $query = $this->db->query('SELECT count(*) as count from result where comp_id=' . $row->idcomp);
		 $answer = $query->row();
		 return $answer->count;
	 }

	 function _fix_name($value, $row)
	 {
		 return $row->name;
	 }

	 function _fix_isact($value, $row)
	 {
		 if ($row->isact > 0) {
			 return "Yes";
		 } else {
			 	return " ";
		 }
	 }


	 public function result()
	 {
		 $this->grocery_crud->set_table('result');
		 $this->grocery_crud->set_subject('Result');
		 $this->grocery_crud->set_relation('fencer_id','fencer','name');
		 $this->grocery_crud->set_relation('comp_id','comp','name');
		 $this->grocery_crud->display_as('fencer_id', 'Fencer');
		 $this->grocery_crud->display_as('comp_id', 'Competition');
		 $this->grocery_crud->callback_column('place', array($this,'_callback_place'));

		 $output = $this->grocery_crud->render();
		 $this->_table_output($output);
	 }

	 public function _callback_place($value, $row)
	 {
		 if ($row->place == 1) {
			 $placeresult = "<span style=\"background-color:gold\">&nbsp; $row->place &nbsp;</span>";
		 } elseif ($row->place == 2) {
			 $placeresult = "<span style=\"background-color:silver\">&nbsp; $row->place &nbsp;</span>";
		 } elseif ($row->place == 3) {
			 $placeresult = "<span style=\"background-color:chocolate\">&nbsp; $row->place &nbsp;</span>";
		 } elseif ($row->place == 4) {
			 $placeresult = "<span style=\"background-color:chocolate\">&nbsp; $row->place &nbsp;</span>";
		 } else {
			 $placeresult = "&nbsp; $row->place &nbsp;";
		 }
	 return $placeresult;
	 }

	function _table_output($output = null)
	{
		$this->load->view('table.php',$output);
	}

	public function updatefencer()
	{
		$tmpname = $_FILES['actfa_memberlist']['tmp_name'];
		$updatedMembers = array_map('str_getcsv', file($tmpname));

		$memberList = array();
		$updatedCount = 0;
		date_default_timezone_set('Australia/Canberra');
		$upout = "";

		array_shift($updatedMembers);
		foreach ($updatedMembers as $key => $value) {
			$name = trim($value[1]) . " " . trim($value[0]);
			$dobtemp = new DateTime($value[7]);
			$dob = $dobtemp->format('Y-m-d');
			$gender = ucfirst(substr($value[8], 0, 1));
			$mbrtemp = new DateTime($value[15]);
			$memberexp = $mbrtemp->format('Y-m-d');
			if (strlen($value[12]) > 2) {
				$club = $value[12];
			} else {
				$club = $value[13];
			}
			if ($club == "EMFC") {
				$club = "Engarde";
			} elseif ($club == "DFC") {
				$club = "Duel";
			} elseif ($club == "ACTFA") {
				$club = "Non Club Member";
			} elseif ($club == "MFC") {
				$club = "Maison Escrime";
			}

			$club = str_replace("Veterans(Archery Centre) (ACT)", "Masters", $club);
			$club = str_replace("(ACT)", "", $club);


			if (strlen($club) > 2) {
				$updclub = "club='$club'";
			} else {
				$updclub = "";
			}

			$upout .= "Updating Name: $name | DOB: $dob | Gender: $gender | Expires: $memberexp | Club: $club ";
			$upquery = "INSERT INTO fencer (name, gender, dateofbirth, club, actfamember)
											   VALUES (\"$name\", '$gender', '$dob', '$club', '$memberexp')
												 ON DUPLICATE KEY UPDATE actfamember='$memberexp', $updclub;";
			$query = $this->db->query($upquery);
			if ($query) {
				$upout .= "-> <b>Success</b>";
				$updatedCount++;
			} else {
				$upout .= "-> <b>FAIL</b>";
			}
			$upout .= "<br />\n";

		}

		$upout .= "<p>$updatedCount members updated</p>\n";
		$output['output'] = $upout;
//		$output['output'] = "<pre>\n" . var_dump($updatedMembers) . "</pre>\n";
		$this->load->view('viewrankings.php', $output);

	}

}
