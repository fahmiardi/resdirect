<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : index.php
    # Defenisi :
    # Untuk halaman utama admin control panel

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    $total_stats = mysql_num_rows(mysql_query("SELECT * FROM stats WHERE 1", $dbcon));
    $payment1 = mysql_num_rows(mysql_query("SELECT * FROM members WHERE `pay_admin` ='1'"));
    $payment2 = mysql_num_rows(mysql_query("SELECT * FROM members WHERE `pay_direct` ='1'"));
    $payment3 = mysql_num_rows(mysql_query("SELECT * FROM members WHERE `pay_random` ='1'"));

    $show_array = array(
    'total_stats' => $total_stats,
        'admin_payment' => $payment1 * $conf['admin_amount'],
        'direct_payment' => $payment2 * $conf['dsponsor_amount'],
        'random_payment' => $payment3 * $conf['rsponsor_amount'] );

    // Tampilkan halaman (template file : admin_home.html)
    display_admin_page("admin_home.html", $show_array);


?>
