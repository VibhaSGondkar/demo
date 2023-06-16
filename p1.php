<?php
include("../DbConnection/DbConnectionPDO.php");
include("../oarmClass/CommonFunction.php");
include("../oarmClass/session.php");
require("../3rdparty/diff.php");
require("../oarmClass/common.php");
include("../oarmClass/dbFunctions.php");
include("../oarmClass/AccessFunctions.php");
$AccessFunct = new AccessDetails();



$dbFunctions = new dbFunct();
include("../oarmClass/DashDbFunction.php");
$Dashboard = new TaskDash();
include("../oarmClass/CommonFunctions.php");
$cmf = new CommonFunctions();

if(!in_array($user_team,$bottask)){
 
  header('Location: ../oarm?page=welcome');

  exit();
  
}

if(isset($session_uid)) {
        if(isLoginSessionExpired()) {
                header("Location:../logout.php?session_expired=1");
        }
}else
{
 
  
header('Location: ../index');
    exit();
}



if(isset($_REQUEST['editp1']))
{

  echo "hello";

}


$dbFunctions = new dbFunct();

if(isset($_POST['search']))
{

$searchresult=1;
$geo=$_POST['geo'];
$hostgroup=$_POST['hostgroup'];
$country=$_POST['country'];
$location=$_POST['location'];
$operator=$_POST['operator'];
logEvent('search '.$geo.' '.$hostgroup.' ');
logEvent('search '.$oarmowner.' '.$hostgroup.' ');
}

if(isset($_POST['searchipdetails']))
{
$searchipresult=1;
$serverip=$_POST['serverip'];
}


if (isset($_POST['addp1'])) {
  $identified_by=$_POST['identified_by']; 
  $issueowner=$_POST['incident_at']; 
  $issue_type=$_POST['issue_type']; 
  $revenue_impact=$_POST['revenue_impact']; 
  $products=implode(",",$_POST['products']);
   $wolken_id=$_POST['wolken_id']; 
   $ps_jira_id=$_POST['ps_jira_id']; 
  $geo=$_POST['geo'];
   $country=$_POST['country']; 
   $operator=$_POST['operator'];
   $location=implode(",",$_POST['location']);
  $hostname=implode(",",$_POST['hostname']);
  $latest_update=$_POST['action']; 
  $business_impact=$_POST['business_impact'];
  $status=$_POST['status'];
  if($status=="open"){
    $reported_time=$_POST['reported_time_open']; 
    $etr=$_POST['etr']; 
    $nupdate=$_POST['nupdate']; 
  }
  elseif($status=="closed"){
    $reported_time=$_POST['reported_time_closed']; 
   
  }
  

  $resolvedate=$_POST['resolvedate'];
  

  logEvent('Tesstat '.$resolvedate.' ');
  
  $date = date('Y-m-d H:i:s');
  $actiontaken="{$date} : {$latest_update}";

  $description = "{$country} - {$operator} : {$product} Down Due to {$issue_type} Issue From  {$issueowner}";



  if ($status == "closed")
  {


      $ageinsec=(strtotime($resolvedate) - strtotime($reported_time));
      logEvent('Testing status '.$status.''.$ageinsec.'');


      if ($ageinsec > "86460")
      {
        $datetime1 = new DateTime($resolvedate);
        $datetime2 = new DateTime($reported_time);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a Days %H H:  %I M: %S S');


      }
      else {
        $datetime1 = new DateTime($resolvedate);
        $datetime2 = new DateTime($reported_time);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%H H:  %I M: %S S');

      }



  }
  else {


    $ageinsec=(time() - strtotime($reported_time));
    logEvent('Testing status not closed  '.$status.' '.$ageinsec.'');


    if ($ageinsec > "86460")
    {
      $datetime1 = new DateTime();
      $datetime2 = new DateTime($reported_time);
      $interval = $datetime1->diff($datetime2);
      $elapsed = $interval->format('%a Days %h H:  %i M: %s S');


    }
    else {
      $datetime1 = new DateTime();
      $datetime2 = new DateTime($reported_time);
      $interval = $datetime1->diff($datetime2);
      $elapsed = $interval->format('%h H:  %i M: %s S');

    }
  }

  $tstamp=time();

  logEvent('report time '.$reported_time.'  ');

  if ($status == "open")
{




  $sql="INSERT INTO oarm_p1_details(team,subgroup,created_time,created_by,issue_reported_by,issueowner,issuetype,geo,country,operator,location,product,servers,wolkenid,action_taken,business_impact,status,next_update,etr,psjiraid,discription,issue_reported_at,revenue_impact,age_of_incidence,update_count) VALUES ('$user_team','$firstsubgroup',sysdate(),'$user_name','$identified_by','$issueowner','$issue_type','$geo','$country','$operator','$location','$products','$hostname','$wolken_id','$action','$business_impact','$status','$nupdate','$etr', '$ps_jira_id','$description','$reported_time','$revenue_impact','$elapsed','1')";

  logEvent('report time sql '.$reported_time.'  ');

  $newid=$dbFunctions->GetLastInsertId($sql);
  if (strpos($newid, 'ERROR') !== false) {
  echo '<script>alert("FAILED TO EXCUTE THE TASK")</script>';
  }
  else{
    header('Location: p1?page=open');
  }



}

else {
 

    $sql="INSERT INTO oarm_p1_details(team,subgroup,created_time,created_by,issue_reported_by,issueowner,issuetype,geo,country,operator,location,product,servers,wolkenid,action_taken,business_impact,status,next_update,etr,psjiraid,discription,issue_reported_at,revenue_impact,age_of_incidence,reolved_at,update_count,latest_update) VALUES ('$user_team','$firstsubgroup',sysdate(),'$user_name','$identified_by','$issueowner','$issue_type','$geo','$country','$operator','$location','$products','$hostname','$wolken_id','$action','$business_impact','$status','$nupdate','$etr', '$ps_jira_id','$description','$reported_time','$revenue_impact','$elapsed','$resolvedate','1','$latest_update')";

    $newid=$dbFunctions->GetLastInsertId($sql);
    if (strpos($newid, 'ERROR') !== false) {
    echo '<script>alert("FAILED TO EXCUTE THE TASK")</script>';
    }
    else{
      header('Location: p1?page=open');
    }


}





}  
     

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>OARM</title>
  <?php

  echo '<link rel="stylesheet" href="../UI/dist/css/table.css">';
  echo '<link rel="stylesheet" href="../UI/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">';
  echo '<link rel="stylesheet" href="../UI/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">';
  echo '<link rel="stylesheet" href="../UI/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">';
  echo '<link rel="stylesheet" href="../UI/dist/css/tablebutton.css">';
  echo '<link rel="stylesheet" href="../UI/dist/css/simple.css">';
      echo ' <link rel="stylesheet" href="../UI/dist/css/form.css">';
    echo '<link rel="stylesheet" href="../UI/dist/css/simple.css">';
    echo ' <link rel="stylesheet" href="../UI/dist/css/form.css">';




?>

<!-- add for modal -->
    <link rel="stylesheet" href="../UI/plugins/fontawesome-free/css/all.min.css">
     <link rel="stylesheet" href="../UI/dist/css/adminlte.min.css">
<!-- end modal -->


<link rel="stylesheet" href="../UI/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="../UI/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="../UI/dist/css/white.css">
    <link rel="stylesheet" href="../UI/dist/css/dark.css">
<link rel="stylesheet" href="../UI/dist/css/mis.css">
    <link rel="stylesheet" href="../UI/dist/css/control.css">



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>




<script>
$(document).ready(function(){
$("#status").change(function(){
$(this).find("option:selected").each(function(){
var optionValue = $(this).attr("value");


if(optionValue){

$('.box input[type=text], .box input[type=datetime-local], .box select, .box textarea').each(function() {
$(this).prop('required',false);
});

$(".box").not("." + optionValue).hide();
$("." + optionValue).show();
$("." + optionValue + " input").attr('required','true');
$("." + optionValue + " select").attr('required','true');
$("." + optionValue + " textarea").attr('required','true');


} else{
$(".box").hide();
}
});
}).change();
});
</script>

     <link rel="stylesheet" href="../UI/plugins/fontawesome-free/css/all.min.css">





    <style>


    .box, .box1, .box3, .bulkbox {

           display: none;

       }

       .bulknewbox{
          display: none;
       }

    .button {
      background-color: #4CAF50; /* Green */
      border: none;
      color: white;
      padding: 8px 8px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 12px;
      margin: 4px 2px;
      cursor: pointer;
    }

    .fa{display:inline-block;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
    .fa-refresh:before{content:"\f021";}

    /*! CSS Used from: https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css */
    .fa{display:inline-block;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;}
    .fa-refresh:before{content:"\f021";}
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/table.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/tablebutton.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/simple.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/form.css */
    .fa{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;}
    .fa{font-family:"Font Awesome 5 Free";}
    .fa{font-weight:900;}
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/plugins/fontawesome-free/css/all.min.css */
    .fa{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;}
    .fa{font-family:"Font Awesome 5 Free";}
    .fa{font-weight:900;}
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/adminlte.min.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/white.css */
    .fa{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;}
    .fa{font-family:"Font Awesome 5 Free";}
    .fa{font-weight:900;}
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/dark.css */
    .fa{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;}
    .fa{font-family:"Font Awesome 5 Free";}
    .fa{font-weight:900;}
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/mis.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/dist/css/control.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: http://10.9.26.71/oarm/UI/plugins/fontawesome-free/css/all.min.css */
    .fa{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1;}
    .fa{font-family:"Font Awesome 5 Free";}
    .fa{font-weight:900;}
    /*! CSS Used from: Embedded */
    .blue-color{color:blue;}
    /*! CSS Used fontfaces */



    .button2 {background-color: #008CBA;} /* Blue */
    .button3 {background-color: #f44336;} /* Red */
    .button4 {background-color: #e7e7e7; color: black;} /* Gray */
    .button5 {background-color: #555555;} /* Black */






        .col-md-12,.col-md-2,.col-md-3,.col-sm-3,.col-sm-6{position:relative;width:100%;padding-right:7.5px;padding-left:7.5px;}
        @media (min-width:576px){
        .col-sm-3{-webkit-flex:0 0 25%;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%;}
        .col-sm-6{-webkit-flex:0 0 50%;-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;}
        }
        @media (min-width:768px){
        .col-md-2{-webkit-flex:0 0 16.666667%;-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%;}
        .col-md-3{-webkit-flex:0 0 25%;-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%;}
        .col-md-12{-webkit-flex:0 0 100%;-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;}
        }

        [type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none;}
        .col-sm-6{position:relative;width:100%;padding-right:7.5px;padding-left:7.5px;}
        @media (min-width:576px){
        .col-sm-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;}
        }


        .col-md-12,.col-md-3,.col-sm-3,.col-sm-6{position:relative;width:100%;padding-right:7.5px;padding-left:7.5px;}
        @media (min-width:576px){
        .col-sm-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%;}
        .col-sm-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;}
        }
        @media (min-width:768px){
        .col-md-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%;}
        .col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;}
        .col-md-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%;}
        }
        .form-control{display:block;width:100%;height:calc(2.25rem + 2px);padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;box-shadow:inset 0 0 0 transparent;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
        @media (prefers-reduced-motion:reduce){
        .form-control{transition:none;}
        }

        .col-md-12,.col-md-2,.col-md-3,.col-sm-3,.col-sm-6{position:relative;min-height:1px;padding-right:15px;padding-left:15px;}
        @media (min-width:768px){
        .col-sm-3,.col-sm-6{float:left;}
        .col-sm-6{width:50%;}
        .col-sm-3{width:25%;}
        }
        @media (min-width:992px){
        .col-md-12,.col-md-2,.col-md-3{float:left;}
        .col-md-12{width:100%;}
        .col-md-3{width:25%;}
        .col-md-2{width:16.66666667%;}
        .col-md-6{width:50%;}
        .col-md-offset-3{margin-left:25%;}
        }

        /*! CSS Used fontfaces */


        /*! CSS Used from: https://oarm.onmobile.com/dist/css/adminlte.min.css */
    *,::after,::before{box-sizing:border-box;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: https://oarm.onmobile.com/css/bootstrap.min.css */
    input,select{margin:0;font:inherit;color:inherit;}
    select{text-transform:none;}
    input::-moz-focus-inner{padding:0;border:0;}
    input{line-height:normal;}
    @media print{
    *,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    select{background:#fff!important;}
    }
    *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    :after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    input,select{font-family:inherit;font-size:inherit;line-height:inherit;}
    p{margin:0 0 10px;}
    .col-md-3,.col-sm-2,.col-sm-3{position:relative;min-height:1px;padding-right:15px;padding-left:15px;}
    @media (min-width:768px){
    .col-sm-2,.col-sm-3{float:left;}
    .col-sm-3{width:25%;}
    .col-sm-2{width:16.66666667%;}
    }
    @media (min-width:992px){
    .col-md-3{float:left;}
    .col-md-3{width:25%;}
    }


    /*! CSS Used from: https://oarm.onmobile.com/dist/css/adminlte.min.css */
    *,::after,::before{box-sizing:border-box;}
    strong{font-weight:bolder;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    }
    /*! CSS Used from: https://oarm.onmobile.com/css/bootstrap.min.css */
    strong{font-weight:700;}
    input,select,textarea{margin:0;font:inherit;color:inherit;}
    select{text-transform:none;}
    input::-moz-focus-inner{padding:0;border:0;}
    input{line-height:normal;}
    textarea{overflow:auto;}
    @media print{
    *,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    select{background:#fff!important;}
    }
    *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    :after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    input,select,textarea{font-family:inherit;font-size:inherit;line-height:inherit;}
    p{margin:0 0 10px;}
    .col-md-4,.col-sm-2{position:relative;min-height:1px;padding-right:15px;padding-left:15px;}
    @media (min-width:768px){
    .col-sm-2{float:left;}
    .col-sm-2{width:16.66666667%;}
    }
    @media (min-width:992px){
    .col-md-4{float:left;}
    .col-md-4{width:33.33333333%;}
    }
    label{display:inline-block;max-width:100%;margin-bottom:5px;font-weight:700;}
    .form-control{display:block;width:100%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;}
    .form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);}
    .form-control::-moz-placeholder{color:#999;opacity:1;}
    .form-control:-ms-input-placeholder{color:#999;}
    .form-control::-webkit-input-placeholder{color:#999;}
    .form-group{margin-bottom:15px;}
    .form-horizontal .form-group{margin-right:-15px;margin-left:-15px;}
    .form-horizontal .form-group:after,.form-horizontal .form-group:before{display:table;content:" ";}
    .form-horizontal .form-group:after{clear:both;}
    /*! CSS Used from: https://oarm.onmobile.com/css/customdata.css */
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit;}
    input{overflow:visible;}
    .form-control{display:block;width:100%;height:calc(2.25rem + 2px);padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;box-shadow:inset 0 0 0 transparent;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
    @media (prefers-reduced-motion:reduce){
    .form-control{transition:none;}
    }
    .form-control::-ms-expand{background-color:transparent;border:0;}
    .form-control:-moz-focusring{color:transparent;text-shadow:0 0 0 #495057;}
    .form-control:focus{color:#495057;background-color:#fff;border-color:#80bdff;outline:0;box-shadow:inset 0 0 0 transparent,none;}
    .form-control::-webkit-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::-moz-placeholder{color:#939ba2;opacity:1;}
    .form-control:-ms-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::-ms-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::placeholder{color:#939ba2;opacity:1;}
    .form-control:disabled{background-color:#e9ecef;opacity:1;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    strong{font-weight:bolder;}
    input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit;}
    input{overflow:visible;}
    .form-control{display:block;width:100%;height:calc(2.25rem + 2px);padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;box-shadow:inset 0 0 0 transparent;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
    @media (prefers-reduced-motion:reduce){
    .form-control{transition:none;}
    }
    .form-control::-ms-expand{background-color:transparent;border:0;}
    .form-control:-moz-focusring{color:transparent;text-shadow:0 0 0 #495057;}
    .form-control:focus{color:#495057;background-color:#fff;border-color:#80bdff;outline:0;box-shadow:inset 0 0 0 transparent,none;}
    .form-control::-webkit-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::-moz-placeholder{color:#939ba2;opacity:1;}
    .form-control:-ms-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::-ms-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::placeholder{color:#939ba2;opacity:1;}
    .form-control:disabled{background-color:#e9ecef;opacity:1;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    *,::after,::before{box-sizing:border-box;}
    p{margin-top:0;margin-bottom:1rem;}
    strong{font-weight:bolder;}
    input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit;}
    input{overflow:visible;}
    .col-md-4{position:relative;width:100%;padding-right:7.5px;padding-left:7.5px;}
    @media (min-width:768px){
    .col-md-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%;}
    }
    .form-control{display:block;width:100%;height:calc(2.25rem + 2px);padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem;box-shadow:inset 0 0 0 transparent;transition:border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
    @media (prefers-reduced-motion:reduce){
    .form-control{transition:none;}
    }
    .form-control::-ms-expand{background-color:transparent;border:0;}
    .form-control:-moz-focusring{color:transparent;text-shadow:0 0 0 #495057;}
    .form-control:focus{color:#495057;background-color:#fff;border-color:#80bdff;outline:0;box-shadow:inset 0 0 0 transparent,none;}
    .form-control::-webkit-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::-moz-placeholder{color:#939ba2;opacity:1;}
    .form-control:-ms-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::-ms-input-placeholder{color:#939ba2;opacity:1;}
    .form-control::placeholder{color:#939ba2;opacity:1;}
    .form-control:disabled{background-color:#e9ecef;opacity:1;}
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }
    /*! CSS Used from: https://oarm.onmobile.com/css/bootstrap.min.css */
    strong{font-weight:700;}
    input,select,textarea{margin:0;font:inherit;color:inherit;}
    select{text-transform:none;}
    input::-moz-focus-inner{padding:0;border:0;}
    input{line-height:normal;}
    textarea{overflow:auto;}
    @media print{
    *,:after,:before{color:#000!important;text-shadow:none!important;background:0 0!important;-webkit-box-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    select{background:#fff!important;}
    }
    *{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    :after,:before{-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
    input,select,textarea{font-family:inherit;font-size:inherit;line-height:inherit;}
    p{margin:0 0 10px;}
    .col-md-4,.col-sm-2{position:relative;min-height:1px;padding-right:15px;padding-left:15px;}
    @media (min-width:768px){
    .col-sm-2{float:left;}
    .col-sm-2{width:16.66666667%;}
    }
    @media (min-width:992px){
    .col-md-4{float:left;}
    .col-md-4{width:33.33333333%;}
    }
    label{display:inline-block;max-width:100%;margin-bottom:5px;font-weight:700;}
    .form-control{display:block;width:100%;height:34px;padding:6px 12px;font-size:14px;line-height:1.42857143;color:#555;background-color:#fff;background-image:none;border:1px solid #ccc;border-radius:4px;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075);box-shadow:inset 0 1px 1px rgba(0,0,0,.075);-webkit-transition:border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;-o-transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;transition:border-color ease-in-out .15s,box-shadow ease-in-out .15s;}
    .form-control:focus{border-color:#66afe9;outline:0;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);}
    .form-control::-moz-placeholder{color:#999;opacity:1;}
    .form-control:-ms-input-placeholder{color:#999;}
    .form-control::-webkit-input-placeholder{color:#999;}
    .form-group{margin-bottom:15px;}
    .form-horizontal .form-group{margin-right:-15px;margin-left:-15px;}
    .form-horizontal .form-group:after,.form-horizontal .form-group:before{display:table;content:" ";}
    .form-horizontal .form-group:after{clear:both;}
    /*! CSS Used from: Embedded */
    @media print{
    *,::after,::before{text-shadow:none!important;box-shadow:none!important;}
    p{orphans:3;widows:3;}
    }

    </style>

<!-- bulk scripts -->




<script>
$(document).ready(function(){
$("#bulkaction").change(function(){
$(this).find("option:selected").each(function(){
var optionValue = $(this).attr("value");
if(optionValue){
$(".bulknewbox").not("." + optionValue).hide();
$("." + optionValue).show();

          $("." + optionValue + " input").attr('required','true');

   $("." + optionValue + " select").attr('required','true');
} else{
$(".bulknewbox").hide();
}
});
}).change();
});
</script>

<script>
$(document).ready(function(){
$("#bulkinventoryoptions").change(function(){
$(this).find("option:selected").each(function(){
var optionValue = $(this).attr("value");
if(optionValue){
$(".bulkbox").not("." + optionValue).hide();
$("." + optionValue).show();

          $("." + optionValue + " input").attr('required','true');

   $("." + optionValue + " select").attr('required','true');
} else{
$(".bulkbox").hide().prop('required',false);
}
});
}).change();
});
</script>





<script>
     function validateinventoryoptions(inventoryoptions) {
       var xhttps;

       if (inventoryoptions== "") {
         document.getElementById("bulkgeo").innerHTML = "";
         return;
       }
       xhttps= new XMLHttpRequest();
       xhttps.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

if (inventoryoptions == "NO"){


           document.getElementById("Nobulkhostname").innerHTML = this.responseText;
}else {
document.getElementById("bulkhostname").innerHTML = this.responseText;
}


         }
       };

       xhttps.open("GET", "../subpage/ot/fo_sub.php?inventoryoptions="+inventoryoptions, true);
       xhttps.send();
     }
     </script>

<script>
function showp1operator(country) {
 var xhttps;
 if (country== "") {
   document.getElementById("operator").innerHTML = "";
   return;
 }
 xhttps= new XMLHttpRequest();
 xhttps.onreadystatechange = function() {
   if (this.readyState == 4 && this.status == 200) {
     document.getElementById("operator").innerHTML = this.responseText;
     $(".select2").select2();
   }
 };

 xhttps.open("GET", "../subpage/ot/p1_sub.php?country="+country, true);
 xhttps.send();
}
</script>



<script>
function showp1location(operator) {
 var xhttps;
 if (operator== "") {
   document.getElementById("location").innerHTML = "";
   return;
 }
 xhttps= new XMLHttpRequest();
 xhttps.onreadystatechange = function() {
   if (this.readyState == 4 && this.status == 200) {
     document.getElementById("location").innerHTML = this.responseText;

   }
 };

 xhttps.open("GET", "../subpage/ot/p1_sub.php?operator="+operator, true);
 xhttps.send();
}
</script>

<script>
  $(function() {
      $('#location').change(function(e) {
  var geo =[];
          var selected = $(e.target).val();
  geo.push($(this).val());
 var jsonString = JSON.stringify(geo);
  $.ajax({
         type: "POST",
         url: "../subpage/ot/p1_sub.php",
         data: {locArray: jsonString } ,
         success: function(msg) {
 document.getElementById("hostname").innerHTML = msg;

          }
  });

      });
  });
  </script>


<!-- bulk close scripts -->







      <script>
      function Showinventoption(hostgroup) {
        var xhttps;
        if (hostgroup== "") {
          document.getElementById("inventoryoptions").innerHTML = "";
          return;
        }
        xhttps= new XMLHttpRequest();
        xhttps.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("inventoryoptions").innerHTML = this.responseText;
          }
        };

        xhttps.open("GET", "../subpage/ot/fo_sub.php?hostgroup="+hostgroup, true);
        xhttps.send();
      }
      </script>

      <script>
      function bulkShowinventoption(hostgroup) {
        var xhttps;
        if (hostgroup== "") {
          document.getElementById("bulkinventoryoptions").innerHTML = "";
          return;
        }
        xhttps= new XMLHttpRequest();
        xhttps.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("bulkinventoryoptions").innerHTML = this.responseText;
          }
        };

        xhttps.open("GET", "../subpage/ot/fo_sub.php?bulkhostgroup="+hostgroup, true);
        xhttps.send();
      }
      </script>



            <script>
            function Showactions(action) {
              var xhttps;
              if (action== "") {
                document.getElementById("actionoptions").innerHTML = "";
                return;
              }
              xhttps= new XMLHttpRequest();
              xhttps.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("actionoptions").innerHTML = this.responseText;
                }
              };

              xhttps.open("GET", "../subpage/ot/fo_sub.php?action="+action, true);
              xhttps.send();
            }
            </script>



      <script>
      function validateinventoryoptions(inventoryoptions) {
        var xhttps;

        if (inventoryoptions== "") {
          document.getElementById("geo").innerHTML = "";
          return;
        }
        xhttps= new XMLHttpRequest();
        xhttps.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {

if (inventoryoptions == "NO"){


            document.getElementById("geo").innerHTML = this.responseText;
}else {
document.getElementById("fullhostname").innerHTML = this.responseText;
}


          }
        };

        xhttps.open("GET", "../subpage/ot/fo_sub.php?inventoryoptions="+inventoryoptions, true);
        xhttps.send();
      }
      </script>





            <script>
            function bulkvalidateinventoryoptions(bulkinventoryoptions) {
              var xhttps;

              if (bulkinventoryoptions== "") {
                document.getElementById("bulkgeo").innerHTML = "";
                return;
              }
              xhttps= new XMLHttpRequest();
              xhttps.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {

      if (bulkinventoryoptions == "NO"){

      document.getElementById("bulkgeo").innerHTML = this.responseText;
      }else {
      document.getElementById("bulkhostname").innerHTML = this.responseText;
      }


                }
              };

              xhttps.open("GET", "../subpage/ot/fo_sub.php?inventoryoptions="+bulkinventoryoptions, true);
              xhttps.send();
            }
            </script>



<script>
function showp1Country(geo) {
  var xhttps;
  if (geo== "") {
    document.getElementById("country").innerHTML = "";
    return;
  }
  xhttps= new XMLHttpRequest();
  xhttps.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("country").innerHTML = this.responseText;
      $(".select2").select2();
    }
  };

  xhttps.open("GET", "../subpage/ot/p1_sub.php?geo="+geo, true);
  xhttps.send();
}
</script>




<script>
function showfooperator(country) {
  var xhttps;
  if (country== "") {
    document.getElementById("operator").innerHTML = "";
    return;
  }
  xhttps= new XMLHttpRequest();
  xhttps.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("operator").innerHTML = this.responseText;

    }
  };

  xhttps.open("GET", "../subpage/ot/fo_sub.php?country="+country, true);
  xhttps.send();
}
</script>


<script>
function showfoloc(operator) {
  var xhttps;
  if (operator== "") {
    document.getElementById("location").innerHTML = "";
    return;
  }
  xhttps= new XMLHttpRequest();
  xhttps.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("location").innerHTML = this.responseText;
      $(".select2").select2();
    }
  };

  xhttps.open("GET", "../subpage/ot/fo_sub.php?operator="+operator, true);
  xhttps.send();
}
</script>




   <script>
   $(function() {
       $('#location').change(function(e) {
   var geo =[];
           var selected = $(e.target).val();
   geo.push($(this).val());
   //  console.dir(selected);
    //     console.log(x);

   //passing array
   //dataString= x;
  var jsonString = JSON.stringify(geo);
   $.ajax({
          type: "POST",
          url: "../subpage/ot/fo_sub.php",
          data: {locArray: jsonString } ,
          success: function(msg) {
              //   alert(msg);

  document.getElementById("hostname").innerHTML = msg;

           }
   });

       });
   });
   </script>


  </head>


  <?php 

$cpage = basename($_SERVER['SCRIPT_FILENAME']);
include ("../view/header.php");
include ("../view/side.php");


?>


              </ul>
              </li>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">

            </div>
        <!--    <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">DataTables</li>
              </ol>
            </div>-->
          </div>
        </div><!-- /.container-fluid -->
      </section>
<!--Search Task -->




<?php



          if(isset($_REQUEST['page']) && ($_REQUEST['page']=="open" ))
         {
         echo '  <!-- Main content -->
         <section class="content">
           <div class="container-fluid">
             <div class="row">
               <div class="col-12">
                 <div class="card">
                   <div class="card-header">';
                     echo '<h1 class="card-title">OPEN P1 INCIDENTS</h1>';
         echo  ' <div class="card-tools">';

       if ($_REQUEST['page']=="open")
       {
       echo '<a href="p1?page=create" class="btn btn-tool">CREATE A NEW P1</a>  ';
       }
         echo '     <button type="submit" id="reload"  class="btn btn-tool"  onclick="window.location.reload();" style="float: center;"><em class="fa fa-refresh"></em></button>
                          <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                               <em class="fas fa-minus"></em></button>
                             <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                               <em class="fas fa-times"></em></button>
                           </div>
                   </div>
                   <!-- /.card-header -->
                   <div class="card-body">


                   <table id="new" class="table table-bordered table-striped">
                     <thead>
                     <tr>
                     <th><center style="font-size:12px">CREATED AT</center></th>
                             <th><center style="font-size:12px">CREATED BY</center></th>
                             <th><center style="font-size:12px">OPERATOR</center></th>
                             <th><center style="font-size:12px">PRODUCT</center></th>
                             <th><center style="font-size:12px">LOCATION</center></th>
                             <th><center style="font-size:12px">DESCRIPTION</center></th>
                             <th><center style="font-size:12px">DOWNTIME</center></th>';
                             echo '<th><center style="font-size:12px">NEXT UPDATE AT</center></th>
                             <th><center style="font-size:12px">ETR</center></th>
                             <th><center style="font-size:12px">STATUS</center></th>
                             <th><center style="font-size:12px">ACTION</center></th>
                              

                       </tr>
                   </thead>
                   <tbody>';


         
                   $projectquery = getDB();
            $getrequest="select * from oarm_p1_details ";

                     $oarm_statement = $projectquery->prepare($getrequest);
                   $oarm_statement->execute();
                   $projectresult = $oarm_statement->fetchAll();
                   $projectquery=null;

                   if(!empty($projectresult)) {
                             foreach($projectresult as $rows) {

                     echo   '<tr>


                 <td><center>'.$rows["created_time"].'</center></td>
                 <td><center>'.strtoupper($rows["created_by"]).'</center></td>
                 <td><center>'.$rows["operator"].'</center></td>
                 <td><center>'.$rows["product"].'</center></td>
                 <td><center>'.$rows["location"].'</center></td>
                 <td><center>'.str_replace("|","</br>",$rows["discription"]).'</center></td>';


                 $ageinsec=(time() - strtotime($rows["created_time"]));
                 logEvent('Testing status not closed  '.$status.' '.$ageinsec.'');
             
             
                 if ($ageinsec > "86460")
                 {
                   $datetime1 = new DateTime();
                   $datetime2 = new DateTime($reported_time);
                   $interval = $datetime1->diff($datetime2);
                   $elapsed = $interval->format('%a Days %h H:  %i M: %s S');
             
             
                 }
                 else {
                   $datetime1 = new DateTime();
                   $datetime2 = new DateTime($reported_time);
                   $interval = $datetime1->diff($datetime2);
                   $elapsed = $interval->format('%h H:  %i M: %s S');
             
                 }



                 echo '<td><center>'.$rows["discription"].'</center></td>';
                 echo '<td><center>'.$rows["next_update"].'</center></td>';
                 echo '<td><center>'.$rows["etr"].'</center></td> ';
                 echo '<td><center>'.$rows["status"].'</center></td> <td><center>';


                 //logEvent('User '.$uname.' if ('.$rows["createdby"].' == '.$uname.')');

                
                 echo '<a href="fo?filedep='.$rows["fileid"].'" class="text-primary"><i class="fa fa-server" aria-hidden="true" title="Deploy"></i></a> &nbsp&nbsp';

                 echo '&nbsp;<a href=p1?editp1='.$rows["oarm_job_id"].'"><i class="fa fa-fw fa-edit" title="Edit"> </i></a></center></td>&nbsp;';





                 echo        '</center>     </td>';





                 }
                                }  echo '        </tr>

                   </tbody>

                   </table>
                 </div>
                 <!-- /.card-body -->
                 </div>
                 <!-- /.card -->
                 </div>
                 <!-- /.col -->
                 </div>
                 <!-- /.row -->
                 </div>
                 <!-- /.container-fluid -->
                 </section>';



                 }





                 if(isset($_REQUEST['page']) && ($_REQUEST['page']=="create" ))
                {


                  echo '  <!-- Main content -->
                  <section class="content">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-header">';

                            echo '<h1 class="card-title">OPEN P1 INCIDENTS</h1>';
                echo  ' <div class="card-tools">';

              if ($_REQUEST['page']=="create")
              {
              echo '<a href="p1?page=create" class="btn btn-tool">OPEN P1</a>  ';
                echo '<a href="p1?page=create" class="btn btn-tool">CLOSED P1</a>  ';
              }
                echo '     <button type="submit" id="reload"  class="btn btn-tool"  onclick="window.location.reload();" style="float: center;"><em class="fa fa-refresh"></em></button>
                                 <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                      <em class="fas fa-minus"></em></button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                                      <em class="fas fa-times"></em></button>
                                  </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">';

  echo '<form action="" method="post"  autocomplete="off">

                          <div class="form-group">


      <div class="col-md-3">
      <div class="select2-purple">
     <label style="font-family: sans-serif;font-size: 10px;">IDENTIFIED BY*</label>
     <select name="identified_by" value="" class="select2" id="identified_by" data-dropdown-css-class="select2-purple" style="width: 100%;">';
     echo '<option value=""></option>
     <option value="monitoring_tool">MONITORING TOOL</option>
     <option value="l3_operation">L3 OPERATION</option>
     <option value="business_team">BUSINESS TEAM</option>
     <option value="OPERATOR">OPERATOR</option>';
     echo '</select></div>
     </div>



      <div class="col-md-3">
     <label style="font-family: sans-serif;font-size: 10px;">INCIDENT AT*</label>
     <div class="select2-purple">
     <select name="incident_at" value="" class="select2" id="incident_at"  data-dropdown-css-class="select2-purple" style="width: 100%;">';
     echo '<option value=""></option>
     <option value="onmobile">ONMOBILE</option>
     <option value="operator">OPERATOR</option>';

     echo '</select></div></div>



      <div class="col-md-3">
     <label style="font-family: sans-serif;font-size: 10px;">ISSUE TYPE*</label>
     <div class="select2-purple">
     <select name="issue_type" value="" class="select2" id="issue_type" data-dropdown-css-class="select2-purple" style="width: 100%;">';
     echo '<option value=""></option>
     <option value="application">APPLICATION</option>
     <option value="network">NETWORK</option>
<option value="billing">BILLING</option>
<option value="hardware">HARDWARE</option>
<option value="os">OS</option>
<option value="vender">VENDER</option>
<option value="capacity">CAPACITY</option>
     ';

     echo '</select>
     </div></div>



       <div class="col-md-3">
     <label style="font-family: sans-serif;font-size: 10px;"> REVENUE IMPACT*</label>
     <div class="select2-purple">
     <select name="revenue_impact" value="" class="select2" id="revenue_impact"  data-dropdown-css-class="select2-purple" style="width: 100%;">';
     echo '<option value=""></option>
     <option value="yes">YES</option>
     <option value="no">NO</option>';
     echo '</select><br></div></div>


    
  <div class="col-md-3">

       <label style="font-family: sans-serif;font-size: 10px;"> PRODUCTS * </label>
      <div class="select2-purple">
     <select name="products[]" class="select2" multiple="multiple" id="products" required="true" aria-label="products" data-dropdown-css-class="select2-purple" style="width: 100%;">
';
     $sql="select distinct product from oarm_products";
     $result = $dbFunctions->SelectAllData($sql);
     echo ' <option value=""></option>';
     if(!empty($result)) {
     foreach($result as $user_data) {
     echo '<option value="'.$user_data["product"].'">'. $user_data["product"].' </option>';
     }}
     echo ' 
     </select>
</div>
</div>




  <div class="col-md-3">
     <label style="font-family: sans-serif;font-size: 10px;">WOLKEN ID *</label>
     <input name="wolken_id" type="text" value="" class="input form-control" id="wolken_id" placeholder="" required="true" aria-label="location" aria-describedby="basic-addon1" />
     </div>

       <div class="col-md-3">
     <label style="font-family: sans-serif;font-size: 10px;">PS JIRA ID *</label>
     <input name="ps_jira_id" type="text" value="" class="input form-control" id="ps_jira_id" placeholder="" required="true" aria-label="location" aria-describedby="basic-addon1" />
     </div>

  <div class="col-md-3">

            <label style="font-family: sans-serif;font-size: 10px;"> GEO * </label>
            <div class="select2-purple">
  <select name="geo" value="" class="select2" id="geo" required="true" onchange="showp1Country(this.value)" aria-label="products" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                ';

                               $newquery = getDB();
                                $sql= "select distinct geo from oarm_host_group order by geo ASC";
                                $oarm_statement = $newquery->prepare($sql);
                                $oarm_statement->execute();
                                $result = $oarm_statement->fetchAll();
                                echo "<option value=''></option>";
                                foreach($result as $key => $value){
                                echo "<option value='$value[0]'> $value[0] </option>";
                                }
                                echo'</select>
                                 <br> </div></div>
      <div class="col-md-3">

            <label style="font-family: sans-serif;font-size: 10px;"> COUNTRY * </label>
            <div class="select2-purple">
  <select name="country" value="" class="select2" id="country" required="true" onchange="showp1operator(this.value)" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                ';

                                echo'</select>  </div></div>

                                  <div class="col-md-3">
                                <label style="font-family: sans-serif;font-size: 10px;"> OPERATOR * </label>
                                <div class="select2-purple">
                                <select name="operator" value="" class="select2" id="operator" required="true" onchange="showp1location(this.value)" data-dropdown-css-class="select2-purple" style="width: 100%;">
                                ';

                                echo'</select>
                                </div></div>

                                <div class="col-md-3">

                                <label style="font-family: sans-serif;font-size: 10px;"> LOCATION * </label>
  <div class="select2-purple">
                                <select name="location[]" class="select2" multiple="multiple" id="location" required="true" aria-label="location" data-dropdown-css-class="select2-purple" style="width: 100%;"></select>
</div>
</div>


                                <div class="col-md-3">

                                <label style="font-family: sans-serif;font-size: 10px;"> HOSTNAME * </label>
<div class="select2-purple">
                                <select name="hostname[]" class="select2" multiple="multiple" id="hostname" required="true" aria-label="location" data-dropdown-css-class="select2-purple" style="width: 100%;"></select>
<br></div>

                  </div>


  <div class="col-md-3">
        <label style="font-family: sans-serif;font-size: 10px;">ACTION*</label>
<textarea  name="action" class="textarea"  style="width: 100%; height: 200px;  border: 1px solid #dddddd;"></textarea>
                                        </div>
           <div class="col-md-3">
        <label style="font-family: sans-serif;font-size: 10px;">BUSINESS IMPACT*</label>
<textarea  name="business_impact" class="textarea"  style="width: 100%; height: 200px;  border: 1px solid #dddddd;"></textarea>
                                                 </div>

                                                      <div class="col-md-3">
                                                     <label style="font-family: sans-serif;font-size: 10px;"> STATUS*</label>
                                                     <div class="select2-purple">
                                                     <select name="status"  id="status" value="" class="select2" id="status"   data-dropdown-css-class="select2-purple" style="width: 100%;">';
                                                     echo '<option value=""></option>
                                                     <option value="open">OPEN</option>
                                                     <option value="closed">CLOSED</option>';
                                                     echo '</select></div>
                                                </div>

                                                     <div class="open box">


 <div class="col-md-3">

                                                                                   <label style="font-family: sans-serif;font-size: 10px;">REPORTED TIME (IST)*</label>

                                                                          <input type="datetime-local" id="reporttime" class="form-control" name="reported_time_open" style="-webkit-text-fill-color: rgba(38, 12, 12); font-size: 10px;">



                                                                           <label style="font-family: sans-serif;font-size: 10px;">ETR (IST) * ( Blank - If  ETR Not Available)</label>


                                                                        <input type="datetime-local" id="etr" class="form-control" name="etr" style="-webkit-text-fill-color: rgba(38, 12, 12); font-size: 10px;">';
                                                                        $nupdate== date("Y-m-d H:i:00", strtotime('+30 minutes', $time));
echo '
                             <label style="font-family: sans-serif;font-size: 10px;">NEXT UPDATE AT (IST)*</label>
                                                                                        <input type="text" id="nupdate" class="form-control" name="nupdate" style="-webkit-text-fill-color: rgba(38, 12, 12); font-size: 10px;" value="2022-06-21 17:14:00" readonly="">
                                                     </div>
                                                     </div>
                                                

<div class="closed box">

 <div class="col-md-3">
       <label style="font-family: sans-serif;font-size: 10px;">REPORTED TIME (IST)*</label>
                           <input type="datetime-local" id="reporttime" class="form-control" name="reported_time_closed" style="-webkit-text-fill-color: rgba(38, 12, 12); font-size: 10px;">

              <label style="font-family: sans-serif;font-size: 10px;">RESOLVED  AT (IST)* </label>
                                              <input type="datetime-local" id="resolvedate" class="form-control" name="resolvedate" style="-webkit-text-fill-color: rgba(38, 12, 12); font-size: 10px;">

</div>
</div>

<div class="col-md-4 offset-md-3">
<br>
<button type="submit" name="addp1" value="addp1"  class="btn btn-block btn-primary" style="float: center;"> SUBMIT</button>
</div>


</form>
                        </div>
                        <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        </div>
                        <!-- /.col -->
                        </div>
                        <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->
                        </section>';
                        }




 ?>





             </div>
             <!-- /.content-wrapper -->

             <!-- Control Sidebar -->
             <aside class="control-sidebar control-sidebar-dark">
               <!-- Control sidebar content goes here -->
             </aside>
             <!-- /.control-sidebar -->

             <!-- Main Footer -->




             <footer class="main-footer">
             <strong>Copyright &copy; 2021-2022<a href="https://www.onmobile.com/">&nbsp;OnMobile</a>.</strong>
             Automation - Technology ,All rights reserved.

             <!--  <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>-->

               <div class="float-right d-none d-sm-inline-block">
                <strong>Version</strong> 7.2.SP2.0
               </div>
             </footer>
           </div>
           <!-- ./wrapper -->

           <!-- jQuery -->
           <script src="../UI/plugins/jquery/jquery.min.js"></script>
           <!-- Bootstrap -->
           <script src="../UI/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
           <!-- overlayScrollbars -->
           <script src="../UI/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
           <!-- AdminLTE App -->
           <script src="../UI/dist/js/adminlte.js"></script>


           <script src="../UI/plugins/select2/js/select2.full.min.js"></script>

           <?php
             if(((isset($_REQUEST['page']) && ($_REQUEST['page']=="open") || ($_REQUEST['dash']=="host" )  || ($_REQUEST['dash']=="task" )   || ($_REQUEST['dash']=="searchip")    )))

           {

             echo '<script src="../UI/plugins/datatables/jquery.dataTables.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>';
             echo '<script src="../UI/plugins/jszip/jszip.min.js"></script>';

             echo '<!--<script src="../UI/plugins/pdfmake/pdfmake.min.js"></script><script src="../UI/plugins/pdfmake/vfs_fonts.js"></script>-->';

             echo '<script src="../UI/plugins/datatables-buttons/js/buttons.html5.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-buttons/js/buttons.print.min.js"></script>';
             echo '<script src="../UI/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>';


           }

           ?>






                      <script src="../UI/plugins/select2/js/select2.full.min.js"></script>

           <script>
             $(function () {
               $("#new").DataTable({
                 "responsive": true, "lengthChange": true, "autoWidth": false,  "paging": true,   "searching": true, "order": [[ 0, "desc" ]],

                 "buttons": ["copy", "excel", "colvis"]
               }).buttons().container().appendTo('#new_wrapper .col-md-6:eq(0)');
               $('#example2').DataTable({
                 "paging": true,
                 "lengthChange": false,
                 "searching": true,
                 "order": [[ 0, "desc" ]],

                 "info": true,
                 "autoWidth": false,
                 "responsive": true,
               });
             });

           </script>


           <script>
             $(function () {
               $("#new1").DataTable({
                 "responsive": true, "lengthChange": true, "autoWidth": false,  "paging": true,    "order": [[ 0, "asc" ]],

                 "buttons": ["copy",  "excel", "colvis"]
               }).buttons().container().appendTo('#new_wrapper .col-md-6:eq(0)');
               $('#example2').DataTable({
                 "paging": true,
                 "lengthChange": false,
                 "searching": true,
                 "order": [[ 0, "desc" ]],

                 "info": true,
                 "autoWidth": false,
                 "responsive": true,
               });
             });

           </script>


           <script>
           // Add the following code if you want the name of the file appear on select
           $(".custom-file-input").on("change", function() {
             var fileName = $(this).val().split("\\").pop();
             $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
           });
           </script>

           <script>
           function getvalue(val){
              var buttonValue = document.getElementById('x').value;
             // console.log(buttonValue);
              idval.value=buttonValue;
           }
              </script>

           <script>
             $(function () {
               $("#newtable").DataTable({
                 "responsive": true, "lengthChange": true, "autoWidth": false,  "paging": true,    "order": [[ 0, "asc" ]],

                 "buttons": ["copy", "excel", "colvis"]
               }).buttons().container().appendTo('#newtable_wrapper .col-md-6:eq(0)');
               $('#example2').DataTable({
                 "paging": true,
                 "lengthChange": false,
                 "searching": true,
                 "order": [[ 0, "desc" ]],

                 "info": true,
                 "autoWidth": false,
                 "responsive": true,
               });
             });

           </script>





           <script>
           function SETENVCOOKIE(env) {
             var now = new Date();
             var time = now.getTime();
             time += 60 * 1000;
             now.setTime(time);
             document.cookie = "CA_ENV=" + env + ";expires=" + now.toUTCString()  + ";path=/";

           }

           </script>


           <script>
           function ShowSubgroup(mainkey) {
           var xhttps;
           var x = document.cookie;
           //console.log(x);



           var nameEQ = "count=";
           var ca = document.cookie.split(';');
           for(var i=0;i < ca.length;i++) {
           var c = ca[i];
           while (c.charAt(0)==' ') c = c.substring(1,c.length);
           if (c.indexOf(nameEQ) == 0) var output = c.substring(nameEQ.length,c.length);
           }


           var now = new Date();
           var time = now.getTime();
           time += 3600 * 1000;
           now.setTime(time);
           var element="mainkey_"+output;
           document.cookie = element+"=" + mainkey + ";expires=" + now.toUTCString() + ";path=/";

           var ele="subkey_"+output;
           console.log(ele);

              if (mainkey== "") {
                document.getElementById(ele).innerHTML = "";
                return;
              }
              xhttps= new XMLHttpRequest();
              xhttps.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                  document.getElementById(ele).innerHTML = this.responseText;
                }
              };

              xhttps.open("GET", "select.php?mainkey="+mainkey, true);

              xhttps.send();
            }
            </script>




           <script>
            function GetExistingValue(subkey) {
              var xhttps;
           var x = document.cookie;
           //console.log(x);
           var nameEQ = "count=";
           var ca = document.cookie.split(';');
           for(var i=0;i < ca.length;i++) {
           var c = ca[i];
           while (c.charAt(0)==' ') c = c.substring(1,c.length);
           if (c.indexOf(nameEQ) == 0) var output = c.substring(nameEQ.length,c.length);
           }

           var eles="existingval_"+output;
           console.log(eles);


           var now = new Date();
           var time = now.getTime();
           time += 3600 * 1000;
           now.setTime(time);
           var element="subkey_"+output;
           document.cookie = element+"=" + subkey + ";expires=" + now.toUTCString() + ";path=/";



              if (mainkey== "") {
                document.getElementById(eles).innerHTML = "";
                return;
              }
              xhttps= new XMLHttpRequest();
              xhttps.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
           //alert (this.responseText)
           var x=this.responseText;

           document.getElementById(eles).value = x;
           //alert( this.responseText);
                }
              };
              xhttps.open("GET", "select.php?subkey="+output, true);

              xhttps.send();
            }

            </script>

           <script>
           function GetidonChange(id){
            var v=id;
           var str_c = v.split('_');
           var str_count=str_c[1];
           var now = new Date();
           var time = now.getTime();
           time += 3600 * 1000;
           now.setTime(time);
           document.cookie = "count=" + str_count + ";expires=" + now.toUTCString() + ";path=/";
           }

           </script>


           <script>
             $(function () {
               //Initialize Select2 Elements
               $('.select2').select2()

               //Initialize Select2 Elements
               $('.select2bs4').select2({
                 theme: 'bootstrap4'
               })

               //Datemask dd/mm/yyyy
               $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
               //Datemask2 mm/dd/yyyy
               $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
               //Money Euro
               $('[data-mask]').inputmask()

               //Date picker
               $('#reservationdate').datetimepicker({
                   format: 'L'
               });

               //Date and time picker
               $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

               //Date range picker
               $('#reservation').daterangepicker()
               //Date range picker with time picker
               $('#reservationtime').daterangepicker({
                 timePicker: true,
                 timePickerIncrement: 30,
                 locale: {
                   format: 'MM/DD/YYYY hh:mm A'
                 }
               })
               //Date range as a button
               $('#daterange-btn').daterangepicker(
                 {
                   ranges   : {
                     'Today'       : [moment(), moment()],
                     'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                     'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                     'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                     'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                   },
                   startDate: moment().subtract(29, 'days'),
                   endDate  : moment()
                 },
                 function (start, end) {
                   $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                 }
               )

               //Timepicker
               $('#timepicker').datetimepicker({
                 format: 'LT'
               })

               //Bootstrap Duallistbox
               $('.duallistbox').bootstrapDualListbox()

               //Colorpicker
               $('.my-colorpicker1').colorpicker()
               //color picker with addon
               $('.my-colorpicker2').colorpicker()

               $('.my-colorpicker2').on('colorpickerChange', function(event) {
                 $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
               })

               $("input[data-bootstrap-switch]").each(function(){
                 $(this).bootstrapSwitch('state', $(this).prop('checked'));
               })

             })
             // BS-Stepper Init
             document.addEventListener('DOMContentLoaded', function () {
               window.stepper = new Stepper(document.querySelector('.bs-stepper'))
             })

             // DropzoneJS Demo Code Start
             Dropzone.autoDiscover = false

             // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
             var previewNode = document.querySelector("#template")
             previewNode.id = ""
             var previewTemplate = previewNode.parentNode.innerHTML
             previewNode.parentNode.removeChild(previewNode)

             var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
               url: "/target-url", // Set the url
               thumbnailWidth: 80,
               thumbnailHeight: 80,
               parallelUploads: 20,
               previewTemplate: previewTemplate,
               autoQueue: false, // Make sure the files aren't queued until manually added
               previewsContainer: "#previews", // Define the container to display the previews
               clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
             })

             myDropzone.on("addedfile", function(file) {
               // Hookup the start button
               file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
             })

             // Update the total progress bar
             myDropzone.on("totaluploadprogress", function(progress) {
               document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
             })

             myDropzone.on("sending", function(file) {
               // Show the total progress bar when upload starts
               document.querySelector("#total-progress").style.opacity = "1"
               // And disable the start button
               file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
             })

             // Hide the total progress bar when nothing's uploading anymore
             myDropzone.on("queuecomplete", function(progress) {
               document.querySelector("#total-progress").style.opacity = "0"
             })

             // Setup the buttons for all transfers
             // The "add files" button doesn't need to be setup because the config
             // `clickable` has already been specified.
             document.querySelector("#actions .start").onclick = function() {
               myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
             }
             document.querySelector("#actions .cancel").onclick = function() {
               myDropzone.removeAllFiles(true)
             }
             // DropzoneJS Demo Code End
           </script>



           <script src="../UI/dist/js/oarm.js"></script>





           </body>
           </html>
