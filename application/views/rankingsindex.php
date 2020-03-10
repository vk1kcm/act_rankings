<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>ACTFA Rankings - Rank Index</title>

<style type='text/css'>
body {
  background-color: #fff;
  font: 13px/20px normal Helvetica, Arial, sans-serif;
  color: #4F5155;
}

a {
    color: blue;
    text-decoration: none;
    font-size: 14px;
}

h1 {
  color: #444;
  background-color: transparent;
  border-bottom: 1px solid #D0D0D0;
  font-size: 19px;
  font-weight: normal;
  margin: 0 0 14px 0;
  padding: 14px 15px 10px 15px;
}

a:hover
{
    text-decoration: underline;
}

#container {
  margin: 10px;
  border: 1px solid #D0D0D0;
  box-shadow: 0 0 8px #D0D0D0;
}

#body {
  margin: 0 15px 0 15px;
}

table {
  border: 2px solid black;
	border-collapse: collapse;
}

th, td {
  padding: 5px;
}

tr:nth-child(even) {
  background-color: #f2f2f2;
}

tr:hover {
  background-color: #FF7070;
}


th {
  background-color: #2066FF;
  color: white;
}
</style>
</head>
<body>
  <?php if (strlen(strstr($_SERVER['HTTP_USER_AGENT'],'Wget')) <= 0): ?>
  <!-- This header is not included if the page is being grabed by Wget -->
<!-- Beginning header -->
    <div>
        <a href='<?php echo site_url()?>'>Home</a> &nbsp;&nbsp;
        Tables [
        <a href='<?php echo site_url('details/fencer')?>'>Fencer</a> |
        <a href='<?php echo site_url('details/comp')?>'>Competition</a> |
        <a href='<?php echo site_url('details/result')?>'>Results</a>
        ] &nbsp;&nbsp;&nbsp;
        Rolling [<a href='<?php echo site_url('rankings')?>'>Rankings</a> |
        <a href='<?php echo site_url('rankings/collectResults/n')?>'>Collated Rankings</a>]
        &nbsp;&nbsp;&nbsp;
        Static [<a href='<?php echo site_url('rankings/static')?>'>Rankings</a> |
        <a href='<?php echo site_url('rankings/collectResults/y')?>'>Collated Rankings</a>]
    </div>
<!-- End of header-->
<?php endif; ?>

    <div style='height:20px;'></div>
    <div id="container">
      <div id="body">
      <h1>ACTFA Rankings</h1>
      <?php if ($static == "y") {
        echo "<h3>Current Year Results Only (Static Rankings)</h3>\n";
      } else {
        echo "<h3>Previous 12 Months Included (Rolling Rankings)</h3>\n";
      }
      ?>

      <table border="1">
<?php

  $categories = ['U9','U11','U13','U15','U17','U20','U23','Novice','Veteran','Open'];
  foreach ($categories as $value)
  {
    $lcvalue = strtolower($value);
    echo "<tr>\n";
    echo "<th>$value</th>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/m/foil/' . $static) . "'>Mens Foil</a></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/m/epee/' . $static) . "'>Mens Epee</a></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/m/sabre/' . $static) . "'>Mens Sabre</a></td>\n";
    echo "      <td bgcolor=\"#fe9a2e\"></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/f/foil/' . $static) . "'>Womens Foil</a></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/f/epee/' . $static) . "'>Womens Epee</a></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/f/sabre/' . $static) . "'>Womens Sabre</a></td>\n";
    if (strlen(strstr($_SERVER['HTTP_USER_AGENT'],'Wget')) <= 0) {
    echo "      <td bgcolor=\"#82fa58\"></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/x/foil/' . $static) . "'>XML Foil</a></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/x/epee/' . $static) . "'>XML Epee</a></td>\n";
    echo "      <td><a href='" . site_url('rankings/' . $lcvalue . '/x/sabre/' . $static) . "'>XML Sabre</a></td>\n";
  }
    echo "</tr>\n";
}
?>
      </table>
      <p>&nbsp;</p>
    </div>
    </div>
<!-- Beginning footer -->
<div></div>
<!-- End of Footer -->
</body>
</html>
