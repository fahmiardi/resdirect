<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : activate.php
    # Defenisi :
    # Untuk meng-aktivasi atau me-nonaktivasi downline

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");
    require("level_inc.php");


    $update = false;
    $ref = $_REQUEST["ref"];
    $uname = $_REQUEST["uname"];

    if ($uname || $level != "") {

        switch($ref) {
            // direct downline :
            case "direct" :
            $rotation = ($level == 1?"+2":"-2");
            $qdb = mysql_query("SELECT pay_direct FROM members WHERE userid='$uname' AND direct_sponsor = '$userid'");
            if ($qdb > 0) {
                $qdb = mysql_query("UPDATE members SET pay_direct='$level' WHERE userid='$uname' AND direct_sponsor = '$userid'");
                update($uname);
                setrotation($userid, $rotation);
            } else {
                show_error("Maaf, member $uname bukan direct downline anda");
            }
            break;

            // random downline :
            case "random" :
            $rotation = ($level == 1?"+1":"-1");
            $qdb = mysql_query("SELECT pay_random FROM members WHERE userid='$uname' AND random_sponsor = '$userid'");
            if ($qdb > 0) {
                $qdb = mysql_query("UPDATE members SET pay_random='$level' WHERE userid='$uname' AND random_sponsor = '$userid'");
                update($uname);
                setrotation($userid, $rotation);
            } else {
                show_error("Maaf, member $uname bukan random downline anda");
            }
            break;
        }

    }

    // Fungsi update status member :
    function update($uname) {
        global $conf;

        $qdb = mysql_query("SELECT pay_direct,pay_random,pay_admin FROM members WHERE userid='$uname'") or show_error(mysql_error());
        $row = mysql_fetch_array($qdb);

        if ($conf[rsponsor] == 1) {
            if (($row ['pay_direct'] == '1') && ($row ['pay_random'] == '1') && ($row ['pay_admin'] == '1')) {
                $qdb = mysql_query("UPDATE members SET userlevel='1' WHERE userid='$uname'") or show_error(mysql_error());
                send_active_mail($uname);
            } else {
                $qdb = mysql_query("UPDATE members SET userlevel='0' WHERE userid='$uname'") or show_error(mysql_error());
            }
        } else {
            if (($row ['pay_direct'] == '1') && ($row ['pay_admin'] == '1')) {
                $qdb = mysql_query("UPDATE members SET userlevel='1' WHERE userid='$uname'") or show_error(mysql_error());
                send_active_mail($uname);
            } else {
                $qdb = mysql_query("UPDATE members SET userlevel='0' WHERE userid='$uname'") or show_error(mysql_error());
            }
        }
    }

    // Fungsi set rotasi member :
    function setrotation($uname, $value) {
        global $conf;
        $qdb = mysql_query("SELECT rotation FROM members WHERE userid='$uname'") or show_error(mysql_error());
        $row = mysql_fetch_array($qdb);
        $rot = $row['rotation'];
        if ($rot >= 3) {
            $rot = $rot - ($value);
            $qdb = mysql_query("UPDATE members SET rotation='$rot' WHERE userid='$uname'") or show_error(mysql_error());
        }
    }

    // Kembali ke halaman referral :
    header("Location: referral.php");

?>