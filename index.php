<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : index.php
    # Defenisi :
    # Untuk menampilkan halaman utama web anda

    require("config_inc.php");
    require("func_inc.php");
    include "sponsor_inc.php";

    $show_array = array(
    'direct_sponsor' => $direct_sponsor,
        'random_sponsor' => $random_sponsor,
        'title' => $conf['program_name'] // Judul halaman (ganti bila perlu) !
    );

    // Tampilkan halaman (template file : page_home.html)
    display_home_page("page_home.html", $show_array);
?>