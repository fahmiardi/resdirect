<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : profile.php
    # Defenisi :
    # Untuk edit profile admin

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Proses simpan data admin :
    $error = "";
    if ($_POST) {

        if (!check_email($admin_email)) {
             $error .= "<li>Email harus diisi.</li>";
        }
        if (!strlen($admin_password)) {
             $error .= "<li>Password harus diisi.</li>";
        }
        if (!strlen($admin_name)) {
             $error .= "<li>Nama lengkap harus diisi.</li>";
        }
        if (!strlen($admin_userid)) {
             $error .= "<li>UserID harus diisi.</li>";
        }
        if ($error) {
            display_html($error, "msg.html");
        }

        $qdb = "UPDATE admin SET
            userid = '$admin_userid',
            password = '$admin_password',
            name = '$admin_name' ,
            bank ='$admin_bank',
            address ='$admin_address',
            phone ='$admin_phone' ,
            email ='$admin_email'";
        $dbres = mysql_query($qdb) or display_html(mysql_error(), "error.html");
        header("Location: profile.php");
    } else {
        // Tampilkan halaman (template file : admin_account.html)
        display_admin_page("admin_account.html", "");
    }


?>