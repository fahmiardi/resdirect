<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : add_account.php
    # Defenisi :
    # Untuk mendaftarkan member baru oleh admin

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    if ($_POST) {

        if (!strlen($userid)) {
             $error .= "<li>UserID harus diisi.</li>";
        }
        if (!strlen($pass)) {
             $error .= "<li>Password harus diisi.</li>";
        }
        if (!check_email($email)) {
             $error .= "<li>Email harus diisi.</li>";
        }
        if (!strlen($name)) {
             $error .= "<li>Nama lengkap harus diisi.</li>";
        }
        if ($error) {
             display_html($error, "msg.html");
        }


        $random_sponsor_userid = trim($random_sponsor_userid);
        $direct_sponsor_userid = trim($direct_sponsor_userid);
        $password = trim($password);
        $email = trim($email);

        $qdb = "INSERT INTO members SET
            userid = '$userid' ,
            name = '$name' ,
            bank ='$bank',
            address ='$address',
            phone ='$phone' ,
            email ='$email',
            password ='$password',
            userlevel = '$userlevel',
            rotation = '$rotation',
            direct_sponsor ='$direct_sponsor_userid',
            random_sponsor = '$random_sponsor_userid',
            pay_direct  = '$pay_direct',
            pay_random  = '$pay_random',
            pay_admin  = '$pay_admin',
            payment  = '$payment',
            joindate = NOW()";
        $dbres = mysql_query($qdb) or display_html(mysql_error(), "error.html");
        header("Location: account.php?userid=$userid");
    } else {
        display_admin_page("admin_account_add.html", $show_array, 1);
    }


?>