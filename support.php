<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : support.php
    # Defenisi :
    # Untuk menampilkan halaman support

    require("config_inc.php");
    require("func_inc.php");
    include "sponsor_inc.php";

    $show_array = array(
    'direct_sponsor' => $direct_sponsor,
        'random_sponsor' => $random_sponsor,
        'title' => "Support" // Judul halaman (ganti bila perlu) !
    );

    // Tampilkan halaman (template file : page_support.html)
    display_home_page("page_support.html", $show_array);

?>