<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : delete.php
    # Defenisi :
    # Untuk menampilkan member yang belum melakukan upgrade dari batas waktu yang
    # ditentukan (default : 14 hari)

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");

    // Kalau yang mau dihapus satu member saja :
    if (IsSet($del_userid)) {
        send_delete_mail($del_userid);
        mysql_query("DELETE FROM `members` WHERE `userid` = '$del_userid'");
        display_html("Member $del_userid sudah dihapus!", "msg.html");
    }

    // Jumlah maksimum member yang ditampilkan dalam 1 halaman (default 20)
    $max_show = 20 ;
    if (!IsSet($page)) {
        $page = 1;
    }

    $start_num = ($page-1) * $max_show ;
    $end_num = $start_num + $max_show ;

    $expires = 1641600;
    $dbres = mysql_query("SELECT * FROM members WHERE userlevel ='0' AND UNIX_TIMESTAMP(NOW()) - $expires  >  UNIX_TIMESTAMP(joindate) ORDER BY joindate DESC ");

    $n = 1 ;
    $no = $start_num;

    // Kalau member ada, lalu siapkan tampilan :
    if (mysql_num_rows($dbres) > 0) {
        $total_member = mysql_num_rows($dbres);
        while ($row = mysql_fetch_array($dbres)) {

            // Kalau mau hapus semua member:
            if (IsSet($del)) {
                send_delete_mail($row['userid']);
                mysql_query("DELETE FROM `members` WHERE `userid` = '$row[userid]'");
            }
            // Tampilkan semua member :
            else
            {
                if ($n > $start_num and $n <= $end_num) {
                    $no++;

                    $all_members .= "<tr>";
                    $all_members .= "<td width=\"20\" bgcolor=\"#FFFFFF\">$no</td>";
                    $all_members .= "<td width=\"256\" bgcolor=\"#FFFFFF\"><a href=\"account.php?userid=$userid\">$row[name] ( $userid)</a></td>";
                    $all_members .= "<td width=\"224\" bgcolor=\"#FFFFFF\"><a href=\"contact.php?userid=$userid\">$row[email]</a></td>";
                    $all_members .= "<td width=\"113\" bgcolor=\"#FFFFFF\">$row[joindate]";
                    $all_members .= "<td width=\"80\" bgcolor=\"#FFFFFF\"><a href=\"delete.php?del_userid=$row[userid]\"><b>Hapus</b></a></td>";
                    $all_members .= "</tr>";
                }

                else
                    {
                    echo("") ;
                }
            }
            $n = $n + 1 ;
        }
        // Menghitung jumlah halaman tampilan member
        $number_pages = 1 + $total_member / $max_show ;
        $c = 1 ;
        while ($c < $number_pages) {
            $page_num .= "[<a href=\"delete.php?page=$c\">Hal $c</a>]";
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
    // Tampilkan halaman (template file : admin_member_delete.html)
    display_admin_page("admin_member_delete.html", $show_array);


?>