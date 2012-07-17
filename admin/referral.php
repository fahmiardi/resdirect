<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : referral.php
    # Defenisi :
    # Untuk menampilkan dowline member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Jumlah maksimum downline yang ditampilkan dalam 1 halaman
    $max_show = $conf['maxrefshow'] ;
    if (!IsSet($page)) {
        $page = 1;
    }

    $start_num = ($page-1) * $max_show ;
    $end_num = $start_num + $max_show ;

    //perhitungan direct downline dan random downline
    $dbres1 = mysql_query("SELECT * FROM members WHERE direct_sponsor='$userid'");
    $dbres2 = mysql_query("SELECT * FROM members WHERE direct_sponsor='$userid' AND pay_direct='1'");
    $dbres3 = mysql_query("SELECT * FROM members WHERE random_sponsor='$userid'");
    $dbres4 = mysql_query("SELECT * FROM members WHERE random_sponsor='$userid' AND pay_random='1'");

    $direct_downline_total = mysql_num_rows($dbres1);
    $direct_downline_activated = mysql_num_rows($dbres2);

    $random_downline_total = mysql_num_rows($dbres3);
    $random_downline_activated = mysql_num_rows($dbres4);


    //**********************DIRECT DOWNLINE************************//
    $n = 1 ;
    $it = $start_num;
    //Kalau punya direct downline, mempersiapkan tampilan
    if ($direct_downline_total > 0) {
        while ($row = mysql_fetch_array($dbres1)) {
            if ($n > $start_num and $n <= $end_num) {
                $downline = $row[userid] ;
                $stats = mysql_num_rows(mysql_query("SELECT * FROM stats WHERE userid='$downline'"));
                $d_ref = mysql_num_rows(mysql_query("SELECT direct_sponsor FROM members WHERE direct_sponsor='$downline'"));
                $r_ref = mysql_num_rows(mysql_query("SELECT direct_sponsor FROM members WHERE random_sponsor='$downline'"));
                $it++;
                $direct_downline .= "<tr>";
                $direct_downline .= "<td width=\"21\" bgcolor=\"#FFFFFF\">$it</td>";
                $direct_downline .= "<td width=\"253\" bgcolor=\"#FFFFFF\"><a href=\"account.php?userid=$downline\">$row[name] ($downline)</a></td>";
                $direct_downline .= "<td width=\"179\" bgcolor=\"#FFFFFF\"><a href=\"contact.php?userid=$downline\">$row[email]</a></td>";
                $direct_downline .= "<td width=\"97\" bgcolor=\"#FFFFFF\"><a href=\"referral.php?userid=$downline\">D=$d_ref R=$r_ref</a></td>";
                $direct_downline .= "<td width=\"65\" bgcolor=\"#FFFFFF\"><a href=\"stats.php?userid=$userid\">$stats</a></td>";

                $url = "activate.php?ref=direct&uname=$row[userid]";

                $st1 = "<b>Paid</b>";
                $st2 = "Free";

                $status = ($row[userlevel] == 1?"$st1":"$st2");

                $direct_downline .= "<td width=\"96\" bgcolor=\"#FFFFFF\">$status</td>";
                $direct_downline .= "</tr>";
            } else {
                echo("") ;
            }
            $n = $n + 1 ;
        }
        // Menghitung jumlah halaman tampilan direct downline
        $number_pages = 1 + $direct_downline_total / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            $direct_pagenum .= "[<a href=\"referral.php?page=$c&userid=$userid\">Hal $c</a>]";
            $c = $c + 1 ;
        }
    }
    // Member ternyata gak punya direct downline
    else
    {
        $direct_downline = "Belum ada Direct Downline";
        $direct_pagenum = "";
    }

    //**********************RANDOM DOWNLINE************************//

    $nr = 1 ;
    $itr = $start_num;
    //Kalau punya random downline, mempersiapkan tampilan
    if ($random_downline_total > 0) {
        while ($row = mysql_fetch_array($dbres3)) {
            if ($nr > $start_num and $nr <= $end_num) {
                $downline = $row[userid] ;
                $stats = mysql_num_rows(mysql_query("SELECT * FROM stats WHERE userid='$downline'"));
                $d_ref = mysql_num_rows(mysql_query("SELECT direct_sponsor FROM members WHERE direct_sponsor='$downline'"));
                $r_ref = mysql_num_rows(mysql_query("SELECT direct_sponsor FROM members WHERE random_sponsor='$downline'"));
                $it++;
                $random_downline .= "<tr>";
                $random_downline .= "<td width=\"21\" bgcolor=\"#FFFFFF\">$it</td>";
                $random_downline .= "<td width=\"253\" bgcolor=\"#FFFFFF\"><a href=\"account.php?userid=$downline\">$row[name] ($downline)</a></td>";
                $random_downline .= "<td width=\"179\" bgcolor=\"#FFFFFF\"><a href=\"contact.php?userid=$downline\">$row[email]</a></td>";
                $random_downline .= "<td width=\"97\" bgcolor=\"#FFFFFF\"><a href=\"referral.php?userid=$downline\">D=$d_ref R=$r_ref</a></td>";
                $random_downline .= "<td width=\"65\" bgcolor=\"#FFFFFF\"><a href=\"stats.php?userid=$userid\">$stats</a></td>";

                $url = "activate.php?ref=direct&uname=$row[userid]";

                $st1 = "<b>Paid</b>";
                $st2 = "Free";

                $status = ($row[userlevel] == 1?"$st1":"$st2");

                $random_downline .= "<td width=\"96\" bgcolor=\"#FFFFFF\">$status</td>";
                $random_downline .= "</tr>";
            } else {
                echo("") ;
            }
            $nr = $nr + 1 ;
        }
        // Menghitung jumlah halaman tampilan random downline
        $number_pages = 1 + $random_downline_total / $max_show ;
        $cr = 1 ;
        while ($cr < $number_pages) {
            $random_pagenum .= "[<a href=\"referral.php?page=$cr&userid=$userid\">Hal $cr</a>]";
            $cr = $cr + 1 ;
        }
    }
    // Member ternyata gak punya random downline
    else
    {
        $random_downline = "Belum ada Random Downline";
        $random_pagenum = "";
    }

    $show_array = array(
    'total_direct_downline' => $direct_downline_total,
        'total_active_direct_downline' => $direct_downline_activated,
        'direct_downline' => $direct_downline,
        'total_random_downline' => $random_downline_total,
        'total_active_random_downline' => $random_downline_activated,
        'random_downline' => $random_downline,
        'direct_page' => $direct_pagenum,
        'random_page' => $random_pagenum );
    // Tampilkan halaman (template file : admin_member_referral.html)
    display_member_page("admin_member_referral.html", $show_array, 1);

?>