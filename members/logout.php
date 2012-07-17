<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : logout.php
    # Defenisi :
    # Untuk proses logout member dari member area.

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Proses logout :
    session_defaults();
    display_html("Anda sudah LogOut", "member_login.html");
    exit;

?>