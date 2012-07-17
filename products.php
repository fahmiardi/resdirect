<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : products.php
    # Defenisi :
    # Untuk menampilkan halaman produk

    require("config_inc.php");
    require("func_inc.php");
    include "sponsor_inc.php";

    $show_array = array(
    'direct_sponsor' => $direct_sponsor,
        'random_sponsor' => $random_sponsor,
        'title' => "Products" // Judul halaman (ganti bila perlu) !
    );

    // Tampilkan halaman (template file : page_products.html)
    display_home_page("page_products.html", $show_array);

?>