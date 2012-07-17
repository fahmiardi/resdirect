<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : password.php
    # Defenisi :
    # Untuk mengirimkan password member kalau lupa

    require("../config_inc.php");
    require("../func_inc.php");

    if (isset($_POST['actions']) && $_POST['actions']=="lupaPassword") {
        $userid = htmlentities(trim($_POST['userid']));

        $dbq = "SELECT * FROM members where userid='$uname'";
        $dbres = mysql_query($dbq, $dbcon) or display_html(mysql_error(), "error.html");
        
	//Lihat konfigurasi email dari database :
        $letters_row = mysql_fetch_array(
        mysql_query("SELECT pass_msg FROM letters", $dbcon))
        or display_html(mysql_error(), "error.html");
        $msg = $letters_row["pass_msg"];

        if (mysql_num_rows($dbres) > 0) {
            $dbrow = mysql_fetch_array($dbres);
            $pastitle = "Password $userid!";
            $em = $dbrow['email'];
            $password = $dbrow['password'];

            // Proses parse email password :
            $msg = str_replace("{member_name}", $dbrow["name"], $msg);
            $msg = str_replace("{member_email}", $dbrow["email"], $msg);
            $msg = str_replace("{member_phone}", $dbrow["phone"], $msg);
            $msg = str_replace("{member_userid}", $dbrow["userid"], $msg);
            $msg = str_replace("{member_password}", $dbrow["password"], $msg);
            $msg = str_replace("{admin_name}", $conf['admin_name'], $msg);
            $msg = str_replace("{admin_phone}", $conf['admin_phone'], $msg);
            $msg = str_replace("{admin_email}", $conf['admin_email'], $msg);
            $msg = str_replace("{admin_bank}", $conf['admin_bank'], $msg);
            $msg = str_replace("{admin_amount}", $admin_amount, $msg);

            $msg = str_replace("{member_url}", $conf['member_url'], $msg);
            $msg = str_replace("{program_name}", $conf['program_name'], $msg);
            $msg = str_replace("{program_url}", $conf['program_url'], $msg);

            $admin_em = $conf[admin_email];
            sendmail($admin_em, $em, $pastitle, $msg, '3');
            $msg = "Password untuk UserID $userid sudah dikirimkan";

        } else {
            $msg = "UserID tidak ditemukan";
        }
    }
    // Tampilkan halaman (template file : member_password.html)
    display_html($msg, "member_password.html");
?>
