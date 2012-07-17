<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                                                                       #
    #                                                                       #
    #***********************************************************************#
    # File : func_inc.php
    # Defenisi :
    # Kumpulan dari semua fungsi-fungsi yang dibutuhkan oleh setiap script


    require "class.fasttemplate.php";

    error_reporting (E_ALL & ~E_NOTICE);

    // Persiapkan template
    $tpl = new FastTemplate($conf['tpldir']);
    $tpl->no_strict();

    // Fungsi template menampilkan halaman2 utama
    function display_home_page($page, $show_array = array()) {
        global $tpl, $dbcon, $conf, $direct_sponsor , $random_sponsor , $LIC_USERID;
        $direct_row = mysql_fetch_array(
        mysql_query("SELECT name,userid,email,bank,phone FROM members WHERE userid ='$direct_sponsor'", $dbcon))
        or display_html("Tidak ada Direct Sponsor!", "error.html");

        $qdb = mysql_query("SELECT userlevel FROM members", $dbcon) or display_html("Tidak ada Member!", "error.html");
        $paid_member = 0 ;
        $total_member = mysql_num_rows($qdb);
        while ($row = mysql_fetch_array($qdb)) {
            if ($row['userlevel'] == 1) $paid_member++;
        }
        $free_member = $total_member - $paid_member ;
        $tpl->define(array(
        "index" => "page_index.html",
            "tpl" => $page
        ));

        $tpl->assign(array(

        // Site info
        'program_name' => $conf['program_name'],
            'program_url' => $conf['program_url'],
            'member_url' => $conf['member_url'],
            'launch_date' => $conf['launch_date'],
	    'year_now' => $conf['year_now'],
            'total_member' => $total_member,
            'paid_member' => $paid_member,
            'free_member' => $free_member,
            'script_userid' => $LIC_USERID,

        // direct sponsor info
        'direct_sponsor_userid' => $direct_row['userid'],
            'direct_sponsor_name' => $direct_row['name'],
            'direct_sponsor_email' => $direct_row['email'],
            'direct_sponsor_phone' => $direct_row['phone'],
            'direct_sponsor_bank' => $direct_row['bank'],
            'direct_sponsor_amount' => $conf['dsponsor_amount'],

        // admin info
        'admin_name' => $conf['admin_name'],
            'admin_email' => $conf['admin_email'],
            'admin_phone' => $conf['admin_phone'],
            'admin_address' => $conf['admin_address'],
            'admin_bank' => $conf['admin_bank'],
            'admin_amount' => $conf['admin_amount'],

        // total biaya
        'total_amount' => $conf['admin_amount']+$conf['dsponsor_amount']+$conf['rsponsor_amount']
        ));

        // Kalau menggunakan random sponsor :
        if ($conf['rsponsor'] == 1) {
            $random_row = mysql_fetch_array(
            mysql_query("SELECT name,userid,email,bank,phone FROM members WHERE userid ='$random_sponsor'", $dbcon))
            or display_html("Tidak ada Random Sponsor!", "error.html");

            $tpl->assign(array(
            // random sponsor info
            'random_sponsor_userid' => $random_row['userid'],
                'random_sponsor_name' => $random_row['name'],
                'random_sponsor_email' => $random_row['email'],
                'random_sponsor_phone' => $random_row['phone'],
                'random_sponsor_bank' => $random_row['bank'],
                'random_sponsor_amount' => $conf['rsponsor_amount'] ));
        }
        $tpl->assign($show_array);
        $tpl->parse("BODY", "tpl");
        $tpl->parse("TPL", "index");
        $tpl->fastprint("TPL");
    }

    $now = gmdate("'d-m-Y H:i:s'");

    // Fungsi membuat 3 angka unik
    function unik_number() {
        $chars = '0123456789';
        mt_srand((double)microtime() * 1000000 * getmypid()); // seed the random number generater (must be done)
        $number = '';
        while (strlen($number) < 3)
        $number .= substr($chars, (mt_rand()%strlen($chars)), 1);
        return $number;

    }

    $ref = getenv("HTTP_REFERER");
    $addr = $HTTP_SERVER_VARS['REMOTE_ADDR'];

    // Fungsi mencatat statistik
    function write_stats ($id) {
        global $dbcon, $ref, $addr;

        $dbq = "INSERT INTO stats
            (userid,time, ip, ref)
            VALUES
            ('$id', NOW(),'$addr', '$ref')
            ";
        mysql_query($dbq, $dbcon);
    }

    // Fungsi cek status member, aktif atau tidak
    function read_aktif($uid) {
        global $dbcon;
        $dbq = "SELECT * FROM members WHERE userid ='$uid' AND userlevel ='1' LIMIT 1";
        $result = mysql_query($dbq, $dbcon) ;
        if (mysql_num_rows($result) > 0) {
            return true;
        }
        else return false;
    }

    // Fungsi match password
    function match_password($userid, $pwd) {
        global $dbcon;
        $dbq = "SELECT * FROM members WHERE userid ='$userid' AND password=md5('$pwd')";
        $result = mysql_query($dbq, $dbcon) ;
        if (mysql_num_rows($result) == 1) {
            return true;
        }
        else return false;
    }

    // Fungsi cek member userid, sudah digunakan atau tidak
    function read_member ($uid) {
        global $dbcon;
        $result = mysql_fetch_array(
        mysql_query("SELECT userid FROM members WHERE userid = '$uid'", $dbcon));
        return $result;
    }

    // Fungsi kirim email saat member diaktifkan
    function send_active_mail($userid) {
        global $conf, $dbcon;
        $dbq = "SELECT * FROM members where userid='$userid'";
        $dbres = mysql_query($dbq, $dbcon) or display_html(mysql_error(), "error.html");
        $dbrow = mysql_fetch_array($dbres);

        $admin_email = $conf['admin_email'];
        $member_email = $dbrow['email'];

        $letters_row = mysql_fetch_array(
        mysql_query("SELECT active_msg,active_title FROM letters", $dbcon))
        or display_html(mysql_error(), "error.html");
        $title = $letters_row['active_title'];
        $msg = $letters_row['active_msg'];
        $msg = str_replace("{member_name}", $dbrow['name'], $msg);
        $msg = str_replace("{member_email}", $dbrow['email'], $msg);
        $msg = str_replace("{member_phone}", $dbrow['phone'], $msg);
        $msg = str_replace("{member_userid}", $dbrow['userid'], $msg);
        $msg = str_replace("{member_password}", $dbrow['password'], $msg);
        $msg = str_replace("{admin_name}", $conf['admin_name'], $msg);
        $msg = str_replace("{admin_phone}", $conf['admin_phone'], $msg);
        $msg = str_replace("{admin_email}", $conf['admin_email'], $msg);
        $msg = str_replace("{admin_bank}", $conf['admin_bank'], $msg);
        $msg = str_replace("{admin_amount}", $admin_amount, $msg);

        $msg = str_replace("{member_url}", $conf['member_url'], $msg);
        $msg = str_replace("{program_name}", $conf['program_name'], $msg);
        $msg = str_replace("{program_url}", $conf['program_url'], $msg);

        sendmail($admin_email, $member_email, $title, $msg, '1');
    }

    // Fungsi kirim email saat member dihapus
    function send_delete_mail($userid) {
        global $conf, $dbcon;
        $dbq = "SELECT * FROM members where userid='$userid'";
        $dbres = mysql_query($dbq, $dbcon) or display_html(mysql_error(), "error.html");
        $dbrow = mysql_fetch_array($dbres);

        $admin_email = $conf['admin_email'];
        $member_email = $dbrow['email'];

        $letters_row = mysql_fetch_array(
        mysql_query("SELECT del_msg,del_title FROM letters", $dbcon))
        or display_html(mysql_error(), "error.html");
        $title = $letters_row['del_title'];
        $msg = $letters_row['del_msg'];
        $msg = str_replace("{member_name}", $dbrow['name'], $msg);
        $msg = str_replace("{member_email}", $dbrow['email'], $msg);
        $msg = str_replace("{member_phone}", $dbrow['phone'], $msg);
        $msg = str_replace("{member_userid}", $dbrow['userid'], $msg);
        $msg = str_replace("{member_password}", $dbrow['password'], $msg);
        $msg = str_replace("{admin_name}", $conf['admin_name'], $msg);
        $msg = str_replace("{admin_phone}", $conf['admin_phone'], $msg);
        $msg = str_replace("{admin_email}", $conf['admin_email'], $msg);
        $msg = str_replace("{admin_bank}", $conf['admin_bank'], $msg);
        $msg = str_replace("{admin_amount}", $admin_amount, $msg);

        $msg = str_replace("{member_url}", $conf['member_url'], $msg);
        $msg = str_replace("{program_name}", $conf['program_name'], $msg);
        $msg = str_replace("{program_url}", $conf['program_url'], $msg);

        sendmail($admin_email, $member_email, $title, $msg, '1');
    }

    // Fungsi kirim email
    function sendmail($mailfrom, $mailto, $mailsubject, $msg, $priority) {
        $subject = $mailsubject;
        $headers = "From: $mailfrom\r\n";
        $headers .= "Reply-To: $mailfrom\r\n";
        $headers .= "X-Priority: $priority\r\n";
        $headers .= "X-Mailer: Cash-Planet\r\n";

        $send = mail($mailto, $mailsubject, $msg, $headers , "-f $mailfrom");

        if (!$send) return false;

        return true;
    }

    // Fungsi template menampilkan member area
    function display_member_page($page = '', $show_array = array(), $admin_page = '') {
        global $tpl, $conf, $dbcon, $LIC_USERID;
	$userid = $_SESSION['userid'];
        if ($userid != "") {
            $member_row = mysql_fetch_array(
            mysql_query("SELECT * FROM members WHERE userid = '$userid'", $dbcon))
            or display_html(mysql_error(), "error.html");

            $direct_sponsor = $member_row['direct_sponsor'];
            $random_sponsor = $member_row['random_sponsor'];

            $direct_row = mysql_fetch_array(
            mysql_query("SELECT name,userid,email,bank,phone FROM members WHERE userid ='$direct_sponsor'", $dbcon));

            $status = ($member_row['userlevel'] == 1?"Paid Member":"Free Member");

            $qdb = mysql_query("SELECT userlevel FROM members", $dbcon) or display_html(mysql_error(), "error.html");
            $paid_member = 0 ;
            $total_member = mysql_num_rows($qdb);
            while ($row = mysql_fetch_array($qdb)) {
                if ($row['userlevel'] == 1) $paid_member++;
            }
            $free_member = $total_member - $paid_member ;

            if ($admin_page) {
                $tpl->define(array(
                "index" => "admin_index.html",
                    "tpl" => $page
                ));
            } else {
                $tpl->define(array(
                "index" => "member_index.html",
                    "tpl" => $page
                ));
            }

            // Kalau menggunakan random sponsor :
            if ($conf['rsponsor'] == 1) {

                $random_row = mysql_fetch_array(
                mysql_query("SELECT name,userid,email,bank,phone FROM members WHERE userid ='$random_sponsor'", $dbcon));

                $tpl->assign(array(
                // random sponsor info
                'random_sponsor_userid' => $random_row['userid'],
                    'random_sponsor_name' => $random_row['name'],
                    'random_sponsor_email' => $random_row['email'],
                    'random_sponsor_phone' => $random_row['phone'],
                    'random_sponsor_bank' => $random_row['bank'],
                    'random_sponsor_amount' => $conf['rsponsor_amount'] + $member_row['payment'] ));
            }

            $tpl->assign(array(
            // Site info
            'program_name' => $conf['program_name'],
                'program_url' => $conf['program_url'],
                'member_url' => $conf['member_url'],
                'launch_date' => $conf['launch_date'],
		'year_now' => $conf['year_now'],
                'total_member' => $total_member,
                'paid_member' => $paid_member,
                'free_member' => $free_member,
                'total_amount' => $conf['dsponsor_amount']+$conf['rsponsor_amount']+$conf['admin_amount'],
                'script_userid' => $LIC_USERID,

            // member personal info
            'userid' => $member_row['userid'],
                'password' => $member_row['password'],
                'name' => $member_row['name'],
                'address' => $member_row['address'],
                'email' => $member_row['email'],
                'phone' => $member_row['phone'],
                'bank' => $member_row['bank'],
                'rotation' => $member_row['rotation'],
                'joindate' => $member_row['joindate'],
                'userlevel' => $member_row['userlevel'],
                'lastip' => $member_row['lastip'],
                'payment' => $member_row['payment'],
                'status' => $status,

            // member payment info
            'pay_direct' => $member_row['pay_direct'],
                'pay_random' => $member_row['pay_random'],
                'pay_admin' => $member_row['pay_admin'],

            // direct sponsor info
            'direct_sponsor_userid' => $direct_row['userid'],
                'direct_sponsor_name' => $direct_row['name'],
                'direct_sponsor_email' => $direct_row['email'],
                'direct_sponsor_phone' => $direct_row['phone'],
                'direct_sponsor_bank' => $direct_row['bank'],
                'direct_sponsor_amount' => $conf['dsponsor_amount'] + $member_row['payment'],

            // admin info
            'admin_name' => $conf['admin_name'],
                'admin_email' => $conf['admin_email'],
                'admin_phone' => $conf['admin_phone'],
                'admin_bank' => $conf['admin_bank'],
                'admin_amount' => $conf['admin_amount'] + $member_row['payment']
            ));


            $tpl->assign($show_array);
            $tpl->parse("BODY", "tpl");
            $tpl->parse("TPL", "index");
            $tpl->fastprint("TPL");
        }
    }

    // Fungsi template menampilkan admin area
    function display_admin_page($page, $show_array = array()) {
        global $tpl, $conf, $dbcon, $LIC_USERID;

        $admin_row = mysql_fetch_array(
        mysql_query("SELECT * FROM admin", $dbcon))
        or display_html(mysql_error(), "error.html");

        $qdb = mysql_query("SELECT userlevel FROM members", $dbcon) or display_html(mysql_error(), "error.html");
        $paid_member = 0 ;
        $total_member = mysql_num_rows($qdb);
        while ($row = mysql_fetch_array($qdb)) {
            if ($row['userlevel'] == 1) $paid_member++;
        }
        $free_member = $total_member - $paid_member ;


        $tpl->define(array(
        "index" => "admin_index.html",
            "tpl" => $page
        ));
        $tpl->assign(array(

        // Site info
        'program_name' => $conf['program_name'],
            'program_url' => $conf['program_url'],
            'member_url' => $conf['member_url'],
            'launch_date' => $conf['launch_date'],
	    'year_now' => $conf['year_now'],
            'total_member' => $total_member,
            'paid_member' => $paid_member,
            'free_member' => $free_member,
            'script_userid' => $LIC_USERID,

        // admin info
        'admin_userid' => $admin_row['userid'],
            'admin_password' => $admin_row['password'],
            'admin_lastip' => $admin_row['lastip'],
            'admin_name' => $conf['admin_name'],
            'admin_address' => $conf['admin_address'],
            'admin_email' => $conf['admin_email'],
            'admin_phone' => $conf['admin_phone'],
            'admin_bank' => $conf['admin_bank']
        ));
        $tpl->assign($show_array);
        $tpl->parse("BODY", "tpl");
        $tpl->parse("TPL", "index");
        $tpl->fastprint("TPL");
        exit;
    }

    // Fungsi validasi email
    function check_email($email) {
        if (!preg_match('/^[0-9a-zA-Z\.\-\_]+\@[0-9a-zA-Z\.\-]+$/', $email))
            return false;
        if (preg_match('/^[^0-9a-zA-Z]|[^0-9a-zA-Z]$/', $email))
            return false;
        if (!preg_match('/([0-9a-zA-Z_]{1})\@./', $email) )
            return false;
        if (!preg_match('/.\@([0-9a-zA-Z_]{1})/', $email) )
            return false;
        if (preg_match('/.\.\-.|.\-\..|.\.\..|.\-\-./', $email) )
            return false;
        if (preg_match('/.\.\_.|.\-\_.|.\_\..|.\_\-.|.\_\_./', $email) )
            return false;
        if (!preg_match('/\.([a-zA-Z]{2,5})$/', $email) )
            return false;

        return true;
    }

    // Fungsi untuk menampilkan pesan, error dll...
    function display_html($msg, $page, $show_array = array()) {
        global $tpl, $conf, $dbcon, $LIC_USERID;

        $qdb = mysql_query("SELECT userlevel FROM members", $dbcon);
        $paid_member = 0 ;
        $total_member = mysql_num_rows($qdb);
        while ($row = mysql_fetch_array($qdb)) {
            if ($row['userlevel'] == 1) $paid_member++;
        }
        $free_member = $total_member - $paid_member ;
        $content = '';
        $tpl->define(array(
        "tpl" => $page,
            "index" => "msg_index.html"
        ));

        $tpl->assign(array(
        'msg' => $msg,
            // Site info
        'program_name' => $conf['program_name'],
            'program_url' => $conf['program_url'],
            'member_url' => $conf['member_url'],
            'launch_date' => $conf['launch_date'],
	    'year_now' => $conf['year_now'],
            'total_member' => $total_member,
            'paid_member' => $paid_member,
            'script_userid' => $LIC_USERID,
            'free_member' => $free_member ));

        $tpl->assign($show_array);
        $tpl->parse("BODY", "tpl");
        $tpl->parse("TPL", "index");
        $tpl->fastprint("TPL");
        exit;

    }

?>
