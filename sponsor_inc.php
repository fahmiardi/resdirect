<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : sponsor_inc.php
    # Defenisi :
    # Untuk melacak sponsor dan menyimpan statistiknya

    // Cek cookie :
    $direct_sponsor_cookie = $HTTP_COOKIE_VARS['dsponsor'];
    $random_sponsor_cookie = $HTTP_COOKIE_VARS['rsponsor'];

    // Masa berlaku cookie :
    $cookie_expired = time() + $conf['expires'] * 60 * 60 * 24 ;

    isset($_GET['id']) ? $id = $_GET['id'] : $id='';

    // Cek query sponsor (?id=userid)
    if (isset($id) && $id !="") {
        if (read_aktif($id)) {
            //if ($id != $direct_sponsor_cookie)
            setcookie ("dsponsor", $id, $cookie_expired);

            $direct_sponsor = $id;
            write_stats($id);
        }

    }
    else {
	    // Cek cookie direct sponsor :
	    if (isset($direct_sponsor_cookie)) {
		if (read_aktif($direct_sponsor_cookie)) {
			$direct_sponsor = $direct_sponsor_cookie;
		}
		else
		{
		    $direct_sponsor = make_sponsor();
		    setcookie ("dsponsor", $direct_sponsor, $cookie_expired);
		}
	    } else {
		$direct_sponsor = make_sponsor();
		setcookie ("dsponsor", $direct_sponsor, $cookie_expired);
	    }
    }

    // Cek Sistem apakah menggunakan random sponsor ?
    if ($conf['rsponsor'] == 1) {

        // Cek cookie random sponsor :
        if (isset($random_sponsor_cookie)) {
            if (read_aktif($random_sponsor_cookie)) {
		$random_sponsor = $random_sponsor_cookie;
		}
            else
            {
                $random_sponsor = make_sponsor();
                setcookie ("rsponsor", $random_sponsor, $cookie_expired);
            }
        } else {
            $random_sponsor = make_sponsor($direct_sponsor);
            setcookie ("rsponsor", $random_sponsor, $cookie_expired);
        }

        // Cek direct sponsor dan random sponsor tidak bisa sama
        if ($direct_sponsor == $random_sponsor) {
            $random_sponsor = make_sponsor($direct_sponsor);
            setcookie ("rsponsor", $random_sponsor, $cookie_expired);
        }
    }

    // Fungsi cari sponsor secara random
    function make_sponsor($except = '') {
        global $dbcon;
        srand((double)microtime() * 10000000);
        $qdb = "SELECT * FROM members WHERE `userlevel`='1' AND `userid` != '$except' ORDER BY RAND()";

        $qres = mysql_query($qdb, $dbcon) or display_html(mysql_error(), "error.html");
        if (mysql_num_rows($qres)) {

            $out = array();
            while ($row = mysql_fetch_array($qres)) {

                $i = 1;
                while ($i < $row['rotation']) {
                    $out[] = $row['userid'];
                    $i++;
                }
            }
            $rand_keys = array_rand($out, 1);
            $sponsor = $out[$rand_keys];
        }

        return $sponsor;
    }

?>
