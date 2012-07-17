<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : confirm.php
    # Defenisi :
    # Untuk proses konfirmasi transfer/pembayaran member. Email akan dikirimkan
    # kepada semua sponsornya dan kepada admin.

    require("../config_inc.php");
    require("../func_inc.php");
    require("login_inc.php");


    $error = "";

    // Data member  :
    $row = mysql_fetch_array(mysql_query("SELECT * FROM members WHERE userid = '$userid'", $dbcon));
    $s_email = $row['email'];
    $d_sponsor = $row['direct_sponsor'];
    $r_sponsor = $row['random_sponsor'];

    if(isset($_POST['actions']) && $_POST['actions'] == "confirm") {
	    $direct_sponsor_mail = $_POST['direct_sponsor_mail'];
	    $random_sponsor_mail = $_POST['random_sponsor_mail'];
	    $admin_mail = $_POST['admin_mail'];
	    $cek = $_POST['cek'];

	    // Kirim email kepada direct sponsor :
	    if ($direct_sponsor_mail != "") {
		$direct_row = mysql_fetch_array(mysql_query("SELECT name,email FROM members WHERE userid ='$d_sponsor'", $dbcon));

		$from = "$s_email";
		$to = $direct_row['email'];
		$subject = "[$userid] , Konfirmasi Transfer Direct Referral";

		if (!sendmail($from, $to, $subject, $direct_sponsor_mail, '1')) {
		    $error .= "<li>Gagal mengirimkan email kepada Direct Sponsor ($to), Silahkan menghubungi secara manual</li>";
		}
	    }
	    // Kirim email kepada random sponsor :
	    if ($random_sponsor_mail != "") {
		$random_row = mysql_fetch_array(mysql_query("SELECT name,email FROM members WHERE userid ='$r_sponsor'", $dbcon));
		$from = $s_email;
		$to = $random_row['email'];
		$subject = "[$userid] , Konfirmasi Transfer Random Referral";

		if (!sendmail($from, $to, $subject, $random_sponsor_mail, '1')) {
		    $error .= "<li>Gagal mengirimkan email kepada Random Sponsor ($to), Silahkan menghubungi secara manual</li>";
		}
	    }

	    // Kirim email kepada admin :
	    if ($admin_mail != "") {
		$from = $s_email;
		$to = $conf['admin_email'];
		$subject = "[$userid] , Konfirmasi Transfer";

		if (!sendmail($from, $to, $subject, $admin_mail, '1')) {
		    $error .= "<li>Gagal mengirimkan email kepada Admin ($to), Silahkan menghubungi secara manual</li>";
		}
	    }
            
	    ($cek != "ON") ? $error .= "<li>Ceklis tanda persetujuan.</li>" : '';

	    // Kalau ada error :
	    if ($error != "") {
		display_html($error, "msg.html");
	    }

	    // Konfirmasi sukses :
	    else
	    {
		$msg = "<b>Konfirmasi Sukses...</b><br>";
		$msg .= "Email telah dikirimkan kepada Sponsor anda dan Admin<br>";
		$msg .= "Silahkan menunggu keanggotaan anda untuk diaktivasi oleh para Sponsor anda";
		display_html($msg, "msg.html");
	    }
	    exit;
      }

?>
