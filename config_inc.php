<?php

                #***********************************************************************#
                #                                                                       #
                # "Script PHP untuk program affiliasi, reseller, mlm dan arisan online" #
                #                   Budicomputer.com Randomizer Script                      #
                #                    http://www.Budicomputer.com                            #
                #          Resale Right \A9 2007  Budicomputerz@yahoo.com                     #
                #                                                                       #
                #***********************************************************************#
                # File : config_inc.php
                # Defenisi :
                # Untuk konfigurasi mysql database dan loading konfigurasi lainnya dari
                # database.

                //************************ KONFIGURASI MySQL *********************//


$conf['dbname'] = "directreseller";
$conf['dbhost'] = "localhost";
$conf['dbuser'] = "root";
$conf['dbpass'] = "banatahta";


                //Loading konfigurasi dari database
                $dbcon = mysql_connect ($conf['dbhost'], $conf['dbuser'], $conf['dbpass']) or die (mysql_error());
                mysql_select_db($conf['dbname'])  or die (mysql_error());

                $admin_row=mysql_fetch_array(mysql_db_query(
                $conf['dbname'],"SELECT name,address,email,phone,bank,with_rsponsor,maxrefshow,
                maxstatsshow,cookiexpires,tpldir,program_name,program_url,member_url,
                launch_date,dsponsor_amount,rsponsor_amount,admin_amount,LIC_USERID
                FROM admin")) or die (mysql_error());


                $conf['admin_name']           = $admin_row['name'];
                $conf['admin_email']          = $admin_row['email'];
                $conf['admin_phone']          = $admin_row['phone'];
                $conf['admin_bank']           = $admin_row['bank'];
                $conf['admin_address']        = $admin_row['address'];
                $conf['rsponsor']             = $admin_row['with_rsponsor'];
                $conf['maxrefshow']           = $admin_row['maxrefshow'];
                $conf['maxstatsshow']         = $admin_row['maxstatsshow'];
                $conf['expires']              = $admin_row['cookiexpires'];
                $conf['tpldir']               = $admin_row['tpldir'];
                $conf['program_name']         = $admin_row['program_name'];
                $conf['program_url']          = $admin_row['program_url'];
                $conf['member_url']           = $admin_row['member_url'];
                $conf['launch_date']          = $admin_row['launch_date'];
                $conf['dsponsor_amount']      = $admin_row['dsponsor_amount'];
                $conf['rsponsor_amount']      = $admin_row['rsponsor_amount'];
                $conf['admin_amount']         = $admin_row['admin_amount'];
                $LIC_USERID                 = $admin_row['LIC_USERID'];
		$conf['year_now']             = date('Y');
                ?>