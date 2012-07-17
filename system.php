<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : system.php
    # Defenisi :
    # Untuk menampilkan halaman system

    require("config_inc.php");
    require("func_inc.php");
    include "sponsor_inc.php";

    $show_array = array(
    'direct_sponsor' => $direct_sponsor,
        'random_sponsor' => $random_sponsor,
        'title' => "System" // Judul halaman (ganti bila perlu) !
    );

    // Tampilkan halaman (template file : page_system.html)
    display_home_page("page_system.html", $show_array);

?>