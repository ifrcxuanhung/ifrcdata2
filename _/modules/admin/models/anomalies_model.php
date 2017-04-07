<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  calendar_page_model.php               	      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  model calendar_page                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.28 (Tung)        New Create      */
/* * ****************************************************************** */

class Anomalies_model extends CI_Model {

    public $table = "vndb_anomalies";

    public function __construct() {
        parent::__construct();
    }

    public function excute($table, $column) {
        set_time_limit(0);
        $this->db->simple_query('DROP TABLE IF EXISTS `vndb_anomalies`');
        $sql = "CREATE TABLE IF NOT EXISTS `vndb_anomalies` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `ticker` varchar(10) DEFAULT NULL,
                `date` date DEFAULT NULL,
                `pdate` date DEFAULT NULL,
                `ndate` date DEFAULT NULL,
                `value` bigint(20) DEFAULT NULL,
                `pvalue` bigint(20) DEFAULT NULL,
                `nvalue` bigint(20) DEFAULT NULL,
                `fieldname` varchar(16) DEFAULT NULL,
                `t1` date DEFAULT NULL,
                `t2` date DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `ticker` (`ticker`,`date`,`pdate`,`ndate`) USING BTREE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        $this->db->simple_query($sql);

        $sql = "INSERT INTO {$this->table} (ticker, date) (SELECT ticker, date FROM {$table} WHERE date > '2008-08-30');";

        if ($this->db->simple_query($sql) == 1) {
            $sql = "UPDATE {$this->table} SET fieldname = '{$column}'";
            if ($this->db->simple_query($sql) == 1) {
                $sql = "UPDATE {$this->table}, bkh_calendar SET ndate = nxt_date, pdate = prv_date WHERE {$this->table}.date = bkh_calendar.date;";
                if ($this->db->simple_query($sql) == 1) {
                    $sql = "UPDATE {$this->table}, {$table} SET {$this->table}.value = {$table}.{$column} WHERE {$this->table}.date = {$table}.date AND {$this->table}.ticker = {$table}.ticker;";
                    if ($this->db->simple_query($sql) == 1) {
                        $sql = "UPDATE {$this->table}, {$table} SET {$this->table}.nvalue = {$table}.{$column} WHERE {$this->table}.ndate = {$table}.date AND {$this->table}.ticker = {$table}.ticker;";
                        if ($this->db->simple_query($sql) == 1) {
                            $sql = "UPDATE {$this->table}, {$table} SET {$this->table}.pvalue = {$table}.{$column} WHERE {$this->table}.pdate = {$table}.date AND {$this->table}.ticker = {$table}.ticker;";
                            if ($this->db->simple_query($sql) == 1) {
                                $this->db->simple_query("DROP TABLE IF EXISTS `vndb_anomalies_tmp`");

                                $this->db->simple_query("CREATE TEMPORARY TABLE IF NOT EXISTS vndb_anomalies_tmp LIKE vndb_anomalies;");

                                $this->db->simple_query("INSERT INTO vndb_anomalies_tmp(`ticker`, `date`, `pdate`, `ndate`, `value`, `pvalue`, `nvalue`, `fieldname`, `t1`, `t2`)(select `ticker`, `date`, `pdate`, `ndate`, `value`, `pvalue`, `nvalue`, `fieldname`, `t1`, `t2` FROM vndb_anomalies where pvalue = nvalue AND `value` <> pvalue)");

                                $this->db->simple_query('DROP TABLE IF EXISTS `vndb_anomalies`');

                                $this->db->simple_query('CREATE TEMPORARY TABLE IF NOT EXISTS vndb_anomalies LIKE vndb_anomalies_tmp');

                                $this->db->simple_query("INSERT INTO vndb_anomalies(`ticker`, `date`, `pdate`, `ndate`, `value`, `pvalue`, `nvalue`, `fieldname`, `t1`, `t2`)(select `ticker`, `date`, `pdate`, `ndate`, `value`, `pvalue`, `nvalue`, `fieldname`, `t1`, `t2` FROM vndb_anomalies_tmp where pvalue = nvalue AND `value` <> pvalue)");

                                $this->db->simple_query('DROP TABLE IF EXISTS `vndb_anomalies_tmp`');
                                $this->db->query("SELECT * FROM vndb_anomalies")->result_array();
                            } else {
                                return FALSE;
                            }
                        } else {
                            return FALSE;
                        }
                    } else {
                        return FALSE;
                    }
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

}