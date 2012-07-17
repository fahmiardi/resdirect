<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : stats.php
    # Defenisi :
    # Untuk menampilkan statistik web duplikasi member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");
    require("level_inc.php");
    
    $pagenum = "";
    // Jumlah maksimum perhalaman
    $max_show = $conf['maxstatsshow'] ;

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

    //Menghtung jumlah statistik website
    $dbstats = mysql_query("SELECT * FROM stats WHERE userid='$userid' ORDER BY time DESC", $dbcon);
    $total_stats = mysql_num_rows($dbstats);


    $n = 1 ;
    $it = $start_num;

    //Kalau punya statistik, mempersiapkan tampilannya
    if ($total_stats > 0) {
        while ($row = mysql_fetch_array($dbstats)) {
            if ($n > $start_num and $n <= $end_num) {
                $it++;
                list($thn, $bln, $tgl) = explode("-", $row[time]);
                list($tgl, $wktu) = explode(" ", $tgl);
                $website_stats .= "<tr>";
                $website_stats .= "<td width=\"3%\" bgcolor=\"#FFFFFF\">$it</td>";
                $website_stats .= "<td width=\"20%\" bgcolor=\"#FFFFFF\">$tgl/$bln/$thn - $wktu</td>";
                $website_stats .= "<td width=\"20%\" bgcolor=\"#FFFFFF\">$row[ip]</td>";
                $website_stats .= "<td width=\"20%\" bgcolor=\"#FFFFFF\">$row[ref]</td>";
                $website_stats .= "</tr>";
            } else {
                echo("") ;
            }
            $n = $n + 1 ;
        }

        $number_pages = 1 + $total_stats / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            $pagenum .= "[<a href=\"stats.php?page=$c\">Hal $c</a>]";
            $c = $c + 1 ;
        }
    }
    // Ternyata gak punya stastik :-(
    else
    {
         $website_stats = "Belum ada statistik";
    }

    //mempersiapkan template untuk ditampilkan
    $show_array = array(
    'website_stats' => $website_stats,
        'total_stats' => $total_stats,
        'page' => $pagenum );
    // Tampilkan halaman (template file : member_stats.html)
    display_member_page("member_stats.html", $show_array);

?>
