<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : bonus.php
    # Defenisi :
    # Untuk menampilkan halaman bonus, Sebelum halaman bonus ditampilkan,
    # member harus mengisikan dulu kesaksiannya tentang program anda.

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");
    require("level_inc.php");

    $error = "";

    // Proses kesaksian member dan menyimpan ke database :
    if (isset($_POST['actions']) && $_POST['actions']=="memtesti") {
	$userid = $_POST['userid'];
	$email = $_POST['email'];
	$memname = $_POST['memname'];
	$content = $_POST['content'];
	$url = $_POST['url'];

        if (!strlen($memname)) {
             $error .= "<li>Mohon diisi Nama anda.</li>";
        }
        if (!strlen($email)) {
             $error .= "<li>Mohon diisi Email anda.</li>";
        }
        if (!strlen($content)) {
             $error .= "<li>Mohon diisi Komentar anda.</li>";
        }

        if ($error) {
             display_html($error, "error.html");
        }

        $dbq = "INSERT INTO testimonials SET
            userid = '$userid',
            name = '$memname',
            email='$email',
            url  = '$url',
            content  = '$content',
            date = NOW()";
        $res = mysql_query($dbq, $dbcon) or show_error(mysql_error());
    }

    // Cek kesaksian member :
    $row = mysql_fetch_array(mysql_query("SELECT userid, active FROM testimonials WHERE userid = '$userid'", $dbcon));
    if ($row) {
        // Member sudah mengisikan kesaksiannya, lalu menampilkan halaman bonus :
        // Tampilkan halaman (template file : member_bonus.html)
	if($row['active']==0) {
		display_html("Testimoni anda belum diapprove oleh admin. Mohon tunggu..", "msg.html");
	}
	else {
        	display_member_page("member_bonus.html");
	}
    } else {
        // Member belum mengisikan kesaksiannya, tampilkan dulu halaman testimonials:
        // Tampilkan halaman (template file : member_testimonial.html)
        display_member_page("member_testimonial.html");
    }
?>
