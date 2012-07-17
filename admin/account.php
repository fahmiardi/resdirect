<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : account.php
    # Defenisi :
    # Untuk menampilkan profile member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");
    $userid = $_REQUEST["userid"];
    // Proses profile member :
    $error = "";
    if ($_POST) {

        if (!check_email($email)) {
             $error .= "<li>Email harus diisi.</li>";
        }
        if (!strlen($pass)) {
             $error .= "<li>Password harus diisi.</li>";
        }
        if (!strlen($name)) {
             $error .= "<li>Nama lengkap harus diisi.</li>";
        }
        if (!strlen($userid)) {
             $error .= "<li>UserID harus diisi.</li>";
        }
        if ($error) {
             display_html($error, "msg.html");
        }
        if (!strlen($userid)) {
             $error .= "<li>UserID harus diisi.</li>";
        }

        $random_sponsor_userid = trim($random_sponsor_userid);
        $direct_sponsor_userid = trim($direct_sponsor_userid);
        $password = trim($password);
        $email = trim($email);

        $qdb = "UPDATE members SET
            name = '$name' ,
            bank ='$bank',
            address ='$address',
            phone ='$phone' ,
            email ='$email',
            password ='$pass',
            userlevel = '$userlevel',
            rotation = '$rotation',
            direct_sponsor ='$direct_sponsor_userid',
            random_sponsor = '$random_sponsor_userid',
            pay_direct  = '$pay_direct',
            pay_random  = '$pay_random',
            pay_admin  = '$pay_admin',
            payment  = '$payment'

            WHERE userid = '$userid'";
        $dbres = mysql_query($qdb) or display_html(mysql_error(), "error.html");
        // Tampilkan halaman account.php?userid=$userid
        header("Location: account.php?userid=$userid");
    } else {
        if ($userid) {
            if (isset($act)) {
                if ($act == 'del') {
                    $qdb = "DELETE FROM `members` WHERE `userid` = '$userid'";
                    $dbres = mysql_query($qdb) or display_html(mysql_error(), "error.html");
                    // Tampilkan halaman members.php
                    header("Location: members.php");
                }
            } else {
                $show_array = array('userid' => $userid);
                // Tampilkan halaman (template file : admin_member_account.html)
                display_member_page("admin_member_account.html", $show_array, 1);
            }

        }
        // Error :
        else display_html("UserID member tidak ditemukan!", "error.html");

    }


?>