<?php
    #***********************************************************************#
    #                                                                       #
    # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
    #                                                                                                               #
    #                                                                       #
    #***********************************************************************#
    # File : install.php
    # Defenisi :
    # Untuk instalasi script Budicomputer. Hati-hati! Jangan diedit!

    @extract($HTTP_GET_VARS);
    @extract($HTTP_POST_VARS);
    @extract($HTTP_SERVER_VARS);
    error_reporting (E_ALL & ~E_NOTICE);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="en">
<head>
	<meta charset="utf-8">
        <title>Instalasi Budicomputer Script</title>
</head>
<body>
<?php
    $sql_admin = "CREATE TABLE `admin` (
  `id` int(10) NOT NULL default '0',
  `userid` varchar(12) collate latin1_general_ci NOT NULL default '',
  `password` varchar(255) collate latin1_general_ci NOT NULL default '',
  `last_login` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `lastip` varchar(60) collate latin1_general_ci NOT NULL default '',
  `name` varchar(60) collate latin1_general_ci NOT NULL default '',
  `address` text collate latin1_general_ci NOT NULL,
  `email` varchar(60) collate latin1_general_ci NOT NULL default '',
  `phone` varchar(60) collate latin1_general_ci NOT NULL default '',
  `bank` text collate latin1_general_ci NOT NULL,
  `with_rsponsor` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `maxrefshow` int(10) NOT NULL default '0',
  `maxstatsshow` int(10) NOT NULL default '0',
  `cookiexpires` int(30) NOT NULL default '0',
  `tpldir` varchar(255) collate latin1_general_ci NOT NULL default '',
  `program_name` varchar(60) collate latin1_general_ci NOT NULL default '',
  `program_url` varchar(60) collate latin1_general_ci NOT NULL default '',
  `member_url` varchar(60) collate latin1_general_ci NOT NULL default '',
  `launch_date` varchar(60) collate latin1_general_ci NOT NULL default '',
  `dsponsor_amount` varchar(60) collate latin1_general_ci NOT NULL default '',
  `rsponsor_amount` varchar(60) collate latin1_general_ci NOT NULL default '',
  `admin_amount` varchar(60) collate latin1_general_ci NOT NULL default '',
  `LIC_USERID` varchar(255) collate latin1_general_ci NOT NULL default ''
) ENGINE=MyISAM;";

    $sql_letters = "CREATE TABLE `letters` (
  `user_title` text collate latin1_general_ci NOT NULL,
  `dsponsor_title` text collate latin1_general_ci NOT NULL,
  `rsponsor_title` text collate latin1_general_ci NOT NULL,
  `admin_title` text collate latin1_general_ci NOT NULL,
  `del_title` text collate latin1_general_ci NOT NULL,
  `active_title` text collate latin1_general_ci NOT NULL,
  `pass_msg` text collate latin1_general_ci NOT NULL,
  `newuser_msg` text collate latin1_general_ci NOT NULL,
  `dsponsor_msg` text collate latin1_general_ci NOT NULL,
  `rsponsor_msg` text collate latin1_general_ci NOT NULL,
  `admin_msg` text collate latin1_general_ci NOT NULL,
  `del_msg` text collate latin1_general_ci NOT NULL,
  `active_msg` text collate latin1_general_ci NOT NULL,
  FULLTEXT KEY `active_msg` (`active_msg`),
  FULLTEXT KEY `del_title` (`del_title`,`active_title`)
) ENGINE=MyISAM;";

    $sql_login_session = "CREATE TABLE `login_session` (
  `userid` char(12) collate latin1_general_ci default NULL,
  `string` char(32) collate latin1_general_ci default NULL,
  `lastlogin` datetime default NULL
) ENGINE=MyISAM;";

    $sql_members = "CREATE TABLE `members` (
  `uid` int(11) NOT NULL auto_increment,
  `userid` varchar(12) collate latin1_general_ci NOT NULL default '',
  `password` varchar(255) collate latin1_general_ci NOT NULL default '',
  `bank` varchar(200) collate latin1_general_ci NOT NULL default 'No_Rek A/N Nama',
  `email` varchar(60) collate latin1_general_ci NOT NULL default '',
  `name` varchar(50) collate latin1_general_ci NOT NULL default '',
  `address` varchar(100) collate latin1_general_ci NOT NULL default '',
  `phone` varchar(200) collate latin1_general_ci NOT NULL default 'N/A',
  `direct_sponsor` varchar(12) collate latin1_general_ci NOT NULL default '',
  `random_sponsor` varchar(12) collate latin1_general_ci NOT NULL default '',
  `payment` varchar(100) collate latin1_general_ci NOT NULL default '',
  `pay_direct` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `pay_random` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `pay_admin` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `joindate` datetime NOT NULL default '2000-00-20 00:00:00',
  `userlevel` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `lastip` varchar(15) collate latin1_general_ci NOT NULL default '',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `rotation` int(3) NOT NULL default '10',
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `username` (`userid`),
  FULLTEXT KEY `email` (`email`),
  FULLTEXT KEY `address` (`address`)
) ENGINE=MyISAM;";

    $sql_stats = "CREATE TABLE `stats` (
  `userid` varchar(32) collate latin1_general_ci default NULL,
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(32) collate latin1_general_ci default NULL,
  `ref` varchar(200) collate latin1_general_ci default NULL
) ENGINE=MyISAM;";

    $sql_testimonials = "CREATE TABLE `testimonials` (
  `userid` varchar(32) collate latin1_general_ci NOT NULL default '',
  `name` varchar(32) collate latin1_general_ci NOT NULL default '',
  `email` varchar(32) collate latin1_general_ci NOT NULL default '',
  `url` text collate latin1_general_ci NOT NULL,
  `content` text collate latin1_general_ci NOT NULL,
  `active` enum('0','1') collate latin1_general_ci NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `uid` (`userid`),
  KEY `uid2` (`userid`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM;";

    $sql_admin_data1 = "UPDATE admin SET
        userid='$_POST[admin_user]',
        password='$_POST[admin_pass]',
        tpldir='$_POST[tpldir]',
        LIC_USERID='$_POST[LIC_USERID]'";

    $sql_letters_data = "INSERT INTO `letters` VALUES ('Konfirmasi pendaftaran anda di !', 'Selamat! Anda mendapatkan seorang calon Direct Referral', 'Selamat! Anda mendapatkan seorang calon Random Referral', 'Selamat! Anda mendapatkan seorang calon Member', 'Keanggotaan anda dihapus !', 'Selamat! Keanggotaan anda sudah diaktifkan!', 'Halo {member_name},\r\n\r\nEmail ini dikirimkan karena anda mengisikan Form Member Password.\r\nData keanggotaan anda di {program_name} adalah :\r\n\r\nUserID   : {member_userid}\r\nPassword : {member_password}\r\n\r\nUntuk login ke member area, silahkan kunjungi :\r\nhttp://{member_url}\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\nNo. Telp/HP : {admin_phone}', 'Halo {member_name} ,\r\n\r\nTerima kasih karena telah melakukan pendaftaran di {program_name}.\r\nBerikut ini data yang anda isikan di Formulir Pendaftaran :\r\nNama        : {member_name}\r\nEmail       : {member_email}\r\nNo.Telp/HP  : {member_phone}\r\nUserID      : {member_userid}\r\nPassword    : {member_password}\r\n\r\nSelanjutnya untuk mengaktifkan keanggotaan anda, Silahkan melakukan\r\ntransfer biaya Aktivasi kepada :\r\n\r\n1. Direct Sponsor anda :\r\n-.Nama         : {dsponsor_name}\r\n-.No. Telp/HP  : {dsponsor_phone}\r\n-.Email        : {dsponsor_email}\r\n-.Bank/No. Rek : {dsponsor_bank}\r\n-.Transfer     : Rp.{dsponsor_amount}\r\n\r\n2. Random Sponsor anda :\r\n-.Nama         : {rsponsor_name}\r\n-.No. Telp/HP  : {rsponsor_phone}\r\n-.Email :Email : {rsponsor_email}\r\n-.Bank/No. Rek : {rsponsor_bank}\r\n-.Transfer     : Rp.{rsponsor_amount}\r\n\r\n3. Pengelola/Admin :\r\n-.Nama         : {admin_name}\r\n-.No. Telp/HP  : {admin_phone}\r\n-.Email :Email : {admin_email}\r\n-.Bank/No. Rek : {admin_bank}\r\n-.Transfer     : Rp.{admin_amount}\r\n\r\nSetelah melakukan transfer kepada 3 orang di atas, Segera\r\nlakukan konfirmasi di member area. Kami telah menyediakan\r\nFormulir Konfirmasi Pembayaran di member area :\r\nhttp://{member_url}\r\n\r\nSetelah melakukan konfirmasi Silahkan segera isikan nomor\r\nrekening Bank anda di Member Area. Karena pembayaran oleh\r\nreferral anda akan dibayarkan ke nomor rekening ini.\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\nNo. Telp/HP : {admin_phone}', 'Halo {dsponsor_name} ,\r\n\r\nSelamat anda telah mendapatkan seorang calon direct referral.\r\nBerikut in\r\n        i datanya :\r\nNama        : {member_name}\r\nEmail       : {member_email}\r\nNo.Telp/HP  : {member_phone}\r\nUserID      : {member_userid}\r\nTransfer    : {dsponsor_amount}\r\n\r\nSebuah email telah kami kirimkan kepada member ini berisi\r\nlangkah-langkah selanjutnya untuk mengaktifkan keanggotaannya,\r\ntermasuk mengirimkan nomor rekening anda untuk dilakukan transfer.\r\n\r\nLakukan follow up terhadap member baru tersebut agar segera\r\nmelakukan pembayaran kepada anda.\r\n\r\nApabila member baru tersebut telah melakukan konfirmasi email,\r\nsegera cek kebenaran transfernya lalu segera aktifkan keanggotaannya\r\ndi member area :\r\nhttp://{member_url}\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\nNo. Telp/HP : {admin_phone}', 'Halo {rsponsor_name} ,\r\n\r\nSelamat anda telah mendapatkan seorang calon random referral.\r\nBerikut ini datanya :\r\nNama        : {member_name}\r\nEmail       : {member_email}\r\nNo.Telp/HP  : {member_phone}\r\nUserID      : {member_userid}\r\nTransfer    : {rsponsor_amount}\r\n\r\nSebuah email telah kami kirimkan kepada member ini berisi\r\nlangkah-langkah selanjutnya untuk mengaktifkan keanggotaannya,\r\ntermasuk mengirimkan nomor rekening anda untuk dilakukan transfer.\r\n\r\nLakukan follow up terhadap member baru tersebut agar segera\r\nmelakukan pembayaran kepada anda.\r\n\r\nApabila member baru tersebut telah melakukan konfirmasi email,\r\nsegera cek kebenaran transfernya lalu segera aktifkan keanggotaannya\r\ndi member area :\r\nhttp://{member_url}\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\nNo. Telp/HP : {admin_phone}', 'Member baru :\r\n\r\nNama        : {member_name}\r\nEmail       : {member_email}\r\nNo.Telp/HP  : {member_phone}\r\nUserID      : {member_userid}\r\nPassword    : {member_password}\r\n\r\n\r\n1. Direct Sponsor :\r\n-.Nama         : {dsponsor_name}\r\n-.No. Telp/HP  : {dsponsor_phone}\r\n-.Email        : {dsponsor_email}\r\n-.Bank/No. Rek : {dsponsor_bank}\r\n-.Transfer     : Rp.{dsponsor_amount}\r\n\r\n2. Random Sponsor :\r\n-.Nama         : {rsponsor_name}\r\n-.No. Telp/HP  : {rsponsor_phone}\r\n-.Email :Email : {rsponsor_email}\r\n-.Bank/No. Rek : {rsponsor_bank}\r\n-.Transfer     : Rp.{rsponsor_amount}\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\n        \r\nNo. Telp/HP : {admin_phone}', 'Halo {member_name}...\r\n\r\nAnda menerima email ini karena telah melewati batas waktu\r\nuntuk melakukan Upgrade pada program {program_name}.\r\nBatas waktu untuk melakukan Upgrade adalah 2 Minggu (14 hari).\r\n\r\nOleh karena itu Account anda di Cash-Planet :\r\nNama   : {member_name}\r\nEmail  : {member_email}\r\nUserid : {member_userid}\r\nStatus : Free Member\r\nAkan di delete dari {program_name} .\r\n\r\nHal ini dilakukan untuk memperluas kesempatan Paid Member Cash-Planet\r\nagar tetap bisa mendapatkan New Referral.\r\n\r\nAnda boleh ikut berpartisipasi kembali dengan program ini\r\ndengan melakukan pendaftaran ulang melalui website :\r\nhttp://{program_url}\r\n\r\nKami tunggu kedatangan anda kembali...\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\nNo. Telp/HP : {admin_phone}', 'Halo {member_name}...\r\n\r\nKeanggotaan anda di {program_name} sudah diaktifkan.\r\n\r\nBerikut ini data-data anda :\r\nNama   : {member_name}\r\nEmail  : {member_email}\r\nUserid : {member_userid}\r\nStatus : Paid Member\r\nWeb duplikasi : http://{program_url}/?id={member_userid}\r\n\r\nPromosikan website duplikasi anda untuk mendapatkan keuntungan melalui program ini.\r\n\r\nUntuk login ke member area :\r\nhttp://{member_url}\r\n\r\nSering-seringlah login ke member area untuk melihat perkembangan statistik dan downline anda.\r\n\r\n\r\nSukses Selalu!\r\n\r\n\r\n{admin_name}\r\n{program_name}\r\nHomepage : http://{program_url}\r\nEmail : {admin_email}\r\nNo. Telp/HP : {admin_phone}');";

if (isset($_SERVER['HTTP_HOST']))
{
	$base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
	$base_url .= '://'. $_SERVER['HTTP_HOST'];
	$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
}
$root = $_SERVER['DOCUMENT_ROOT'].str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
$dir_theme = $root."theme/templatemo_049_studio/";
$url_member = $base_url."members/";
$tpldir = $base_url."theme/templatemo_049_studio/";

$sql_admin_data = "INSERT INTO `admin` VALUES (0, 'admin', md5('admin'), '2012-07-12 15:31:43', '', 'Nama admin', 'Alamat admin', 'admin@domain.com', '081323779601', 'Bank Admin', '1', 40, 40, 7, '$dir_theme', 'ProgramKeberuntungan.Com', '$base_url', '$url_member', '01-01-2009', '50000', '25000', '25000', '$tpldir');";
    $sql_members_data1 = "INSERT INTO `members` VALUES (1, 'demo_user1', md5('password'), '-', 'demo@domain.com', 'Demo1', '-', '-', '', '', '111', '1', '1', '1', '2009-01-01 03:45:22', '1', '', '0000-00-00 00:00:00', 10);";
    $sql_members_data2 = "INSERT INTO `members` VALUES (2, 'demo_user2', md5('password'), '-', 'demo@domain.com', 'Demo2', '-', '-', 'demo_user', '', '', '1', '1', '1', '2009-01-01 04:02:10', '1', '', '0000-00-00 00:00:00', 10)";

    if (isset($_POST['button'])) {
        $error = '';
        if (!strlen($_POST['db_name'])) {
             $error .= "<li>Nama database harus diisi!</li>";
        }
        if (!strlen($_POST['db_user'])) {
             $error .= "<li>Username database harus diisi!</li>";
        }
        if (!strlen($_POST['db_pass'])) {
             $error .= "<li>Password database harus diisi!</li>";
        }
        if (!strlen($_POST['db_host'])) {
             $error .= "<li>Host database harus diisi!</li>";
        }
        if (!strlen($_POST['tpldir'])) {
             $error .= "<li>Direktori template harus diisi!</li>";
        }
        if (!strlen($_POST['admin_user'])) {
             $error .= "<li>UserID admin harus diisi!</li>";
        }
        if (!strlen($_POST['admin_pass'])) {
             $error .= "<li>Password admin harus diisi!</li>";
        }

        if ($error) {
            echo "$error";
             exit;
        } else {
	    $db_host = $_POST['db_host'];
	    $db_user = $_POST['db_user'];
	    $db_pass = $_POST['db_pass'];
	    $db_name = $_POST['db_name'];

            $db = mysql_connect($db_host, $db_user, $db_pass) or die ("Tidak bisa connect database");
            $db_exist = mysql_select_db($db_name, $db);


            if ($db_exist) {
                printf("Database sudah ada\n<br>Membuat tabel database...");
                $db = mysql_connect($db_host, $db_user, $db_pass);
                mysql_select_db($db_name, $db);

                $result = mysql_query($sql_admin) or die (mysql_error());
                $result2 = mysql_query($sql_letters) or die (mysql_error());
                $result3 = mysql_query($sql_login_session) or die (mysql_error());
                $result4 = mysql_query($sql_members) or die (mysql_error());
                $result5 = mysql_query($sql_stats) or die (mysql_error());
                $result6 = mysql_query($sql_testimonials) or die (mysql_error());
                $result7 = mysql_query($sql_admin_data) or die (mysql_error());
                $result8 = mysql_query($sql_admin_data1) or die (mysql_error());
                $result9 = mysql_query($sql_letters_data) or die (mysql_error());
                $result10 = mysql_query($sql_members_data1) or die (mysql_error());
                $result11 = mysql_query($sql_members_data2) or die (mysql_error());
                $result12 = write_config();
                if ($result && $result2 && $result3 && $result4 && $result5 && $result6 && $result2 && $result3 && $result4 && $result5 && $result6 && $result7 && $result8 && $result9 && $result10 && $result11 && $result12)
            ?>
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<b>Install</b></p>
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<b>Instalasi sukses !</b></p>
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<b>Segera login ke <a href="admin/">admin control panel</a> lalu edit
konfigurasi website anda segera!<br>
Gunakan userid dan password yang anda berikan tadi...</b></p>

            <?php



            } else {
                $create_db = mysql_query("CREATE DATABASE $db_name") or die ("errordb");

                if ($create_db) {
                    echo"Membuat database...\n<br>Membuat tabel...";
                    $db = mysql_connect($db_host, $db_user, $db_pass);
                    mysql_select_db($db_name, $db);

                    $result = mysql_query($sql_admin) or die (mysql_error());
                    $result2 = mysql_query($sql_letters) or die (mysql_error());
                    $result3 = mysql_query($sql_login_session) or die (mysql_error());
                    $result4 = mysql_query($sql_members) or die (mysql_error());
                    $result5 = mysql_query($sql_stats) or die (mysql_error());
                    $result6 = mysql_query($sql_testimonials) or die (mysql_error());
                    $result7 = mysql_query($sql_admin_data) or die (mysql_error());
                    $result8 = mysql_query($sql_admin_data1) or die (mysql_error());
                    $result9 = mysql_query($sql_letters_data) or die (mysql_error());
                    $result10 = mysql_query($sql_members_data1) or die (mysql_error());
                    $result11 = mysql_query($sql_members_data2) or die (mysql_error());
                    $result12 = write_config();
                }
                if ($result && $result2 && $result3 && $result4 && $result5 && $result6 && $result2 && $result3 && $result4 && $result5 && $result6 && $result7 && $result8 && $result9 && $result10 && $result11 && $result12)

            ?>
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<b>Instalasi sukses !</b></p>
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<b>Segera login ke <a href="./admin">admin control panel</a> lalu edit
konfigurasi website anda segera!<br>
Gunakan userid dan password yang anda berikan tadi...</b></p>
<p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<b>Demi keamanan, segera hapus file ini (&quot;install.php&quot;).</b></p>

            <?php
            }
        }

    } else {
    ?>
<p align="center"> <b>Install Script Direct And Random Sponsor</b></p>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
  <table border="0" width="100%" class="b01" style="border-collapse: collapse" bordercolor="#111111" cellpadding="0" cellspacing="0">
<tr>
<td width="80%" colspan="2" align="right">
<p align="center"><b>Konfigurasi :</b></td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">Nama database :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input name="db_name" size="47" value=""></td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">Username database :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input name="db_user" size="47" value="">
(Jika belum ada, silahkan buat dari</td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">Password database :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input name="db_pass" size="47" value="">
CPANEL account hosting anda.)</td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">Host database :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input name="db_host" size="47" value="localhost">
(biasanya localhost)</td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">Direktori template :</td>
<td width="36%" bgcolor="#FFFFFF"><b>&nbsp;</b><input type="text" name="tpldir" size="47" value="<?php echo $dir_theme; ?>">
(contoh :/home/userid/public_html/tpl/)</td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">UserID admin :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input type="text" name="admin_user" size="47" value=""></td>
</tr>
<tr>
<td width="8%" bgcolor="#FFFFFF" align="right">Password admin :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input type="text" name="admin_pass" size="47" value=""></td>
</tr>
<tr>
      <td width="8%" bgcolor="#FFFFFF" align="right">UserID budicomputer :</td>
<td width="36%" bgcolor="#FFFFFF">&nbsp;<input type="text" name="LIC_USERID" size="47" value=""></td>
</tr>
</table></p>
  <p align="center" style="text-indent: 20; line-height: 150%; margin-left: 10; margin-right: 10">
<input type="submit" value="Install" name="button"></p>
</form>

<?php }
?>

<p align="center">&nbsp;</p>
<p align="center"><b><a href="<?php echo $base_url; ?>">ProgramKeberuntungan</a></b><br>
  Copyright &copy; 2012 - <?php echo date('Y'); ?>
</p>

</body>

</html>

    <?php

        function write_config() {
            global $db_name, $db_host, $db_user, $db_pass;
            $fp = fopen("config_inc.php", "w");
            flock($fp, LOCK_EX);
            fwrite ($fp , "<?php\n
                #***********************************************************************#
                #                                                                       #
                # \"Script PHP untuk program affiliasi, reseller, mlm dan arisan online\" #
                #                   Budicomputer.com Randomizer Script                      #
                #                    http://www.Budicomputer.com                            #
                #          Resale Right \A9 2007  Budicomputerz@yahoo.com                     #
                #                                                                       #
                #***********************************************************************#
                # File : config_inc.php
                # Defenisi :
                # Untuk konfigurasi mysql database dan loading konfigurasi lainnya dari
                # database.

                //************************ KONFIGURASI MySQL *********************//\n\n\n");

            fwrite ($fp , "\$conf['dbname'] = \"$db_name\";\n");
            fwrite ($fp , "\$conf['dbhost'] = \"$db_host\";\n");
            fwrite ($fp , "\$conf['dbuser'] = \"$db_user\";\n");
            fwrite ($fp , "\$conf['dbpass'] = \"$db_pass\";\n");
            fwrite ($fp , "");
            fwrite ($fp , "\n
                //Loading konfigurasi dari database
                \$dbcon = mysql_connect (\$conf['dbhost'], \$conf['dbuser'], \$conf['dbpass']) or die (mysql_error());
                mysql_select_db(\$conf['dbname'])  or die (mysql_error());

                \$admin_row=mysql_fetch_array(mysql_db_query(
                \$conf['dbname'],\"SELECT name,address,email,phone,bank,with_rsponsor,maxrefshow,
                maxstatsshow,cookiexpires,tpldir,program_name,program_url,member_url,
                launch_date,dsponsor_amount,rsponsor_amount,admin_amount,LIC_USERID
                FROM admin\")) or die (mysql_error());


                \$conf['admin_name']           = \$admin_row['name'];
                \$conf['admin_email']          = \$admin_row['email'];
                \$conf['admin_phone']          = \$admin_row['phone'];
                \$conf['admin_bank']           = \$admin_row['bank'];
                \$conf['admin_address']        = \$admin_row['address'];
                \$conf['rsponsor']             = \$admin_row['with_rsponsor'];
                \$conf['maxrefshow']           = \$admin_row['maxrefshow'];
                \$conf['maxstatsshow']         = \$admin_row['maxstatsshow'];
                \$conf['expires']              = \$admin_row['cookiexpires'];
                \$conf['tpldir']               = \$admin_row['tpldir'];
                \$conf['program_name']         = \$admin_row['program_name'];
                \$conf['program_url']          = \$admin_row['program_url'];
                \$conf['member_url']           = \$admin_row['member_url'];
                \$conf['launch_date']          = \$admin_row['launch_date'];
                \$conf['dsponsor_amount']      = \$admin_row['dsponsor_amount'];
                \$conf['rsponsor_amount']      = \$admin_row['rsponsor_amount'];
                \$conf['admin_amount']         = \$admin_row['admin_amount'];
                \$LIC_USERID                 = \$admin_row['LIC_USERID'];
		\$conf['year_now']             = date('Y');
                ");
            fwrite ($fp , "?>");
            fclose($fp);
        }

?>
