<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : testimonials.php
    # Defenisi :
    # Untuk edit kesaksian member

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    //$userid = $_REQUEST["userid"];
    if (isset($userid)) {
        // Edit testimonial :
        if ($job == "edit") {
            $dbres = mysql_query("SELECT * FROM `testimonials` WHERE `userid` = '$userid'")
            or show_error(mysql_error());
            $row = mysql_fetch_array($dbres);
            $show_array = array(
            'name' => $row['name'],
                'email' => $row['email'],
                'url' => $row['url'],
                'content' => $row['content'],
                'show' => $row['active'],
                'userid' => $row['userid'],
                'date' => $row['date'] );
            // Tampilkan halaman (template file : admin_testimonials_change.html)
            display_admin_page("admin_testimonials_change.html", $show_array);
            exit;
        }
        // Hapus testimonial :
        elseif ($job == "del") {
            $dbres = mysql_query("DELETE FROM `testimonials` WHERE `userid` = '$userid'")
            or show_error(mysql_error());
            // Tampilkan halaman testimonials.php
            header("Location: testimonials.php");
        }
        // Simpan testimonial :
        elseif ($job == "save") {
            $qdb = "UPDATE `testimonials` SET
                `name` = '$test_name',
                `email` ='$test_email',
                `url` ='$test_url',
                `content` ='$test_content',
                `active` ='$test_show'
                WHERE
                `userid` = '$userid'";
            $dbres = mysql_query($qdb) or show_error(mysql_error());
            // Tampilkan halaman testimonials.php
            header("Location: testimonials.php");
        }

    }

    // Menampilkan daftar kesaksian member :
    $max_show = 20 ;
    if (!IsSet($page)) {
        $page = 1;
    }

    $start_num = ($page-1) * $max_show ;
    $end_num = $start_num + $max_show ;

    $dbres = mysql_query("SELECT * FROM `testimonials` WHERE 1 ORDER BY date DESC")
    or show_error(mysql_error());
     ;

    $total = mysql_num_rows($dbres);

    $n = 1 ;
    $no = $start_num;
    $content = "";
    $page_num = "";
    if ($total > 0) {
        while ($row = mysql_fetch_array($dbres)) {
            if ($n > $start_num and $n <= $end_num) {
                $no++;
                $content .= "<tr>";
                $content .= "<td width=\"22\" bgcolor=\"#FFFFFF\"><b>$no</b></td>";
                $content .= "<td width=\"96\" bgcolor=\"#FFFFFF\"><a href=\"account.php?userid=$row[userid]\"><b>$row[userid]</b></a></td>";
                $content .= "<td width=\"33\" bgcolor=\"#FFFFFF\">$row[active]</td>";
                $content .= "<td width=\"358\" bgcolor=\"#FFFFFF\">$row[content]</td>";
                $content .= "<td width=\"44\" bgcolor=\"#FFFFFF\"><b>[<a href=\"testimonials.php?job=edit&amp;userid=$row[userid]\">e</a>] [<a href=\"testimonials.php?job=del&amp;userid=$row[userid]\">x</a>]</b></td>";
                $content .= "</tr>";

            } else {
                echo("") ;
            }
            $n = $n + 1 ;
        }
        // Menghitung jumlah halaman tampilan kesaksian
        $number_pages = 1 + $total / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            $page_num .= "[<a href=\"testimonials.php?page=$c\">Hal $c</a>]";
            $c = $c + 1 ;
        }
    }
    // Tidak ada kesaksian :
    else
    {
        $content = "Belum ada Testimonials";
        $page_num = "";
    }
    $show_array = array(
    'page' => $page_num,
        'testimonials' => $content,
        'total' => $total,
        'userid' => $userid );

    // Tampilkan halaman (template file : admin_testimonials.html)
    display_admin_page("admin_testimonials.html", $show_array);


?>