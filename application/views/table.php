<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>ACTFA Rankings - Data</title>

<?php
foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />

<?php endforeach; ?>
<?php foreach($js_files as $file): ?>

    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<style type='text/css'>
body
{
    font-family: Helvetica,sans-serif;
    font-size: 14px;
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
</style>

<!--
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/dropzone.css'); ?>" />
<script src="<?php echo base_url('assets/js/dropzone.js'); ?>"></script>
-->

</head>
<body>
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
    <div style='height:20px;'></div>
    <div>
        <?php echo $output; ?>

    </div>
<!-- Beginning footer -->
<div>
  <?php if (strlen(strstr(uri_string(),'fencer')) > 0): ?>
    <br />
    <?php echo form_open_multipart('details/updatefencer'); ?>
    Upload CSV ACTFA membership file: <?php echo form_upload('actfa_memberlist'); ?>
    <?php echo form_submit('upload', 'Upload'); ?>
    <?php echo form_close(); ?>
    <?php echo form_open('details/updatejsonfencer'); ?>
    Update ACTFA membership from portal: <?php echo form_submit('getmembers', 'Get Membership Data'); ?>
    <?php echo form_close(); ?>


  <?php endif; ?>
  <?php if (strlen(strstr(uri_string(),'result')) > 0): ?>
    <br />
    <?php echo form_open_multipart('xmlimport/uploadresult'); ?>
    Upload Engarde XML results file: <?php echo form_upload('engardexml'); ?>
    <?php echo form_submit('upload', 'Upload'); ?>
    <?php echo form_close(); ?>

<!--    <form action="< ?php echo base_url('index.php/xmlimport/uploadresult'); ?>" class="dropzone">
      <div class="dz-message needsclick">
        Drop here, or click to upload the current membership list.
      </div>
        <div class="fallback">
            <input name="engardexml" type="file" multiple />
        </div>
    </form>
-->

  <?php endif; ?>


</div>
<!-- End of Footer -->
</body>
</html>
