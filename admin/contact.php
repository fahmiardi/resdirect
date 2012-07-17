<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : contact.php
    # Defenisi :
    # Untuk mengirim email kepada member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");


    if ($_POST) {
        // Mengirim email kepada 1 member saja :
        if ($uid) {
            $dbres = mysql_query("SELECT * FROM members WHERE userid ='$uid'");
            if (mysql_num_rows($dbres)) {
                // Parse data member :
                while ($row = mysql_fetch_array($dbres)) {
                    $f = array('userid', 'password', 'bank', 'email', 'name', 'address', 'phone', 'direct_sponsor', 'random_sponsor',
                        'pay_direct', 'pay_random', 'pay_admin', 'joindate', 'userlevel');
                    $msge = $msg;
                    for($i = 0; $i < count($f); $i++) {
                        $jj = $f[$i];
                        $msge = str_replace("{".$jj."}", $row[$jj], $msge);
                    }
                    $mailto = "$row[name] <$row[email]>";
                    $result = sendmail($mailfrom, $row['email'], $mailsubject, $msge, $priority);
                }
                $show_array = array('msg' => "Email sudah dikirimkan kepada $uid");
                // Tampilkan halaman (template file : msg.html)
                display_admin_page("msg.html", $show_array);
            }
        } else {
            // Mengirim email kepada banyak member :

            switch($sento) {
                // Semua member :
                case 'All' :
                 $dbres = mysql_query("SELECT * FROM members");
                break;
                // Paid member saja:
                case 'Paid' :
                 $dbres = mysql_query("SELECT * FROM members WHERE `userlevel` = '1'");
                break;
                // Free member saja:
                case 'Free' :
                 $dbres = mysql_query("SELECT * FROM members WHERE `userlevel` = '0'");
                break;
                default :
                 $dbres = mysql_query("SELECT * FROM members");
            }

            if (mysql_num_rows($dbres)) {
                // Parse data member :
                while ($row = mysql_fetch_array($dbres)) {
                    $f = array('userid', 'password', 'bank', 'email', 'name', 'address', 'phone', 'direct_sponsor', 'random_sponsor',
                        'pay_direct', 'pay_random', 'pay_admin', 'joindate', 'userlevel');
                    $msge = $msg;
                    for($i = 0; $i < count($f); $i++) {
                        $jj = $f[$i];
                        $msge = str_replace("{".$jj."}", $row[$jj], $msge);
                    }
                    $result = sendmail($mailfrom, $row['email'], $mailsubject, $msge, $priority);
                }
                $show_array = array('msg' => "Email sudah dikirimkan kepada Member");
                // Tampilkan halaman (template file : msg.html)
                display_admin_page("msg.html", $show_array);
            }

        }
    } else {
        $show_array = array('uid' => $uid);
        // Tampilkan halaman (template file : admin_contact.html)
        display_admin_page("admin_contact.html", $show_array);

    }


?>