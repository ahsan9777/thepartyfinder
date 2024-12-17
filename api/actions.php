<?php
class Actions {
	public $recordsPerPage = '50';
    public $recordsPerPage2 = '50';
    private $main;
    private $functions;
    private $mailer;

    public function __construct(Main $main, Functions $functions, Mailer $mailer){
        $this->main = $main;
        $this->functions = $functions;
        $this->mailer = $mailer;
    }

    public function get_category_bk($params){
        
        $retValue = $this->main->get_category_bk($params);
        return $retValue;
    }
    
    public function get_nav_event($params){
        
        $retValue = $this->main->get_nav_event($params);
        return $retValue;
    }
    
    public function get_nav_event_weekly($params){
        
        $retValue = $this->main->get_nav_event_weekly($params);
        return $retValue;
    }
    
    public function get_post($params){
        
        $retValue = $this->main->get_post($params);
        return $retValue;
    }
    
    public function get_weekly_post($params){
        
        $retValue = $this->main->get_weekly_post($params);
        return $retValue;
    }
    
    public function get_story($params){
        
        $retValue = $this->main->get_story($params);
        return $retValue;
    }
    
    
    public function get_venue($params){
        
        $retValue = $this->main->get_venue($params);
        return $retValue;
    }
    
    public function get_top_venue($params){
        
        $retValue = $this->main->get_top_venue($params);
        return $retValue;
    }
    
    public function get_ladies_night($params){
        
        $retValue = $this->main->get_ladies_night($params);
        return $retValue;
    }
    
    public function get_video($params){
        
        $retValue = $this->main->get_video($params);
        return $retValue;
    }
    
    public function get_pdf($params){
        
        $retValue = $this->main->get_pdf($params);
        return $retValue;
    }
    
    public function form_submit($params){
        
        $retValue = $this->main->form_submit($params);
        return $retValue;
    }
    
    public function email_test($params){
        
        $retValue = $this->main->email_test($params);
        return $retValue;
    }

    public function get_title($params){
        
        $retValue = $this->main->get_title($params);
        return $retValue;
    }

    
}