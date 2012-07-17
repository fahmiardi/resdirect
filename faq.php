<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   							   			            #
    #                                                                       #
    #***********************************************************************#
    # File : faq.php
    # Defenisi :
    # Untuk menampilkan halaman tanya jawab

    require("config_inc.php");
    require("func_inc.php");
    include "sponsor_inc.php";

    $show_array = array(
    'direct_sponsor' => $direct_sponsor,
        'random_sponsor' => $random_sponsor,
        'title' => "Faq - Tanya Jawab" // Judul halaman (ganti bila perlu) !
    );

    // Tampilkan halaman (template file : page_faq.html)
    display_home_page("page_faq.html", $show_array);

?>