<?php

class Observatory_Model extends CI_Model {

    public $table = 'idx_sample';
    public $vndb_economics_ref = "vndb_economics_ref";
    public $vndb_economics_data = "vndb_economics_data";

    public function __construct() {
        parent::__construct();
    }

    /*     * ************************************************************** */
    /*    Name ： upload_sample                                                */
    /* --------------------------------------------------------------- */
    /*    Description  ： add new data sample in database                 */
    /* --------------------------------------------------------------- */
    /*    Params  ：  			                                       */
    /* --------------------------------------------------------------- */
    /*    Return  ：   count data success or false if file not exist  */
    /* --------------------------------------------------------------- */
    /*    Warning ：                                                     */
    /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                         */
    /* --------------------------------------------------------------- */
    /*    M001 : New  2012.08.14 (LongNguyen)                             */
    /*     * ************************************************************** */

    public function upload_sample() {
        if (is_file(APPPATH . '../../assets/upload/files/sample/sample.csv')) {
            $file = fopen(APPPATH . '../../assets/upload/files/sample/sample.csv', 'r');
            $this->db->truncate($this->table);
            $i = 0;
            $count = 0;
            while ($info = fgetcsv($file)) {
                if (++$i == 1) {
                    $headers = $info;
                } else {
                    foreach ($headers as $key => $item) {
                        $data[$item] = $info[$key];
                    }
                    if ($this->db->insert($this->table, $data)) {
                        $count++;
                    }
                }
            }
            return $count;
        } else {
            return FALSE;
        }
    }

    /*     * ********************************************************* */
    /*    Name ： import currency                                               
      /* --------------------------------------------------------------- */
    /*    Description  ：                
      /* --------------------------------------------------------------- */
    /*    Params  ：  			                                       
      /* --------------------------------------------------------------- */
    /*    Return  ：   
      /* --------------------------------------------------------------- */
    /*    Warning ：                                                    
      /* --------------------------------------------------------------- */
    /*    Copyright : IFRC                                        
      /* --------------------------------------------------------------- */
    /*    M001 : New  2013.06.17 (PhanMinh)                         
      /*     * ********************************************************* */

    public function import_currency() {
        /* update 2013.07.16 (Nguyen Tuan Anh) */
        /* empty table currency */
        $this->db->query("TRUNCATE TABLE idx_currency_day;");
        $this->db->query("TRUNCATE TABLE idx_currency_month;");
        $this->db->query("TRUNCATE TABLE idx_currency_year;");

        /* path file currency text */
        $path = '\\\LOCAL\IFRCVN\VFDB\DATA\idx_currency_day.txt';

        /* config path file on mysql */
        $base_url = str_replace('\\', '\\\\', $path);

        /* import idx_currency_day */
        $sql = "LOAD DATA LOCAL INFILE '{$base_url}'
                INTO TABLE idx_currency_day
                FIELDS TERMINATED BY '\t' (code, date, close)
                SET yyyymmdd = DATE_FORMAT(date, '%Y%m%d')";
        $this->db->query($sql);

        /* import idx_currency_month */
        $sql = "INSERT INTO idx_currency_month (code, yyyymm, date, close)
                SELECT code, DATE_FORMAT(date, '%Y%m') AS yyyymm, MAX(date) AS max_date, close
                FROM idx_currency_day
                GROUP BY YEAR(date), MONTH(date), code
                ORDER BY code, date;";
        $this->db->query($sql);

        /* import idx_currency_year */
        $sql = "INSERT INTO idx_currency_year (code, yyyy, date, close)
                SELECT code, DATE_FORMAT(date,'%Y') AS yyyy, MAX(date) AS max_date, close
                FROM idx_currency_day
                GROUP BY YEAR(date), code
                ORDER BY code, date;";
        $this->db->query($sql);
    }

    public function import_economics() {
        $data_name = array(
            $this->vndb_economics_ref => array('column' => 'code,name,description'),
            $this->vndb_economics_data => array('column' => 'name,code_ctr,indcode,year,value,last_upd')
        );
        foreach ($data_name as $name => $column) {
            $this->db->query("TRUNCATE TABLE " . $name);
            $path = '\\\LOCAL\IFRCVN\VFDB\DATA\\' . $name . '.txt';
            $base_url = str_replace('\\', '\\\\', $path);
            $this->db->query("LOAD DATA LOCAL INFILE '" . $base_url . "' INTO TABLE " . $name . " FIELDS TERMINATED BY '\t' IGNORE 1 LINES (" . $column['column'] . ")");
        }
    }

}