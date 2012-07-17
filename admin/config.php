<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : config.php
    # Defenisi :
    # Untuk edit konfigurasi program

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    if ($_POST) {
        // Simpan konfigurasi program :
        if ($doing == "config") {

            $qdb = "UPDATE admin SET
                with_rsponsor  = '$with_rsponsor' ,
                maxrefshow     = '$maxrefshow' ,
                maxstatsshow   = '$maxstatsshow' ,
                cookiexpires   = '$cookiexpires' ,
                tpldir         = '$tpldir' ,
                program_name   = '$program_name' ,
                program_url    = '$program_url' ,
                member_url     = '$member_url' ,
                launch_date    = '$launch_date' ,
                dsponsor_amount= '$dsponsor_amount' ,
                rsponsor_amount= '$rsponsor_amount' ,
                admin_amount   = '$admin_amount'";
            $dbres = mysql_query($qdb) or display_html(mysql_error(), "error.html");
            header("Location: config.php");
        }
        // Simpan konfigurasi email :
        elseif ($doing == "letter") {
            $qdb = "UPDATE letters SET
                user_title           ='$user_title',
                dsponsor_title       ='$dsponsor_title',
                rsponsor_title       ='$rsponsor_title',
                admin_title          ='$admin_title',
                active_title         ='$active_title',
                del_title            ='$del_title',
                pass_msg             ='$pass_msg',
                newuser_msg          ='$newuser_msg',
                dsponsor_msg         ='$dsponsor_msg',
                rsponsor_msg         ='$rsponsor_msg',
                admin_msg            ='$admin_msg',
                active_msg           ='$active_msg',
                del_msg              ='$del_msg'";
            $dbres = mysql_query($qdb) or display_html(mysql_error(), "error.html");
            header("Location: config.php");
        }
    } else {
        // Proses parse konfigurasi :
        $letters_row = mysql_fetch_array(
        mysql_db_query($conf['dbname'], "SELECT * FROM letters"))
        or display_html(mysql_error(), "error.html");


        $show_array = array(

        'newuser_msg'       => $letters_row["newuser_msg"],
            'dsponsor_msg'  => $letters_row["dsponsor_msg"],
            'rsponsor_msg'  => $letters_row["rsponsor_msg"],
            'admin_msg'     => $letters_row["admin_msg"],
            'user_title'    => $letters_row["user_title"],
            'dsponsor_title' => $letters_row["dsponsor_title"],
            'rsponsor_title' => $letters_row["rsponsor_title"],
            'admin_title'    => $letters_row["admin_title"],
            'active_title'   => $letters_row["active_title"],
            'del_title'      => $letters_row["del_title"],
            'active_msg'     => $letters_row["active_msg"],
            'pass_msg'       => $letters_row["pass_msg"],
            'del_msg'        => $letters_row["del_msg"],
            'tpldir'         => $conf['tpldir'],
            'dsponsor_amount' => $conf['dsponsor_amount'],
            'rsponsor_amount' => $conf['rsponsor_amount'],
            'admin_amount'    => $conf['admin_amount'],
            'with_rsponsor'   => $conf['rsponsor'],
            'maxrefshow'      => $conf['maxrefshow'],
            'maxstatsshow'    => $conf['maxstatsshow'],
            'cookiexpires'    => $conf['expires'] );

        // Tampilkan halaman (template file : admin_config.html)
        display_admin_page("admin_config.html", $show_array);
    }


?>

