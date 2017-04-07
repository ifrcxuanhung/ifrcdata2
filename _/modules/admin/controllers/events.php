<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Events extends Admin {

    protected $data;

    function __construct() {
        parent::__construct();
        $this->load->helper(array('my_array_helper', 'form'));
        $this->load->model('events_model', 'mevents');
    }

    function index($time = ''){
        if($this->input->is_ajax_request()){
            if(!in_array($time, array('history', 'today', ''))){
                $output = $this->mevents->listEvent('','', $time);
            }else{
                if($this->input->get('type')){
                    $output = $this->mevents->listEvent($this->input->get('type')); 
                }else{
                    $output = $this->mevents->listEvent('',$time); 
                }
            }
            $this->output->set_output(json_encode($output));
        }else{
            $this->template->write_view('content', 'events/index', $this->data);
            $this->template->write('title', 'Events');
            $this->template->render();
        }
    }

    function prepare($time = ''){
         if($this->input->is_ajax_request()){
            if(!in_array($time, array('history', 'today', ''))){
                $output = $this->mevents->listEventPrepare('', $time);
            }else{
                $output = $this->mevents->listEventPrepare($time);
            }
            $this->output->set_output(json_encode($output));
        }else{
            $this->template->write_view('content', 'events/prepare', $this->data);
            $this->template->write('title', 'Events Prepare');
            $this->template->render();
        }
    }

    function view($time = ''){
         if($this->input->is_ajax_request()){
            if(!in_array($time, array('history', 'today', ''))){
                $output = $this->mevents->listEventView('', $time);
            }else{
                $output = $this->mevents->listEventView($time);
            }
            $this->output->set_output(json_encode($output));
        }else{
            $this->template->write_view('content', 'events/view', $this->data);
            $this->template->write('title', 'Events View');
            $this->template->render();
        }
    }

    function corporate() {
        $this->data->data_type = $this->mevents->get_data_type();
        $this->template->write_view('content', 'events/events_list', $this->data);
        $this->template->write('title', 'Corporate');
        $this->template->render();
    }

    function add(){
        $id = $this->uri->segment(4);
        $data_info = $this->mevents->get_data($id);
        $this->data->info = $data_info;
        $info_type = $this->mevents->get_data_type_2();
        $this->data->info_type = $info_type;
        $this->data->info_check = $this->mevents->get_data_type_3($id);
        if($this->input->post('ok') != ''){
            if($this->input->post('type')){
                $data_type = $this->input->post('type');
                $ticker = $this->input->post('ticker');
                $market = $this->input->post('market');
                $date_ann = $this->input->post('date_ann');
                $this->mevents->check_type($id);
                foreach($data_type as $type){
                    $data_insert = array(
                        'news_id' => $id,
                        'evt_id' => $type,
                        'ticker' => $ticker,
                        'market' =>  $market,
                        'date_ann' => $date_ann
                    );
                    $this->mevents->insert($data_insert);
                }

                $data_update = array(
                    'event_type' => $info_type[$data_type[0]-1]['evname_en'],
                    'status' => 'CLASSIFIED'
                );
                $this->mevents->update($data_update,$id);
            }else{
                $data_update = array(
                    'status' => 'NO'
                );
                $this->mevents->update($data_update,$id);
            }
            if($data_info['date_ann'] == date('Y-m-d')){
                redirect(admin_url().'events/index/today');
            }else{
                redirect(admin_url().'events/index/history');
            }
        }
        $this->template->write_view('content', 'events/add', $this->data);
        $this->template->write('title', 'Add');
        $this->template->render();
    }

    function edit(){
        $id = $this->uri->segment(4);
        $this->data->info_type = $this->mevents->get_data_type_2();
        $info_check = $this->mevents->get_data_type_3($id);
        $this->data->info_check = $info_check;
        $this->data->info = $this->mevents->get_data_2($id,$info_check);
        if($this->input->post('ok') != ''){
            $data_type = array_unique($this->input->post('type'));
            foreach($data_type as $type){
                $data_check = array(
                    'type' => $type,
                    'ticker' => $this->input->post(str_replace(' ','_',$type).'_ticker'),
                    'market' => $this->input->post(str_replace(' ','_',$type).'_market')
                );
                $this->mevents->check_data($data_check);
                $arr_type = explode(' ',$type);
                $data_insert = array();
                foreach($_POST as $key => $value){
                    $arr_key = explode('_',$key);
                    if(count($arr_type) == 2){
                        if($arr_key[0] == $arr_type[0] && $arr_key[1] == $arr_type[1]){
                            unset($arr_key[0],$arr_key[1]);
                            $key_final = implode('_',$arr_key);
                            $data_insert['events_type'] = $type;
                            $data_insert[$key_final] = $value;
                        }
                    }else{
                        if($arr_key[0] == $arr_type[0]){
                            unset($arr_key[0]);
                            $key_final = implode('_',$arr_key);
                            $data_insert['events_type'] = $type;
                            $data_insert[$key_final] = $value;
                        }
                    }
                    
                }
                $this->mevents->insert_2($data_insert);
            }
            $data_update = array(
                'status' => 'CONFIRM'
            );
            $this->mevents->update($data_update,$id);
            redirect(admin_url().'events/prepare');
        }
        $this->template->write_view('content', 'events/edit', $this->data);
        $this->template->write('title', 'Edit');
        $this->template->render();
    }

    function get_data_by_filter(){
        if($this->input->is_ajax_request()){
            $data_value = $this->input->post('value_type');
            $output = $this->mevents->listEventsByFilter($data_value);
            $this->output->set_output(json_encode($output));
        }
    }

    function get_type(){
        if($this->input->is_ajax_request()){
            $output = $this->mevents->get_type();
            $this->output->set_output(json_encode($output));
        }
    }
}
