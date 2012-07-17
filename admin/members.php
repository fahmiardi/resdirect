<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : members.php
    # Defenisi :
    # Untuk menampilkan semua member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Jumlah maksimum member yang ditampilkan dalam 1 halaman (default 20)
    $max_show = 20;

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

    if (isset($_POST['actions']) && $_POST['actions']=="cariAdmin") {
	$query = htmlentities(trim($_POST['query']));

        // Proses mencari member :
        if ($query != "") {
            $dbres = mysql_query("SELECT * FROM members WHERE userid LIKE '%$query%'
                OR name LIKE '%$query%' OR email LIKE '%$query%'
                ORDER BY joindate DESC", $dbcon)
            or display_html(mysql_error(), "error.html");
        }
	else {
		$dbres = mysql_query("SELECT * FROM members ORDER BY joindate DESC");
	}
    } else {
        $dbres = mysql_query("SELECT * FROM members ORDER BY joindate DESC");
    }

    $total_member = mysql_num_rows($dbres);
    $total_paid_member = mysql_num_rows(mysql_query("SELECT * FROM members WHERE userlevel='1'", $dbcon));
    $total_free_member = $total_member - $total_paid_member;

    $n = 1;
    $no = $start_num;
    $all_members = "";
    $page_num = "";
    // Kalau member ada, lalu siapkan tampilan :
    if ($total_member > 0) {
        while ($row = mysql_fetch_array($dbres)) {
            if ($n > $start_num and $n <= $end_num) {
                $no++;
                 $userid = $row['userid'] ;

                $stats = mysql_num_rows(mysql_query("SELECT * FROM stats WHERE userid='$userid'", $dbcon));
                $d_ref = mysql_num_rows(mysql_query("SELECT direct_sponsor FROM members WHERE direct_sponsor='$userid'", $dbcon));
                $r_ref = mysql_num_rows(mysql_query("SELECT direct_sponsor FROM members WHERE random_sponsor='$userid'", $dbcon));
                $all_members .= "<tr>";
                $all_members .= "<td width=\"20\" bgcolor=\"#FFFFFF\">$no</td>";
                $all_members .= "<td width=\"218\" bgcolor=\"#FFFFFF\"><a href=\"account.php?userid=$userid\">$row[name] ( $userid)</a></td>";
                $all_members .= "<td width=\"204\" bgcolor=\"#FFFFFF\"><a href=\"contact.php?uid=$userid\">$row[email]</a></td>";
                $all_members .= "<td width=\"100\" bgcolor=\"#FFFFFF\"><a href=\"referral.php?userid=$userid\">D=$d_ref R=$r_ref</a>";
                $all_members .= "<td width=\"47\" bgcolor=\"#FFFFFF\"><a href=\"stats.php?userid=$userid\">$stats</a></td>";

                $st1 = "<a href=\"activate.php?userid=$row[userid]&level=0\">NonAktifkan</a>";
                $st2 = "<a href=\"activate.php?userid=$row[userid]&level=1\"><b>Aktifkan</b></a>";
                $aktif = ($row['pay_admin'] == 1?"$st1":"$st2");

                $all_members .= "<td width=\"54\" bgcolor=\"#FFFFFF\">$aktif</td>";

                $st1 = "<b>Paid</b>";
                $st2 = "Free";
                $status = ($row['userlevel'] == 1?"$st1":"$st2");

                $all_members .= "<td width=\"50\" bgcolor=\"#FFFFFF\">$status</td>";
                $all_members .= "</tr>";
            }

            else
                {
                echo("") ;
            }
            $n = $n + 1 ;
        }
        // Menghitung jumlah halaman tampilan member
        $number_pages = 1 + $total_member / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            $page_num .= "[<a href=\"members.php?page=$c\">Hal $c</a>]";
            $c = $c + 1 ;
        }
    }
    // Ternyata tidak ada member :
    else
    {
        $all_members = "Tidak ada Member";
        $page_num = "";
    }

    $show_array = array(
    'page_num' => $page_num,
        'all_members' => $all_members );
    // Tampilkan halaman (template file : admin_member.html)
    display_admin_page("admin_member.html", $show_array);


?>
