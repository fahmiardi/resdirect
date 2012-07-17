<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : products.php
    # Defenisi :
    # Untuk menampilkan halaman produk di control panel member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");
    require("level_inc.php");

    // Tampilkan halaman (template file : member_products.html)
    display_member_page("member_products.html");

?>

