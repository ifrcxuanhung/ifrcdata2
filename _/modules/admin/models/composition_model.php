<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  composition_model.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  model composition                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.09.05 (LongNguyen)        New Create      */
/* * ****************************************************************** */

class Composition_model extends CI_Model {

    private $date;
    private $time;

    function __construct() {
        parent::__construct();
        $this->date = date("Ymd");
        $this->time = date("His");
    }

    //Insert Composition
    function Composition_Insert($compo) {
        $compo["dates"] = '2010-09-17';
        $compo["times"] = $this->time;
        if ($this->db->insert("idx_composition", $compo))
            return true;
        else
            return false;
    }

    //Update Specs
    function Specs_Insert($specs, $h = "", $m = "", $s = "") {
        $timech = $h . ':' . $m . ':' . $s;

        $column = array(
            "idx_mcap" => $specs['idx_mcap'],
            "idx_last" => $specs['idx_last'],
            "records" => $specs['records'],
            "dates" => $this->date,
            "times" => $timech
        );

        $this->db->update("idx_specs", $column, "id = '" . $specs['id'] . "'");
    }

    function Compo_insert($arr, $idx_code) {
        foreach ($arr as $row) {
            unset($row['id']);
            $row['idx_code'] = $idx_code;
            $this->db->insert("idx_compo", $row);
        }
    }

    function inser_mother($cache, $h = '', $m = '', $s = '') {
        if ($cache)
            foreach ($cache as $specs) {
                //viewarr($specs);
                //Lay bang du lieu tu Compo
                $cache[$specs["idx_code"]]["composition"] = $this->db->get_where("idx_compo", array("idx_code" => $specs['idx_code']))->result_array();
                if (!is_array($cache[$specs["idx_code"]]["composition"])) {
                    $cache[$specs["idx_code"]]["mother"] = $this->db->get_where("idx_compo", array("idx_code " => $specs['idx_mother']))->result_array();
                    if (is_array($cache[$specs["idx_code"]]["mother"])) {
                        $this->Compo_insert($cache[$specs["idx_code"]]["mother"], $specs['idx_code']);
                    }
                }
                $records = 0;
                $this->db->select('stk_code,stk_shares_idx,stk_float_idx,stk_capp_idx,stk_name');
                $this->db->where('idx_code', $specs['idx_code']);
                $cache[$specs["idx_code"]]["composition"] = $this->db->get('idx_compo')->result_array();
                if ($cache[$specs["idx_code"]]["composition"])
                    foreach ($cache[$specs["idx_code"]]["composition"] as $compo) {
                        $records++;
                        $compo['specs_id'] = $specs['id'];
                        $this->db->select('stk_last,stk_price,stk_rlast,stk_curr,stk_mult');
                        $this->db->where('stk_code', $compo['stk_code']);
                        $compo['cache'] = $this->db->get('stk_prices')->result_array();
                        if (is_array($compo['cache']) == TRUE && count($compo['cache']) > 0) {
                            $compo['cache'] = $compo['cache'][0];
                        }
                        $this->db->select('stk_divgross,stk_divnet');
                        $this->db->where('stk_code', $compo['stk_code']);
                        $compo['div'] = $this->db->get('stk_div')->result_array();
                        if (is_array($compo['div']) == TRUE && count($compo['div']) > 0) {
                            $compo['div'] = $compo['div'][0];
                        }
                        if ($compo['cache']['stk_last'] == 0) {
                            $compo['cache']['stk_price'] = $compo['cache']['stk_rlast'];
                        } else {
                            $compo['cache']['stk_price'] = $compo['cache']['stk_last'];
                        }
                        $compo['stk_curr'] = $compo['cache']['stk_curr'];
                        $compo['stk_mult'] = $compo['cache']['stk_mult'];
                        $compo['stk_price'] = $compo['cache']['stk_price'];
                        $compo['stk_dprice'] = (isset($compo['div']['stk_divgross']) == TRUE) ? $compo['div']['stk_divgross'] : 'NULL';
                        $compo['idx_code'] = $specs["idx_code"];
                        $compo['idx_curr'] = $specs['idx_curr'];
                        if ($compo['stk_curr'] != $specs['idx_curr']) {
                            $cur_rates = $compo['stk_curr'] . "_to_" . $specs['idx_curr'];
                            $this->db->select('curr_conv');
                            $this->db->where('code', $cur_rates);
                            $cur_rates = $this->db->get('cur_rates')->result_array();
                            if (is_array($cur_rates) == TRUE && count($cur_rates) > 0)
                                $compo['curr_conv'] = $cur_rates[0]['curr_conv'];
                        } else {
                            $compo['curr_conv'] = "1.000000";
                        }
                        //Xoa Cache
                        unset($compo['cache']);
                        unset($compo['div']);

                        $this->Composition_Insert($compo);
                    }
                $specs['records'] = $records;
                //Update Specs
                $specs['idx_last'] = $specs['idx_base'] * $specs['idx_mcap'] / $specs['idx_divisor'];
                $this->Specs_Insert($specs, $h, $m, $s);
            }

        $content = date("d-m-Y h:i:s") . " | Insert mother & Composition | {$_SERVER['REMOTE_ADDR']} | \n";
        file_put_contents("assets/log/file_log.txt", $content, FILE_APPEND);
    }

    function calculs_index($cache, $h, $m, $s) {

        $row = 0;
        if ($cache)
            foreach ($cache as $specs) {
                // kiem tra type
                //viewarr($specs);
                if ($specs['idx_type'] == 'P') {
                    $row++;
                    $records = 0;
                    $specs['idx_mcap'] = 0;
                    $this->db->select('stk_code,stk_shares_idx,stk_float_idx,stk_capp_idx');
                    $this->db->where('idx_code', $specs['idx_code']);
                    $cache[$specs["idx_code"]]["composition"] = $this->db->get('idx_compo')->result_array();
                    if ($cache[$specs["idx_code"]]["composition"])
                        foreach ($cache[$specs["idx_code"]]["composition"] as $compo) {
                            $records++;
                            $this->db->select('stk_last,stk_price,stk_rlast,stk_curr,stk_mult');
                            $this->db->where('stk_code', $compo['stk_code']);
                            $compo['cache'] = $this->db->get('stk_prices')->result_array();
                            $compo['cache'] = $compo['cache'][0];
                            if ($compo['cache']['stk_last'] == 0) {
                                $compo['cache']['stk_price'] = $compo['cache']['stk_rlast'];
                            } else {
                                $compo['cache']['stk_price'] = $compo['cache']['stk_last'];
                            }
                            $compo['stk_curr'] = $compo['cache']['stk_curr'];
                            $compo['stk_mult'] = $compo['cache']['stk_mult'];
                            $compo['stk_price'] = $compo['cache']['stk_price'];
                            $compo['stk_dprice'] = $compo['cache']['stk_dprice'];

//Xoa Cache
                            unset($compo['cache']);
                            //Ma chuyen doi tien te

                            if ($compo['stk_curr'] != $specs['idx_curr']) {
                                $cur_rates = $compo['stk_curr'] . "_TO_" . $specs['idx_curr'];
                                $this->db->select('curr_conv');
                                $this->db->where('code', $cur_rates);
                                $cur_rates = $this->db->get('cur_rates')->result_array();
                                $compo['curr_conv'] = $cur_rates[0]['curr_conv'];
                            } else {
                                $compo['curr_conv'] = "1";
                            }

                            //Tinh toan 2 cot cuoi
                            $compo['stk_curr_conv'] = $compo['stk_price'] / ($compo['curr_conv'] * $compo['stk_mult'] );
                            $compo['stk_dcurr_conv'] = $compo['stk_dprice'] / ($compo['curr_conv'] * $compo['stk_mult'] );

                            $compo['stk_mcap_idx'] = $compo['stk_shares_idx'] * ($compo['stk_float_idx'] / 100) * ($compo['stk_capp_idx'] / 100) * $compo['stk_curr_conv'];

                            $compo['stk_dcap_idx'] = $compo['stk_shares_idx'] * ($compo['stk_float_idx'] / 100) * ($compo['stk_capp_idx'] / 100) * $compo['stk_dcurr_conv'];

                            $this->update_composition($compo, $specs['id'], $compo['stk_code']);

                            //Tinh tong
                            $specs['idx_mcap'] += $compo['stk_mcap_idx'];
                            $specs['idx_dcap'] += $compo['stk_dcap_idx'];
                        }

                    $specs['records'] = $records;
                    //Update Specs
                    $specs['idx_last'] = $specs['idx_base'] * $specs['idx_mcap'] / $specs['idx_divisor'];
                    $this->Specs_Insert($specs, $h, $m, $s);
                } // end kiem tra type
            }
        echo $row;
    }

    function update_composition($compo, $specs_id, $stk_code) {
        unset($compo['stk_code']);
        //viewarr($compo);
        //echo $specs_id.'-'.$stk_code.'<br>';
        $this->Sql_Update("idx_composition", $compo, "specs_id = '" . $specs_id . "' and stk_code = '" . $stk_code . "'");
    }

    function cal_composition($cache, $h, $m, $s) {

        // update stk_price compostion
        $sql = "
		UPDATE stk_prices as a, idx_composition as b SET b.stk_price = a.stk_rlast WHERE a.stk_code = b.stk_code AND a.stk_last=0
		";
        $this->db->query($sql);

        $sql = "
		UPDATE stk_prices as a, idx_composition as b SET b.stk_price = a.stk_last WHERE a.stk_code = b.stk_code AND a.stk_last<>0
		";
        $this->db->query($sql);
        //-----------------------
        // Update stk_curr_conv, stk_dcurr_conv, stk_mcap_idx, stk_dcap_idx
        $this->db->query("
		  Update idx_composition set
		  stk_curr_conv = stk_price/(stk_mult*curr_conv),
		  stk_dcurr_conv = stk_dprice/(stk_mult*curr_conv),
		  stk_mcap_idx  = stk_shares_idx * (stk_float_idx/ 100) * (stk_capp_idx / 100) * stk_curr_conv,
		  stk_dcap_idx  = stk_shares_idx * (stk_float_idx/ 100) * (stk_capp_idx / 100) * stk_dcurr_conv
		");
        //---------------------------------
        // select tong stk_mcap_idx, stk_dcap_idx
        $sql = "
		SELECT specs_id, SUM(stk_mcap_idx) as stk_mcap_idx, sum(stk_dcap_idx) as stk_dcap_idx FROM idx_composition
		GROUP BY specs_id
		";
        $sp = $this->db->query($sql)->result_array();
        // --------------------------------
        // update stk_wgt
        $sql = "select id,stk_mcap_idx,specs_id from idx_composition";
        $composition = $this->db->query($sql)->result_array();
        if ($composition)
            foreach ($composition as $compo => $value) {
                foreach ($sp as $key => $row) {
                    if ($row['specs_id'] == $value['specs_id']) {
                        $wgt = ($value['stk_mcap_idx'] / $row['stk_mcap_idx']) * 100;
                        $sql = "Update idx_composition set stk_wgt = " . $wgt . ", times = '" . $h . ":" . $m . ":" . $s . "', dates = '" . date('Y-m-d') . "' Where id = " . $value['id'];
                        $this->db->query($sql);
                        break;
                    }
                }
            }
        //--------------------------------------
        // update idx_mcap, idx_dcap, idx_last
        if ($sp)
            foreach ($sp as $key => $row) {
                $sql2 = "
			    Update idx_specs set
			    idx_mcap = {$row['stk_mcap_idx']},
			    idx_dcap = {$row['stk_dcap_idx']},
			    idx_last = idx_base * {$row['stk_mcap_idx']} / idx_divisor ,
			    times = '" . $h . ":" . $m . ":" . $s . "'
			    WHERE id = {$row['specs_id']} and idx_type='P' and calculs = 1";
                $this->db->query($sql2);
            }


        // Tinh idx_last R
        if ($cache)
            foreach ($cache as $specs) {
                $sql = "Select idx_dcap, idx_last, idx_mcap FROM idx_specs WHERE idx_code = '{$specs['idx_mother']}'";
                $p = $this->db->query($sql)->result_array();
                //viewarr($p);
                $last_r = ($p[0]['idx_last'] + ($specs['idx_base'] * $p[0]['idx_dcap'] / $specs['idx_divisor']));
                //echo $last_r;
                $sql = "Update idx_specs SET idx_last = '$last_r', times = '" . $h . ":" . $m . ":" . $s . "', idx_mcap = " . $p[0]['idx_mcap'] . " , idx_dcap = " . $p[0]['idx_dcap'] . "   WHERE id = {$specs['id']} and calculs = 1 ";
                $this->db->query($sql);
            }
        $ran = $this->randomFloat();


        if ($cache)
            foreach ($cache as $specs) {
                $sql = "Update idx_specs SET idx_var = ((idx_last/(idx_pclose*(1+(0.05*RAND()))))-1)*100 WHERE id = {$specs['id']} and calculs = 1 ";
                $this->db->query($sql);

                $sql = "Update idx_specs SET idx_last = idx_last * (1 + (idx_var/100))  WHERE id = {$specs['id']} and calculs = 1 ";
                $this->db->query($sql);
            }

        // tinh to_adjust idx_composition
        $this->next_day();
        // tinh to_adjust idx_specs
        $sql = "
			UPDATE idx_specs as s, idx_composition as c
			SET s.to_adjust = c.to_adjust
			WHERE s.idx_code = c.idx_code and c.to_adjust = 1
		";
        $this->db->query($sql);

        // update next_shares_idx
        $sql = "
			UPDATE idx_composition
			SET nxt_shares_idx = stk_shares_idx
			WHERE to_adjust = 1 and nxt_shares_idx = 0
		";
        $this->db->query($sql);

        // update nxt_float_idx
        $sql = "
			UPDATE idx_composition
			SET nxt_float_idx = stk_float_idx
			WHERE to_adjust = 1 and nxt_float_idx = 0
		";
        $this->db->query($sql);

        // update nxt_capp_idx
        $sql = "
			UPDATE idx_composition
			SET nxt_capp_idx = stk_capp_idx
			WHERE to_adjust = 1 and nxt_capp_idx = 0
		";
        $this->db->query($sql);

        // update nxt_price
        $sql = "
			UPDATE idx_composition
			SET nxt_price = stk_price
			WHERE to_adjust = 1 and nxt_price = 0
		";
        $this->db->query($sql);

        // update idx_mcap_nxt
        $sql = "
			UPDATE idx_specs as s, idx_composition as c
			SET s.idx_mcap_nxt = c.nxt_shares_idx * (c.nxt_float_idx/ 100) * (c.nxt_capp_idx / 100) * c.stk_curr_conv
			WHERE s.idx_code = c.idx_code and c.to_adjust = 1
		";
        $this->db->query($sql);

        $sql = "
			UPDATE idx_specs
			SET idx_divisor_NXT = idx_divisor * idx_mcap_nxt / idx_mcap
			WHERE to_adjust = 1
		";
        $this->db->query($sql);


        $content = date("d-m-Y h:i:s") . " | Calculation composition | {$_SERVER['REMOTE_ADDR']} \n";
        file_put_contents("assets/log/file_log.txt", $content, FILE_APPEND);
    }

    function randomFloat($min = 0, $max = 1) {
        return number_format($min + mt_rand() / mt_getrandmax() * ($max - $min), 2);
    }

    function next_day() {
        $sql = "
		UPDATE idx_ca as a, idx_composition as c
		SET c.nxt_shares_idx = a.nxt_shares_idx,
		    c.nxt_float_idx = a.nxt_float_idx,
			c.nxt_capp_idx = a.nxt_capp_idx,
			c.nxt_price = a.nxt_price,
			c.nxt_adjust = a.nxt_adjust
		WHERE
		    c.idx_code = a.idx_code and
		    c.stk_code = a.stk_code and
			c.dates = a.dates
		";
        $this->db->query($sql);

        $sql = "
		UPDATE idx_composition
		SET to_adjust = 1
		WHERE
			nxt_adjust <> 0 or
			nxt_price <> 0 or
			nxt_capp_idx <> 0 or
			nxt_float_idx <> 0 or
			nxt_shares_idx <> 0
		";
        $this->db->query($sql);


        // tinh next day cho chi so con
        $sql = "select * from idx_specs as s, idx_ca a where s.idx_code = a.idx_code and a.adj_curr = '1' ";
        $data = $this->db->query($sql)->result_array();
        //viewarr($data);
        if ($data)
            foreach ($data as $item) {
                $sql = "select id from idx_composition where idx_code in (select idx_code from idx_specs where idx_mother = '{$item['idx_code']}' and idx_type = '{$item['idx_type']}' and stk_code = '{$item['stk_code']}')";
                $data1 = $this->db->query($sql);
                foreach ($data1 as $item1) {
                    // cap nhat nxt_shares_idx
                    $sql = "Update idx_composition
						Set nxt_shares_idx = '{$item['nxt_shares_idx']}'
						Where id = {$item1['id']}
						";
                    $this->db->query($sql);
                }
            }

        $content = date("d-m-Y h:i:s") . " | Next day | {$_SERVER['REMOTE_ADDR']} |  \n";
        file_put_contents("assets/log/file_log.txt", $content, FILE_APPEND);
    }

    function ins_intraday_compo() {
        $specs = $this->db->get_where("idx_specs", array('calculs' => 1))->result_array();
        foreach ($specs as $key => $row) {
            unset($specs[$key]['id']);
            $this->db->insert("idx_intraday", $specs[$key]);
        }

        $compo = $this->db->get("idx_composition")->result_array();
        foreach ($compo as $key => $row) {
            unset($compo[$key]['id']);
            $this->db->insert("idx_compo_intraday", $compo[$key]);
        }
        $content = date("d-m-Y h:i:s") . " | Insert idx_intraday, compo_intraday | {$_SERVER['REMOTE_ADDR']} |  \n";
        file_put_contents("assets/log/file_log.txt", $content, FILE_APPEND);
    }

    function get_idx_specs() {
        $this->db->select('idx_code,idx_mcap,id,idx_curr,idx_base,idx_divisor,idx_type,idx_mother,idx_plast');
        $this->db->where('IDX_TYPE', 'R');
        return $this->db->get('idx_specs')->result_array();
    }

}
