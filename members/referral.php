<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : referral.php
    # Defenisi :
    # Untuk menampilkan downline member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");
    require("level_inc.php");

    // Jumlah maksimum perhalaman
    $max_show = $conf['maxrefshow'] ;

    if(isset($_GET['page']) && $_GET['page'] !="") {
	$page = (int)$_GET['page'];
	if($page<=0) {
		$page = 1;
	}
    }
    else {
	$page = 1;
    }

    $start_num = ($page-1) * $max_show ;
    $end_num = $start_num + $max_show ;

    // Menghitung jumlah direct referral
    $dbres1 = mysql_query("SELECT * FROM members WHERE direct_sponsor='$userid'", $dbcon);
    $dbres2 = mysql_query("SELECT * FROM members WHERE direct_sponsor='$userid' AND pay_direct='1'", $dbcon);
    $direct_referral_total = mysql_num_rows($dbres1);
    $direct_referral_activated = mysql_num_rows($dbres2);

    //**********************DIRECT REFERRAL************************//
    $n = 1 ;
    $it = $start_num;

    if ($direct_referral_total > 0) {
        while ($row = mysql_fetch_array($dbres1)) {
            if ($n > $start_num and $n <= $end_num) {
                $it++;
                $direct_referral .= "<tr>";
                $direct_referral .= "<td width=\"5%\" bgcolor=\"#FFFFFF\">$it</td>";
                $direct_referral .= "<td width=\"14%\" bgcolor=\"#FFFFFF\">$row[userid]</td>";
                $direct_referral .= "<td width=\"27%\" bgcolor=\"#FFFFFF\">$row[name]</td>";
                $direct_referral .= "<td width=\"25%\" bgcolor=\"#FFFFFF\"><a href=\"mailto:$row[email]\">$row[email]</td>";
                $payment = $row[payment]+$conf[dsponsor_amount];
                $direct_referral .= "<td width=\"17%\" bgcolor=\"#FFFFFF\">Rp.$payment</td>";

                $st1 = "<a href=\"activate.php?ref=direct&uname=$row[userid]&level=0\">NonAktifkan</a>";
                $st2 = "<a href=\"activate.php?ref=direct&uname=$row[userid]&level=1\"><b>Aktifkan</b></a>";

                $status = ($row[pay_direct] == 1?"$st1":"$st2");

                $direct_referral .= "<td width=\"12%\" bgcolor=\"#FFFFFF\">$status</td>";
                $direct_referral .= "</tr>";
            } else {
                echo("") ;
            }
            $n = $n + 1 ;
        }

        $number_pages = 1 + $direct_referral_total / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            $direct_pagenum .= "[<a href=\"referral.php?page=$c\">Hal $c</a>]";
            $c = $c + 1 ;
        }
    }

    else
    {
        $direct_referral = "Belum ada Direct Referral";
        $direct_pagenum = "";
    }

    //**********************RANDOM REFERRAL************************//
    if ($conf['rsponsor'] == 1) {

        $dbres3 = mysql_query("SELECT * FROM members WHERE random_sponsor='$userid'", $dbcon);
        $dbres4 = mysql_query("SELECT * FROM members WHERE random_sponsor='$userid' AND pay_random='1'", $dbcon);
        $random_referral_total = mysql_num_rows($dbres3);
        $random_referral_activated = mysql_num_rows($dbres4);

        $nr = 1 ;
        $itr = $start_num;

        if ($random_referral_total > 0) {
            while ($row = mysql_fetch_array($dbres3)) {
                if ($nr > $start_num and $nr <= $end_num) {
                    $itr++;
                    $random_referral .= "<tr>";
                    $random_referral .= "<td width=\"5%\" bgcolor=\"#FFFFFF\">$itr</td>";
                    $random_referral .= "<td width=\"14%\" bgcolor=\"#FFFFFF\">$row[userid]</td>";
                    $random_referral .= "<td width=\"27%\" bgcolor=\"#FFFFFF\">$row[name]</td>";
                    $random_referral .= "<td width=\"25%\" bgcolor=\"#FFFFFF\"><a href=\"mailto:$row[email]\">$row[email]</td>";

                    $payment = $row['payment']+$conf['rsponsor_amount'];
                    $random_referral .= "<td width=\"17%\" bgcolor=\"#FFFFFF\">Rp.$payment</td>";

                    $st1 = "<a href=\"activate.php?ref=random&uname=$row[userid]&level=0\">NonAktifkan</a>";
                    $st2 = "<a href=\"activate.php?ref=random&uname=$row[userid]&level=1\"><b>Aktifkan</b></a>";

                    $status = ($row['pay_random'] == 1?"$st1":"$st2");

                    $random_referral .= "<td width=\"12%\" bgcolor=\"#FFFFFF\">$status</td>";
                    $random_referral .= "</tr>";
                } else {
                    echo("") ;
                }
                $nr = $nr + 1 ;
            }

            $number_pages = 1 + $random_referral_total / $max_show ;
            $cr = 1 ;
            while ($cr < $number_pages) {
                $random_pagenum .= "[<a href=\"referral.php?page=$cr\">Hal $cr</a>]";
                $cr = $cr + 1 ;
            }
        }

        else
        {
            $random_referral = "Belum ada Random Referral";
            $random_pagenum = "";
        }
    }

    //mempersiapkan template untuk ditampilkan
    $show_array = array(
    'total_direct_referral' => $direct_referral_total,
        'total_active_direct_referral' => $direct_referral_activated,
        'direct_referral' => $direct_referral,
        'total_random_referral' => $random_referral_total,
        'total_active_random_referral' => $random_referral_activated,
        'random_referral' => $random_referral,
        'direct_page' => $direct_pagenum,
        'random_page' => $random_pagenum );
    // Tampilkan halaman (template file : member_referral.html)
    display_member_page("member_referral.html", $show_array);

?>
