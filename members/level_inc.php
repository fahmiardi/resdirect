<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : level_inc.php
    # Defenisi :
    # Untuk cek status member , Jika status member adalah FREE maka script
    #akan menampilkan pesan untuk upgrade.

    // Cek status member :
    if ($userlevel != 1) {
        $msg = "Maaf, Status keanggotaan anda masih <b>Free Member</b>..<br>";
        $msg .= "Halaman ini tersedia hanya untuk <b>Paid Member</b>.<br>";
        $msg .= "<a href=\"upgrade.php\">Klik di sini</a> untuk mengupgrade keangotaan anda.";
        display_html($msg, "msg.html");
    }
?>
