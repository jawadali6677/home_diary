<?php
include "function.php";

if(isset($_SESSION['adminLogin'][0]) AND isset($_SESSION['adminLogin'][1]) AND !empty($_SESSION['adminLogin'][0]) AND !empty($_SESSION['adminLogin'][1]))
{
    if(isset($_POST['old_password']) AND isset($_POST['new_password']) AND isset($_POST['confirm_password'] ))
    {
        $adminID=$_SESSION['adminLogin'][1];
        $old=$_POST['old_password'];
        $new=$_POST['new_password'];
        $confirm=$_POST['confirm_password'];

        if($new==$confirm)
        {
            $run=$con->prepare("SELECT * FROM `admin` WHERE id=? AND password=?");
            $run->bindParam(1,$adminID,PDO::PARAM_INT);
            $run->bindParam(2,$old,PDO::PARAM_INT);
            if($run->execute())
            {
                if($run->rowCount()>0)
                {
                    $run=$con->prepare("UPDATE `admin` SET `password`=? WHERE id=?");
                    $run->bindParam(1,$confirm,PDO::PARAM_STR);
                    $run->bindParam(2,$adminID,PDO::PARAM_INT);
                    if($run->execute())
                    {
                        return MsgDisplay('success','Password Change Successfully.....!','');
                    }
                }
                else
                {
                    return MsgDisplay('error','Invalid Old Password.....!','');
                }
            }
        }
        else
        {
            return MsgDisplay('error','Missmatch New Password And Confirm Password.....!','');
        }

    }
    
}



?>