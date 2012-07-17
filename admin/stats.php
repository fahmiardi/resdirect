<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : stats.php
    # Defenisi :
    # Untuk menampilkan statistik website

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Jumlah maksimum perhalaman
    $max_show = $conf['maxstatsshow'] ;
    if (!IsSet($page)) {
        $page = 1;
    }

    $start_num = ($page-1) * $max_show ;
    $end_num = $start_num + $max_show ;

    // Statistik 1 member saja :
    if (isset($userid)) {
        $dbres = mysql_query("SELECT * FROM stats WHERE userid = '$userid' ORDER BY time DESC");
    }
    // Statistik website keseluruhan :
    else
    {
        $dbres = mysql_query("SELECT * FROM stats ORDER BY time DESC");
    }
    $total_stats = mysql_num_rows($dbres);

    $n = 1 ;
    $no = $start_num;

    $website_stats = "";
    $page_num = "";
    // Kalau ada kunjungan :
    if ($total_stats > 0) {
        while ($row = mysql_fetch_array($dbres)) {
            if ($n > $start_num and $n <= $end_num) {
                $no++;
                list($thn, $bln, $tgl) = explode("-", $row['time']);
                list($tgl, $wktu) = explode(" ", $tgl);
                $website_stats .= "<tr>";
                $website_stats .= "<td width=\"5%\" bgcolor=\"#FFFFFF\">$no</td>";
                $website_stats .= "<td width=\"15%\" bgcolor=\"#FFFFFF\">$row[userid]</td>";
                $website_stats .= "<td width=\"25%\" bgcolor=\"#FFFFFF\">$tgl/$bln/$thn - $wktu</td>";
                $website_stats .= "<td width=\"15%\" bgcolor=\"#FFFFFF\">$row[ip]</td>";
                $website_stats .= "<td width=\"40%\" bgcolor=\"#FFFFFF\">$row[ref]</td>";
                $website_stats .= "</tr>";

            } else {
                echo("") ;
            }
            $n = $n + 1 ;
        }
        // Menghitung jumlah halaman tampilan statistik
        $number_pages = 1 + $total_stats / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            if (isset($userid)) {
                $page_num .= "[<a href=\"stats.php?page=$c&userid=$userid\">Hal $c</a>]";
            } else {
                $page_num .= "[<a href=\"stats.php?page=$c\">Hal $c</a>]";
            }
            $c = $c + 1 ;
        }
    }
    // Ternyata tidak ada kunjungan :
    else
    {
        $website_stats = "Belum ada Kunjungan";
        $page_num = "";
    }
    $show_array = array(
    'page_num' => $page_num,
        'website_stats' => $website_stats,
        'total_stats' => $total_stats );
    // Tampilkan halaman (template file : admin_stats.html)
    display_admin_page("admin_stats.html", $show_array);


?>