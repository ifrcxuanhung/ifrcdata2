<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* * ****************************************************************** */
/*     Client  Name  :  IFRC                                      */
/*     Project Name  :  cms v3.0                                     */
/*     Program Name  :  home.php                                      */
/*     Entry Server  :                                               */
/*     Called By     :  System                                       */
/*     Notice        :  File Code is utf-8                           */
/*     Copyright     :  IFRC                                         */
/* ------------------------------------------------------------------- */
/*     Comment       :  controller home                             */
/* ------------------------------------------------------------------- */
/*     History       :                                               */
/* ------------------------------------------------------------------- */
/*     Version V001  :  2012.08.14 (Tung)        New Create      		*/
/* * ****************************************************************** */

class Calendar_page extends Admin{
	public function __construct(){
		parent::__construct();
		$this->load->Model('calendar_page_model', 'mcalendar');
	}
	public function index() {
		$day = $this->input->get('day'); 
		$month = $this->input->get('month'); 
		$year = $this->input->get('year');

		if($day == "") $day = date("j"); 

		if($month == "") $month = date("m"); 

		if($year == "") $year = date("Y"); 
		$currentTimeStamp = strtotime("$year-$month-$day");
		$this->data->currentTimeStamp = $currentTimeStamp; 
		$this->data->monthName = date("F", $currentTimeStamp); 
		$this->data->numDays = date("t", $currentTimeStamp); 
		$this->data->year = $year;
		$this->data->month = $month;
		$this->data->m = date('M', $currentTimeStamp);

		//manage events
		$this->data->events = $this->mcalendar->findEventByDate($month, $year);




        $this->data->title = 'List user group';
        $this->template->write('title', 'User group');
		
        $this->template->write_view('content', 'calendar_page/calendar_page_index', $this->data);
        $this->template->render();
    }

    public function listCurrDate(){
    	if($this->input->is_ajax_request()){
	    	$response = $this->mcalendar->listCurrDate();
	    	echo json_encode($response);
	    }
    }
}