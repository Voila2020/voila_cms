<? 

//error_reporting(0);
session_start();
include_once("../../config.php");
include_once("../common/dbConnection.php");
//include_once("../login/login.inc.php");
include_once("../includes/dbUtils.php");

/*******getCMSLang();output array****************************************/
getCMSLang();/*
1- $langsArray=array([ar,Arabic],[en,english]);
2- $langsArrayView=array([ar,Arabic],[en,english]);
3- $CMSLang=''; -> like pagLang
4- $VIEWLang=''; -> like pagLang*/
/*******getActModule();output array****************************************/
$activeModule=getActModule();
/*$activeModule['G'] Active module Group ID
//$activeModule['M'] Active module ID
//$activeModule['GN'] Active module Group name
//$activeModule['MN'] Active module name*/
//checkUserLogin();
include_once("../includes/defines.php");
//common variables:
$so	= $_REQUEST['so'];
$sb =  $_REQUEST['sb'];
$pn =  $_REQUEST['pn'];
$LPP = (isset($_REQUEST['llp']))?$_REQUEST['llp']:_LPP;
$st = $pn*$LPP ;
$limit = "LIMIT $st,$LPP " ;
$so    = ($so=="DESC")? "ASC" : "DESC" ; 
$sort  = (isset($sb))?"ORDER BY $sb $so":"" ;
$like =  $_REQUEST['like'];
$curr_date=date('U');?>