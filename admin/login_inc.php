<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                   										            #
    #                                                                       #
    #***********************************************************************#
    # File : login_inc.php
    # Defenisi :
    # Cek login admin untuk memasuki admin area

    session_start();

    $ses_admin_string = $_SESSION["ses_admin_string"];
    $ses_admin_userid = $_SESSION["ses_admin_userid"];
    $lastip = $_SERVER["REMOTE_ADDR"];
    $expire_time = time() + 60 * 60 * 1;

    delete_old_login_session();

    if (isset($ses_admin_string) && isset($ses_admin_userid)) {
        cek_session($ses_admin_string);
    } 
    else {
        if (isset($_POST['actions']) && $_POST['actions']=="loginAdmin") {
	    $uname = htmlentities(trim($_POST['uname']));
	    $pass = htmlentities(trim($_POST['pass']));

            if ($uname && $pass) {
                login_user($uname, $pass);
	    }
            else {
		display_html("UserID atau Password tidak boleh kosong.", "admin_login.html");
		exit;
	    }
        } else {
            display_html("", "admin_login.html");
            exit;
        }
    }


    function login_user ($uname, $pass) {
        global $dbcon;

        $dbq = "SELECT userid, password FROM admin where userid='$uname'";
        $dbres = mysql_query($dbq, $dbcon);

        if (mysql_num_rows($dbres) == 1) {
            $dbrow = mysql_fetch_array($dbres);

            if (md5($pass) != $dbrow['password']) {
                session_defaults();
                display_html("UserId atau Password anda salah!", "admin_login.html");
                exit;
            } else {
                $_SESSION['ses_admin_userid'] = $dbrow['userid'];
                $_SESSION['ses_admin_string'] = MD5(TIME());
                GenerateSecret($_SESSION['ses_admin_userid'], $_SESSION['ses_admin_string']);
            }

        } else {
            session_defaults();
            display_html("UserId atau Password anda salah!", "admin_login.html");
        }
    }

    function cek_user ($uname, $pass) {
        global $dbcon;

        $dbq = "SELECT userid,password FROM members where userid='$uname' AND password='$pass'";
        $dbres = mysql_query($dbq, $dbcon);

        if (!mysql_num_rows($dbres)) {
            session_defaults();
            display_html("Silahkan login kembali", "admin_login.html");
            exit;
        }
    }

    function cek_session ($ses_admin_string) {
        global $dbcon;

        $dbres = mysql_query("SELECT * FROM login_session where string='$ses_admin_string'", $dbcon);

        if (mysql_num_rows($dbres) < 1) {
            session_defaults();
            display_html("Silahkan login kembali", "admin_login.html");
        }
    }

    function session_defaults() {
        global $ses_admin_userid, $dbcon;

        $dbres = mysql_query("DELETE FROM login_session WHERE userid = '$ses_admin_userid'", $dbcon);
        session_destroy();
    }

    function delete_old_login_session() {
	global $dbcon;

        // 2 hours
        $expiredtime = 1 * 60 * 60;
        $expired = time() - $expiredtime;

        $dbq = "DELETE FROM login_session WHERE unix_timestamp(lastlogin) <= '$expired'";
        $dbres = mysql_query($dbq, $dbcon);
    }

    function GenerateSecret ($uname, $string) {
        global $dbcon;

        $lastlogin = date("Y-m-d H:i:s");
        $dbq1 = "INSERT INTO login_session SET 
            userid = '$uname',
            string = '$string',
            lastlogin = '$lastlogin'";
        $res = mysql_query($dbq1, $dbcon);
    }

?>
