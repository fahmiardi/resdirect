<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : activate.php
    # Defenisi :
    # Untuk meng-aktivasi atau me-nonaktivasi member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    $update = false;
    $level = htmlentities(trim($_GET["level"]));
    $userid = htmlentities(trim($_GET["userid"]));

    if (($userid != "") || ($level != "")) {
        $dbres = mysql_query("UPDATE members SET pay_admin='$level' WHERE userid='$userid'", $dbcon);
        update($userid);
    }

    // Kembali ke halaman members.php :
    header("Location: members.php");

    // Fungsi update status member :
    function update($uname) {
        global $conf, $dbcon;

        $qdb = mysql_query("SELECT pay_direct,pay_random,pay_admin FROM members WHERE userid='$uname'", $dbcon) or display_html(mysql_error(), "error.html");
        $row = mysql_fetch_array($qdb);

        if ($conf['rsponsor'] == 1) {
            if (($row ['pay_direct'] == '1') && ($row ['pay_random'] == '1') && ($row ['pay_admin'] == '1')) {
                $qdb = mysql_query("UPDATE members SET userlevel='1' WHERE userid='$uname'", $dbcon) or display_html(mysql_error(), "error.html");
                send_active_mail($uname);
            } else {
                $qdb = mysql_query("UPDATE members SET userlevel='0' WHERE userid='$uname'", $dbcon) or display_html(mysql_error(), "error.html");
            }
        } else {
            if (($row ['pay_direct'] == '1') && ($row ['pay_admin'] == '1')) {
                $qdb = mysql_query("UPDATE members SET userlevel='1' WHERE userid='$uname'", $dbcon) or display_html(mysql_error(), "error.html");
                send_active_mail($uname);
            } else {
                $qdb = mysql_query("UPDATE members SET userlevel='0' WHERE userid='$uname'", $dbcon) or display_html(mysql_error(), "error.html");
            }
        }
    }
?>
