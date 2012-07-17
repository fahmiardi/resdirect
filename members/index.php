<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : index.php
    # Defenisi :
    # Untuk menampilkan halaman utama control panel member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Tampilkan halaman (template file : member_home.html)
    display_member_page("member_home.html");

?>