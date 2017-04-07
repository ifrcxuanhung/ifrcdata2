<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Events_model extends CI_Model {

    public $final_table = 'vndb_events_day';
    public $_table = 'vndb_events_filter';

    function __construct() {
        parent::__construct();
        ini_set('memory_limit', '-1');
    }

    public function listEvent($type = '', $time = '', $date = ''){
        if($type == ''){
            if($date == ''){
                $date = date('Y-m-d');
                switch($time){
                    case 'history': $op = '<'; break;
                    case 'today': $op = ''; break;
                    default: $op = '>='; break;
                }
                if($op != 'all'){
                    $this->db->where('date_ann ' . $op, $date);
                }
            }else{
                $this->db->where('date_ann', $date);
            }
        }else{
            $this->db->where('event_type', $type);
        }
        // DB table to use
        $sTable = $this->final_table;

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'ticker', 'market', 'date_ann', 'event_type', 'evname', 'content','status');
        $aColumns_filter = array('id', 'ticker', 'market', 'date_ann', 'event_type', 'evname', 'content','status');
        $aColumns2 = array('id', 'ticker', 'market', 'date_ann', 'event_type', 'evname', 'content','status');

        $this->db->order_by('date_ann','desc');
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol)) + 1], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            $a_filter = array();
            for($i=0; $i<count($aColumns_filter); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $arr_search = explode(',',$sSearch);
                    $a_filter = array();
                    foreach($arr_search as $value_search){
                        $value_search = trim($value_search);
                        if($value_search == ''){
                            continue;
                        }else{
                            $row = $this->db->query('SELECT COUNT(*) as count_row FROM '.$sTable.' WHERE '.$aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'')->row_array();
                            if($row['count_row'] != 0){
                                $a_filter[] = $aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'';
                            }
                        }
                    }
                    if(!empty($a_filter)){
                        $a_filter_final[] = implode(' AND ', $a_filter);
                    }
                }
            }
            if(!empty($a_filter_final)){
                $this->db->where('((' . implode(') OR (', $a_filter_final) . '))', NULL, false);
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns2 as $col)
            {
                if(!in_array($col, array('id'))){
                    $value = '';
                    $value = $aRow[$col];
                    if($col == 'content'){
                        $value = substr(strip_tags(html_entity_decode(htmlspecialchars_decode($aRow['content']))), 0, 70) . '...<a header="' . $aRow['ticker'] . ':' . $aRow['date_ann'] . '" href="javascript:void(0)" content="' . $aRow['content'] . '" class="view-more">view more</a>';
                    }
                    if(is_numeric($value) && $col != 'pay_yr'){
                        $value = normalFormat($value);
                    }
                    $row[] = $value;
                }
            }
            $row[] = '<a href="' . admin_url() . 'events/add/' . $aRow['id'] . '" title="Edit" class="with-tip">View</a>';
            $output['aaData'][] = $row;
        }
        return $output;

        // return $this->db->get($this->final_table)->result_array();
    }

    public function listEventPrepare($time = '', $date = ''){
        // DB table to use
        $sTable = $this->final_table;
        $sTable_2 = $this->_table;

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array("id", "{$sTable}.ticker", "{$sTable}.market", "{$sTable}.date_ann", "event_type", "evname", "content","status");
        $aColumns_filter = array("id", "{$sTable}.ticker", "{$sTable}.market", "{$sTable}.date_ann", "event_type", "evname", "content","status");
        $aColumns2 = array("id", "ticker", "market", "date_ann", "event_type", "evname", "content","status");

        $this->db->join($sTable_2,"{$sTable}.id = {$sTable_2}.news_id");
        $status = array('CLASSIFIED', 'CONFIRM');
        $this->db->where_in('status', $status);
        $this->db->where("{$sTable_2}.evt_id <>",6);
        if($date == ''){
            $date = date('Y-m-d');
            switch($time){
                case 'history': $op = '<'; break;
                case 'today': $op = ''; break;
                default: $op = '>='; break;
            }
            if($op != 'all'){
                $this->db->where("{$sTable}.date_ann " . $op, $date);
            }
        }else{
            $this->db->where("{$sTable}.date_ann", $date);
        }
        $this->db->order_by('date_ann','desc');
        $this->db->group_by("{$sTable_2}.news_id");

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol)) + 1], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            $a_filter = array();
            for($i=0; $i<count($aColumns_filter); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $arr_search = explode(',',$sSearch);
                    $a_filter = array();
                    foreach($arr_search as $value_search){
                        $value_search = trim($value_search);
                        if($value_search == ''){
                            continue;
                        }else{
                            $row = $this->db->query('SELECT COUNT(*) as count_row FROM '.$sTable.' WHERE '.$aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'')->row_array();
                            if($row['count_row'] != 0){
                                $a_filter[] = $aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'';
                            }
                        }
                    }
                    if(!empty($a_filter)){
                        $a_filter_final[] = implode(' AND ', $a_filter);
                    }
                }
            }
            if(!empty($a_filter_final)){
                $this->db->where('((' . implode(') OR (', $a_filter_final) . '))', NULL, false);
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns2 as $col)
            {
                if(!in_array($col, array('id'))){
                    $value = '';
                    $value = $aRow[$col];
                    if($col == 'content'){
                        $value = substr(strip_tags(html_entity_decode(htmlspecialchars_decode($aRow['content']))), 0, 70) . '...<a header="' . $aRow['ticker'] . ':' . $aRow['date_ann'] . '" href="javascript:void(0)" content="' . $aRow['content'] . '" class="view-more">view more</a>';
                    }
                    $row[] = $value;
                }
            }
            $row[] = '<a href="' . admin_url() . 'events/edit/' . $aRow['id'] . '" title="Edit" class="with-tip">View</a>';
            $output['aaData'][] = $row;
        }
        return $output;

        // return $this->db->get($this->final_table)->result_array();
    }

    public function listEventView($time = '', $date = ''){
        // DB table to use
        $sTable = 'vndb_ca_daily';

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array("ticker","market","events_type","date_ann","sh_old","sh_add","sh_new","sh_type","ipo_date","ftrd","date_ex","date_rec","date_pay","ratio","year","period","prices","{$sTable}.div");
        $aColumns_filter = array("ticker","market","events_type","date_ann","sh_old","sh_add","sh_new","sh_type","ipo_date","ftrd","date_ex","date_rec","date_pay","ratio","year","period","prices","div");
        $aColumns2 = array("ticker","market","events_type","date_ann","sh_old","sh_add","sh_new","sh_type","ipo_date","ftrd","date_ex","date_rec","date_pay","ratio","year","period","prices","div");

        if($date == ''){
            $date = date('Y-m-d');
            switch($time){
                case 'history': $op = '<'; break;
                case 'today': $op = ''; break;
                default: $op = '>='; break;
            }
            if($op != 'all'){
                $this->db->where("date_ann " . $op, $date);
            }
        }else{
            $this->db->where("date_ann", $date);
        }
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol)) + 1], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            $a_filter = array();
            for($i=0; $i<count($aColumns_filter); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $arr_search = explode(',',$sSearch);
                    $a_filter = array();
                    foreach($arr_search as $value_search){
                        $value_search = trim($value_search);
                        if($value_search == ''){
                            continue;
                        }else{
                            $row = $this->db->query('SELECT COUNT(*) as count_row FROM '.$sTable.' WHERE '.$aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'')->row_array();
                            if($row['count_row'] != 0){
                                $a_filter[] = $aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'';
                            }
                        }
                    }
                    if(!empty($a_filter)){
                        $a_filter_final[] = implode(' AND ', $a_filter);
                    }
                }
            }
            if(!empty($a_filter_final)){
                $this->db->where('((' . implode(') OR (', $a_filter_final) . '))', NULL, false);
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns2 as $col)
            {
                $value = '';
                $value = $aRow[$col];
                if($value == '0' || $value == '0000-00-00'){
                    $value = '';
                }
                if(is_numeric($value) && $col != 'year'){
                    $value = number_format($value);
                }
                $row[] = $value;
            }
            $output['aaData'][] = $row;
        }
        return $output;

        // return $this->db->get($this->final_table)->result_array();
    }

    public function listEventsByFilter($data_value){
        // DB table to use
        $sTable = $this->final_table;

        /* Array of database columns which should be read and sent back to DataTables. Use a space where
         * you want to insert a non-database field (for example a counter or static image)
         */
        $aColumns = array('id', 'ticker', 'market', 'date_ann', 'event_type', 'evname', 'content');
        $aColumns_filter = array('id', 'ticker', 'market', 'date_ann', 'event_type', 'evname', 'content');
        $aColumns2 = array('id', 'ticker', 'market', 'date_ann', 'event_type', 'evname', 'content');
        
        if($data_value != ''){
            $arr_dv = explode(',',$data_value);
            if(count($arr_dv) > 1){
                $this->db->where_in('event_type',$arr_dv);
                $sql_where = "event_type in ('".implode("','",$arr_dv)."')";
            }else{
                $this->db->where('event_type',$data_value);
                $sql_where = "event_type ='".$data_value."'";
            }
        }
    
        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);
    
        // Paging
        if(isset($iDisplayStart) && $iDisplayLength != '-1')
        {
            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
        
        // Ordering
        if(isset($iSortCol_0))
        {
            for($i=0; $i<intval($iSortingCols); $i++)
            {
                $iSortCol = $this->input->get_post('iSortCol_'.$i, true);
                $bSortable = $this->input->get_post('bSortable_'.intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_'.$i, true);
    
                if($bSortable == 'true')
                {
                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol)) + 1], $this->db->escape_str($sSortDir));
                }
            }
        }
        
        /* 
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */
        if(isset($sSearch) && !empty($sSearch))
        {
            $a_filter = array();
            for($i=0; $i<count($aColumns_filter); $i++)
            {
                $bSearchable = $this->input->get_post('bSearchable_'.$i, true);
                
                // Individual column filtering
                if(isset($bSearchable) && $bSearchable == 'true')
                {
                    $arr_search = explode(',',$sSearch);
                    $a_filter = array();
                    foreach($arr_search as $value_search){
                        $value_search = trim($value_search);
                        if($value_search == ''){
                            continue;
                        }else{
                            $row = $this->db->query('SELECT COUNT(*) as count_row FROM '.$sTable.' WHERE '.$sql_where.' AND '.$aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'')->row_array();
                            if($row['count_row'] != 0){
                                $a_filter[] = $aColumns_filter[$i] . ' LIKE \'%' . $this->db->escape_like_str($value_search) . '%\'';
                            }
                        }
                    }
                    if(!empty($a_filter)){
                        $a_filter_final[] = implode(' AND ', $a_filter);
                    }
                }
            }
            if(!empty($a_filter_final)){
                $this->db->where('((' . implode(') OR (', $a_filter_final) . '))', NULL, false);
            }
        }
        
        // Select Data
        $this->db->select('SQL_CALC_FOUND_ROWS '.str_replace(' , ', ' ', implode(', ', $aColumns)), false);
        $rResult = $this->db->get($sTable);
    
        // Data set length after filtering
        $this->db->select('FOUND_ROWS() AS found_rows');
        $iFilteredTotal = $this->db->get()->row()->found_rows;
    
        // Total data set length
        $iTotal = $this->db->count_all($sTable);
    
        // Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal,
            'iTotalDisplayRecords' => $iFilteredTotal,
            'aaData' => array()
        );

        
        foreach($rResult->result_array() as $aRow)
        {
            $row = array();
            
            foreach($aColumns2 as $col)
            {
                if(!in_array($col, array('id'))){
                    $value = '';
                    $value = $aRow[$col];
                    if($col == 'content'){
                        $value = substr(strip_tags(html_entity_decode(htmlspecialchars_decode($aRow['content']))), 0, 70) . '...<a header="' . $aRow['ticker'] . ':' . $aRow['date_ann'] . '" href="javascript:void(0)" content="' . $aRow['content'] . '" class="view-more">view more</a>';
                    }
                    if(is_numeric($value) && $col != 'pay_yr'){
                        $value = normalFormat($value);
                    }
                    $row[] = $value;
                }
            }
            $output['aaData'][] = $row;
        }
        return $output;

        // return $this->db->get($this->final_table)->result_array();
    }

    public function get_data_type(){
        return $this->db->query("SELECT DISTINCT EVENT_TYPE FROM ".$this->final_table." WHERE EVENT_TYPE <> '' ORDER BY EVENT_TYPE ASC")->result_array();
    }

    public function get_data_type_2(){
        $result_list =  $this->db->get('vndb_events_list')->result_array();
        $data_list = array();
        foreach($result_list as $rl){
            $this->db->where('evt_id',$rl['id']);
            $result_field = $this->db->get('vndb_events_field')->result_array();
            $data_field = array();
            foreach($result_field as $rf){
                $field = array(
                    'name' => $rf['field'],
                    'format' => $rf['format']
                );
                $data_field[] = $field;
            }
            $rl['field'] = $data_field;
            $data_list[] =  $rl;
        }
        return $data_list;
    }

    public function get_data_type_3($id){
        $this->db->where('news_id',$id);
        $result = $this->db->get($this->_table)->result_array();
        $data = array();
        foreach($result as $rs){
            $data[] = $rs['evt_id'];
        }
        return $data;
    }

    public function get_data($id){
        $this->db->where('id',$id);
        return $this->db->get($this->final_table)->row_array();
    }

    public function get_data_2($id,$data){
        $this->db->where('id',$id);
        $result_final = $this->db->get($this->final_table)->row_array();
        foreach($data as $dt){
            $this->db->where('id',$dt);
            $result = $this->db->get('vndb_events_list')->row_array();
            $this->db->where('ticker',$result_final['ticker']);
            $this->db->where('market',$result_final['market']);
            $this->db->where('events_type',$result['evname_en']);
            $this->db->where('date_ann',$result_final['date_ann']);
            $result_2 = $this->db->get('vndb_ca_daily')->row_array();
            $result_final[$result['evname_en']] = $result_2;
        }
        return $result_final;
    }

    public function check_type($id){
        $this->db->where('news_id',$id);
        $row = $this->db->get($this->_table)->num_rows();
        if($row != 0){
            $this->db->where('news_id',$id);
            $this->db->delete($this->_table);
        }
    }

    public function check_data($data){
        $this->db->where('ticker',$data['ticker']);
        $this->db->where('market',$data['market']);
        $this->db->where('events_type',$data['type']);
        $this->db->where('date_ann',$data['date_ann']);
        $row = $this->db->get('vndb_ca_daily')->num_rows();
        if($row != 0){
            $this->db->where('ticker',$data['ticker']);
            $this->db->where('market',$data['market']);
            $this->db->where('events_type',$data['type']);
            $this->db->where('date_ann',$data['date_ann']);
            $this->db->delete('vndb_ca_daily');
        }
    }

    public function insert($data){
        $this->db->insert($this->_table,$data);
    }

    public function insert_2($data){
        $this->db->insert('vndb_ca_daily',$data);
    }

    public function update($data,$id){
        $this->db->where('id',$id);
        $this->db->update($this->final_table,$data);
    }

    public function get_type(){
        return $this->db->get('vndb_events_list')->result_array();
    }
}