<?php

if (isset($_POST['convert'])){
    //gathering files info
    $file = $_FILES['book']['name'];
    $tmp_file = $_FILES['book']['tmp_name'];
    move_uploaded_file($tmp_file,$file);
    $extention = $_POST['extention'];
    $file_info = fopen("file_info.txt",'w+');
    $write = fwrite($file_info,$file);
    fclose($file_info);
    //saving file name into file_info.txt
    $open_file = fopen("file_info.txt","r");
    $read_file = fread($open_file,filesize("file_info.txt"));
    if(file_exists($read_file)){
        //deleting the file extention and copying it
        @chmod($read_file,0775);
        $remove = strstr($read_file,".",true);
        $remove = str_replace(".","",$remove);
        @$copy   = copy($read_file,"uploaded/" . $remove . "." . $extention);
        //saving the new file name again without any extention
        $file_info = fopen("file_info.txt",'w+');
        $write = fwrite($file_info,$read_file);
        fclose($file_info);
        //saving the extention that the user choosed in a file called extention
        $create_file = fopen('extention.txt','w+');
        $write_ext   = fwrite($create_file,$extention);
        fclose($create_file);

        $open_ext = fopen("extention.txt",'r');
        $read_extention = fread($open_ext,filesize("extention.txt"));

        if($copy){
            echo "File Was Converted And  Copied To : " . __DIR__ . "/uploaded ";
            //getting the new file name without extention and adding the new extention to it
            echo "<a href='uploaded/$remove.$read_extention '>Download</a><br>";

            echo "
            <form action='' method='post'>
            <a href='index.php' name='delete'>Back To Home Page</a>
            </form>
            ";
            if(isset($_POST['delete'])){
                unlink("uploaded/$read_file");
                unlink("uploaded/$remove.$read_extention");
            }
        }else{
            echo "Fail To Convert The File";
        }

    }else{
        echo "File Doesn't Exist !";
    }
}