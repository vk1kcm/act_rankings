<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>ACTFA Rankings - Rankings</title>

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
a:hover
{
    text-decoration: underline;
}

#container {
  margin: 10px;
  border: 1px solid #D0D0D0;
  box-shadow: 0 0 8px #D0D0D0;
}

</style>

<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/engarde.css'); ?>" />

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
    <div>
        <?php echo $output; ?>
    </div>
<!-- Beginning footer -->
<div></div>
<!-- End of Footer -->
</body>
</html>
