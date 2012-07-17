<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                                                                       #
    #                                                                       #
    #***********************************************************************#
    # File : login_inc.php
    # Defenisi :
    # Cek login member untuk memasuki member area.
    session_start();

    $ses_string = $_SESSION["ses_string"];
    $userid = $_SESSION["userid"];
    $userlevel = $_SESSION['userlevel'];
    $lastip = $_SERVER["REMOTE_ADDR"];
    $expire_time = time() + 60 * 60 * 1;

    delete_old_login_session();

    if (isset($ses_string) && isset($userid)) {
        cek_session($ses_string);
    } 
    else {
        if (isset($_POST['actions']) && $_POST['actions']=="login") {
	    $uname = htmlentities(trim($_POST['uname']));
	    $pass = htmlentities(trim($_POST['pass']));

            if ($uname && $pass) {
                login_user($uname, $pass );
	    }
            else {
		display_html("", "member_login.html");
		exit;
	    }
        } else {
            display_html("", "member_login.html");
            exit;
        }
    }


    function login_user ($uname, $pass) {
        global $dbcon;
        $dbq = "SELECT userid,password,userlevel FROM members where userid='$uname'";
        $dbres = mysql_query($dbq, $dbcon);

        if (mysql_num_rows($dbres) == 1) {
            $dbrow = mysql_fetch_array($dbres);

            if (md5($pass) != $dbrow['password']) {
                session_defaults();
                display_html("UserId atau Password anda salah!", "member_login.html");
                exit;
            } else {
                $_SESSION['ses_string'] = MD5(TIME());
                $_SESSION['userid'] = $dbrow['userid'];
                $_SESSION['userlevel'] = $dbrow['userlevel'];
                GenerateSecret($dbrow['userid'], $_SESSION['ses_string']);
            }

        } else {
            session_defaults();
            display_html("UserId atau Password anda salah!", "member_login.html");
            exit;
        }
    }

    function cek_user ($uname, $pass) {
        global $dbcon;
        $dbq = "SELECT userid,password FROM members where userid='$uname' AND password='$pass'";
        $dbres = mysql_query($dbq, $dbcon);

        if (!mysql_num_rows($dbres)) {
            session_defaults();
            display_html("Silahkan login kembali", "member_login.html");
            exit;
        }
    }

    function cek_session ($ses_string) {
        global $dbcon;
        $dbres = mysql_query("SELECT * FROM login_session where string='$ses_string'", $dbcon);

        if (mysql_num_rows($dbres) < 1) {
            session_defaults();
            display_html("Silahkan login kembali", "member_login.html");
	    exit;
        }
    }

    function session_defaults() {
        global $userid, $dbcon;
        $dbres = mysql_query("DELETE FROM login_session WHERE userid = '$userid'", $dbcon);
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
