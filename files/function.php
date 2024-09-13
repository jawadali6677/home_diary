<?php 
  session_start();


  global $con;

  define("BASEURL","http://localhost/home_diary");


	try
	{
        $db=new PDO("mysql:host=localhost;dbname=home_diary","root","");
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $con=$db;
    }

    catch(PDOException $e)
    {
        echo "Sorry database connection error:-".$e->getMessage();
        exit();
    }


  function check($array)
  {
  	echo "<pre>";
  	print_r($array);
  	exit();
  }

  function MsgDisplay($status,$msg,$url=NULL)
  {
      if ($status==='success' && !empty($url)) 
      {
          echo json_encode([
              'success'=>'success',
              'message'=>$msg,
              'url'=>$url,
              
          ] , 200);
      }
      else if ($status==='success' && empty($url)) 
      {
          echo json_encode([
              'success'=>'success',
              'message'=>$msg,
          ] , 200);
      }
      else if ($status==='error' && empty($url)) 
      {
          echo json_encode([
              'error'=>'error',
              'message'=>$msg,
          ] , 500);
      }
  else if ($status==='refersh') 
  {
    echo json_encode([
        'success'=>'success',
        'message'=>$msg,
        'signout'=>1,
    ]);
  }
  else
  {
    echo json_encode([
        'redirect'=>'redirect',
        'url'=>$url,
    ]);
  }
  }

  function convertTo12Hour($time24) {
    return date("g:i a", strtotime($time24));
}

  ?>