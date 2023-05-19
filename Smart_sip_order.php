<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Smart_sip_order extends MY_Controller {

    protected $setting;
    protected $liquid_sch_arr;
	public $filename;
    public function __construct() {
        parent::__construct();
       // print_r($this->session); die;
        $this->load->model('mutual_funds_model', 'mf');
        $this->load->library('Bsestar_mutualfund');
        $this->load->library('mongo_db');
        $this->mongo_db->reconnect(['config_group' => 'default']);
        $result = $this->mongo_db->where(array('status' => 1))->get('smart_sip_settings')[0];

        $this->setting = $result;
        $this->liquid_sch_arr = array('400004'=>'BSL153G-GR',
                                '400013'=>'HDLFGN-GR',
                                '400015'=>'ICICI1565-GR',
                                '400025'=>'RELLFTPI-GR',
                                '400027'=> 'SBI072SG-GR',
                                '400030'=> 'MONG-GR',
                                '400021'=> 'RGF-LFSG-GR',
                                '400024'=> 'QTLFRG-GR',
                                '400032'=> 'UTICPIGG-GR',
                                '400028'=> 'IDFCFCCG-GR',
                                '400034'=> 'BAFLFRG-GR',
                                '400009'=> 'DSP52-GR',
                                '400019'=> 'K462-GR',
                                '400033'=> 'MAFCFGPG-GR',
                                '400012'=> '208-GR',
                                '400029'=> 'SULFPG-GR',
                                '400042'=> 'MOLFGP-GR',
                                '400014'=> 'HSBCCFPG-GR',
                                '400035'=> 'EDILSG-GR',
                                '400007'=> 'LT154L-GR',
                                '400040'=> 'AXFCFGP-GR',
                                '400047'=> 'IIFLLRG-GR',
                                '400044'=> 'DWSCFSG-GR',
                                '400001'=> 'BPLQIG-GR',
                                '400041'=> 'PLFSG-GR',
                                '400043'=> 'IDBI-LFGP-GR',
                                '400049'=> 'PPLFG-GR',
                                '400017'=> 'LFRG-GR',
                                '400020'=> 'LCLFGP-GR',
                                '400006'=> 'CRLISG-GR',
                                '400048'=> 'INLFGP-GR',
                                '400054'=> 'MHMLFRG-GR',
                                '400045'=> 'UNLGR-GR',
                                '400055'=> 'YELGR-GR',
                                '400056'=> 'ITLFGP-GR',
                                '400010'=> 'QULFGP-GR',
                                '400057'=> 'TSLFRG-GR'
                             );
        //print_r($this->setting);
		// die;

        //ini_set('max_execution_time', 0);
		$this->is_mfi_for_stp_enabled = getSettingsTableValue('IS_MFI_FOR_STP_ENABLED');
		$this->is_mfi_for_stp_enabled = intval($this->is_mfi_for_stp_enabled);

		if($this->is_mfi_for_switch_enabled != 1){
			$this->is_mfi_for_switch_enabled = 0;
			$this->bsestar_mutualfund_enviroment = 1;
			$this->order_env = 'MFD';
		}
		else{
			// as ledger is enabled then MFI orders will be placed
			$this->bsestar_mutualfund_enviroment = 2;
			$this->order_env = 'MFI';
		}
    }

    function index(){
      
    } 

    function SmartSuperSipStats()
    {   
      $ascheme_code = $this->input->post('scheme_code');
       $smartsip = $this->mongo_db->where(array('schemecode'=>$ascheme_code,'years'=>5))->get('smart_sip_summary_table_momentum')[0];
       $supersip = $this->mongo_db->where(array('schemecode'=>$ascheme_code,'years'=>5))->get('sip_summary_table_topup')[0];
       echo json_encode([$smartsip,$supersip]);
    }

       /* Fn to place order in SIP master table and to be executed later on start date */
       public function placeOrder($params = [])
       {
         //   error_reporting(E_ALL);
         // ini_set('display_errors', 'on');
        // x($_POST['additional_investment']);
         $post_param = $params; 
         $data = $_POST;
         if(empty($params)){
          $params = $_POST;
         }

        $partner_id = $params['partner_id'];//partner_id 
        $source = $params['source'];//source 
        $client_id = $params['client_id'];
        if(empty($client_id)){
          $client_id = $this->session->client_id;
        }

        $scheme_code = $params['scheme_code'];
        $amount = $params['installment_amount_smart'];

        $getmandateid = $this->getMandateId($client_id,$amount);
        $mandateid = (!empty($getmandateid['mandate_id']))?$getmandateid['mandate_id']:"";
        $data_in['mandate_id'] = $mandateid;
        $data_in['mandate_type'] = ($getmandateid['mandatetype']=='emandate')?'emandate':"normal";
        
        
        $bankinfo = $this->getBankInfo($client_id);
        //$liquid_arr = $this->getLiquidSchemeForSmartSIP($scheme_code);
        //$data = array_merge($data,$liquid_arr);
        // SmartSip Liquid Scheme code
        if($params['super_sip_flag']==0){
                $liquid_arr = ['liquid_scheme_code' => $params['liquid_scheme_code']."-L0",
                    'accord_liquid_scheme_code' => $params['accord_liquid_scheme_code']
                  ];
        } else{
                $liquid_arr = ['liquid_scheme_code' => '','accord_liquid_scheme_code' => ''];
        }
 
        if(empty($client_id)){
            redirect(base_url());
        }else{
            $scheme_name = $params['scheme_name'];
            $data_in['client_id'] = $client_id;
            $data_in['order_id'] = '';
            $data_in['accord_scheme_code'] = $this->getAccordSchemeCode($scheme_code);
            $data_in['scheme_code'] = $scheme_code;
            $data_in['buy_sell'] = 'P';
            $data_in['buy_sell_type'] = 'FRESH';
            $data_in['amount'] = $amount;
            $data_in['quantity'] = '';
            $data_in['start_date'] = date('Y-m-d', strtotime($params['date']));
            $data_in['additional_investment'] = $params['additional_investment'];
            
            if(!empty($post_param)){
              if($getmandateid['mandate_type']=='OTM'){
                $data_in['is_otm'] = 1;
              }else{
                $data_in['is_otm'] = 0;
              }
            }else{
              $data_in['is_otm'] = $params['is_otm'];
            }
            
            if($params['super_sip_flag'] == 1){
              $flagtype = 'SIP+';
              $data_in['type'] = 'supersip';
              $data_in['additional_investment'] = 1;
            }
            else{
              $flagtype = 'SMARTSIP';
            }
            $data_in['trans_mode'] = ($params['trans_mode']=='mob')?'mob':'web';

            unset($data['date']);
            $orderTypeForExpiry = ''; //added to check order type - prashant
            $schemeTypeForExpiry = strtolower($params['scheme_type']); //added to check order type - prashant
            $folio = $this->checkFolioNo($scheme_code,$client_id);
            //echo $folio; die;
            if(!empty($folio)){
                $data_in['buy_sell_type'] = 'ADDITIONAL';
                $data_in['folio_number'] = $folio;
            }else{
                $folio = '';
            }

            $data_in = array_merge($data_in,$liquid_arr);
            if(empty($partner_id) && empty($source)){
                $partner_id = $this->session->userdata('created_by');
                $source = $this->session->userdata('source');
                if(!empty($partner_id) && !empty($source)){
                    $data_in['created_by'] = $partner_id;
                    $data_in['source']     = $source;
                }
                else{
                    $data_in['created_by'] = $client_id;
                    $data_in['source']     = $client_id;
                }   
            }
            else{
                $data_in['created_by'] = $partner_id;
                $data_in['source']     = $source;
            }

                  //broker code
                  $broker_id = $this->getBrokerCode($client_id); //sub broker id for b2b2c

                  if(!empty($broker_id) ){
                    $data_in['broker_id'] = $broker_id;
                  }

                  //add code for send communication to partner
                  if($broker_id !='sam_0000'){
                        $partnerDetails = $this->getPartnerBasicDetails($broker_id);
                   }
              

                $next_month_day = date('Y-m-d',strtotime($morder['start_date']."+1 month") );
                $next_sip_t_day = $this->filterHolidays($data_in['start_date']);
                $data_in['next_t_day']     = $next_sip_t_day['tplus0'];
                $data_in['t_1day']         = $next_sip_t_day['tminus1'];
                $data_in['t_3day']         = $next_sip_t_day['tminus3'];
                
                $min_eq_amt_flag = $this->getSchemeMinValues($scheme_code,$client_id);
                $data_in['minimum_purchase_amount'] = $min_eq_amt_flag['Minimum_Purchase_Amount'];
                $transaction_modesess = $this->session->userdata('transaction_mode');
                $params = [];
                $params['env']              = 'default';
                $params['select_data']      = "transaction_mode";
                $params['table_name']       = 'mf_client_master';
                $params['where']            = TRUE;
                $params['where_data']       = ['client_id' => $client_id];

                $params['return_array']     = True;
               // $params['print_query_exit'] = TRUE;
                $transaction_modedetails    = $this->mf->get_table_data_with_type($params);
                $db_trans_mode= $transaction_modedetails[0]['transaction_mode'];

                if($transaction_modesess){
                  $transaction_mode = $transaction_modesess;
                }
                else{
                  $transaction_mode = $db_trans_mode;
                }
                $data_in['order_mode'] = $transaction_mode; //add transaction mode
                $insparam               = [];
                $insparam['env']        = 'db';
                $insparam['table_name'] = 'mf_master_smart_sip';
                $insparam['data']       = $data_in;
                //$insparam['print_query_exit'] = TRUE;
                //$insparam['batch']      = TRUE;
                $return = $this->mf->insert_table_data($insparam);
                // it for policy report
                $send_plocy=json_encode(array('smartsip_order_count'=>1));
                $this->update_policy_data($send_plocy);
                $this->campaign_trigger_event($data_in,$data_in['type']);
                // end;

                if($return){
                    $response = array('order_status' => 'success', 'data' => $res, 'success_msg' => 'Your Order has been successfully placed');
                }else{
                     $response = array('order_status' => 'fail', 'data' => 'fail', 'success_msg' => 'Your Order has been Failed');
                     die;
                }
                $orderno = $return;
                $order_date = date("Y-m-d");



               $res[7] = '0';
               $res[5] = '';

            $client_data = $this->fetchClientDetails($client_id);
            $client_data[0]->IFSC = substr($client_data[0]->IFSC,0,4);
            $client_data[0]->IFSCNew = substr($client_data[0]->IFSCNew,0,4);

            if(!empty($client_data)){
                $client_data = $client_data[0];
                $atom_bankcodes = $this->getBankCodesNetBankingAtom([$client_data->IFSC, $client_data->IFSCNew]);
             }
             $client_data = json_decode(json_encode($client_data),true);
             //x($client_data);
                //$template = 'smart_sip_order_confirmation';
                $encrypt_client_id  = $this->mf->encrypt_decrypt('encrypt',$client_id);
                 $encrypt_amount_id = $this->mf->encrypt_decrypt('encrypt',$amount);
                // $autologin_url     = RANK_MF_URL.'Mf_client_login/autoLoginmutualfundOrder/'.$encrypt_client_id.'/mandate-registration?smart_mandate_id=';

                 $autologin_url    = RANK_MF_URL.'mf_client_login/autoLoginmutualfund/'.$encrypt_client_id.'/otmmandate';
                  
                 $url=RANK_MF_URL_SHORT;
                 $sms_deficiency_url =  getUrlShort($url,$client_id,44);

                 $detail['parameters']['link'] = $deficiency_url;
                 //y($client_data['ClntMob']);
                 $detail['client_name'] = $client_data['ClntName'];
                 $detail['client_id'] = $client_id;
                 $detail['scheme_code'] = $scheme_code;
                 $detail['scheme_name'] = $scheme_name;
                 $detail['amount'] = $amount;
                 
                 $bank_accno = 'XXXXXX' . substr($bankinfo['client_acc_no'], strlen($bankinfo['client_acc_no']) - 4, 4); 
                 $detail['client_mobile'] = trim($client_data['ClntMob']);
                 $detail['client_email'] = $client_data['Email1'];
                 $detail['parameters']['Order-number'] = $orderno;
                 if(isset($data_in['type']) && $data_in['type'] == 'supersip'){
                       $word = 'SIP Plus';
                       if(!empty($mandateid)){
                            $template = "RankMF-SIPplus-OrderPlaced-1648218880";
                       }else{
                            $template = "RankMF-SIPplus-OrderIncomplete-r1-1648219099";
                       }
                       $detail['parameters']['Investment-amount'] = $amount;
                       $detail['parameters']['Scheme-name'] = $scheme_name;
                       $detail['parameters']['SIP-Plus-amount'] = $amount;
                       $detail['parameters']['Order-date'] = $order_date;
                       $detail['parameters']['Order-number'] = $orderno;
                       $detail['parameters']['Payment-mode'] = "BANK MANDATE";
                       $detail['parameters']['Bank-mandate-ID'] = $mandateid;
                       $detail['parameters']['Bank-name'] = $bankinfo['bank_name'];
                       $detail['parameters']['Bank-account-number'] = $bank_accno;
                       $detail['parameters']['SIP-plus-date'] = $data_in['start_date'];
                       $detail['parameters']['Mandate-link'] = $autologin_url;
                        
                 }
                 else{
                    if($data_in['additional_investment'] == 1){
                       $word = 'SmartSIP Plus';
                       $detail['parameters']['SmartSIP-Plus-amount'] = $amount;
                       $detail['parameters']['SmartSIP-Plus-date'] = $data_in['start_date'];
                       $detail['parameters']['Next-SmartSIP-Plus-date'] = $data_in['next_t_day'];
                       $detail['parameters']['Payment-mode'] = "BANK MANDATE";
                       
                       if(!empty($mandateid)){
                            $template = "rankmf-smartsip-plus-orderplaced-1648131858";
                       }else{
                            $template = "rankmf-smartsip-plus-orderincomplete-r1-1648132487";
                       }
                       
                    }else{
                       $word = 'SmartSIP';
                       $detail['parameters']['SmartSIP-amount'] = $amount;
                       $detail['parameters']['SmartSIP-date'] = $data_in['start_date'];
                       $detail['parameters']['Next-SmartSIP-date'] = $data_in['next_t_day'];
                       if(!empty($mandateid)){
                            $template = "rankmf-smartsip-orderplaced-1648122756";
                       }else{
                            $template = "rankmf-smartsip-orderincomplete-r1-1648127013";
                       }
                       
                    }

                    $detail['parameters']['Investment-amount'] = $amount;
                    $detail['parameters']['Equity-Scheme']       = $scheme_name;

                    if($mandateid){
                        $detail['parameters']['Order-date'] = $order_date;
                        $detail['parameters']['Bank-mandate-ID'] = $mandateid;
                        $detail['parameters']['Bank-name'] = $bankinfo['bank_name'];
                        $detail['parameters']['Bank-account-number'] = $bank_accno;
                        

                    }else{

                        $detail['parameters']['Payment-mode'] = "BANK MANDATE";//$data_in['next_t_day'];
                        $detail['parameters']['Mandate-link'] = $autologin_url;
                    } 
                 }

             $detail['channel'] = $template;
             $new_scheme_name=$this->trmipschme($scheme_name);
             //$detail['sms_text'] = "Dear Customer, Your ".$word." Order for Scheme ".$new_scheme_name." Amount: Rs ".$amount." has been successfully placed.Team RankMF";
             $sms_amount = "Rs. ".$amount;
             if(!empty($mandateid)){ 
                $detail['sms_text'] = " Hi, Your ".$word." order vide ".$orderno." for ".$new_scheme_name." of ".$sms_amount." is successfully placed. Your next SmartSIP date is ".$data_in['next_t_day'].".  - Team RankMF";
             }else{
                $detail['sms_text'] = "Hi, Your ".$word." order for ".$new_scheme_name." of ".$sms_amount." is incomplete. Please set up your bank mandate to complete your ".$word." order. ".$sms_deficiency_url." - Team RankMF"; 
             }
             $detail['templateName'] = $template;
              
                
             $detail['parameters']['ToMail'] = $client_data['Email1'];
             $detail['parameters']['Name'] = $client_data['ClntName'];
             $detail['parameters']['scheme_name'] = $scheme_name;
             $detail['parameters']['amount'] = $amount;
             $detail['parameters']['type'] = $word;
             $detail['parameters']['day_date'] = date('d', strtotime($data_in['start_date']));
             $detail['parameters']['process_date'] = date('d/m/Y',strtotime($this->tPlusDaysCalculation('','','','basket')['expireDate']));
             $detail['parameters']['date'] = date('d/m/Y');

             if(!empty($partnerDetails)){
                $detail['partner_email'] = $partnerDetails->email;
                $detail['partner_name'] = $partnerDetails->name;
             }

            $this->sendOrderConfirmationDetails($detail);

             echo json_encode($response); die;


            //$detail['parameters']['link'] = 'local';
            // $this->sendOrderConfirmationDetails($detail);
             $response = array('order_status' => 'success', 'data' => $res,'msg' => $res[6], 'success_msg' => $success_msg);

               $this->load->view('jsonresponse', ['data' => $res])  ;
               die;


        }

       }

       public function getChartData($ascheme_code, $l_ascheme_code, $start_date)
       {
        $result_data = array();
        $temp_array = array();
        $return_data = array();
        //return 'herere';
       // $this->mongo_db->where(array('schemecode'=>$ascheme_code,'lq_schemecode'=>$l_ascheme_code))
       //                ->deleteAll('mf_chart_smart_sip',$result_data);

        while(strtotime($start_date) < strtotime('2018-09-01')){

            $where_navdate = $this->getMongoDate($start_date);


             $result= $this->mongo_db->where(array('nav_date' => $where_navdate, 'schemecode' => $ascheme_code ))->get('mf_smart_sip_eq_momemtum')[0];

             $date_init = $start_date;
            while (empty($result)){
                $date_init = date('Y-m-d', strtotime($date_init."+1 day"));
                $navdate = $this->getMongoDate( $date_init);
                $result= $this->mongo_db->where(array('nav_date' => $navdate, 'schemecode' => $ascheme_code ))->get('mf_smart_sip_eq_momemtum')[0];

              }
             // print_r($result); die;

             if(empty($result)){
                print_r($start_date); die();
             }
             $mos =  $result['margin_safety'];

             $signal = $this->getSignals($mos);

             $action = $signal['baseSafetyLavel']; //'sell'; //'invest','sell','skip','double_invest'
             $rbalance = $signal['rebalanceSafetyLevel'];//'very_aggressive'; //'very_aggressive', 'aggressive'
             $rebalance_sell = $signal['rebalanceSafetyLevel'];
             if(empty($unique_id)){
               $unique_id =  $this->last_insert_id();

             }

            //$current_units = $this->getLastSmartTransactionScheme($unique_id);
            $current_units = $temp_array;

            //$current_units['schemecode']    =  $ascheme_code;
            //$current_units['lq_schemecode'] =  $l_ascheme_code;
            $current_units['opening_liquid_units'] = $current_units['closing_liquid_units'];
            $current_units['opening_eq_units'] = $current_units['closing_eq_units'];
            $current_units['buy_eq_units'] = $current_units['rebalance_buy_liquid_units'] = '';
            $current_units['base_signal'] =  $action;
            $current_units['master_id'] = $unique_id;
            $current_liquid_nav = $this->getCurrentNav($l_ascheme_code,$start_date);
            $current_eq_nav = $this->getCurrentNav($ascheme_code,$start_date);
            $current_units['liquid_nav'] = $current_liquid_nav;
            $current_units['eq_nav'] = $current_eq_nav;
            $current_units['sip_date'] = $start_date;
            $current_units['margin_of_safety'] = $mos;

            $bse_eq_sc = $this->getAccordSchemeCode($ascheme_code);
            $bse_lq_sc = $this->getAccordSchemeCode($l_ascheme_code);
            $min_eq_amt_flag = $this->getSchemeMinValues($bse_eq_sc); //storing min purchase, multiplier flags
            $min_liquid_amt_flag = $this->getSchemeMinValues($bse_lq_sc);//storing min purchase, multiplier flags

            $morder['amount'] = 10000;
            $additional_investment = 0;
            // print_r($action); die;

              if($action == 'invest'){
                
                  $morder['units'] = $morder['amount'] / $current_eq_nav;
                  $current_units['buy_eq_units'] =  $morder['units'] ;
                  $current_units['closing_eq_units'] +=  $morder['units'];

              }elseif($action == 'double_invest'){
                        if($additional_investment){
                          $morder['amount'] = $this->setting['base_aggr_invest_multiplier'] * $morder['amount']; //additional investment from client
                        }
                            $total_sip = $morder['amount'];
                         //sell SIP amt in liquid if true and available
                              $c_unitsliquid = mround($current_units['closing_liquid_units'],2);
                              $units_sell_rb = $units_sell = $rebalance_add_eq_units = 0;
                             // print_r($c_unitsliquid); print_r($min_liquid_amt_flag['Minimum_Redemption_Qty']); die;
                            if(empty($c_unitsliquid)){
                                $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                $current_units['closing_eq_units'] +=  $morder['units'];
                                $morder['liquid_units'] = 0;
                                 $morder['buy_eq_units'] =  $morder['units'];
                                //$this->purchaseSmartLumpsum($morder,$current_units); //by default equity

                            }else{
                                $sipAmtUnits = mround($morder['amount']/$current_liquid_nav, 2); ;

                                if(($current_liquid_nav * $current_units['closing_liquid_units'] ) >= $morder['amount']){ //checking if sip amt in liquid units worth is available

                                   if($additional_investment){

                                  }else{
                                    $rebalance_add_eq_amt = intval($sipAmtUnits*$current_liquid_nav);
                                    $rebalance_add_eq_units =  $rebalance_add_eq_amt / $current_eq_nav;
                                     $current_units['closing_liquid_units'] -=  $sipAmtUnits; //updating closing units(regular sip amt deducted in units)

                                  }

                                      //xxxx//$total_sip += $morder['amount'];

                                     // print_r(  $current_units['closing_liquid_units'] ); die;
                                    if(round($current_units['closing_liquid_units']) >= 1 ){

                                        //sell % of units depending on Rebalance signal (~50% or ~100%)
                                     if($rbalance == 'very_aggressive'){ //sell ~100% of liquid units ,invest in regular SIP
                                        $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_vaggr_invest_per'],1) ;
                                       $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units

                                      $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                       // $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units);
                                        //print_r($total_sip); die;
                                         $total_sip += ($units_sell_rb * $current_liquid_nav); // calculating price from liquid units sold
                                       }
                                       else if($rbalance == 'aggressive'){ //sell ~50% of liquid units, invest in regular SIP
                                            $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_aggr_invest_per'],1) ;
                                          //  print_r($current_units['closing_liquid_units']); print_r($this->setting['rebalance_aggr_invest_per']); die;
                                            $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                            //print_r($units_sell); die;
                                            $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                           // $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units);
                                            $total_sip += ($units_sell_rb * $current_liquid_nav); // calculating price from liquid units sold
                                           }
                                       }

                                      }else{ //sell whatever remaining units are available

                                       echo '<pre>';  echo 'else sell remaining';
                                          $units_sell_rb = mround($current_units['closing_liquid_units'],2) ;
                                         // print_r($current_units['closing_liquid_units']); die;
                                        //  $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                            $all_sold_units = ['sell_liquid_units' => $units_sell_rb, 'rebalance_units' => 0 ]; //rebalance_units = units minused from closing liquid
                                            //$this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell_rb ,$morder['order_mode'], $current_units,$all_sold_units);
                                            $total_sip += ($units_sell_rb * $current_liquid_nav);

                                      }
                                    //purchase regular SIP + liquid units sold
                                      //xxxxx $morder['amount'] = $total_sip; //amt recalulated
                                      $morder['units'] = $total_sip / $current_eq_nav; //units to be bought
                                    //  $morder['closing_eq_units'] = $morder['amount'] / $current_eq_nav;
                                      $morder['liquid_units'] = 0;
                                       $eq_units_buy_amt = intval($current_liquid_nav*$units_sell_rb); // calculating amt in rs from units fold in equity
                                      $current_units['rebalance_buy_eq_units'] = $eq_units_buy_amt / $current_eq_nav ;
                                       $current_units['closing_liquid_units'] -= $units_sell_rb; // in this condn closing units needs to be updated
                                    //    $current_units['closing_liquid_units'] -= $sipAmtUnits; // - 10k amt from liquid

                                    // x($current_units['rebalance_buy_eq_units']);


                                      $current_units['buy_eq_units'] += ($morder['amount'] / $current_eq_nav);
                                     // $morder['units'] =  $current_units['buy_eq_units'];
                                  //  $current_units['buy_eq_units'] = $morder['units'] - $current_units['rebalance_buy_eq_units'] ;

                                    $current_units['closing_eq_units'] += $current_units['buy_eq_units'];
                                    $current_units['closing_eq_units'] += $current_units['rebalance_buy_eq_units'] ;
                                    $current_units['closing_eq_units'] += $rebalance_add_eq_units ;
                                    // $current_units['closing_eq_units'] = $current_units['closing_liquid_units'] - $sipAmtUnits;

                                    echo $start_date; print_R($current_units); print_R($all_sold_units);
                                    echo '--------------------';
                                     // $this->purchaseSmartLumpsum($morder,$current_units);
                                }

                            }
                        elseif($action == 'sell'){ // sell eq and buy liquid
                            $total_sip = $morder['amount'];
                            $eq_sell_units_rb = $liquid_units_buy_amt = 0;
                           $eq_sell_units_eq = $current_units['closing_eq_units'] * $this->setting['base_sell_units_per']; //selling specified % of equity units from setting (approx 15%)
                           //-----------//$current_units['closing_eq_units'] -= $eq_sell_units_eq;
                            if(true){
                        //  if($current_units['closing_eq_units'] > $min_eq_amt_flag['Minimum_Redemption_Qty'] ){

                             if($rebalance_sell == 'very_aggressive_sell'){
                                $eq_sell_units_rb = $this->setting['rebalance_vaggr_sell_units_per'] * $current_units['closing_eq_units'];
                             }elseif($rebalance_sell == 'aggressive_sell'){
                              $eq_sell_units_rb = $this->setting['rebalance_aggr_sell_units_per'] * $current_units['closing_eq_units'] ;
                             }

                             $eq_sell_units = $eq_sell_units_rb + $eq_sell_units_eq;
                             $eq_sell_units = mround($eq_sell_units,1);
                             //print_r($this->setting['rebalance_aggr_sell_units_per']); echo '<pre>'; print_r($eq_sell_units_rb); die;
                             $current_units['closing_eq_units'] -= $eq_sell_units_rb; //updating equity units

                             $all_sold_units = ['sell_eq_units' => $eq_sell_units_eq, 'rebalance_sell_eq_units' => $eq_sell_units_rb];
                            // $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units );
                             $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                           }
                           $current_units['rebalance_buy_liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav ;
                            $liquid_units_buy_amt += $total_sip;
                            $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;

                            if(true){
                           //if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $liquid_units_buy_amt){ //min amt condition for lumpsum
                               // if(   ($liquid_units_buy_amt * 1000) % ($min_liquid_amt_flag['Purchase_Amount_Multiplier'] * 1000 )  == 0 ){ //multiple of amt multiple
                                     $morder['amount'] = $liquid_units_buy_amt;
                                     $morder['scheme_code'] = $morder['liquid_scheme_code'];
                                     $morder['liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav;
                                     $morder['units'] = 0;
                                    $current_units['closing_liquid_units'] = $current_units['closing_liquid_units'] + $current_units['buy_liquid_units'] + $current_units['rebalance_buy_liquid_units'] ;
                                    $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                                    // $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                               // }else{ //not satisfying multiple amt criteria

                                //}

                            }

                        }
                      else if($action == 'skip'){

                            $morder['scheme_code'] = $morder['liquid_scheme_code'];
                            $current_units['buy_liquid_units'] = $morder['amount'] / $current_liquid_nav;
                            $current_units['closing_liquid_units'] +=   $current_units['buy_liquid_units'];

                            $morder['units'] = 0;
                            //x($morder['buy_liquid_units']);
                           //$this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ



                      }

                        $insparam  =[];
                        $insparam['env']        = 'db';
                        $insparam['table_name'] = 'mf_chart_smart_sip';
                        $insparam['data']       = $current_units;
                        $this->mf->insert_table_data($insparam);


                    $result_data[] = $current_units;
                  //  $return_data[] = $current_units;
                    $temp_array = $current_units;

                    // if(count($result_data) == 100){
                    //     $this->mongo_db->insertAll('mf_chart_smart_sip',$result_data);
                    //     $result_data = array();
                    // }

                    $start_date = date("Y-m-d", strtotime($start_date . "+1 month" ) );

                   // / print_r($current_units);
               } //endwhile

                // if(!empty($result_data)){
                //     $this->mongo_db->insertAll('mf_chart_smart_sip',$result_data);
                //     $result_data = array();
                // }

                return $return_data;

       }

       //ACTUAL SMART SIP FN TO EXECUTE ORDERS REAL TIME on T DAY
       public function fetchExecuteOrdersReal($date ='', $fixed_date = '', $input_client_id = '')
       {

           /*fetching all orders for same day date*/
            $today = date('d');
            if(isset($_POST['date_oday'])){
              $tday = $_POST['date_oday'];
            }else{
              $tday = $date;//date('Y-m-d');


            }
            $fullday = date('Y-m-d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "ss.*, scheme_master.Redemption_Qty_Multiplier";
            $params['table_name']       = 'mf_master_smart_sip AS ss';
            $params['join']             = TRUE;
            $params['multiple_joins']   = TRUE;
            $params['join_table']       = array('mf_scheme_master as scheme_master');
            $params['join_on']          = array('ss.scheme_code = scheme_master.Scheme_Code');
            $params['join_type']        = array('INNER');
            $params['where']            = TRUE;
            $params['where_data']       = ['ss.next_t_day' => $tday, 'ss.order_status' => 2, 'ss.stop_redeem_request' => 0, 'ss.type' => 'smartsip', 'scheme_master.Purchase_Allowed' => 'Y', 'ss.pause_sip' => 0, 'ss.is_otm' => 0];

            // checking input parameter client_id present or not. If present then read only SmartSIP records for that specific client
            if(isset($input_client_id) && !empty($input_client_id)){
                $params['where_data']['ss.client_id'] = trim(strip_tags($input_client_id));
            }

            $params['return_array']     = True;
           // $params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);
          // x($master_orders);
            if(!empty($master_orders)){
            foreach($master_orders as $morder){
                $where_navdate = $this->getMongoDate($morder['t_1day']);
                
                if(isset($fixed_date) && !empty($fixed_date)){
                  $where_condn = array('schemecode' => $morder['accord_scheme_code']
                ,'nav_date' => $where_navdate
                 );
                }else{
                  $where_condn = array('schemecode' => $morder['accord_scheme_code']);
                }
                $result = $this->mongo_db->where($where_condn)->sort('nav_date', 'desc')->limit(1)->get('mf_smart_sip_eq_momentum')[0]; // getting Margin of Safety for todays date for schemecode
                // while (empty($result)){
                //     $date = date('Y-m-d', strtotime($date."+1 day"));
                //     $navdate = $this->getMongoDate( $date);
                //     $result= $this->mongo_db->where(array('nav_date' => $navdate, 'schemecode' => $morder['accord_scheme_code'] ))->get('mf_smart_sip_eq_momentum')[0];

                //   }

                 //x($result);
                if(empty($result)){
                  $response = array('order_status' => 'error', 'msg' => 'No Mosdex Data found for that day', );

                    $this->load->view('jsonresponse', ['data' => $response])  ;
                    die;
                }
                $mongo_nav_date   = (array)$result['nav_date'];
                $mongo_nav_date    = date('Y-m-d',$mongo_nav_date['milliseconds']/1000) ;

                $mos = $morder['margin_of_safety'] = $result['margin_safety'];
                //code for signal calculation
                $signal = $this->getSignals($result['margin_safety']);
               // x($signal);
                $action = $signal['baseSafetyLavel']; //'sell'; //'invest','sell','skip','double_invest'
                $rbalance = $signal['rebalanceSafetyLevel'];//'very_aggressive'; //'very_aggressive', 'aggressive'
                $rebalance_sell = $signal['rebalanceSafetyLevel']; //'aggressive_sell_sell'; // 'aggressive_sell', 'very_aggressive_sell_sell'
                $morder['base_signal'] = $action;
                //x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], $morder['id']);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                $current_units['base_signal'] =  $action;
                $current_units['order_mode'] = $morder['order_mode'];
                $current_units['margin_of_safety'] = $morder['margin_of_safety'];
                $current_units['mongo_date'] = $mongo_nav_date;

                // error_reporting(-1);
                // ini_set('display_errors', 1);
                $current_liquid_nav = $this->getCurrentNav($morder['accord_liquid_scheme_code']) ; //,date
                $current_liquid_nav_default = '4397.63'; //$this->getCurrentNav($morder['accord_liquid_scheme_code_default'], $date) ;
                $current_eq_nav = $this->getCurrentNav($morder['accord_scheme_code']); //date
               // print_r($current_liquid_nav); print_r($current_eq_nav);  die;
                $min_eq_amt_flag = $this->getSchemeMinValues($morder['scheme_code'],$morder['client_id']); //storing min purchase, multiplier flags
                $min_liquid_amt_flag = $this->getSchemeMinValues($morder['liquid_scheme_code'],$morder['client_id']);//storing min purchase, multiplier flags
                $liquid_scheme_redemption_multiplier = 0.001;
                $min_liquid_amt_flag_default = $this->getSchemeMinValues($morder['liquid_scheme_code_default'],$morder['client_id']);//storing min purchase, multiplier flags for default liquid
                //x($min_liquid_amt_flag_default);
                $lcheck = $this->checkIfLedgerSufficent($morder['amount'],$morder['id'], $morder['next_t_day']); //checking if amt sufficent for transaction from mandate + ledger
                //x($lcheck);
                $mcheck = $this->checkMFDMandate($morder['amount'], $morder['client_id']); //checking if approved MFD mandate for OTM is available incase of insuff led balance
               // x($mcheck);
            // print_r($min_eq_amt_flag); print_r($min_liquid_amt_flag); die;
                $additional_investment = $morder['additional_investment'];
                if($action == 'invest'){
                  if($lcheck['amount'] >= $morder['amount'] ){
                      if($lcheck['t_minus_three_signal'] == 'double_invest' && $lcheck['mandate_success'] == '1'){
                         $morder['amount'] = $lcheck['amount'];
                      }
                        $morder['units'] = $morder['amount'] / $current_eq_nav;
                        $morder['liquid_units'] = $current_units['rebalance_buy_eq_units'] =  0;
                        $current_units['buy_eq_units'] =   $morder['units'];

                        $this->purchaseSmartLumpsumNew($morder, $current_units);
                    }else{
                      //skip order
                       $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                       $this->insertSkipOrder($skip_data);
                    }
                }elseif($action == 'double_invest'){
                         $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                         //$multiple = 10; //hardcoded
                         $eq_schemecode = $morder['scheme_code'];

                         $principal_amt = $morder['amount'];

                         $total_invested_amt = 0;
                       if($additional_investment){
                          $total_invested_amt += ($this->setting['base_aggr_invest_multiplier'])*$morder['amount'];
                          $morder['amount']    = $total_invested_amt; //addn investment

                        }
                         $total_sip = $morder['amount'];
                         $amount_regular =  $morder['amount'];
                       //sell SIP amt in liquid if true and available
                          // $c_unitsliquid = mround($current_units['closing_liquid_units'],1);
                          $c_unitsliquid = $this->get_lower_nearest_value($current_units['closing_liquid_units'],$liquid_scheme_redemption_multiplier);
                       //y($c_unitsliquid); x($min_liquid_amt_flag['Minimum_Redemption_Qty']);
                        if(empty($c_unitsliquid) || $c_unitsliquid <= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){
                               $morder['liquid_units'] = 0;
                               $current_units['comment'] = json_encode(array('purchase' => 'No Liquid units or less thatn redemption qty'));
                               $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                               $morder['units'] = $current_units['buy_eq_units'];


                            //by default equity
                               if($lcheck['amount'] >= $morder['amount'] ){
                                 $this->purchaseSmartLumpsumNew($morder, $current_units);
                               }else
                               { //die('principal amt');
                                // code to check if only regular amt in ledger is available
                                  if($lcheck['amount'] >= $principal_amt ){
                                  

                                      $current_units['buy_eq_units'] = $principal_amt / $current_eq_nav;
                                      $morder['units'] = $current_units['buy_eq_units'];


                                      $current_units['comment'] = json_encode(array('purchase' => 'Only sufficent ledger for Principal amt not topup'));
                                      $morder['amount'] = $principal_amt;
                                       $this->purchaseSmartLumpsumNew($morder,$current_units);
                                   }else{


                                   $skip_data = ['master_id' => $morder['id'],
                                          'client_id' => $morder['client_id'],
                                          'scheme_code' => $morder['scheme_code'],
                                          'amount' => $morder['amount'],
                                          'sip_date' => $date,
                                          'reason' => 'Insufficient Ledger'
                                          ];
                                   $this->insertSkipOrder($skip_data);
                                    }
                            }

                             
                        }else{ //if liquid units available
                          if($additional_investment){
                             $sipAmtUnits = 0;
                          }else{
                             // $sipAmtUnits = mround($morder['amount']/$current_liquid_nav, 1);
                             $sipAmtUnits = $this->get_lower_nearest_value($morder['amount']/$current_liquid_nav, $liquid_scheme_redemption_multiplier);

                          }
                           //y($morder['amount']); x($current_liquid_nav * $current_units['closing_liquid_units'] ); 
                            if( ( ($current_liquid_nav * $current_units['closing_liquid_units'] ) >= $principal_amt && $c_unitsliquid >= 0.1 ) || $additional_investment){ //checking if sip amt in liquid units worth is available

                                //$total_sip += $morder['amount'];
                                // print_r(  $current_units['closing_liquid_units'] ); die;
                                if(round($current_units['closing_liquid_units']) >= 1 ){

                                 if(!$additional_investment){
                                  $rebalance_add_eq_amt = intval($sipAmtUnits*$current_liquid_nav);
                                  $rebalance_add_eq_units =  $rebalance_add_eq_amt / $current_eq_nav;
                                   $current_units['closing_liquid_units'] -=  $sipAmtUnits; //updating closing units(regular sip amt deducted in units)

                                  }else{

                                  }

                                    $sell_all_units = 0;
                                    //sell % of units depending on Rebalance signal (~50% or ~100%)
                                     if($rbalance == 'very_aggressive'){
                                      // $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_vaggr_invest_per'],1) ;
                                      $units_sell_rb = $this->get_lower_nearest_value($current_units['closing_liquid_units'] * $this->setting['rebalance_vaggr_invest_per'], $liquid_scheme_redemption_multiplier);
                                          /*
                                          // commented below code on 16th Feb 2022 because redeeming all LIQUID units created an issue, if client have more than 1 SmartSIP scheduled and all of them have same folio number for LIQUID scheme
                                          if($this->setting['rebalance_vaggr_invest_per'] == 1 )
                                          {
                                            $sell_all_units = 1;
                                          }else{
                                            $sell_all_units = 0;
                                          }
                                          */
                                      }
                                      else if($rbalance == 'aggressive'){
                                       // $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_aggr_invest_per'],1);
                                       $units_sell_rb = $this->get_lower_nearest_value($current_units['closing_liquid_units'] * $this->setting['rebalance_aggr_invest_per'], $liquid_scheme_redemption_multiplier);
                                      }
                                     $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units

                                    $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                    $current_units['rebal_buy_amt'] = $units_sell * $current_liquid_nav;
                                   $current_units['order_id'] = $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units, $sell_all_units);
                                    //print_r($total_sip); die;
                                    $total_sip_installment = $total_sip;
                                    $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                    $current_units['rebalance_buy_amt'] = $units_sell * $current_liquid_nav; 
                                     //print_r($total_sip); die;
                                    $current_units['rebalance_buy_amt'] = $this->round_nearest($current_units['rebalance_buy_amt'],$multiple ); 
                                   }else{
                                    $total_sip_installment = $total_sip;
                                   }

                                  }else{ //sell whatever remaining units are available
                                  //x('ell allll');
                                     $current_units['comment'] = json_encode(array('redemption' => 'liquid Units less than Min Redemp quantity'));

                                      // $units_sell_rb = mround($current_units['closing_liquid_units'],1) ;
                                      $units_sell_rb = $this->get_lower_nearest_value($current_units['closing_liquid_units'],$liquid_scheme_redemption_multiplier);
                                     // print_r($current_units['closing_liquid_units']); die;
                                    //  $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                      $units_sell_rb_amt = $current_units['closing_liquid_units'] * $current_liquid_nav;
                                      $units_sell_rb_amt = $this->round_min($units_sell_rb_amt,$multiple); //rounding to lower value if units amt not in multiple
                                      $units_sell_rb = $units_sell_rb_amt / $current_liquid_nav; 
                                      // $units_sell_rb = mround($current_units['closing_liquid_units'],2) ;
                                      $units_sell_rb = $this->get_lower_nearest_value($current_units['closing_liquid_units'], $liquid_scheme_redemption_multiplier);



                                        $all_sold_units = ['sell_liquid_units' => $units_sell_rb, 'rebalance_units' => 0 ]; //rebalance_units = units minused from closing liquid
                                        $current_units['closing_liquid_units'] -= $units_sell_rb; // in this condn closing units needs to be updated
                                        $current_units['rebal_buy_amt'] = $units_sell_rb * $current_liquid_nav;

                                        $current_units['order_id'] = $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell_rb ,$morder['order_mode'], $current_units,$all_sold_units,0);
                                        $total_sip_installment = $total_sip;
                                        $total_sip += ($units_sell_rb * $current_liquid_nav);

                                  }
                                //purchase regular SIP + liquid units sold
                                  $morder['amount'] = $total_sip_installment; //amt recalulated
                                  $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                  $morder['liquid_units'] = 0;
                                   $eq_units_buy_amt = intval($current_liquid_nav*$units_sell_rb); // calculating amt in rs from units fold in equity
                                  $current_units['rebalance_buy_eq_units'] = $eq_units_buy_amt / $current_eq_nav ;
                                // x($current_units['rebalance_buy_eq_units']);

                                  $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                                  print_r($morder['amount'] );
                                  // $current_units['buy_eq_units'] -= $units_sell_rb;
                                  // $current_units['buy_eq_units'] -= $rebalance_add_eq_units;
                                 // $morder['units'] =  $current_units['buy_eq_units'];
                                  //$current_units['closing_eq_units'] += $rebalance_add_eq_units ;
                                 
                                  $morder['amount'] = $this->round_min($morder['amount'],$multiple ); //rounding off to min mutliple of purchase multiplier
                                  if($lcheck['amount'] >= $morder['amount'] ){
                                    $this->purchaseSmartLumpsumNew($morder,$current_units);
                                  
                                   $morder['amount'] = $total_sip - $total_sip_installment; //amt recalulated
                                  $current_units['secondary_base_buy_eq_units'] = $rebalance_add_eq_units;
 
                                   $this->purchaseSmartLumpsumProcessList($morder,$current_units);
                                  }
                                  else{
                                    // code to check if only regular amt in ledger is available
                                    if($lcheck['amount'] >= $principal_amt){
                                        $current_units['comment'] = json_encode(array('purchase' => 'Only sufficent ledger for Principal amt not topup'));
                                         $current_units['buy_eq_units'] = $principal_amt / $current_eq_nav;
                                         $morder['units'] = $current_units['buy_eq_units'];

                                         $morder['amount'] = $principal_amt;
                                         $this->purchaseSmartLumpsumNew($morder,$current_units);
                                         $current_units['buy_eq_units'] = $morder['units'] = 0;
                                         $morder['amount'] = $total_sip - $total_sip_installment; //amt recalulated
                                         $current_units['secondary_base_buy_eq_units'] = $rebalance_add_eq_units;

                                         $this->purchaseSmartLumpsumProcessList($morder,$current_units);
                                     }else{
                                         $current_units['comment'] = json_encode(array('purchase' => 'Insufficent ledger. Only sold units bought'));
                                     //skip order
                                       $morder['amount'] = $total_sip - $amount_regular; //amt recalulated
                                       $morder['amount'] = $this->round_min($morder['amount'],$multiple);
                                       $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                       $morder['liquid_units'] = $current_units['buy_eq_units'] = 0;
                                    // x($current_units['rebalance_buy_eq_units']);

                                        if(($min_eq_amt_flag['Minimum_Purchase_Amount'] <= $morder['amount']) )  {
                                           $this->purchaseSmartLumpsumProcessList($morder,$current_units);

                                        }else if($min_liquid_amt_flag_default['Minimum_Purchase_Amount'] <= $morder['amount']) { //
                                        // ledger credit

                                        }
                                     }
                                      $skip_data = ['master_id' => $morder['id'],
                                          'client_id' => $morder['client_id'],
                                          'scheme_code' => $eq_schemecode,
                                          'amount' => $principal_amt,
                                          'sip_date' => $date,
                                          'reason' => 'Insufficient Ledger'
                                          ];
                                      $this->insertSkipOrder($skip_data);
                                  }
                            }

                             if( ($lcheck['t_minus_three_signal'] == 'invest' || $lcheck['t_minus_three_signal'] == 'double_invest') && $additional_investment && ($lcheck['amount'] <= $principal_amt) && $lcheck['mandate_success'] == 1 ){ // inserting process list order for T day mandate call to be executed if successfull
                                $morder['amount'] = $principal_amt; 
                                $current_units['buy_eq_units'] = $principal_amt / $current_eq_nav;
                                $current_units['to_be_executed'] = 1;
                                $current_units['rebalance_buy_eq_units'] = $current_units['secondary_base_buy_eq_units'] = $current_units['closing_liquid_units_default'] = 0;
                                $current_units['closing_eq_units'] += $current_units['buy_eq_units'];
                                $this->purchaseSmartLumpsumProcessList($morder,$current_units); 
                                
                              }
                        }
                    elseif($action == 'sell'){ // sell eq and buy liquid

                      if($lcheck['amount'] < $morder['amount'] ){ //skip orders as per flow
                         $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                                       $this->insertSkipOrder($skip_data);
                                  
                      }else{ // process orders

                         $multiple = ceil($min_liquid_amt_flag['Purchase_Amount_Multiplier']);
                        // $multiple = 10; //hardcoded

                        $total_sip = $morder['amount'];
                        $eq_sell_units_rb = $liquid_units_buy_amt = 0;
                        $eq_sell_units_eq = $current_units['closing_eq_units'] * $this->setting['base_sell_units_per']; //selling specified % of equity units from setting (approx 15%)
                       //-----------// $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                       // $min_eq_amt_flag['Minimum_Redemption_Qty'] = 50;
                     //  echo $current_units['closing_eq_units']; die;
                       // $c_unitseq = mround($current_units['closing_eq_units'],1);
                       $c_unitseq = $this->get_lower_nearest_value($current_units['closing_eq_units'],$morder['Redemption_Qty_Multiplier']);
                       
                       if($rebalance_sell == 'very_aggressive_sell'){
                            $eq_sell_units_rb = $this->setting['rebalance_vaggr_sell_units_per'] * $current_units['closing_eq_units'];
                         }elseif($rebalance_sell == 'aggressive_sell'){
                          $eq_sell_units_rb = $this->setting['rebalance_aggr_sell_units_per'] * $current_units['closing_eq_units'] ;
                         }

                         $eq_sell_units = $eq_sell_units_rb + $eq_sell_units_eq;
                         // $eq_sell_units = mround($eq_sell_units,2);
                         $eq_sell_units = $this->get_lower_nearest_value($eq_sell_units, $morder['Redemption_Qty_Multiplier']);
                         //print_r($this->setting['rebalance_aggr_sell_units_per']); echo '<pre>'; 
                      //   print_r($eq_sell_units_rb); die;
                      if($eq_sell_units > $min_eq_amt_flag['Minimum_Redemption_Qty'] ){
                           // $current_units['closing_eq_units'] -= $eq_sell_units_rb; //updating equity units
                           // $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                           $current_units['closing_eq_units'] -= $eq_sell_units; //updating equity units
                           $current_units['rebal_buy_amt'] = $eq_sell_units * $current_eq_nav;
                           $all_sold_units = ['sell_eq_units' => $eq_sell_units_eq, 'rebalance_sell_eq_units' => $eq_sell_units_rb];

                         $current_units['order_id'] = $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units );
                         $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                       }
                       elseif(($current_units['closing_eq_units'] > $min_eq_amt_flag['Minimum_Redemption_Qty']) && ($eq_sell_units < $min_eq_amt_flag['Minimum_Redemption_Qty'])){
                           $current_units['closing_eq_units'] -= $min_eq_amt_flag['Minimum_Redemption_Qty'];
                           $current_units['rebal_buy_amt'] = $min_eq_amt_flag['Minimum_Redemption_Qty'] * $current_eq_nav;
                           $all_sold_units = ['sell_eq_units' => ($this->setting['base_sell_units_per'] * $min_eq_amt_flag['Minimum_Redemption_Qty']), 'rebalance_sell_eq_units' => ((1 - $this->setting['base_sell_units_per']) * $min_eq_amt_flag['Minimum_Redemption_Qty'])];

                           $current_units['order_id'] = $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $min_eq_amt_flag['Minimum_Redemption_Qty'], $morder['order_mode'] , $current_units,  $all_sold_units );
                           $liquid_units_buy_amt = intval($current_eq_nav*$min_eq_amt_flag['Minimum_Redemption_Qty']); // calculating amt in rs from units fold in equity
                       }else{ //sell all units
                        //die('aaaaaaaaaaaaaaaaaaaaaaadsaa');
                        $current_units['comment'] = json_encode(array('redemption' => 'Eq Units less than Min Redemp quantity'));
                         if(!empty($c_unitseq) && $c_unitseq >= 0.1 ){ //if no units at all
                              $current_units['rebal_buy_amt'] = $eq_sell_units * $current_eq_nav;

                            $current_units['order_id'] =   $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units, 0);

                            $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                        }else{
                         $liquid_units_buy_amt = 0; // insufficent units hence no eq units sold
                        }



                       }
                       
                      
                       //x($current_units['rebalance_buy_amt']);
                       if($lcheck['amount'] >= $morder['amount']){ //add regular sip amt if ledger amt available
                        //##$liquid_units_buy_amt += $total_sip;
                        $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                       }
                       
                       
                                 $morder['amount'] = $total_sip;
                                 $morder['scheme_code'] = $morder['liquid_scheme_code'];
                                 $morder['liquid_units'] = $total_sip / $current_liquid_nav;
                                 $morder['units'] = 0;
                                // $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                                  $morder['amount'] = $this->round_min($morder['amount'],$multiple); //rounding off to min
                                  if($lcheck['amount'] >= $morder['amount'] ){
                          
                                   $this->purchaseSmartLumpsumNew($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ

                                   $current_units['rebalance_buy_liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav ;
                                   $current_units['rebalance_buy_amt'] = $this->round_nearest($liquid_units_buy_amt,$multiple );
                                    $morder['amount'] = $current_units['rebalance_buy_amt'];
                                   $this->purchaseSmartLumpsumProcessList($morder,$current_units,'liquid');

                                   }else{
                                     $current_units['comment'] = json_encode(array('purchase' => 'Insufficent ledger. Only sold units bought'));

                                       $current_units['rebalance_buy_liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav ;
                                       $current_units['rebalance_buy_amt'] = $this->round_nearest($liquid_units_buy_amt,$multiple );


                                      $morder['amount'] = $this->round_min($liquid_units_buy_amt,$multiple); //rounding to lower value if units amt not in multiple

                                    //buy only if more than minimum
                                    if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $morder['amount']){
                                       $this->purchaseSmartLumpsumNew($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ

                                    }
                                    else if($min_liquid_amt_flag_default['Minimum_Purchase_Amount'] <= $morder['amount']) //units sold not sufficent for minimum purchase amt for liquid
                                    {   //ledger credit
                                       // $current_units['buy_liquid_units_default'] = $morder['amount'] / $current_liquid_nav_default;
                                       // $morder['scheme_code'] = $morder['liquid_scheme_code_default'];
                                       // $this->purchaseSmartLumpsumNew($morder,$current_units,'default_liquid');


                                    }else{
                                      $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                                       $this->insertSkipOrder($skip_data);
                                    }
                                  }

                        //     }else{ //not satisfying multiple amt criteria

                        //     }

                        } //else l check true

                    }
                  else if($action == 'skip'){
                        $morder['scheme_code'] = $morder['liquid_scheme_code'];
                        $current_units['buy_liquid_units'] = $morder['amount'] / $current_liquid_nav;
                        $morder['units'] = 0;
                        //x($morder['buy_liquid_units']);

                         if($lcheck['amount'] >= $morder['amount']){
                          
                           $this->purchaseSmartLumpsumNew($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                         }else{
                          //skip order
                           $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                           $this->insertSkipOrder($skip_data);

                        }

                   }
                    $next_date_tb = date('Y-m', strtotime($morder['next_t_day']) ).'-'.date('d', strtotime($morder['start_date']) );
                    $next_month_day = date('Y-m-d',strtotime($next_date_tb."+1 month") );
                    $install_diff = date_diff(date_create($next_month_day),date_create($morder['next_t_day']))->days;
                    if($install_diff > 31)
                        $next_month_day = $next_date_tb;

                    $next_sip_t_day = $this->filterHolidays($next_month_day);
                    $update_master_data = [
                                          'next_t_day' => $next_sip_t_day['tplus0'],
                                          't_1day'     => $next_sip_t_day['tminus1'],
                                          't_3day'     => $next_sip_t_day['tminus3']
                                          ];

                    $params_up                  =  [];
                    $params_up['env']           =  'db';                   
                    $params_up['table_name']    =  'mf_master_smart_sip';
                    $params_up['update_data']   =  $update_master_data;           
                    $params_up['where']         =  TRUE;
                    $params_up['where_data']    =  ['id' => $morder['id'] ]; 
                    //$params_up['print_query']     =  TRUE;
                    $update_status = $this->mf->update_table_data_with_type($params_up);
                    sleep(1);

                  }// foreach end for all clients in master

                  // calculating and updating next_t_day for those SmartSIP/SuperSIP records which have either scheme purchase allowed flag as N or pause_sip = 1
                  $this->update_next_tday_for_records_of_smartsip_table($date, 'smartsip', 'purchase_flag_no');
                  $this->update_next_tday_for_records_of_smartsip_table($date, 'smartsip', 'paused_smartsip');

                  $response = array('order_status' => 'success', 'msg' => 'Orders executed');
                  $this->load->view('jsonresponse', ['data' => $response])  ;
                }else{
                  $response = array('order_status' => 'fail', 'msg' => 'No orders found');
                  $this->load->view('jsonresponse', ['data' => $response])  ;
                }


       } 

       //ACTUAL SUPER SIP FN TO EXECUTE ORDERS REAL TIME on T DAY
       public function fetchExecuteOrdersSuper($date = '', $fixed_date = '', $input_client_id = '')
       {

           /*fetching all orders for same day date*/
            $today = date('d');
            if(isset($_POST['date_oday'])){
              $tday = $_POST['date_oday'];
            }else{
              $tday = $date;//date('Y-m-d');


            }
            $fullday = date('Y-m-d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "ss.*, scheme_master.Redemption_Qty_Multiplier";
            $params['table_name']       = 'mf_master_smart_sip AS ss';
            $params['join']             = TRUE;
            $params['multiple_joins']   = TRUE;
            $params['join_table']       = array('mf_scheme_master as scheme_master');
            $params['join_on']          = array('ss.scheme_code = scheme_master.Scheme_Code');
            $params['join_type']        = array('INNER');
            $params['where']            = TRUE;
            $params['where_data']       = ['ss.next_t_day' => $tday, 'ss.order_status' => 2, 'ss.stop_redeem_request' => 0, 'ss.type' => 'supersip', 'scheme_master.Purchase_Allowed' => 'Y', 'ss.pause_sip' => 0, 'ss.is_otm' => 0];

            // checking input parameter client_id present or not. If present then read only SuperSIP records for that specific client
            if(isset($input_client_id) && !empty($input_client_id)){
                $params['where_data']['ss.client_id'] = trim(strip_tags($input_client_id));
            }

            $params['return_array']     = True;
           // $params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);

            foreach($master_orders as $morder){
                $where_navdate = $this->getMongoDate($morder['t_1day']);
                
                if($fixed_date){
                  $where_condn = array('schemecode' => $morder['accord_scheme_code']
                ,'nav_date' => $where_navdate
                 );
                }else{
                  $where_condn = array('schemecode' => $morder['accord_scheme_code']);
                }
                $result = $this->mongo_db->where($where_condn)->sort('nav_date', 'desc')->limit(1)->get('mf_smart_sip_eq_momentum')[0]; // getting Margin of Safety for todays date for schemecode
                // while (empty($result)){
                //     $date = date('Y-m-d', strtotime($date."+1 day"));
                //     $navdate = $this->getMongoDate( $date);
                //     $result= $this->mongo_db->where(array('nav_date' => $navdate, 'schemecode' => $morder['accord_scheme_code'] ))->get('mf_smart_sip_eq_momentum')[0];

                //   }

                // x($result);
                if(empty($result)){
                  $response = array('order_status' => 'error', 'msg' => 'No Mosdex Data found for that day', );

                    $this->load->view('jsonresponse', ['data' => $response])  ;
                    die;
                }
                $mongo_nav_date   = (array)$result['nav_date'];
                $mongo_nav_date    = date('Y-m-d',$mongo_nav_date['milliseconds']/1000) ;

                $mos = $morder['margin_of_safety'] = $result['margin_safety'];
                //code for signal calculation
                $signal = $this->getSignals($result['margin_safety']);
               // x($signal);
                $action = $signal['baseSafetyLavel']; //'sell'; //'invest','sell','skip','double_invest'
                $rbalance = $signal['rebalanceSafetyLevel'];//'very_aggressive'; //'very_aggressive', 'aggressive'
                $rebalance_sell = $signal['rebalanceSafetyLevel']; //'aggressive_sell_sell'; // 'aggressive_sell', 'very_aggressive_sell_sell'
                $morder['base_signal'] = $action;
                //x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], $morder['id']);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                $current_units['base_signal'] =  $action;
                $current_units['order_mode'] = $morder['order_mode'];
                $current_units['margin_of_safety'] = $morder['margin_of_safety'];
                $current_units['mongo_date'] = $mongo_nav_date;

                // error_reporting(-1);
                // ini_set('display_errors', 1);
                $current_liquid_nav = $this->getCurrentNav($morder['accord_liquid_scheme_code']) ; //,date
                $current_liquid_nav_default = '4397.63'; //$this->getCurrentNav($morder['accord_liquid_scheme_code_default'], $date) ;
                $current_eq_nav = $this->getCurrentNav($morder['accord_scheme_code']); //date
               // print_r($current_liquid_nav); print_r($current_eq_nav);  die;
                $min_eq_amt_flag = $this->getSchemeMinValues($morder['scheme_code'],$morder['client_id']); //storing min purchase, multiplier flags
                $min_liquid_amt_flag = $this->getSchemeMinValues($morder['liquid_scheme_code'],$morder['client_id']);//storing min purchase, multiplier flags
                $liquid_scheme_redemption_multiplier = 0.001;
                $min_liquid_amt_flag_default = $this->getSchemeMinValues($morder['liquid_scheme_code_default'],$morder['client_id']);//storing min purchase, multiplier flags for default liquid
                //x($min_liquid_amt_flag_default);
                $lcheck = $this->checkIfLedgerSufficent($morder['amount'],$morder['id'], $morder['next_t_day']); //checking if amt sufficent for transaction from mandate + ledger
                //x($lcheck);
                $mcheck = $this->checkMFDMandate($morder['amount'], $morder['client_id']); //checking if approved MFD mandate for OTM is available incase of insuff led balance
               // x($mcheck);
            // print_r($min_eq_amt_flag); print_r($min_liquid_amt_flag); die;
                $additional_investment = $morder['additional_investment'];
                if($action != 'double_invest'){
                  if($lcheck['amount'] >= $morder['amount'] ){
                      if($lcheck['t_minus_three_signal'] == 'double_invest'){
                         $morder['amount'] = $lcheck['amount'];
                      }
                        $morder['units'] = $morder['amount'] / $current_eq_nav;
                        $morder['liquid_units'] = $current_units['rebalance_buy_eq_units'] =  0;
                        $current_units['buy_eq_units'] =   $morder['units'];

                        $this->purchaseSmartLumpsumNew($morder, $current_units);
                    }else{
                      //skip order
                       $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                       $this->insertSkipOrder($skip_data);
                    }
                }elseif($action == 'double_invest'){
                         $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                         $multiple = 10; //hardcoded
                         $eq_schemecode = $morder['scheme_code'];

                         $principal_amt = $morder['amount'];

                         $total_invested_amt = 0;
                       if($additional_investment){
                          $total_invested_amt += ($this->setting['base_aggr_invest_multiplier'])*$morder['amount'];
                          $morder['amount']    = $total_invested_amt; //addn investment

                        }
                         $total_sip = $morder['amount'];
                         $amount_regular =  $morder['amount'];
                       //sell SIP amt in liquid if true and available
                          // $c_unitsliquid = mround($current_units['closing_liquid_units'],1);
                          $c_unitsliquid = $this->get_lower_nearest_value($current_units['closing_liquid_units'],$liquid_scheme_redemption_multiplier);
                       //y($c_unitsliquid); x($min_liquid_amt_flag['Minimum_Redemption_Qty']);
                        if(true ){
                               $morder['liquid_units'] = 0;
                               $current_units['comment'] = json_encode(array('purchase' => 'No Liquid units or less thatn redemption qty'));
                               $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                               $morder['units'] = $current_units['buy_eq_units'];


                            //by default equity
                               if($lcheck['amount'] >= $morder['amount'] ){
                                 $this->purchaseSmartLumpsumNew($morder, $current_units);
                               }else
                               { //die('principal amt');
                                // code to check if only regular amt in ledger is available
                                  if($lcheck['amount'] >= $principal_amt ){
                                  

                                      $current_units['buy_eq_units'] = $principal_amt / $current_eq_nav;
                                      $morder['units'] = $current_units['buy_eq_units'];


                                      $current_units['comment'] = json_encode(array('purchase' => 'Only sufficent ledger for Principal amt not topup'));
                                      $morder['amount'] = $principal_amt;
                                       $this->purchaseSmartLumpsumNew($morder,$current_units);
                                   }
                                   elseif($lcheck['amount'] >= 0 ){ // for super sip check is made for atleast minumum amt in ledger
                        
                                      $morder['amount'] = $lcheck['amount'];
                                      $morder['units'] = $morder['amount'] / $current_eq_nav;
                                      $morder['liquid_units'] = $current_units['rebalance_buy_eq_units'] =  0;
                                      $current_units['buy_eq_units'] =   $morder['units'];

                                      $this->purchaseSmartLumpsumNew($morder, $current_units);
                                  
                                  }
                                  else{


                                   $skip_data = ['master_id' => $morder['id'],
                                          'client_id' => $morder['client_id'],
                                          'scheme_code' => $morder['scheme_code'],
                                          'amount' => $morder['amount'],
                                          'sip_date' => $date,
                                          'reason' => 'Insufficient Ledger'
                                          ];
                                   $this->insertSkipOrder($skip_data);
                                    }
                            }

                             
                        }

                             if( ($lcheck['t_minus_three_signal'] == 'invest' || $lcheck['t_minus_three_signal'] == 'double_invest') && $additional_investment && ($lcheck['amount'] <= $principal_amt) && $lcheck['mandate_success'] == 1 ){ // inserting process list order for T day mandate call to be executed if successfull
                                $morder['amount'] = $principal_amt; 
                                $current_units['buy_eq_units'] = $principal_amt / $current_eq_nav;
                                $current_units['to_be_executed'] = 1;
                                $current_units['rebalance_buy_eq_units'] = $current_units['secondary_base_buy_eq_units'] = $current_units['closing_liquid_units_default'] = 0;
                                $current_units['closing_eq_units'] += $current_units['buy_eq_units'];
                                $this->purchaseSmartLumpsumProcessList($morder,$current_units);
                                
                              }
                  }
                        
                    $next_date_tb   = date('Y-m', strtotime($morder['next_t_day']) ).'-'.date('d', strtotime($morder['start_date']) );
                    $next_month_day = date('Y-m-d',strtotime($next_date_tb."+1 month") );
                    $install_diff   = date_diff(date_create($next_month_day),date_create($morder['next_t_day']))->days;
                    if($install_diff > 31)
                        $next_month_day = $next_date_tb;

                    $next_sip_t_day = $this->filterHolidays($next_month_day);
                    $update_master_data = [
                                          'next_t_day' => $next_sip_t_day['tplus0'],
                                          't_1day'     => $next_sip_t_day['tminus1'],
                                          't_3day'     => $next_sip_t_day['tminus3']
                                          ];

                    $params_up                  =  [];
                    $params_up['env']           =  'db';                   
                    $params_up['table_name']    =  'mf_master_smart_sip';
                    $params_up['update_data']   =  $update_master_data;           
                    $params_up['where']         =  TRUE;
                    $params_up['where_data']    =  ['id' => $morder['id'] ]; 
                    //$params_up['print_query']     =  TRUE;
                    $update_status = $this->mf->update_table_data_with_type($params_up);

                }// foreach end for all clients in master


                // calculating and updating next_t_day for those SmartSIP/SuperSIP records which have either scheme purchase allowed flag as N or pause_sip = 1
                $this->update_next_tday_for_records_of_smartsip_table($date, 'supersip', 'purchase_flag_no');
                $this->update_next_tday_for_records_of_smartsip_table($date, 'supersip', 'paused_smartsip');

                $response = array('order_status' => 'success', 'msg' => 'Super Orders executed', );

               $this->load->view('jsonresponse', ['data' => $response])  ;

       }

       public function fetchExecuteOrdersFuture($date, $fixed_date = '')
       {

           /*fetching all orders for same day date*/
            $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*";
            $params['table_name']       = 'mf_master_smart_sip';
            $params['where']            = TRUE;
            $params['where_data']       = ['day(start_date)' => $today, 'order_status' => 2];

            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);

            foreach($master_orders as $morder){
                $where_navdate = $this->getMongoDate($date);
                
                if($fixed_date){
                  $where_condn = array('schemecode' => $morder['accord_scheme_code']
                ,'nav_date' => $where_navdate
                 );
                }else{
                  $where_condn = array('schemecode' => $morder['accord_scheme_code']);
                }
                $result = $this->mongo_db->where($where_condn)->sort('nav_date', 'desc')->limit(1)->get('mf_smart_sip_eq_momentum')[0]; // getting Margin of Safety for todays date for schemecode
                // while (empty($result)){
                //     $date = date('Y-m-d', strtotime($date."+1 day"));
                //     $navdate = $this->getMongoDate( $date);
                //     $result= $this->mongo_db->where(array('nav_date' => $navdate, 'schemecode' => $morder['accord_scheme_code'] ))->get('mf_smart_sip_eq_momentum')[0];

                //   }

                // x($result);
               $mongo_nav_date   = (array)$result['nav_date'];
               $mongo_nav_date    = date('Y-m-d',$mongo_nav_date['milliseconds']/1000) ;

               $mos = $morder['margin_of_safety'] = $result['margin_safety'];
                //code for signal calculation
                $signal = $this->getSignals($result['margin_safety']);
               // x($signal);
                 $action = $signal['baseSafetyLavel']; //'sell'; //'invest','sell','skip','double_invest'
                 $rbalance = $signal['rebalanceSafetyLevel'];//'very_aggressive'; //'very_aggressive', 'aggressive'
                 $rebalance_sell = $signal['rebalanceSafetyLevel']; //'aggressive_sell_sell'; // 'aggressive_sell', 'very_aggressive_sell_sell'
                $morder['base_signal'] = $action;
               // x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], $morder['id']);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                $current_units['base_signal'] =  $action;
                $current_units['order_mode'] = $morder['order_mode'];
                $current_units['margin_of_safety'] = $morder['margin_of_safety'];
                $current_units['mongo_date'] = $mongo_nav_date;

                error_reporting(-1);
                ini_set('display_errors', 1);
                $current_liquid_nav = $this->getCurrentNav($morder['accord_liquid_scheme_code'], $date) ;
                $current_liquid_nav_default = '4397.63'; //$this->getCurrentNav($morder['accord_liquid_scheme_code_default'], $date) ;
                $current_eq_nav = $this->getCurrentNav($morder['accord_scheme_code'], $date );
               // print_r($current_liquid_nav); print_r($current_eq_nav);  die;
                $min_eq_amt_flag = $this->getSchemeMinValues($morder['scheme_code'],$morder['client_id']); //storing min purchase, multiplier flags
                $min_liquid_amt_flag = $this->getSchemeMinValues($morder['liquid_scheme_code'],$morder['client_id']);//storing min purchase, multiplier flags
                $min_liquid_amt_flag_default = $this->getSchemeMinValues($morder['liquid_scheme_code_default'],$morder['client_id']);//storing min purchase, multiplier flags for default liquid
                //x($min_liquid_amt_flag_default);
                $lcheck = $this->checkIfLedgerSufficent($morder['amount'], $morder['client_id']); //checking if ledger sufficent for transaction
                $mcheck = $this->checkMFDMandate($morder['amount'], $morder['client_id']); //checking if approved MFD mandate for OTM is available incase of insuff led balance
               // x($mcheck);
            // print_r($min_eq_amt_flag); print_r($min_liquid_amt_flag); die;
                $additional_investment = $morder['additional_investment'];
                if($action == 'invest'){
                    $morder['units'] = $morder['amount'] / $current_eq_nav;
                    $morder['liquid_units'] = $current_units['rebalance_buy_eq_units'] =  0;
                    $current_units['buy_eq_units'] =   $morder['units'];
                   // x($lcheck);
                    if($lcheck){
                        $this->purchaseSmartLumpsum($morder, $current_units);
                    }else if($mcheck){ //approved MFD mandate for OTM 
                        if($mcheck['mandate_amt'] >= $morder['amount']){
                           $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                        }

                        if(!empty($order_id)){
                            $this->OTMPayment($mcheck['mandate_id'], $order_id);
                          
                         }

                    }else{
                      //skip order
                       $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                       $this->insertSkipOrder($skip_data);
                    }
                }elseif($action == 'double_invest'){
                         $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                         $multiple = 10; //hardcoded
                         $eq_schemecode = $morder['scheme_code'];

                         $principal_amt = $morder['amount'];
                       if($additional_investment){
                          $total_invested_amt += ($this->setting['base_aggr_invest_multiplier'])*$morder['amount'];
                          $morder['amount']    = $total_invested_amt; //addn investment
                           $lcheck = $this->checkIfLedgerSufficent($morder['amount'], $morder['client_id']); //checking if ledger sufficent for TOPUP transaction

                        }
                        $total_sip = $morder['amount'];
                         $amount_regular =  $morder['amount'];
                     //sell SIP amt in liquid if true and available
                          $c_unitsliquid = mround($current_units['closing_liquid_units'],1);
                          //y($c_unitsliquid); x($min_liquid_amt_flag['Minimum_Redemption_Qty']);
                        if(empty($c_unitsliquid) || $c_unitsliquid <= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){
                            $morder['liquid_units'] = 0;
                            $current_units['comment'] = json_encode(array('purchase' => 'No Liquid units or less thatn redemption qty'));
                             $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                             $morder['units'] = $current_units['buy_eq_units'];


                            //by default equity
                             if($lcheck){
                               $this->purchaseSmartLumpsum($morder, $current_units);
                             }else if($mcheck){
                                  if($mcheck['mandate_amt'] >= $morder['amount']){
                                     $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                                  }
                                   if(!empty($order_id)){
                                      $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                   }

                            }else{
                              //skip order
                              // code to check if only regular amt in ledger is available
                                $lcheck = $this->checkIfLedgerSufficent($principal_amt, $morder['client_id']); //checking if ledger sufficent for transaction
                                if($lcheck){

                                    $current_units['buy_eq_units'] = $principal_amt / $current_eq_nav;
                                    $morder['units'] = $current_units['buy_eq_units'];


                                    $current_units['comment'] = json_encode(array('purchase' => 'Only sufficent ledger for Principal amt not topup'));
                                    $morder['amount'] = $principal_amt;
                                     $this->purchaseSmartLumpsum($morder,$current_units);
                                 }else{


                                 $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                                 $this->insertSkipOrder($skip_data);
                                  }
                            }

                        }else{
                            $sipAmtUnits = mround($morder['amount']/$current_liquid_nav, 1);
                           // y($morder['amount']); x($current_liquid_nav * $current_units['closing_liquid_units'] ); 
                            if(($current_liquid_nav * $current_units['closing_liquid_units'] ) >= $morder['amount'] && $c_unitsliquid >= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){ //checking if sip amt in liquid units worth is available

                                //$total_sip += $morder['amount'];
                                // print_r(  $current_units['closing_liquid_units'] ); die;
                                if(round($current_units['closing_liquid_units']) >= 1 ){

                                 if(!$additional_investment){
                                  $rebalance_add_eq_amt = intval($sipAmtUnits*$current_liquid_nav);
                                  $rebalance_add_eq_units =  $rebalance_add_eq_amt / $current_eq_nav;
                                   $current_units['closing_liquid_units'] -=  $sipAmtUnits; //updating closing units(regular sip amt deducted in units)

                                  }else{

                                  }

                                    //sell % of units depending on Rebalance signal (~50% or ~100%)
                                 if($rbalance == 'very_aggressive'){ //sell ~100% of liquid units ,invest in regular SIP
                                    $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_vaggr_invest_per'],1) ;
                                   $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units

                                  $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                  if($this->setting['rebalance_vaggr_invest_per'] == 1 )
                                  {
                                    $sell_all_units = 1;
                                  }else{
                                     $sell_all_units = 0;

                                  }
                                    $sell_all_units = 0;    // re-setting variable here because redeeming all liquid units causing an issue
                                    $current_units['rebal_buy_amt'] = $units_sell * $current_liquid_nav;
                                    $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units, $sell_all_units);
                                    //print_r($total_sip); die;
                                     $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                      $current_units['rebalance_buy_amt'] = $units_sell * $current_liquid_nav; 
                                     //print_r($total_sip); die;
                                      $current_units['rebalance_buy_amt'] = $this->round_nearest($current_units['rebalance_buy_amt'],$multiple ); 
                                   }
                                   else if($rbalance == 'aggressive'){ //sell ~50% of liquid units, invest in regular SIP
                                        $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_aggr_invest_per'],1);
                                        $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                        //print_r($units_sell); die;
                                        $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];
                                        $current_units['rebal_buy_amt'] = $units_sell * $current_liquid_nav;

                                        $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units);
                                        $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                      $current_units['rebalance_buy_amt'] = $units_sell * $current_liquid_nav; 
                                     //print_r($total_sip); die;
                                      $current_units['rebalance_buy_amt'] = $this->round_nearest($current_units['rebalance_buy_amt'],$multiple ); 
                                       }
                                   }

                                  }else{ //sell whatever remaining units are available
                                  //  x('ell allll');
                                     $current_units['comment'] = json_encode(array('redemption' => 'liquid Units less than Min Redemp quantity'));

                                      $units_sell_rb = mround($current_units['closing_liquid_units'],1) ;
                                     // print_r($current_units['closing_liquid_units']); die;
                                    //  $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                      $units_sell_rb_amt = $current_units['closing_liquid_units'] * $current_liquid_nav;
                                      $units_sell_rb_amt = $this->round_min($units_sell_rb_amt,$multiple); //rounding to lower value if units amt not in multiple
                                      $units_sell_rb = $units_sell_rb_amt / $current_liquid_nav; 
                                      $units_sell_rb = mround($current_units['closing_liquid_units'],1) ;



                                        $all_sold_units = ['sell_liquid_units' => $units_sell_rb, 'rebalance_units' => 0 ]; //rebalance_units = units minused from closing liquid
                                        $current_units['closing_liquid_units'] -= $units_sell_rb; // in this condn closing units needs to be updated
                                        $current_units['rebal_buy_amt'] = $units_sell_rb * $current_liquid_nav;

                                        $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell_rb ,$morder['order_mode'], $current_units,$all_sold_units,0);
                                        $total_sip += ($units_sell_rb * $current_liquid_nav);

                                  }
                                //purchase regular SIP + liquid units sold
                                  $morder['amount'] = $total_sip; //amt recalulated
                                  $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                  $morder['liquid_units'] = 0;
                                   $eq_units_buy_amt = intval($current_liquid_nav*$units_sell_rb); // calculating amt in rs from units fold in equity
                                  $current_units['rebalance_buy_eq_units'] = $eq_units_buy_amt / $current_eq_nav ;
                                // x($current_units['rebalance_buy_eq_units']);

                                  $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                                  print_r($morder['amount'] );
                                  // $current_units['buy_eq_units'] -= $units_sell_rb;
                                  // $current_units['buy_eq_units'] -= $rebalance_add_eq_units;
                                  $current_units['secondary_base_buy_eq_units'] = $rebalance_add_eq_units;
                                 // $morder['units'] =  $current_units['buy_eq_units'];
                                  //$current_units['closing_eq_units'] += $rebalance_add_eq_units ;
                                 
                                  $morder['amount'] = $this->round_nearest($morder['amount'],$multiple ); //rounding off to nearest mutliple of purchase multiplier
                                  if($lcheck){
                                   $this->purchaseSmartLumpsum($morder,$current_units);
                                  }
                                  else if($mcheck){
                                    if($mcheck['mandate_amt'] >= $amount_regular){
                                      $current_units['Minimum_Purchase_Amount'] = $min_eq_amt_flag['Minimum_Purchase_Amount'];
                                       $order_id = $this->purchaseSmartLumpsum($morder, $current_units,'equity','MFDP_L');
                                    }
                                     if(!empty($order_id)){
                                        $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                     }

                                  }else{
                                    // code to check if only regular amt in ledger is available
                                    $lcheck = $this->checkIfLedgerSufficent($principal_amt, $morder['client_id']); //checking if ledger sufficent for transaction
                                    if($lcheck){
                                        $current_units['comment'] = json_encode(array('purchase' => 'Only sufficent ledger for Principal amt not topup'));

                                         $this->purchaseSmartLumpsum($morder,$current_units);
                                     }else{
                                         $current_units['comment'] = json_encode(array('purchase' => 'Insufficent ledger. Only sold units bought'));
                                     //skip order
                                       $morder['amount'] = $total_sip - $amount_regular; //amt recalulated
                                       $morder['amount'] = $this->round_min($morder['amount'],$multiple);
                                       $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                       $morder['liquid_units'] = $current_units['buy_eq_units'] = 0;
                                    // x($current_units['rebalance_buy_eq_units']);

                                        if(($min_eq_amt_flag['Minimum_Purchase_Amount'] <= $morder['amount']) )  {
                                           $this->purchaseSmartLumpsum($morder,$current_units);

                                        }else if($min_liquid_amt_flag_default['Minimum_Purchase_Amount'] <= $morder['amount']) //units sold not sufficent for minimum purchase amt for liquid
                                        {   
                                           $current_units['buy_liquid_units_default'] = $morder['amount'] / $current_liquid_nav_default;
                                           $morder['scheme_code'] = $morder['liquid_scheme_code_default'];
                                           $this->purchaseSmartLumpsum($morder,$current_units,'default_liquid');


                                        }
                                     }
                                      $skip_data = ['master_id' => $morder['id'],
                                          'client_id' => $morder['client_id'],
                                          'scheme_code' => $eq_schemecode,
                                          'amount' => $principal_amt,
                                          'sip_date' => $date,
                                          'reason' => 'Insufficient Ledger'
                                          ];
                                      $this->insertSkipOrder($skip_data);
                                  }
                            }

                        }
                    elseif($action == 'sell'){ // sell eq and buy liquid
                         $multiple = ceil($min_liquid_amt_flag['Purchase_Amount_Multiplier']);
                         $multiple = 10; //hardcoded

                        $total_sip = $morder['amount'];
                        $eq_sell_units_rb = $liquid_units_buy_amt = 0;
                       $eq_sell_units_eq = $current_units['closing_eq_units'] * $this->setting['base_sell_units_per']; //selling specified % of equity units from setting (approx 15%)
                       //-----------// $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                       // $min_eq_amt_flag['Minimum_Redemption_Qty'] = 50;
                     //  echo $current_units['closing_eq_units']; die;
                        $c_unitseq = mround($current_units['closing_eq_units'],1);
                       
                       if($rebalance_sell == 'very_aggressive_sell'){
                            $eq_sell_units_rb = $this->setting['rebalance_vaggr_sell_units_per'] * $current_units['closing_eq_units'];
                         }elseif($rebalance_sell == 'aggressive_sell'){
                          $eq_sell_units_rb = $this->setting['rebalance_aggr_sell_units_per'] * $current_units['closing_eq_units'] ;
                         }

                         $eq_sell_units = $eq_sell_units_rb + $eq_sell_units_eq;
                         $eq_sell_units = mround($eq_sell_units,2);
                         //print_r($this->setting['rebalance_aggr_sell_units_per']); echo '<pre>'; 
                      //   print_r($eq_sell_units_rb); die;
                      if($eq_sell_units > $min_eq_amt_flag['Minimum_Redemption_Qty'] ){
                          // $current_units['closing_eq_units'] -= $eq_sell_units_rb; //updating equity units
                          // $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                          $current_units['closing_eq_units'] -= $eq_sell_units; //updating equity units
                          $current_units['rebal_buy_amt'] = $eq_sell_units * $current_eq_nav;
                          $all_sold_units = ['sell_eq_units' => $eq_sell_units_eq, 'rebalance_sell_eq_units' => $eq_sell_units_rb];

                         $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units );
                         $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                       }
                       elseif(($current_units['closing_eq_units'] > $min_eq_amt_flag['Minimum_Redemption_Qty']) && ($eq_sell_units < $min_eq_amt_flag['Minimum_Redemption_Qty'])){
                           $current_units['closing_eq_units'] -= $min_eq_amt_flag['Minimum_Redemption_Qty'];
                           $current_units['rebal_buy_amt'] = $min_eq_amt_flag['Minimum_Redemption_Qty'] * $current_eq_nav;
                           $all_sold_units = ['sell_eq_units' => ($this->setting['base_sell_units_per'] * $min_eq_amt_flag['Minimum_Redemption_Qty']), 'rebalance_sell_eq_units' => ((1 - $this->setting['base_sell_units_per']) * $min_eq_amt_flag['Minimum_Redemption_Qty'])];

                           $current_units['order_id'] = $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $min_eq_amt_flag['Minimum_Redemption_Qty'], $morder['order_mode'] , $current_units,  $all_sold_units );
                           $liquid_units_buy_amt = intval($current_eq_nav*$min_eq_amt_flag['Minimum_Redemption_Qty']); // calculating amt in rs from units -fold in equity
                       }else{ //sell all units
                        //die('aaaaaaaaaaaaaaaaaaaaaaadsaa');
                        $current_units['comment'] = json_encode(array('redemption' => 'Eq Units less than Min Redemp quantity'));
                         if(!empty($c_unitseq) && $c_unitseq >= 0.1 ){ //if no units at all
                              $current_units['rebal_buy_amt'] = $eq_sell_units * $current_eq_nav;

                              $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units, 1 );

                            $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                        }else{
                         $liquid_units_buy_amt = 0; // insufficent units hence no eq units sold
                        }



                       }
                       
                       $current_units['rebalance_buy_liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav ;
                       $current_units['rebalance_buy_amt'] = $this->round_nearest($liquid_units_buy_amt,$multiple ); 
                       //x($current_units['rebalance_buy_amt']);
                       if($lcheck){ //add regular sip amt if ledger amt available
                        $liquid_units_buy_amt += $total_sip;
                        $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                       }
                       else if($mcheck){ //approved MFD mandate for OTM
                          $liquid_units_buy_amt += $total_sip;
                          $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                        }
                       // x($liquid_units_buy_amt);
                       //if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $liquid_units_buy_amt){ //min amt condition for lumpsum
                           // if(   ($liquid_units_buy_amt * 1000) % ($min_liquid_amt_flag['Purchase_Amount_Multiplier'] * 1000 )  == 0 ){ //multiple of amt multiple
                                 $morder['amount'] = $liquid_units_buy_amt;
                                 $morder['scheme_code'] = $morder['liquid_scheme_code'];
                                 $morder['liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav;
                                 $morder['units'] = 0;
                                // $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                                  $morder['amount'] = $this->round_nearest($morder['amount'],$multiple); //rounding off to
                                  if($lcheck){
                          
                                   $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                                   }else if($mcheck){ //approved MFD mandate for OTM
                                       $order_id = $this->purchaseSmartLumpsum($morder,$current_units,'liquid','MFDP_L');
                                     if(!empty($order_id)){
                                        $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                      
                                     }

                                  }else{
                                     $current_units['comment'] = json_encode(array('purchase' => 'Insufficent ledger. Only sold units bought'));

                                      $morder['amount'] = $this->round_min($morder['amount'],$multiple); //rounding to lower value if units amt not in multiple

                                    //buy only if more than minimum
                                    if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $morder['amount']){
                                       $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ

                                    }
                                    else if($min_liquid_amt_flag_default['Minimum_Purchase_Amount'] <= $morder['amount']) //units sold not sufficent for minimum purchase amt for liquid
                                    {   
                                       $current_units['buy_liquid_units_default'] = $morder['amount'] / $current_liquid_nav_default;
                                       $morder['scheme_code'] = $morder['liquid_scheme_code_default'];
                                       $this->purchaseSmartLumpsum($morder,$current_units,'default_liquid');


                                    }else{
                                      $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                                       $this->insertSkipOrder($skip_data);
                                    }
                                  }

                        //     }else{ //not satisfying multiple amt criteria

                        //     }

                        // }

                    }
                  else if($action == 'skip'){
                        $morder['scheme_code'] = $morder['liquid_scheme_code'];
                        $current_units['buy_liquid_units'] = $morder['amount'] / $current_liquid_nav;
                        $morder['units'] = 0;
                        //x($morder['buy_liquid_units']);

                         if($lcheck){
                          
                           $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                         }else if($mcheck){ //approved MFD mandate for OTM
                           if($mcheck['mandate_amt'] >= $morder['amount']){
                             $order_id = $this->purchaseSmartLumpsum($morder,$current_units,'liquid','MFD');
                          }

                           if(!empty($order_id)){
                              $this->OTMPayment($mcheck['mandate_id'], $order_id);
                            
                           }

                        }else{
                          //skip order
                           $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                           $this->insertSkipOrder($skip_data);

                        }

                   }

                }// foreach end for all clients in master


       }

      public function oldreconcileOrders($date, $fixed_date = '')
       {

           /*fetching all orders for same day date*/
            $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*,mf_order_smart_sip.id as update_id,mf_order_smart_sip.buy_sell as buy_sell,mf_order_smart_sip.quantity as quantity,mf_order_smart_sip.amount as amount,mf_order_smart_sip.order_id as order_id";
            $params['table_name']       = 'mf_order_smart_sip';
            $params['join']             =  TRUE;  
            $params['join_table']       = 'mf_master_smart_sip';  
            $params['join_on']          = 'mf_order_smart_sip.master_id = mf_master_smart_sip.id';
            $params['join_type']        = 'INNER';
            $params['where']            = TRUE;
            $params['where_data']       = ['mos_date' => $date, 'mf_order_smart_sip.order_id !=' => ''];
            $params['return_array']     = True;
          // $params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);
            //x($master_orders);
            foreach($master_orders as $morder){
              // x($morder);
               $where_navdate = $this->getMongoDate($date);
            
               // x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], '', $morder['update_id']);
                $current_units_previous = $this->getPreviousSmartTransaction($morder['client_id'], $morder['update_id']);
                y($current_units_previous);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                

                error_reporting(-1);
                ini_set('display_errors', 1);
                $current_liquid_nav = $this->getNextDayNav($morder['accord_liquid_scheme_code'], $where_navdate) ;
                $current_liquid_nav_default = '4397.63'; //$this->getCurrentNav($morder['accord_liquid_scheme_code_default'], $date) ;
                $current_eq_nav = $this->getNextDayNav($morder['accord_scheme_code'], $where_navdate );
                $update_units = [];
               // print_r($current_liquid_nav); echo'---';print_r($current_eq_nav);  die;
                 // if(empty($morder['mandate_id'])){
                 //      $key = 1; // mfd order
                 //  }else{
                 //       $key = 2; // mfi order
                 //  }
                  $key = 2;
                if($morder['buy_sell'] == 'P'){
                  if( $morder['base_signal'] == 'invest' || $morder['base_signal'] == 'double_invest' ){ //equity purchase
                    $rec_buy_eq_units =  $morder['amount'] / $current_eq_nav;
                    $update_units['closing_eq_units_prev'] = $current_units['closing_eq_units'];
                    
                    $datan['orderid']= $morder['order_id']; 
                    //hitting bse api to get exact units
                   

                    $res  = $this->bsestar_mutualfund->bse_redemption_api($datan,$key); //mfi order
                    if(!empty($res)){
                      $update_units['closing_eq_units'] =  $current_units_previous['closing_eq_units'] + $res['AllottedUnit']; 
                    }else{
                      die('api failed');
                      $update_units['closing_eq_units'] =  $current_units_previous['closing_eq_units'] + $rec_buy_eq_units;
                    }

                  }
                  else  if( $morder['base_signal'] == 'sell' || $morder['base_signal'] == 'skip' ){ //liquid purchase
                     $rec_buy_liquid_units =  $morder['amount'] / $current_liquid_nav;
                    $update_units['closing_liquid_units_prev'] = $current_units['closing_liquid_units'];
                    
                    $datan['orderid']= $morder['order_id']; 
                    //hitting bse api to get exact units
                   
                    $res  = $this->bsestar_mutualfund->bse_redemption_api($datan,$key); //mfi order

                    if(!empty($res)){
                      $update_units['closing_liquid_units'] =  $current_units_previous['closing_liquid_units'] + $res['AllottedUnit']; 
                    }else{
                      $update_units['closing_liquid_units'] =  $current_units_previous['closing_liquid_units'] + $rec_buy_liquid_units;
                    }
                  }
                }
                else if($morder['buy_sell'] == 'R'){
                  if(  $morder['base_signal'] == 'double_invest' ){ //liquid sell 
                        $datan['orderid']= $morder['order_id']; 

                       $res  = $this->bsestar_mutualfund->bse_allotment_api($datan,$key); //mfi order
                       if(!empty($res)){
                          $rem_balance_amt =  $res['Amt'];
                       }else{
                          $rem_balance_amt =  $morder['quantity'] * $current_liquid_nav; 
                          $rem_balance_amt -=  11.8;//11.8 rs gst + transaction charges
                       }

                       $update_units['reconciled_amt'] = $morder['rebal_buy_amt'] - $rem_balance_amt;



                   }
                   else  if( $morder['base_signal'] == 'sell'  ){ //equity sell 
                        $datan['orderid']= $morder['order_id']; 

                       $res  = $this->bsestar_mutualfund->bse_allotment_api($datan,$key); //mfi order
                       if(!empty($res)){
                          $rem_balance_amt =  $res['Amt'];
                       }else{
                          $rem_balance_amt =  $morder['quantity'] * $current_eq_nav; 
                          $rem_balance_amt -=  11.8;//11.8 rs gst + transaction charges
                       }

                       $update_units['reconciled_amt'] = $morder['rebal_buy_amt'] - $rem_balance_amt;

                   }
                }
                  $update_units['reconcile_done'] = 1;
                  $where = ['id' => $morder['update_id'] ];
                 // y($where);
                   y($update_units);
                  //$this->updateSmartOrder($update_units, $where );
                  
     

                }// foreach end for all clients in master
           }
           
          public function reconcileOrders( $fixed_date = '',$master_id='',$type=2)
           {

            $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*,mf_order_smart_sip.id as update_id,mf_order_smart_sip.buy_sell as buy_sell,mf_order_smart_sip.quantity as quantity,mf_order_smart_sip.amount as amount,mf_order_smart_sip.order_id as order_id";
            $params['table_name']       = 'mf_order_smart_sip';
            $params['join']             =  TRUE;  
            $params['join_table']       = 'mf_master_smart_sip';  
            $params['join_on']          = 'mf_order_smart_sip.master_id = mf_master_smart_sip.id';
            $params['join_type']        = 'INNER';
            $params['where']            = TRUE;
            if(!empty($master_id))
            {
            $params['where_data']       = [ 'mf_order_smart_sip.order_status' => 0,'mf_order_smart_sip.order_id !=' => '','mf_order_smart_sip.base_signal !=' => '', 'reconcile_done' => 0, 'mf_order_smart_sip.master_id'=>$master_id];
            $params['order_by']     = 'mf_order_smart_sip.id asc';
            }else{
               $params['where_data']       = [ 'mf_order_smart_sip.order_status' => 0,'mf_order_smart_sip.order_id !=' => '','mf_order_smart_sip.base_signal !=' => '', 'reconcile_done' => 0, 'order_executed_day <' => date('Y-m-d') ]; 
               $params['order_by']     = 'mf_order_smart_sip.id asc';
            }
            if($type == 1){
              // considering only OTM orders
              $params['where_data']['mf_order_smart_sip.otm_mandate_id !='] = '';
            }
            
            $params['return_array']     = True;
            
          //$params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);
        //x($master_orders);
            foreach($master_orders as $morder){
              // x($morder);
               $where_navdate = $this->getMongoDate($date);
            
               // x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], '', $morder['update_id']);
                $current_units_previous = $this->getPreviousSmartTransaction($morder['client_id'], $morder['update_id'],$morder['id']);
                //x($current_units_previous);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                

                error_reporting(-1);
                ini_set('display_errors', 1);
                $current_liquid_nav = $this->getNextDayNav($morder['accord_liquid_scheme_code'], $where_navdate) ;
                $current_liquid_nav_default = '4397.63'; //$this->getCurrentNav($morder['accord_liquid_scheme_code_default'], $date) ;
                $current_eq_nav = $this->getNextDayNav($morder['accord_scheme_code'], $where_navdate );
                $update_units = [];
               // print_r($current_liquid_nav); echo'---';print_r($current_eq_nav);  die;
                 // if(empty($morder['mandate_id'])){
                 //      $key = 1; // mfd order
                 //  }else{
                 //       $key = 2; // mfi order
                 //  }
                  $key = $type;
                if($morder['buy_sell'] == 'P'){
                  if( $morder['base_signal'] == 'invest' || $morder['base_signal'] == 'double_invest' ){ //equity purchase
                    $rec_buy_eq_units =  $morder['amount'] / $current_eq_nav;
                    $update_units['closing_eq_units_prev'] = $current_units['closing_eq_units'];
                    
                    $datan['orderid']= $morder['order_id']; 
                    //hitting bse api to get exact units
                   

                    $res  = $this->bsestar_mutualfund->bse_allotment_api($datan,$key); //mfi order
                    $this->insert_log($datan,$res,$morder['client_id']); // add log by mannu
                    if(!empty($res)){
                      $update_units['closing_eq_units'] =  $current_units_previous['closing_eq_units'] + $res['AllottedUnit']; 
                    }else{ 
                        y($morder);
                       y('BSE ALLOTMENT STATUS API FAILED');
                       continue;
                      $update_units['closing_eq_units'] =  $current_units_previous['closing_eq_units'] + $rec_buy_eq_units;
                    }

                  }
                  else  if( $morder['base_signal'] == 'sell' || $morder['base_signal'] == 'skip' ){ //liquid purchase
                     $rec_buy_liquid_units =  $morder['amount'] / $current_liquid_nav;
                    $update_units['closing_liquid_units_prev'] = $current_units['closing_liquid_units'];
                    
                    $datan['orderid']= $morder['order_id']; 
                    //hitting bse api to get exact units
                   
                    $res  = $this->bsestar_mutualfund->bse_allotment_api($datan,$key); //mfi order
                    $this->insert_log($datan,$res,$morder['client_id']);
                    if(!empty($res)){
                      $update_units['closing_liquid_units'] =  $current_units_previous['closing_liquid_units'] + $res['AllottedUnit']; 
                    }else{
                        y('BSE ALLOTMENT STATUS API FAILED');

                      $update_units['closing_liquid_units'] =  $current_units_previous['closing_liquid_units'] + $rec_buy_liquid_units;
                    }
                  }
                }
                else if($morder['buy_sell'] == 'R'){
                  if(  $morder['base_signal'] == 'double_invest' ){ //liquid sell 
                        $datan['orderid']= $morder['order_id']; 

                       $res  = $this->bsestar_mutualfund->bse_redemption_api($datan,$key); //mfi order
                       $this->insert_log($datan,$res,$morder['client_id']);
                       if(!empty($res)){
                          $rem_balance_amt =  $res['Amt'];
                       }else{
                             y($morder);
                             y('BSE ALLOTMENT STATUS API FAILED');
                             continue;

                          $rem_balance_amt =  $morder['quantity'] * $current_liquid_nav; 
                          $rem_balance_amt -=  11.8;//11.8 rs gst + transaction charges
                       }

                       $update_units['reconciled_amt'] = $rem_balance_amt;



                   }
                   else { //equity sell  // if( $morder['base_signal'] == 'sell'  )
                        $datan['orderid']= $morder['order_id']; 
                       $res  = $this->bsestar_mutualfund->bse_redemption_api($datan,$key); //mfi order
                       $this->insert_log($datan,$res,$morder['client_id']);
                       //x($res);
                       if(!empty($res)){
                          $rem_balance_amt =  $res['Amt'];
                       }else{
                           y($morder);
                           y('BSE ALLOTMENT STATUS API FAILED');
                           continue;

                          $rem_balance_amt =  $morder['quantity'] * $current_eq_nav; 
                          $rem_balance_amt -=  11.8;//11.8 rs gst + transaction charges
                       }

                       $update_units['reconciled_amt'] = $rem_balance_amt;

                   }
                }
                  $update_units['reconcile_done'] = 1;
                  $update_units['recent_order_status'] = 2;
                  $update_units['recent_order_response'] = 'VALID :: ALLOTMENT DONE while doing reconcileOrders';
                  $where = ['id' => $morder['update_id'] ];
//                 $this->db->reconnect(); 
                  //x($update_units);
                 $this->updateSmartOrder($update_units, $where );
                  //x($update_units);
     

                }// foreach end for all clients in master


       }

      public function reconcileOrdersProcessList($date = '', $fixed_date = '',$type=2)
       {
            if($date == ''){
                $date = date("Y-m-d");
            }
            else{
                $date = date("Y-m-d", strtotime($date));
            }

           /*fetching all orders for same day date*/
            $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "mf_order_process_list_smartsip.id,mf_order_process_list_smartsip.order_type,mf_order_process_list_smartsip.scheme_code,mf_order_process_list_smartsip.redemption_order_id,mf_order_smart_sip.quantity,mf_order_smart_sip.base_signal,mf_order_smart_sip.mos_date,mf_master_smart_sip.accord_scheme_code,mf_master_smart_sip.accord_liquid_scheme_code,mf_master_smart_sip.client_id";
            $params['table_name']       = 'mf_order_process_list_smartsip';
            $params['join']             =  TRUE;  
            $params['multiple_joins']=   TRUE;

            $params['join_table']       = ['mf_order_smart_sip','mf_master_smart_sip'];  
            $params['join_on']          = ['mf_order_process_list_smartsip.redemption_order_id = mf_order_smart_sip.order_id AND mf_order_process_list_smartsip.master_id = mf_order_smart_sip.master_id','mf_order_smart_sip.master_id = mf_master_smart_sip.id' ] ;
            $params['join_type'] = array('INNER','INNER');
            $params['where']            = TRUE;
            $params['where_data']       = ['mf_order_process_list_smartsip.reconcile_done' => 0, 'to_be_executed' => 1, 'redemption_order_id !=' => 0, 'mf_order_process_list_smartsip.process_date' => $date];
            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);
          
            foreach($master_orders as $morder){
              // x($morder);
               $update_units = [];
                  $key = $type;
                if($morder['order_type'] == 'lumpsum'){
                    // $rec_buy_eq_units =  $morder['amount'] / $current_eq_nav;
                    // $update_units['closing_eq_units_prev'] = $current_units['closing_eq_units'];
                    
                    $datan['orderid']= $morder['redemption_order_id']; 

                    // JIRA ID: RAN-2886. STARTS
                    if(empty(intval($datan['orderid']))){
                        // checking whether redemption order id is ZERO or not
                        // if it's ZERO then marking that order entry as to_be_executed = 0
                        $update_units['to_be_executed'] = 0;
                        $update_units['reconcile_done'] = 1;
                        $where = ['id' => $morder['id'], 'redemption_order_id' => $morder['redemption_order_id'], 'client_id' => $morder['client_id']];
                        y('Redemption order id found to be ZERO, so marking entry as not to be executed');
                        y($where); y($update_units);
                        $ret =  $this->updateSmartOrderProcessList($update_units, $where );
                        continue;
                        y('This should not be printed, because it is after CONTINUE statement');
                    }
                    // JIRA ID: RAN-2886. ENDS

                    //hitting bse api to get exact units
                   

                    $res  = $this->bsestar_mutualfund->bse_redemption_api($datan,$key); //mfi order
		    y($res, '1. res');
                     // if(false){
                   // x($res);
                    if(!empty($res)){
                      $update_units['amount'] =  $amt_redemed = $res['Amt'];
                      $original_redemption_amount = $res['Amt'];y($res['Amt'], '1. redemption amount');
                      $sum_of_redemption_amount = 0;
                      /* Dividing amount if for same order have more than one entries in MySQL table: mf_order_smart_sip. STARTS */
                      //Commented by Rajesh
                     /**   $order_smartsip_params = [];
                        $order_smartsip_params['env']              = 'default';
                        $order_smartsip_params['select_data']      = "*";
                        $order_smartsip_params['table_name']       = 'mf_order_smart_sip';
                        $order_smartsip_params['where']            = TRUE;
                        $order_smartsip_params['where_data']       = array('order_id' => $morder['redemption_order_id']);
                        $order_smartsip_params['return_array']     = TRUE;
                        // $order_smartsip_params['print_query']   = TRUE;
                        $order_smartsip_records      = $this->mf->get_table_data_with_type($order_smartsip_params);y($order_smartsip_records, '1. order_smartsip_records');
                        if(is_array($order_smartsip_records) && count($order_smartsip_records) > 1){
                            $no_of_orders_with_same_order_id = count($order_smartsip_records);
                            if($no_of_orders_with_same_order_id > 1){
                                $order_smartsip_records = array_column($order_smartsip_records, NULL, 'master_id');y($order_smartsip_records,'2. order_smartsip_records');
                                if(isset($order_smartsip_records[$morder['master_id']]) && isset($order_smartsip_records[$morder['master_id']]['quantity']) && !empty($order_smartsip_records[$morder['master_id']]['quantity']) && isset($res['Nav']) && !empty($res['Nav'])){
                                    $update_units['amount'] = $order_smartsip_records[$morder['master_id']]['quantity'] * $res['Nav'];
				    y($order_smartsip_records[$morder['master_id']], '3. order_smartsip_record');
				    y($order_smartsip_records[$morder['master_id']]['quantity'], 'quantity');
				    y($res['Nav'],'Nav');
				    y($update_units['amount'], 'update_units[amount]');
                                    $sum_of_redemption_amount += $update_units['amount'];
                                }
                            }
                        }
                        unset($order_smartsip_records);
                    */ 
                    /* Dividing amount if for same order have more than one entries in MySQL table: mf_order_smart_sip. ENDS */


                      $min_scheme_amt_flag = $this->getSchemeMinValues($morder['scheme_code'],$morder['client_id']); //storing min purchase, multiplier flags
                      if( $amt_redemed >=  $min_scheme_amt_flag['Minimum_Purchase_Amount'] ){
                        $mult = ceil($min_scheme_amt_flag['Purchase_Amount_Multiplier']);
                        $update_units['amount'] = $this->round_min($amt_redemed, $mult);
                        $update_units['balance_amount'] =   $amt_redemed - $update_units['amount'];
                        $update_units['amount_recieved'] =   $amt_redemed;

                      }

                    }else{
                       $date = $morder['mos_date'];
                        $where_navdate = $this->getMongoDate($date);

                       $current_liquid_nav = $this->getNextDayNav($morder['accord_liquid_scheme_code'], $where_navdate) ;

                       $current_eq_nav = $this->getNextDayNav($morder['accord_scheme_code'], $where_navdate );
                       if($morder['base_signal'] == 'sell'){
                         $sellqty = $current_eq_nav;
                       }else{
                         $sellqty = $current_liquid_nav;

                       }
                         
                        $amtt = $morder['quantity'] * $sellqty;
                        $update_units['amount'] =  $amt_redemed =  $amtt; 
                        $min_scheme_amt_flag = $this->getSchemeMinValues($morder['scheme_code'],$morder['client_id']); //storing min purchase, multiplier flags
                      // x($min_scheme_amt_flag);
                        $mult = ceil($min_scheme_amt_flag['Purchase_Amount_Multiplier']);

                        if( $amt_redemed >=  $min_scheme_amt_flag['Minimum_Purchase_Amount'] ){
                          $update_units['amount'] = $this->round_min($amt_redemed, $mult);
                          $update_units['balance_amount'] =   $amt_redemed - $update_units['amount'];
                          $update_units['to_be_executed'] =   0;
                         }else{
                           $update_units['balance_amount'] =   $amt_redemed;
                           $update_units['to_be_executed'] =   0;
                           $update_units['remark'] =   'less than minimum amt required';
                           $update_units['amount'] = $this->round_min($amt_redemed, $mult);

                           


                         }

                      }
                
                    $update_units['reconcile_done'] = 1;
                    $where = ['id' => $morder['id'] ];
                 y($where); y($update_units);
                   $ret =  $this->updateSmartOrderProcessList($update_units, $where );
                   
                  
     

              } 

            }// foreach end

             if($ret){
                       $response = array('order_status' => 'success', 'msg' => 'Orders reconciled');
                       echo json_encode($response);

               }
       }


       public function fetchExecuteOrders($date, $mos = '')
       {

           /*fetching all orders for same day date*/
            $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*";
            $params['table_name']       = 'mf_master_smart_sip';
            $params['where']            = TRUE;
            $params['where_data']       = 'day(start_date) = '.$today;

            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);

        //  print_r($master_orders ); die;
            foreach($master_orders as $morder){
                $where_navdate = $this->getMongoDate($date);
                $result = $this->mongo_db->where(array('nav_date' => $where_navdate, 'schemecode' => $morder['accord_scheme_code'] ))->get('mf_smart_sip_eq_momentum')[0]; // getting Margin of Safety for todays date for schemecode
                while (empty($result)){
                    $date = date('Y-m-d', strtotime($date."+1 day"));
                    $navdate = $this->getMongoDate( $date);
                    $result= $this->mongo_db->where(array('nav_date' => $navdate, 'schemecode' => $morder['accord_scheme_code'] ))->get('mf_smart_sip_eq_momentum')[0];

                  }

                  //x($result);

               $mos = $morder['margin_of_safety'] = $result['margin_safety'];
                //code for signal calculation
                $signal = $this->getSignals($result['margin_safety']);
                 $action = $signal['baseSafetyLavel']; //'sell'; //'invest','sell','skip','double_invest'
                 $rbalance = $signal['rebalanceSafetyLevel'];//'very_aggressive'; //'very_aggressive', 'aggressive'
                 $rebalance_sell = $signal['rebalanceSafetyLevel']; //'aggressive_sell_sell'; // 'aggressive_sell', 'very_aggressive_sell_sell'
                $morder['base_signal'] = $action;
                // x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], $morder['id']);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                $current_units['base_signal'] =  $action;
                $current_units['order_mode'] = $morder['order_mode'];
                $current_units['margin_of_safety'] = $morder['margin_of_safety'];
                error_reporting(-1);
                ini_set('display_errors', 1);
                $current_liquid_nav = $this->getCurrentNav($morder['accord_liquid_scheme_code'], $date) ;
                $current_eq_nav = $this->getCurrentNav($morder['accord_scheme_code'], $date );
               // print_r($current_liquid_nav); print_r($current_eq_nav);  die;
                $min_eq_amt_flag = $this->getSchemeMinValues($morder['scheme_code']); //storing min purchase, multiplier flags
                $min_liquid_amt_flag = $this->getSchemeMinValues($morder['liquid_scheme_code']);//storing min purchase, multiplier flags
                $lcheck = $this->checkIfLedgerSufficent($morder['amount'], $morder['client_id']); //checking if ledger sufficent for transaction
                $mcheck = $this->checkMFDMandate($morder['amount'], $morder['client_id']); //checking if approved MFD mandate for OTM is available incase of insuff led balance
               // x($mcheck);
            // print_r($min_eq_amt_flag); print_r($min_liquid_amt_flag); die;
                $additional_investment = $morder['additional_investment'];
                if($action == 'invest'){
                    $morder['units'] = $morder['amount'] / $current_eq_nav;
                    $morder['liquid_units'] = $current_units['rebalance_buy_eq_units'] =  0;
                    $current_units['buy_eq_units'] =   $morder['units'];
                   // x($lcheck);
                    if($lcheck){
                        //$this->purchaseSmartLumpsum($morder, $current_units);
                    }else if($mcheck){ //approved MFD mandate for OTM 
                        if($mcheck['mandate_amt'] >= $morder['amount']){
                           $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                        }

                        if(!empty($order_id)){
                            $this->OTMPayment($mcheck['mandate_id'], $order_id);
                          
                         }

                    }else{
                      //skip order
                       $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                      // $this->insertSkipOrder($skip_data);
                    }
                }elseif($action == 'double_invest'){
                         $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                         $multiple = 10; //hardcoded

                       if($additional_investment){
                          $morder['amount'] += $morder['amount']; //addn investment

                        }
                        $total_sip = $morder['amount'];
                         $amount_regular =  $morder['amount'];
                     //sell SIP amt in liquid if true and available
                          $c_unitsliquid = mround($current_units['closing_liquid_units'],1);
                        if(empty($c_unitsliquid) || $c_unitsliquid <= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){
                            $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                            $morder['liquid_units'] = 0;
                            $current_units['comment'] = json_encode(array('purchase' => 'No Liquid units or less thatn redemption qty'));

                            //by default equity
                             if($lcheck){
                               $this->purchaseSmartLumpsum($morder, $current_units);
                             }else if($mcheck){
                                  if($mcheck['mandate_amt'] >= $morder['amount']){
                                     $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                                  }
                                   if(!empty($order_id)){
                                      $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                   }

                            }else{
                              //skip order
                              $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                       $this->insertSkipOrder($skip_data);
                            }

                        }else{
                            $sipAmtUnits = mround($morder['amount']/$current_liquid_nav, 1);
                            if(($current_liquid_nav * $current_units['closing_liquid_units'] ) >= $morder['amount'] || $c_unitsliquid >= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){ //checking if sip amt in liquid units worth is available

                                //$total_sip += $morder['amount'];
                                // print_r(  $current_units['closing_liquid_units'] ); die;
                                if(round($current_units['closing_liquid_units']) >= 1 ){

                                 if(!$additional_investment){
                                  $rebalance_add_eq_amt = intval($sipAmtUnits*$current_liquid_nav);
                                  $rebalance_add_eq_units =  $rebalance_add_eq_amt / $current_eq_nav;
                                   $current_units['closing_liquid_units'] -=  $sipAmtUnits; //updating closing units(regular sip amt deducted in units)

                                  }else{

                                  }

                                    //sell % of units depending on Rebalance signal (~50% or ~100%)
                                 if($rbalance == 'very_aggressive'){ //sell ~100% of liquid units ,invest in regular SIP
                                    $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_vaggr_invest_per'],1) ;
                                   $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units

                                  $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                  if($this->setting['rebalance_vaggr_invest_per'] == 1 )
                                  {
                                    $sell_all_units = 1;
                                  }else{
                                     $sell_all_units = 0;

                                  }
                                    $sell_all_units = 0;    // re-setting variable here because redeeming all liquid units causing an issue
                                    $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units, $sell_all_units);
                                    //print_r($total_sip); die;
                                     $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                      $current_units['rebalance_buy_amt'] = $units_sell * $current_liquid_nav; 
                                     //print_r($total_sip); die;
                                      $current_units['rebalance_buy_amt'] = $this->round_nearest($current_units['rebalance_buy_amt'],$multiple ); 
                                   }
                                   else if($rbalance == 'aggressive'){ //sell ~50% of liquid units, invest in regular SIP
                                        $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_aggr_invest_per'],1);
                                        $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                        //print_r($units_sell); die;
                                        $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                        $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units);
                                        $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                       }
                                   }

                                  }else{ //sell whatever remaining units are available
                                     $current_units['comment'] = json_encode(array('redemption' => 'liquid Units less than Min Redemp quantity'));

                                      $units_sell_rb = mround($current_units['closing_liquid_units'],1) ;
                                     // print_r($current_units['closing_liquid_units']); die;
                                    //  $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                      $units_sell_rb_amt = $current_units['closing_liquid_units'] * $current_liquid_nav;
                                      $units_sell_rb_amt = $this->round_min($units_sell_rb_amt,$multiple); //rounding to lower value if units amt not in multiple
                                      $units_sell_rb = $units_sell_rb_amt / $current_liquid_nav;

                                        $all_sold_units = ['sell_liquid_units' => $units_sell_rb, 'rebalance_units' => 0 ]; //rebalance_units = units minused from closing liquid
                                        $current_units['closing_liquid_units'] -= $units_sell_rb; // in this condn closing units needs to be updated
                                        $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell_rb ,$morder['order_mode'], $current_units,$all_sold_units,0);
                                        $total_sip += ($units_sell_rb * $current_liquid_nav);

                                  }
                                //purchase regular SIP + liquid units sold
                                  $morder['amount'] = $total_sip; //amt recalulated
                                  $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                  $morder['liquid_units'] = 0;
                                   $eq_units_buy_amt = intval($current_liquid_nav*$units_sell_rb); // calculating amt in rs from units fold in equity
                                  $current_units['rebalance_buy_eq_units'] = $eq_units_buy_amt / $current_eq_nav ;
                                // x($current_units['rebalance_buy_eq_units']);

                                  $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                                  print_r($morder['amount'] );
                                  // $current_units['buy_eq_units'] -= $units_sell_rb;
                                  // $current_units['buy_eq_units'] -= $rebalance_add_eq_units;
                                  $current_units['secondary_base_buy_eq_units'] = $rebalance_add_eq_units;
                                 // $morder['units'] =  $current_units['buy_eq_units'];
                                  //$current_units['closing_eq_units'] += $rebalance_add_eq_units ;
                                 
                                  $morder['amount'] = $this->round_nearest($morder['amount'],$multiple ); //rounding off to nearest mutliple of purchase multiplier
                                  if($lcheck){
                                   $this->purchaseSmartLumpsum($morder,$current_units);
                                  }
                                  else if($mcheck){
                                    if($mcheck['mandate_amt'] >= $morder['amount']){
                                       $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                                    }
                                     if(!empty($order_id)){
                                        $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                     }

                                  }else{
                                    //skip order
                                  }
                            }

                        }
                    elseif($action == 'sell'){ // sell eq and buy liquid
                         $multiple = ceil($min_liquid_amt_flag['Purchase_Amount_Multiplier']);
                         $multiple = 10; //hardcoded

                        $total_sip = $morder['amount'];
                        $eq_sell_units_rb = $liquid_units_buy_amt = 0;
                       $eq_sell_units_eq = $current_units['closing_eq_units'] * $this->setting['base_sell_units_per']; //selling specified % of equity units from setting (approx 15%)
                       //-----------// $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                       // $min_eq_amt_flag['Minimum_Redemption_Qty'] = 50;
                     //  echo $current_units['closing_eq_units']; die;
                       if($rebalance_sell == 'very_aggressive_sell'){
                            $eq_sell_units_rb = $this->setting['rebalance_vaggr_sell_units_per'] * $current_units['closing_eq_units'];
                         }elseif($rebalance_sell == 'aggressive_sell'){
                          $eq_sell_units_rb = $this->setting['rebalance_aggr_sell_units_per'] * $current_units['closing_eq_units'] ;
                         }

                         $eq_sell_units = $eq_sell_units_rb + $eq_sell_units_eq;
                         $eq_sell_units = mround($eq_sell_units,2);
                         //print_r($this->setting['rebalance_aggr_sell_units_per']); echo '<pre>'; 
                      //   print_r($eq_sell_units_rb); die;
                      if($eq_sell_units > $min_eq_amt_flag['Minimum_Redemption_Qty'] ){
                         // $current_units['closing_eq_units'] -= $eq_sell_units_rb; //updating equity units
                         // $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                         $current_units['closing_eq_units'] -= $eq_sell_units; //updating equity units
                         $all_sold_units = ['sell_eq_units' => $eq_sell_units_eq, 'rebalance_sell_eq_units' => $eq_sell_units_rb];
                         $current_units['rebal_buy_amt'] = $eq_sell_units * $current_eq_nav;

                         $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units );
                         $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                       }
                       elseif(($current_units['closing_eq_units'] > $min_eq_amt_flag['Minimum_Redemption_Qty']) && ($eq_sell_units < $min_eq_amt_flag['Minimum_Redemption_Qty'])){
                           $current_units['closing_eq_units'] -= $min_eq_amt_flag['Minimum_Redemption_Qty'];
                           $current_units['rebal_buy_amt'] = $min_eq_amt_flag['Minimum_Redemption_Qty'] * $current_eq_nav;
                           $all_sold_units = ['sell_eq_units' => ($this->setting['base_sell_units_per'] * $min_eq_amt_flag['Minimum_Redemption_Qty']), 'rebalance_sell_eq_units' => ((1 - $this->setting['base_sell_units_per']) * $min_eq_amt_flag['Minimum_Redemption_Qty'])];

                           $current_units['order_id'] = $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $min_eq_amt_flag['Minimum_Redemption_Qty'], $morder['order_mode'] , $current_units,  $all_sold_units );
                           $liquid_units_buy_amt = intval($current_eq_nav*$min_eq_amt_flag['Minimum_Redemption_Qty']); // calculating amt in rs from units -fold in equity
                       }else{ //sell all units
                        //die('aaaaaaaaaaaaaaaaaaaaaaadsaa');
                        $current_units['comment'] = json_encode(array('redemption' => 'Eq Units less than Min Redemp quantity'));
                         $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units, 1 );

                         $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity


                       }
                       ;
                       $current_units['rebalance_buy_liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav ;
                       $current_units['rebalance_buy_amt'] = $this->round_nearest($liquid_units_buy_amt,$multiple ); 
                       //x($current_units['rebalance_buy_amt']);
                       if($lcheck){ //add regular sip amt if ledger amt available
                        $liquid_units_buy_amt += $total_sip;
                        $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                       }
                       else if($mcheck){ //approved MFD mandate for OTM
                          $liquid_units_buy_amt += $total_sip;
                          $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                        }
                       // x($liquid_units_buy_amt);
                       if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $liquid_units_buy_amt){ //min amt condition for lumpsum
                            if(   ($liquid_units_buy_amt * 1000) % ($min_liquid_amt_flag['Purchase_Amount_Multiplier'] * 1000 )  == 0 ){ //multiple of amt multiple
                                 $morder['amount'] = $liquid_units_buy_amt;
                                 $morder['scheme_code'] = $morder['liquid_scheme_code'];
                                 $morder['liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav;
                                 $morder['units'] = 0;
                                 $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                                  $morder['amount'] = $this->round_nearest($morder['amount'],$multiple); //rounding off to
                                  if($lcheck){
                          
                                   $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                                   }else if($mcheck){ //approved MFD mandate for OTM
                                       $order_id = $this->purchaseSmartLumpsum($morder,$current_units,'liquid','MFDP_L');
                                     if(!empty($order_id)){
                                        $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                      
                                     }

                                  }else{
                                    //buy only if more than minimum
                                    if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $morder['amount']){
                                       $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ

                                    }else{
                                      $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                                       $this->insertSkipOrder($skip_data);
                                    }
                                  }

                            }else{ //not satisfying multiple amt criteria

                            }

                        }

                    }
                  else if($action == 'skip'){
                        $morder['scheme_code'] = $morder['liquid_scheme_code'];
                        $current_units['buy_liquid_units'] = $morder['amount'] / $current_liquid_nav;
                        $morder['units'] = 0;
                        //x($morder['buy_liquid_units']);

                         if($lcheck){
                          
                           $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                         }else if($mcheck){ //approved MFD mandate for OTM
                           if($mcheck['mandate_amt'] >= $morder['amount']){
                             $order_id = $this->purchaseSmartLumpsum($morder,$current_units,'liquid','MFD');
                          }

                           if(!empty($order_id)){
                              $this->OTMPayment($mcheck['mandate_id'], $order_id);
                            
                           }

                        }else{
                          //skip order
                           $skip_data = ['master_id' => $morder['id'],
                                        'client_id' => $morder['client_id'],
                                        'scheme_code' => $morder['liquid_scheme_code'],
                                        'amount' => $morder['amount'],
                                        'sip_date' => $date,
                                        'reason' => 'Insufficient Ledger'
                                        ];
                           $this->insertSkipOrder($skip_data);

                        }

                   }

                }// foreach end for all clients in master


       }

        public function fetchExecuteOrders2($date, $mos = '')
       {

           /*fetching all orders for same day date*/
            $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*";
            $params['table_name']       = 'mf_master_smart_sip';
            $params['where']            = TRUE;
            $params['where_data']       = 'day(start_date) = '.$today;

            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);

        //  print_r($master_orders ); die;
            foreach($master_orders as $morder){

                $where_navdate = $this->getMongoDate($date);
                $result = $this->mongo_db->where(array('nav_date' => $where_navdate, 'schemecode' => $morder['accord_scheme_code']))->get('mf_smart_sip_eq_momemtum')[0]; // getting Margin of Safety for todays date for schemecode
                while (empty($result)){
                    $date = date('Y-m-d', strtotime($date."+1 day"));
                    $navdate = $this->getMongoDate( $date);
                    $result= $this->mongo_db->where(array('nav_date' => $navdate, 'schemecode' => $morder['accord_scheme_code'] ))->get('mf_smart_sip_eq_momemtum')[0];

                  }

                  //x($result);

               $mos = $morder['margin_of_safety'] = $result['margin_safety'];
                //code for signal calculation
                $signal = $this->getSignals($result['margin_safety']);
                 $action = $signal['baseSafetyLavel']; //'sell'; //'invest','sell','skip','double_invest'
                 $rbalance = $signal['rebalanceSafetyLevel'];//'very_aggressive'; //'very_aggressive', 'aggressive'
                 $rebalance_sell = $signal['rebalanceSafetyLevel']; //'aggressive_sell_sell'; // 'aggressive_sell', 'very_aggressive_sell_sell'
                $morder['base_signal'] = $action;
               // x($signal);
                $current_units = $this->getLastSmartTransaction($morder['client_id'], $morder['id']);
                $current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
                $current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
                $current_units['base_signal'] =  $action;
                $current_units['order_mode'] = $morder['order_mode'];
                $current_units['margin_of_safety'] = $morder['margin_of_safety'];
                error_reporting(-1);
                ini_set('display_errors', 1);
                $current_liquid_nav = $this->getCurrentNav($morder['accord_liquid_scheme_code'], $date) ;
                $current_eq_nav = $this->getCurrentNav($morder['accord_scheme_code'], $date );
               // print_r($current_liquid_nav); print_r($current_eq_nav);  die;
                $min_eq_amt_flag = $this->getSchemeMinValues($morder['scheme_code']); //storing min purchase, multiplier flags
                $min_liquid_amt_flag = $this->getSchemeMinValues($morder['liquid_scheme_code']);//storing min purchase, multiplier flags
                $lcheck = $this->checkIfLedgerSufficent($morder['amount'], $morder['client_id']); //checking if ledger sufficent for transaction
                $mcheck = $this->checkMFDMandate($morder['amount'], $morder['client_id']); //checking if approved MFD mandate for OTM is available incase of insuff led balance
               // x($mcheck);
            // print_r($min_eq_amt_flag); print_r($min_liquid_amt_flag); die;
                $additional_investment = $morder['additional_investment'];
                if($action == 'invest'){
                    $morder['units'] = $morder['amount'] / $current_eq_nav;
                    $morder['liquid_units'] = $current_units['rebalance_buy_eq_units'] =  0;
                    $current_units['buy_eq_units'] =   $morder['units'];
                    if($lcheck){
                        
                        $this->purchaseSmartLumpsum($morder, $current_units);
                    }else if($mcheck){ //approved MFD mandate for OTM
                        if($mcheck['mandate_amt'] >= $morder['amount']){
                           $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                        }

                        if(!empty($order_id)){
                            $this->OTMPayment($mcheck['mandate_id'], $order_id);
                          
                         }

                    }else{
                      //skip order
                    }
                }elseif($action == 'double_invest'){

                       if($additional_investment){
                          $morder['amount'] += $morder['amount']; //addn investment
                        }
                        $total_sip = $morder['amount'];
                         $amount_regular =  $morder['amount'];
                     //sell SIP amt in liquid if true and available
                          $c_unitsliquid = mround($current_units['closing_liquid_units'],1);
                        if(empty($c_unitsliquid) || $c_unitsliquid <= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){
                            $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                            $morder['liquid_units'] = 0;
                            $current_units['comment'] = json_encode(array('purchase' => 'No Liquid units or less thatn redemption qty'));

                            //by default equity
                             if($lcheck){
                               $this->purchaseSmartLumpsum($morder, $current_units);
                             }else if($mcheck){
                                  if($mcheck['mandate_amt'] >= $morder['amount']){
                                     $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                                  }
                                   if(!empty($order_id)){
                                      $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                   }

                            }else{
                              //skip order
                            }

                        }else{
                            $sipAmtUnits = mround( ($morder['amount'] * $this->setting['base_aggr_invest_multiplier'])/$current_liquid_nav, 1);
                            if(($current_liquid_nav * $current_units['closing_liquid_units'] ) >= $morder['amount'] || $c_unitsliquid >= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){ //checking if sip amt in liquid units worth is available

                                //$total_sip += $morder['amount'];
                                // print_r(  $current_units['closing_liquid_units'] ); die;
                                if(round($current_units['closing_liquid_units']) >= 1 ){

                                 if(!$additional_investment){
                                  $rebalance_add_eq_amt = intval($sipAmtUnits*$current_liquid_nav);
                                  $rebalance_add_eq_units =  $rebalance_add_eq_amt / $current_eq_nav;
                                   $current_units['closing_liquid_units'] -=  $sipAmtUnits; //updating closing units(regular sip amt deducted in units)

                                  }else{

                                  }

                                    //sell % of units depending on Rebalance signal (~50% or ~100%)
                                 if($rbalance == 'very_aggressive'){ //sell ~100% of liquid units ,invest in regular SIP
                                    $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_vaggr_invest_per'],1) ;
                                   $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                   
                                   $amt_multiple = $units_sell * $current_liquid_nav;
                                   $amt_multiple = $this->round_nearest($amt_multiple,10); //sell units for amt only in multiple of eq to buy
                                   $units_sell = $amt_multiple/$current_liquid_nav;
                                   $units_sell_rb = abs($units_sell - $sipAmtUnits); //updating incase of difference for multiple amt units


                                  $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                  if($this->setting['rebalance_vaggr_invest_per'] == 1 )
                                  {
                                    $sell_all_units = 1;
                                  }else{
                                     $sell_all_units = 0;

                                  }
                                    $sell_all_units = 0;    // re-setting variable here because redeeming all liquid units causing an issue
                                    $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units, $sell_all_units);
                                    //print_r($total_sip); die;
                                     $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                      $current_units['rebalance_buy_amt'] = $units_sell * $current_liquid_nav; 
                                     //print_r($total_sip); die;
                                      $current_units['rebalance_buy_amt'] = $this->round_nearest($current_units['rebalance_buy_amt'],10 ); 
                                   }
                                   else if($rbalance == 'aggressive'){ //sell ~50% of liquid units, invest in regular SIP
                                        $units_sell_rb = mround($current_units['closing_liquid_units'] * $this->setting['rebalance_aggr_invest_per'],1);
                                        $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                        $amt_multiple = $units_sell * $current_liquid_nav;
                                        $amt_multiple = $this->round_nearest($amt_multiple,10); //sell units for amt only in multiple of eq to buy

                                        $units_sell = $amt_multiple/$current_liquid_nav;
                                        $units_sell_rb = abs($units_sell - $sipAmtUnits); //updating incase of difference for multiple units

                                        //print_r($units_sell); die;
                                        $all_sold_units = ['sell_liquid_units' => $sipAmtUnits, 'rebalance_units' => $units_sell_rb];

                                        $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell, $morder['order_mode'] , $current_units,$all_sold_units);
                                        $total_sip += $units_sell * $current_liquid_nav; // calculating price from liquid units sold
                                       }
                                   }

                                  }else{ //sell whatever remaining units are available
                                     $current_units['comment'] = json_encode(array('redemption' => 'liquid Units less than Min Redemp quantity'));

                                      //$units_sell_rb = mround($current_units['closing_liquid_units'],1) ;
                                      $units_sell_rb_amt = $current_units['closing_liquid_units'] * $current_liquid_nav;
                                      $units_sell_rb_amt = $this->round_min($units_sell_rb_amt,10); //rounding to lower value if units amt not in multiple
                                      $units_sell_rb = $units_sell_rb_amt / $current_liquid_nav;

                                     // print_r($current_units['closing_liquid_units']); die;
                                    //  $units_sell = $sipAmtUnits + $units_sell_rb; //rebalance units+ sip amt sold units
                                        $all_sold_units = ['sell_liquid_units' => $units_sell_rb, 'rebalance_units' => 0 ]; //rebalance_units = units minused from closing liquid
                                        $current_units['closing_liquid_units'] -= $units_sell_rb; // in this condn closing units needs to be updated
                                        $this->sellLiquidUnits($morder['client_id'], $morder['liquid_scheme_code'], $units_sell_rb ,$morder['order_mode'], $current_units,$all_sold_units,0);
                                        $total_sip += ($units_sell_rb * $current_liquid_nav);

                                  }
                                  /* Code to seperate 1st regular eq order*/
                                  $morder['amount'] = $amount_regular;
                                  $current_units['buy_eq_units'] = $amount_regular / $current_eq_nav;
                                  $morder['units'] = $current_units['buy_eq_units'];
                                  if($lcheck){
                                   $this->purchaseSmartLumpsum($morder,$current_units);
                                  }
                                  else if($mcheck){
                                    if($mcheck['mandate_amt'] >= $morder['amount']){
                                       $order_id = $this->purchaseSmartLumpsum($morder, $current_units);
                                    }
                                     if(!empty($order_id)){
                                        $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                     }

                                  }else{
                                    //skip order 
                                  }
                                  $current_units['buy_eq_units'] = 0; //resetting buy eq units
                                  /* Code ends*/

                                //purchase regular SIP + liquid units sold
                                  $morder['amount'] = $total_sip - $amount_regular; //amt recalulated
                                  $morder['units'] = $morder['amount'] / $current_eq_nav; //units to be bought
                                  $morder['liquid_units'] = 0;
                                   $eq_units_buy_amt = intval($current_liquid_nav*$units_sell_rb); // calculating amt in rs from units fold in equity
                                  $current_units['rebalance_buy_eq_units'] = $eq_units_buy_amt / $current_eq_nav ;
                                // x($current_units['rebalance_buy_eq_units']);

                                  print_r($morder['amount'] );
                                  // $current_units['buy_eq_units'] -= $units_sell_rb;
                                  // $current_units['buy_eq_units'] -= $rebalance_add_eq_units;
                                  $current_units['secondary_base_buy_eq_units'] = $rebalance_add_eq_units;
                                 // $morder['units'] =  $current_units['buy_eq_units'];
                                  //$current_units['closing_eq_units'] += $rebalance_add_eq_units ;
                                  $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                                  $morder['amount'] = $this->round_nearest($morder['amount'],10 ); //rounding off to nearest mutliple of purchase multiplier
                                   $this->purchaseSmartLumpsum($morder,$current_units); //sold rebalance mfi order no check order condition

                            }

                        }
                    elseif($action == 'sell'){ // sell eq and buy liquid
                        $total_sip = $morder['amount'];
                        $eq_sell_units_rb = $liquid_units_buy_amt = 0;
                       $eq_sell_units_eq = $current_units['closing_eq_units'] * $this->setting['base_sell_units_per']; //selling specified % of equity units from setting (approx 15%)
                       //-----------// $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                       // $min_eq_amt_flag['Minimum_Redemption_Qty'] = 50;
                     //  echo $current_units['closing_eq_units']; die;
                       if($rebalance_sell == 'very_aggressive_sell'){
                            $eq_sell_units_rb = $this->setting['rebalance_vaggr_sell_units_per'] * $current_units['closing_eq_units'];
                         }elseif($rebalance_sell == 'aggressive_sell'){
                          $eq_sell_units_rb = $this->setting['rebalance_aggr_sell_units_per'] * $current_units['closing_eq_units'] ;
                         }

                         $eq_sell_units = $eq_sell_units_rb + $eq_sell_units_eq;
                         $eq_sell_units = mround($eq_sell_units,2);
                         //print_r($this->setting['rebalance_aggr_sell_units_per']); echo '<pre>'; 
                      //   print_r($eq_sell_units_rb); die;
                      if($eq_sell_units > $min_eq_amt_flag['Minimum_Redemption_Qty'] ){
                         // $current_units['closing_eq_units'] -= $eq_sell_units_rb; //updating equity units
                         // $current_units['closing_eq_units'] -= $eq_sell_units_eq;
                         $current_units['closing_eq_units'] -= $eq_sell_units; //updating equity units
                         $all_sold_units = ['sell_eq_units' => $eq_sell_units_eq, 'rebalance_sell_eq_units' => $eq_sell_units_rb];
                         $current_units['rebal_buy_amt'] = $eq_sell_units * $current_eq_nav;

                         $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units );
                         $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity

                       }
                       elseif(($current_units['closing_eq_units'] > $min_eq_amt_flag['Minimum_Redemption_Qty']) && ($eq_sell_units < $min_eq_amt_flag['Minimum_Redemption_Qty'])){
                           $current_units['closing_eq_units'] -= $min_eq_amt_flag['Minimum_Redemption_Qty'];
                           $current_units['rebal_buy_amt'] = $min_eq_amt_flag['Minimum_Redemption_Qty'] * $current_eq_nav;
                           $all_sold_units = ['sell_eq_units' => ($this->setting['base_sell_units_per'] * $min_eq_amt_flag['Minimum_Redemption_Qty']), 'rebalance_sell_eq_units' => ((1 - $this->setting['base_sell_units_per']) * $min_eq_amt_flag['Minimum_Redemption_Qty'])];

                           $current_units['order_id'] = $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $min_eq_amt_flag['Minimum_Redemption_Qty'], $morder['order_mode'] , $current_units,  $all_sold_units );
                           $liquid_units_buy_amt = intval($current_eq_nav*$min_eq_amt_flag['Minimum_Redemption_Qty']); // calculating amt in rs from units -fold in equity
                       }else{ //sell all units
                        //die('aaaaaaaaaaaaaaaaaaaaaaadsaa');
                        $current_units['comment'] = json_encode(array('redemption' => 'Eq Units less than Min Redemp quantity'));
                         $this->sellEqUnits($morder['client_id'], $morder['scheme_code'], $eq_sell_units, $morder['order_mode'] , $current_units,  $all_sold_units, 1 );

                         $liquid_units_buy_amt = intval($current_eq_nav*$eq_sell_units); // calculating amt in rs from units fold in equity


                       }
                       ;
                       $current_units['rebalance_buy_liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav ;
                       $current_units['rebalance_buy_amt'] = $this->round_nearest($liquid_units_buy_amt,10 ); 
                       //x($current_units['rebalance_buy_amt']);
                       if($lcheck){ //add regular sip amt if ledger amt available
                        $liquid_units_buy_amt += $total_sip;
                        $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                       }
                       else if($mcheck){ //approved MFD mandate for OTM
                          $liquid_units_buy_amt += $total_sip;
                          $current_units['buy_liquid_units'] = $total_sip / $current_liquid_nav;
                        }
                       // x($liquid_units_buy_amt);
                       if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $liquid_units_buy_amt){ //min amt condition for lumpsum
                            if(   ($liquid_units_buy_amt * 1000) % ($min_liquid_amt_flag['Purchase_Amount_Multiplier'] * 1000 )  == 0 ){ //multiple of amt multiple
                                 $morder['amount'] = $liquid_units_buy_amt;
                                 $morder['scheme_code'] = $morder['liquid_scheme_code'];
                                 $morder['liquid_units'] = $liquid_units_buy_amt / $current_liquid_nav;
                                 $morder['units'] = 0;
                                 $multiple = ceil($min_eq_amt_flag['Purchase_Amount_Multiplier']);
                                  $morder['amount'] = $this->round_nearest($morder['amount'],10 ); //rounding off to
                                  if($lcheck){
                          
                                   $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                                   }else if($mcheck){ //approved MFD mandate for OTM
                                       $order_id = $this->purchaseSmartLumpsum($morder,$current_units,'liquid','MFDP_L');
                                     if(!empty($order_id)){
                                        $this->OTMPayment($mcheck['mandate_id'], $order_id);
                                      
                                     }

                                  }else{
                                    //buy only if more than minimum
                                    if($min_liquid_amt_flag['Minimum_Purchase_Amount'] <= $morder['amount']){
                                       $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ

                                    }
                                  }

                            }else{ //not satisfying multiple amt criteria

                            }

                        }

                    }
                  else if($action == 'skip'){
                        $morder['scheme_code'] = $morder['liquid_scheme_code'];
                        $current_units['buy_liquid_units'] = $morder['amount'] / $current_liquid_nav;
                        $morder['units'] = 0;
                        //x($morder['buy_liquid_units']);

                         if($lcheck){
                          
                           $this->purchaseSmartLumpsum($morder,$current_units,'liquid'); //purchasing regular sip amt liquid + sold units in equ
                         }else if($mcheck){ //approved MFD mandate for OTM
                           if($mcheck['mandate_amt'] >= $morder['amount']){
                             $order_id = $this->purchaseSmartLumpsum($morder,$current_units,'liquid','MFD');
                          }

                           if(!empty($order_id)){
                              $this->OTMPayment($mcheck['mandate_id'], $order_id);
                            
                           }

                        }else{
                          //skip order
                        }

                   }

                }// foreach end for all clients in master


       }

       public function purchaseSmartLumpsum($morder,&$current_units,$type = 'equity',$env ='MFI')
       {
          //print_r($current_units);print_r($morder['units']); die;

             $data = array();
            $client = $morder['client_id'];
            $scheme_code = $morder['scheme_code'];
            if(empty($client)){
                redirect(base_url());
            }else{
                $data['trans_code'] = 'NEW' ;
                $data['client_id'] = $client;
                $data['order_id'] = '';

                $data['scheme_code'] = $scheme_code;
                $data['buy_sell'] = 'P';
                $folio = $this->checkFolioNo($scheme_code,$client,$morder['order_mode']);

                if($type == 'equity' && $morder['amount'] >= 200000 ){
                  $data['scheme_code'] = $scheme_code.'-L1';
                }

                if(!empty($folio) ){
                    $data['buy_sell_type'] = 'ADDITIONAL';
                    $data['folio_number']  = $folio;
                }else
                    $data['buy_sell_type'] = 'FRESH';

                if($morder['order_mode'] == 'P'){ //added for physical order
                    $data['DPTxn'] = 'P'; //p for physical
                    $data['order_mode'] = 'P';
                }
                else{
                    $data['DPTxn'] = 'C';
                    $data['order_mode'] = 'D';
                }


                $data['amount'] = intval($morder['amount']);
                $data['quantity'] = '';
               if($env == 'MFI'){
                  $this->bsestar_mutualfund->k = 2;
                }else{
                  $this->bsestar_mutualfund->k = 1;

                }

                 if($env == 'MFDP_L'){ //if mfd OTM then only invest regular sip amt. remaining units thru MFI
                    $data['amount'] = $data['amount'] - $current_units['rebalance_buy_amt'];
                    $res = $this->bsestar_mutualfund->orderEntryParam($data);
                    $this->bsestar_mutualfund->k = 2; //resetiing key to MFI order
                    $data['amount'] = $current_units['rebalance_buy_amt'];
                    $res_mfi = $this->bsestar_mutualfund->orderEntryParam($data);

                    $current_units['comment'] = json_encode(array('order_Status' => 'Eq Units less than Min Redemp quantity'));

                    $data['amount'] = $morder['amount'] - $current_units['rebalance_buy_amt']; //resetting original total amt
                 } else{
                    $res = $this->bsestar_mutualfund->orderEntryParam($data); 
                 }
                 

                $data['order_type'] = 'lumpsum';
                $data['order_id'] = isset($res[2]) ? $res[2] : 0;
                $data['order_status'] = isset($res[7]) ? $res[7] : 1;
                $data['order_response'] = isset($res[6]) ? $res[6] : "Verify Smart/Super SIP Order";
                $data['unique_ref_no'] = isset($res[1]) ? $res[1] : "";


                $data['date_created'] = $date = date('Y-m-d H:i:s');

                $data['created_by'] = $morder['client_id'];
                $data['source']     = $morder['client_id'];
                $data['master_id']     = $morder['master_id'];
                $data['base_signal']     = $morder['base_signal'];
                $data['margin_of_safety']     = $current_units['margin_of_safety'];

                if(isset($data['DPTxn']))
                    unset($data['DPTxn']); //this field is not in database table thats why removed form array
                $params['date'] = date('Y-m-d');
                $params['client_id'] = $client;
                 $params['TransType'] = 'P';
                 $params['order_id'] = $res[2];
                 $data['master_id'] = $morder['id'];

                //$prov_order = $this->bsestar_mutualfund->ProvOrderStatus($params,'MFI');
               //print_r($prov_order);
                if(true){

                    $data['opening_eq_units'] = $current_units['closing_eq_units'];
                    $data['opening_liquid_units'] = $current_units['closing_liquid_units'];

                    if($type == 'equity'){
                       $data['buy_eq_units'] = $current_units['buy_eq_units'];
                       //$data['units'] = $current_units['units'] + $morder['units'];
                       $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];

                       $data['closing_liquid_units'] = $current_units['closing_liquid_units'] + $morder['liquid_units'];
                       $data['rebalance_buy_eq_units'] = $current_units['rebalance_buy_eq_units'];
                       $data['secondary_base_buy_eq_units'] = $current_units['secondary_base_buy_eq_units'];
                       $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;



                    }

                    if($type == 'liquid'){
                     $data['units'] = $current_units['units'] + $morder['units'];

                     $data['buy_liquid_units'] = $current_units['buy_liquid_units'] ;
                     $data['closing_liquid_units'] = $current_units['closing_liquid_units'] + $current_units['buy_liquid_units'] + $current_units['rebalance_buy_liquid_units'] ;
                     $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
                     $data['rebalance_buy_liquid_units'] = $current_units['rebalance_buy_liquid_units'];
                     $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;


                    }

                    if($type == 'default_liquid'){

                     $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] + $current_units['buy_liquid_units_default'] ;
                     $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
                     $data['closing_liquid_units'] = $current_units['closing_liquid_units'] ;

                     $data['buy_liquid_units_default'] = $current_units['buy_liquid_units_default'];

                    }

                }


                 //x($data);
                $data['comment'] = $current_units['comment'];
                $data['mos_date'] = $current_units['mongo_date'];

                $insert_id = $this->insertSmartOrder($data);
                if($env == 'MFDP_L'){
                  $data['order_id'] = $res_mfi[2];
                  $data['order_status'] = $res_mfi[7];
                  $data['order_response'] = $res_mfi[6];
                  $data['unique_ref_no'] = $res_mfi[1];
                  $data['amount'] =  $current_units['rebalance_buy_amt'];
                  $data['comment'] = json_encode(array('order_Status' => 'MFD order 
                    OTM'));
                  $insert_id = $this->insertSmartOrder($data);
                }

                //updating units 
               $current_units['closing_eq_units'] =  $data['closing_eq_units'];
               $current_units['closing_liquid_units'] =  $data['closing_liquid_units'];

                return $res[2];

           }


       }

       public function purchaseSmartLumpsumNew($morder,&$current_units,$type = 'equity',$env ='MFI')
       {
          //print_r($current_units);print_r($morder['units']); die;
            if(isset($morder['amount']) && empty($morder['amount'])){
                // returning from here because amount received for an order seems to be ZERO/BLANK
                return 0;
            }

			if(isset($morder['pg_reference_number']) && empty($morder['pg_reference_number'])){
                // returning from here because amount received for an order seems to be ZERO/BLANK
                return 0;
            }

            $data = array();
            $client = $morder['client_id'];
            $scheme_code = $morder['scheme_code'];
            if(empty($client)){
                redirect(base_url());
            }else{
                $data['trans_code'] = 'NEW' ;
                $data['client_id'] = $client;
                $data['order_id'] = '';

                $data['scheme_code'] = $scheme_code;
                $data['buy_sell'] = 'P';
                $folio = $this->checkFolioNo($scheme_code,$client,$morder['order_mode']);

				$clientinfo = getClientMaster($client);
				$data['client_mobile'] = $clientinfo['client_mobile'];
				$data['client_email']  = $clientinfo['client_email'];
				
                if($type == 'equity' && $morder['amount'] >= 200000 ){
                  $data['scheme_code'] = $scheme_code.'-L1';
                }

				$client_detail[] =  (object)$clientinfo;
				if (!empty($folio)) {
					$data['buy_sell_type'] = 'ADDITIONAL';
					$data['folio_number'] = $folio;
					$allow_smartsip = getSettings("ALLOW_SMARTSIP_2FACTOR");
					if($allow_smartsip==1){
						//Changes made for 2factor authentication By Rajesh
						$foliodata = getFolioData($client,$folio,$scheme_code,'web',1,$client_detail);				
						if(!empty($foliodata)){
							if($foliodata['mismatch_flag']==true){	
								$data['buy_sell_type'] = 'FRESH';
								$data['folio_number'] = '';
							}else{
								$data['client_mobile'] = $foliodata['folio_client_mobile'];
								$data['client_email']  = $foliodata['folio_client_email'];			
							}	
						}
					}
				} else {
					$data['buy_sell_type'] = 'FRESH';
				}
				
                if($morder['order_mode'] == 'P'){ //added for physical order
                    $data['DPTxn'] = 'P'; //p for physical
                    $data['order_mode'] = 'P';
                }
                else{
                    $data['DPTxn'] = 'C';
                    $data['order_mode'] = 'D';
                }


                $data['amount'] = intval($morder['amount']);
                $data['quantity'] = '';
               if($env == 'MFI'){
                  $this->bsestar_mutualfund->k = 2;
                }else{
                  $this->bsestar_mutualfund->k = 1;

                }
                 if(!empty($morder['broker_id'])){
                    $data['broker_id'] = $morder['broker_id'];
                    if($data['broker_id'] !='sam_0000'){
                        $partnerDetails = $this->getPartnerBasicDetails($data['broker_id']);
                        $data['sub_broker_arn'] = $partnerDetails->ARN;
                    }
                 }

				if(isset($morder['pg_reference_number']) && !empty($morder['pg_reference_number'])){
					$data['pg_reference_number'] = $morder['pg_reference_number'];
				}

                 if($env == 'MFDP_L'){ //if mfd OTM then only invest regular sip amt. remaining units thru MFI
                    $data['amount'] = $data['amount'] - $current_units['rebalance_buy_amt'];
                    $res = $this->bsestar_mutualfund->orderEntryParam($data);
                    $this->bsestar_mutualfund->k = 2; //resetiing key to MFI order
                    $data['amount'] = $current_units['rebalance_buy_amt'];
                    $res_mfi = $this->bsestar_mutualfund->orderEntryParam($data);

                    $current_units['comment'] = json_encode(array('order_Status' => 'Eq Units less than Min Redemp quantity'));

                    $data['amount'] = $morder['amount'] - $current_units['rebalance_buy_amt']; //resetting original total amt
                 } else{
                    $res = $this->bsestar_mutualfund->orderEntryParam($data); 
                 }
                 

                $data['order_type'] = 'lumpsum';
                $data['order_id'] = isset($res[2]) ? $res[2] : 0;
                $data['order_status'] = isset($res[7]) ? $res[7] : 1;
                $data['order_response'] = isset($res[6]) ? $res[6] : "Verify Smart/Super SIP Order";
                $data['unique_ref_no'] = isset($res[1]) ? $res[1] : "";


                $data['date_created'] = $date = date('Y-m-d H:i:s');

                $data['created_by'] = $morder['client_id'];
                $data['source']     = $morder['client_id'];
                $data['master_id']     = $morder['master_id'];
                $data['base_signal']     = $morder['base_signal'];
                $data['margin_of_safety']     = $current_units['margin_of_safety'];

                if(isset($data['DPTxn']))
                    unset($data['DPTxn']); //this field is not in database table thats why removed form array
                $params['date'] = date('Y-m-d');
                $params['client_id'] = $client;
                 $params['TransType'] = 'P';
                 $params['order_id'] = $res[2];
                 $data['master_id'] = $morder['id'];

                //$prov_order = $this->bsestar_mutualfund->ProvOrderStatus($params,'MFI');
               //print_r($prov_order);
                if(true){

                    $data['opening_eq_units'] = $current_units['closing_eq_units'];
                    $data['opening_liquid_units'] = $current_units['closing_liquid_units'];

                    if($type == 'equity'){
                       $data['buy_eq_units'] = $current_units['buy_eq_units'];
                       //$data['units'] = $current_units['units'] + $morder['units'];
                       $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];

                       $data['closing_liquid_units'] = $current_units['closing_liquid_units'] + $morder['liquid_units'];
                       $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;



                    }

                    if($type == 'liquid'){
                     $data['units'] = $current_units['units'] + $morder['units'];

                     $data['buy_liquid_units'] = $current_units['buy_liquid_units'] ;
                     $data['closing_liquid_units'] = $current_units['closing_liquid_units'] + $current_units['buy_liquid_units'] + $current_units['rebalance_buy_liquid_units'] ;
                     $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
                     $data['rebalance_buy_liquid_units'] = $current_units['rebalance_buy_liquid_units'];
                     $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;


                    }

                    if($type == 'default_liquid'){

                     $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] + $current_units['buy_liquid_units_default'] ;
                     $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
                     $data['closing_liquid_units'] = $current_units['closing_liquid_units'] ;

                     $data['buy_liquid_units_default'] = $current_units['buy_liquid_units_default'];

                    }

                }


                 //x($data);
                $data['comment'] = $current_units['comment'];
                $data['mos_date'] = $current_units['mongo_date'];
                if($data['order_status'] != '0'){ // if order fail check reaseon and reprocess
                    //y($data, 'FAILED ORDER DETAILS');
					$this->logToFile($this->filename, "FAILED ORDER DETAILS\n".$data);
                }

                if(!empty($data['opening_eq_units']) && is_numeric($data['opening_eq_units'])){
                    $data['opening_eq_units'] = $this->get_lower_nearest_value($data['opening_eq_units'], 1, 4);
                }
                if(!empty($data['opening_liquid_units']) && is_numeric($data['opening_liquid_units'])){
                    $data['opening_liquid_units'] = $this->get_lower_nearest_value($data['opening_liquid_units'], 1, 4);
                }
                if(!empty($data['secondary_base_sell_eq_units']) && is_numeric($data['secondary_base_sell_eq_units'])){
                    $data['secondary_base_sell_eq_units'] = $this->get_lower_nearest_value($data['secondary_base_sell_eq_units'], 1, 4);
                }
                if(!empty($data['secondary_base_buy_eq_units']) && is_numeric($data['secondary_base_buy_eq_units'])){
                    $data['secondary_base_buy_eq_units'] = $this->get_lower_nearest_value($data['secondary_base_buy_eq_units'], 1, 4);
                }
                if(!empty($data['secondary_base_buy_liquid_units']) && is_numeric($data['secondary_base_buy_liquid_units'])){
                    $data['secondary_base_buy_liquid_units'] = $this->get_lower_nearest_value($data['secondary_base_buy_liquid_units'], 1, 4);
                }
                if(!empty($data['rebalance_buy_eq_units']) && is_numeric($data['rebalance_buy_eq_units'])){
                    $data['rebalance_buy_eq_units'] = $this->get_lower_nearest_value($data['rebalance_buy_eq_units'], 1, 4);
                }
                if(!empty($data['rebalance_sell_liquid_units']) && is_numeric($data['rebalance_sell_liquid_units'])){
                    $data['rebalance_sell_liquid_units'] = $this->get_lower_nearest_value($data['rebalance_sell_liquid_units'], 1, 4);
                }
                if(!empty($data['rebalance_sell_eq_units']) && is_numeric($data['rebalance_sell_eq_units'])){
                    $data['rebalance_sell_eq_units'] = $this->get_lower_nearest_value($data['rebalance_sell_eq_units'], 1, 4);
                }
                if(!empty($data['rebalance_buy_liquid_units']) && is_numeric($data['rebalance_buy_liquid_units'])){
                    $data['rebalance_buy_liquid_units'] = $this->get_lower_nearest_value($data['rebalance_buy_liquid_units'], 1, 4);
                }
                if(!empty($data['closing_eq_units_prev']) && is_numeric($data['closing_eq_units_prev'])){
                    $data['closing_eq_units_prev'] = $this->get_lower_nearest_value($data['closing_eq_units_prev'], 1, 4);
                }
                if(!empty($data['closing_eq_units']) && is_numeric($data['closing_eq_units'])){
                    $data['closing_eq_units'] = $this->get_lower_nearest_value($data['closing_eq_units'], 1, 4);
                }
                if(!empty($data['closing_liquid_units']) && is_numeric($data['closing_liquid_units'])){
                    $data['closing_liquid_units'] = $this->get_lower_nearest_value($data['closing_liquid_units'], 1, 4);
                }
                if(!empty($data['closing_liquid_units_prev']) && is_numeric($data['closing_liquid_units_prev'])){
                    $data['closing_liquid_units_prev'] = $this->get_lower_nearest_value($data['closing_liquid_units_prev'], 1, 4);
                }
                if(!empty($data['closing_liquid_units_default']) && is_numeric($data['closing_liquid_units_default'])){
                    $data['closing_liquid_units_default'] = $this->get_lower_nearest_value($data['closing_liquid_units_default'], 1, 4);
                }

                $insert_id = $this->insertSmartOrder($data);
                if($env == 'MFDP_L'){
                  $data['order_id'] = $res_mfi[2];
                  $data['order_status'] = $res_mfi[7];
                  $data['order_response'] = $res_mfi[6];
                  $data['unique_ref_no'] = $res_mfi[1];
                  $data['amount'] =  $current_units['rebalance_buy_amt'];
                  $data['comment'] = json_encode(array('order_Status' => 'MFD order 
                    OTM'));
                  $insert_id = $this->insertSmartOrder($data);
                }

                  $params_up                   = [];
                  $params_up['env']            = 'db';                   
                  $params_up['table_name']     = 'mf_billdesk_transactions';
                  $params_up['update_data']    = ['smorder_id' => $data['order_id'] ];           
                  $params_up['where']          = TRUE;
                  $params_up['where_data']     =  ['master_order_id' => $morder['id'] ,'t_day' => $morder['next_t_day'], 'bill_no' =>$morder['pg_reference_number'] ];  
                  //$params_up['print_query_exit']     =  TRUE;
                  $update_status = $this->mf->update_table_data_with_type($params_up);

                //updating units 
               $current_units['closing_eq_units'] =  $data['closing_eq_units'];
               $current_units['closing_liquid_units'] =  $data['closing_liquid_units'];

                return $res[2];

           }


       }

    public function purchaseSmartLumpsumProcessList($morder,&$current_units,$type = 'equity',$env ='MFI')
       {
          //print_r($current_units);print_r($morder['units']); die;

             $data = array();
            $client = $morder['client_id'];
            $scheme_code = $morder['scheme_code'];
            $today = date('Y-m-d');
            if(empty($client)){
                redirect(base_url());
            }else{
                $data['trans_code'] = 'NEW' ;
                $data['client_id'] = $client;
                $data['order_id'] = '';

                $data['scheme_code'] = $scheme_code;
                $data['buy_sell'] = 'P';
                $folio = $this->checkFolioNo($scheme_code,$client,$morder['order_mode']);

                if($type == 'equity' && $morder['amount'] >= 200000 ){
                  $data['scheme_code'] = $scheme_code.'-L1';
                }

                if(!empty($folio) ){
                    $data['buy_sell_type'] = 'ADDITIONAL';
                    $data['folio_number']  = $folio;
                }else
                    $data['buy_sell_type'] = 'FRESH';

                if($morder['order_mode'] == 'P'){ //added for physical order
                    $data['DPTxn'] = 'P'; //p for physical
                    $data['order_mode'] = 'P';
                }
                else{
                    $data['DPTxn'] = 'C';
                    $data['order_mode'] = 'D';
                }


                $data['amount'] = intval($morder['amount']);
                $data['quantity'] = '';
               
                
                 

                $data['order_type'] = 'lumpsum';
                $data['order_id'] = '';
                $data['order_status'] = '';
                $data['order_response'] = '';
                $data['unique_ref_no'] = '';


                $data['date_created'] = $date = date('Y-m-d H:i:s');

                $data['created_by'] = $morder['client_id'];
                $data['source']     = $morder['client_id'];
                $data['master_id']     = $morder['master_id'];
                $data['base_signal']     = $morder['base_signal'];
                $data['margin_of_safety']     = $current_units['margin_of_safety'];

                if(isset($data['DPTxn']))
                    unset($data['DPTxn']); //this field is not in database table thats why removed form array
                $params['date'] = date('Y-m-d');
                $params['client_id'] = $client;
                 $params['TransType'] = 'P';
                 $params['order_id'] = $res[2];
                 $data['master_id'] = $morder['id'];

                //$prov_order = $this->bsestar_mutualfund->ProvOrderStatus($params,'MFI');
               //print_r($prov_order);
                if(true){

                    $data['opening_eq_units'] = $current_units['closing_eq_units'];
                    $data['opening_liquid_units'] = $current_units['closing_liquid_units'];

                    if($type == 'equity'){
                       $data['buy_eq_units'] = $current_units['buy_eq_units'];
                       //$data['units'] = $current_units['units'] + $morder['units'];
                       $data['closing_eq_units'] = $current_units['closing_eq_units'] + $current_units['rebalance_buy_eq_units'] + $current_units['secondary_base_buy_eq_units'];

                       $data['closing_liquid_units'] = $current_units['closing_liquid_units'] + $morder['liquid_units'];
                       $data['rebalance_buy_eq_units'] = $current_units['rebalance_buy_eq_units'];
                       $data['secondary_base_buy_eq_units'] = $current_units['secondary_base_buy_eq_units'];
                       $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;



                    }

                    if($type == 'liquid'){
                     $data['units'] = $current_units['units'] + $morder['units'];

                     $data['buy_liquid_units'] = $current_units['buy_liquid_units'] ;
                     $data['closing_liquid_units'] = $current_units['closing_liquid_units']  + $current_units['rebalance_buy_liquid_units'] ;
                     $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
                     $data['rebalance_buy_liquid_units'] = $current_units['rebalance_buy_liquid_units'];
                     $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;


                    }

                    if($type == 'default_liquid'){

                     $data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] + $current_units['buy_liquid_units_default'] ;
                     $data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
                     $data['closing_liquid_units'] = $current_units['closing_liquid_units'] ;

                     $data['buy_liquid_units_default'] = $current_units['buy_liquid_units_default'];

                    }

                }


                 //x($data);
                $data['comment'] = $current_units['comment'];
                $data['mos_date'] = $current_units['mongo_date'];
                $data['order_status'] = 5; //could be executed
                $data['order_executed_day'] = '0000-00-00'; //TBD update after process list

                $insert_id = $this->insertSmartOrder($data);


                $next_sip_t_day = $this->filterHolidays($morder['next_t_day']);
                if($type == 'liquid'){ // selling eq to buy liquid t+3
                  $process_date = $next_sip_t_day['tplus3'];
                }else{ // selling liquid to buy equity t+1
                  $process_date = $next_sip_t_day['tplus1'];
                }
                //y($process_date, '$data_sm before');
				if(empty($folio)){
                $params = [];
                $params['env']              = 'default';
                $params['select_data']      = "*";
                $params['table_name']       = 'mf_order_process_list_smartsip';
                $params['where']            = TRUE;
                $params['where_data']       =array( 'client_id' => $client, 'process_date' => $process_date);
                if(!empty($smartsip_order_id) && is_numeric($smartsip_order_id)){
                    $params['where_data']['master_id'] = $smartsip_order_id;
                }
                $params['where_in']             =    TRUE;
                $params['where_in_field']             =    'scheme_code';                
                $params['where_in_data']        = array($scheme_code,$scheme_code.'-L1',$scheme_code.'-L0');
                $params['where_not_in']         = TRUE;
                $params['return_array']     = True;
                $params['limit_data']     = 1;
                $params['limit_start']     = 0;
                $params['order_by']         = "date_created";
                //$params['print_query'] = TRUE;
                $data_sm               = $this->sn->get_table_data_with_type($params);
                //return($data_sm[0]['folio_number']);
                if(!empty($data_sm[0])){
                  $next_sip_t_day = $this->filterHolidays(date("Y-m-d", strtotime($morder['next_t_day'] . ' +2 day')));
                  if($type == 'liquid'){ // selling eq to buy liquid t+3
                    $process_date = $next_sip_t_day['tplus3'];
                  }else{ // selling liquid to buy equity t+1
                    $process_date = $next_sip_t_day['tplus1'];
                  }
                }
				}
                 if(isset($current_units['to_be_executed']) ){
                    $current_units['order_id'] = 0;
                  }
                

                $p_order = [    'master_id' => $morder['id'],
                                'client_id' => $client,
                                'order_type' => 'lumpsum',
                                'order_id' => '',
                                'redemption_order_id' => $current_units['order_id'],
                                'smart_order_p_id' => $insert_id,
                                'scheme_code' => $data['scheme_code'],
                                'folio_number' => $folio,
                                'amount' => $data['amount'],
                                'order_date' => date('Y-m-d'),
                                'process_date' => $process_date,//$this->calculateProcessDate(),
                                'mfi_order_id' => '',
                                'order_mode' => $morder['order_mode']

                            ];

              if(isset($current_units['to_be_executed']) ){ // for T day mandate orders that will be needed to be processed
                  $p_order['to_be_executed'] = 0;
                  $p_order['process_date'] = $next_sip_t_day['tplus2'];
                  $current_units['order_id'] = 0;
                  
                }
               
                $insparam  =[];
                $insparam['env']        = 'db';
                $insparam['table_name'] = 'mf_order_process_list_smartsip';
                $insparam['data']       = $p_order;
                $lid = $this->mf->insert_table_data($insparam);
                //y($insparam,'insert mf_order_process_list_smartsip');
              
                if(isset($current_units['to_be_executed']) ){ // code to update tday mandate table to link process list order 
                   $update_master_data = [];
                   $update_master_data = [
                                          'linked_order' => $lid,
                                          'order_executed_day' => $next_sip_t_day['tplus2']
                                         
                                          ];

                    $params_up                  =  [];
                    $params_up['env']           =  'db';                   
                    $params_up['table_name']    =  'mf_billdesk_transactions_tday';
                    $params_up['update_data']   =  $update_master_data;           
                    $params_up['where']         =  TRUE;
                    //$params_up['print_query']         =  TRUE;
                    $params_up['where_data']    =  ['master_order_id' => $morder['id'] ,'t_day' => $morder['next_t_day'] ]; 
                    $update_status = $this->mf->update_table_data_with_type($params_up);

                }

                //updating units 
               $current_units['closing_eq_units'] =  $data['closing_eq_units'];
               $current_units['closing_liquid_units'] =  $data['closing_liquid_units'];

                return $res[2];

           }


       }
       //Execute Process list orders for lumpsum
       public function PurchaseProcessList($date='')
    {

           $today = date('d');
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*";
            $params['table_name']       = 'mf_order_process_list_smartsip';
            // $params['join']             =  FALSE;  
            // $params['join_table']       = 'mf_master_smart_sip';  
            // $params['join_on']          = 'mf_order_smart_sip.master_id = mf_master_smart_sip.id';
            // $params['join_type']        = 'INNER';
            $params['where']            = TRUE;
            $params['where_data']       = "process_date = '". $date ."' AND order_processed = 0 AND ((to_be_executed = 1 AND redemption_order_id != 0) OR (to_be_executed = 1 AND redemption_order_id = 0))";
            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $master_orders                = $this->mf->get_table_data_with_type($params);
            // x($master_orders);
            foreach($master_orders as $porder){
              // x($morder);
			  $client_detail = array();	
              $data = array();
              $client_id = $porder['client_id'];
    		  $clientinfo = getClientMaster($client_id);
			  $data['client_mobile'] = $clientinfo['client_mobile'];
			  $data['client_email']  = $clientinfo['client_email'];			  
			  $client_detail[] = (object)$clientinfo;
            //
              $client = $client_id;


              if(empty($client)){
                  redirect(base_url());
              }else{
                  $data['trans_code'] = 'NEW' ;
                  $data['client_id'] = $client;
                  $data['order_id'] = '';

                  $data['scheme_code'] = $porder['scheme_code'];

                  // RAN-3029. STARTS
                  if(stripos($porder['scheme_code'], '-l1') === FALSE){
                      $scheme_details_params = [];
                      $scheme_details_params['env']              = 'default';
                      $scheme_details_params['select_data']      = "Scheme_Code, Scheme_Type";
                      $scheme_details_params['table_name']       = 'mf_aggregated_search_data';
                      $scheme_details_params['where']            = TRUE;
                      $scheme_details_params['where_data']       = ['Scheme_Code' => $porder['scheme_code']];
                      $scheme_details_params['return_array']     = True;
                      // $scheme_details_params['print_query_exit'] = TRUE;
                      $retrieved_scheme_details = $this->mf->get_table_data_with_type($scheme_details_params);
                      if($retrieved_scheme_details && isset($retrieved_scheme_details[0]) && isset($retrieved_scheme_details[0]['Scheme_Type']) && !empty($retrieved_scheme_details[0]['Scheme_Type']) && (strtolower($retrieved_scheme_details[0]['Scheme_Type']) == 'equity')){
                          if($porder['amount'] >= 200000){
                            $data['scheme_code'] = $porder['scheme_code'].'-L1';
                          }
                      }
                      unset($retrieved_scheme_details, $scheme_details_params);
                  }
                  // RAN-3029. ENDS

                  $data['buy_sell'] = 'P';

                  if(isset($porder['folio_number']) && !empty($porder['folio_number'])){
						$data['buy_sell_type'] = 'ADDITIONAL';
						$data['folio_number']  = $porder['folio_number'];
						$allow_smartsip = getSettings("ALLOW_SMARTSIP_2FACTOR");
						if($allow_smartsip==1){
							//Changes made for 2factor authentication By Rajesh
							$foliodata = getFolioData($client_id,$porder['folio_number'],$porder['scheme_code'],'web',1,$client_detail);				
							if(!empty($foliodata)){
								if($foliodata['mismatch_flag']==true){	
									$data['buy_sell_type'] = 'FRESH';
									$data['folio_number'] = '';
								}else{
									$data['client_mobile'] = $foliodata['folio_client_mobile'];
									$data['client_email']  = $foliodata['folio_client_email'];			
								}	
							}
						}
                  }else{
                    $checking_folio = $this->checkFolioNo($porder['scheme_code'],$client_id,$porder['order_mode']);
                    if(!empty($checking_folio) ){
                      	$data['buy_sell_type'] = 'ADDITIONAL';
                      	$data['folio_number']  = $checking_folio;
						$allow_smartsip = getSettings("ALLOW_SMARTSIP_2FACTOR");
						if($allow_smartsip==1){
							//Changes made for 2factor authentication By Rajesh
							$foliodata = getFolioData($client_id,$checking_folio,$porder['scheme_code'],'web',1,$client_detail);				
							if(!empty($foliodata)){
								if($foliodata['mismatch_flag']==true){	
									$data['buy_sell_type'] = 'FRESH';
									$data['folio_number'] = '';
								}else{
									$data['client_mobile'] = $foliodata['folio_client_mobile'];
									$data['client_email']  = $foliodata['folio_client_email'];			
								}	
							}
						};
                    }
                    else{
                      $data['buy_sell_type'] = 'FRESH';
                    }
                    unset($checking_folio);
                  }

                  if(isset($porder['order_mode']) && $porder['order_mode'] == 'P'){ //added for physical order
                      $data['DPTxn'] = 'P'; //p for physical
                      $data['order_mode'] = 'P';
                  }
                  else{
                      $data['DPTxn'] = 'C';
                      $data['order_mode'] = 'D';
                  }

                 
                  $data['amount'] = $porder['amount'];
                  $data['pg_reference_number'] = $porder['pg_reference_number'];
                  $data['quantity'] = '';

                  if(!empty($morder['broker_id'])){
                    $data['broker_id'] = $morder['broker_id'];
                    if($data['broker_id'] !='sam_0000'){
                        $partnerDetails = $this->getPartnerBasicDetails($data['broker_id']);
                        $data['sub_broker_arn'] = $partnerDetails->ARN;
                    }
                  }

                  $this->bsestar_mutualfund->k = 2; //setting key to MFI
                  $res = $this->bsestar_mutualfund->orderEntryParam($data);

                  $data['order_type'] = 'lumpsum';
                  $data['order_id'] = isset($res[2]) ? $res[2] : 0;
                  $data['order_status'] = isset($res[7]) ? $res[7] : 1;
                  $data['order_response'] = isset($res[6]) ? $res[6] : "Verify Smart/Super SIP Order";
                  $data['unique_ref_no'] = isset($res[1]) ? $res[1] : "";

                  $update_data                    = array();
                  $update_data['order_processed'] = '1';
                  $update_data['remark']          = '';
                  //$update_data['order_response']  = $orderresponse;
                  $update_data['order_id']    = $res[2];
                   $update_data['order_response']    = $res[7];

                  $where_data = array("id"=>$porder['id']); 

                  $params_up        = [];
                  $params_up['env']          = 'db';                   
                  $params_up['table_name']   = 'mf_order_process_list_smartsip';
                  $params_up['update_data']  = $update_data;
                   //$params_up['print_query_exit'] = TRUE;
                  $params_up['where']        = TRUE;
                  $params_up['where_data']   =  $where_data; 
                  $update_status = $this->mf->update_table_data_with_type($params_up);


                  // $data['date_created'] = $date = date('Y-m-d H:i:s');
                  if(!empty($order) && isset($order['created_by'])){
                      $data['created_by'] = $order['created_by'];
                      $data['source']     = $order['source'];
                  }
                  if(isset($data['DPTxn']))
                      unset($data['DPTxn']); //this field is not in database table thats why removed form array

                  $data['order_executed_day'] = date('Y-m-d');
                  $where = ['id' => $porder['smart_order_p_id'] ];   
                  $insert_id = $this->updateSmartOrder($data,$where);

                 $params_up                  =  [];
                 $params_up['env']           =  'db';                   
                 $params_up['table_name']    =  'mf_billdesk_transactions_tday';
                 $params_up['update_data']   =  ['smorder_id' => $update_data['order_id'] ];           
                 $params_up['where']         =  TRUE;
                 $params_up['where_data']    =  ['master_order_id' => $porder['master_id'], 'order_executed_day' => $date  ]; 
                 //$params_up['print_query']     =  TRUE;
                 $update_status = $this->mf->update_table_data_with_type($params_up);
                  


                  
            }      
           
          }//end foreach
    }

       public function checkIfLedgerSufficent($check_amt, $master_id,$t_day)
       {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*";
            $params['table_name']       = 'mf_billdesk_transactions';
            $params['where']            = TRUE;
            $params['where_data']       = ['master_order_id' => $master_id, 't_day' => $t_day];

            $params['return_array']     = True;
          //  $params['print_query_exit'] = TRUE;
            $ledger               = $this->mf->get_table_data_with_type($params);
            $amount = $ret_arr['mandate_success'] = 0;
			$return_arr['transactions_data'] = array();
            foreach($ledger as $mdate){
				if( ($mdate['transaction_status'] == 'success') || ($mdate['ledger_status'] == 'success') ){
				  $ret_arr['bill_no'][] = $mdate['bill_no'];
				 $return_arr['transactions_data'][] = $mdate;
                 $amount +=  $mdate['amount'];
                 if(($mdate['transaction_status'] == 'success') ){
                    $ret_arr['mandate_success'] = 1;
                 }
              }

            }
            $ret_arr['transactions_data'] = $return_arr['transactions_data'];
            $ret_arr['amount'] = $amount;
            $ret_arr['t_minus_three_signal'] = $ledger[0]['t_minus_three_signal'];


            return $ret_arr;



       } 

       public function checkIfLedgerSufficentOld($amt, $client_id)
       {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "*";
            $params['table_name']       = 'mf_ledger_balance';
            $params['where']            = TRUE;
            $params['where_data']       = ['client_id' => $client_id];

            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $ledger               = $this->mf->get_table_data_with_type($params)[0];

            if ($ledger['closing_cr'] < $amt)
            {
                return false;
            }else{
                return true;
            }


       }

       public function checkMFDMandate($amt, $client_id)
       {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "mandate_id,mandate_amt";
            $params['table_name']       = 'mf_mandate_regitrations_smartsip';
            $params['where']            = TRUE;
            $params['where_data']       = ['client_id' => $client_id, 'mandate_amt >=' => $amt, 'mandate_status' => 'APPROVED'];
            $params['order_by']         = 'mandate_amt desc';

            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $mandate               = $this->mf->get_table_data_with_type($params)[0];

            return $mandate;


       }

          public function insertSmartOrder($insert_data,$where='')
       {
            // $log_csv_file_path = getcwd().'/application/logs/insertSmartOrder_'.date('d-m-y').'.csv';
            // $log_file_handle = @fopen($log_csv_file_path, 'a+');

            $insparam  =[];
            $insparam['env']        = 'db';
            //$insparam['print_query_exit']        = TRUE;
            $insparam['table_name'] = 'mf_order_smart_sip';

            $insert_data['created_by'] = $insert_data['client_id'];
            $insert_data['source'] = $insert_data['client_id'];
            if(!isset($insert_data['order_executed_day'])){
                $insert_data['order_executed_day'] = date('Y-m-d');
            }
            
            if(!isset($insert_data['broker_id']) || (isset($insert_data['broker_id']) && empty($insert_data['broker_id']))){
                //broker code
                $retrieved_broker_id = $this->getBrokerCode($insert_data['client_id']); //sub broker id for b2b2c
                if(!empty($retrieved_broker_id) ){
                    $insert_data['broker_id'] = $retrieved_broker_id;
                }
                unset($retrieved_broker_id);
            }
            //add code for finding ARN details of a partner
            if(isset($insert_data['broker_id']) && !empty($insert_data['broker_id']) && ($insert_data['broker_id'] !='sam_0000')){
                if(!isset($insert_data['sub_broker_arn']) || (isset($insert_data['sub_broker_arn']) && empty($insert_data['sub_broker_arn']))){
                    $retrieved_partnerDetails = $this->getPartnerBasicDetails($insert_data['broker_id']);
                    $insert_data['sub_broker_arn'] = $retrieved_partnerDetails->ARN;
                    unset($retrieved_partnerDetails);
                }
            }

            $insparam['data']       = $insert_data;

            // @fputcsv($log_file_handle, array_merge(array_keys(array_merge($insert_data, (empty($where)?array():$where))),array('Query Type', 'datetime')));
            // @fputcsv($log_file_handle, array_merge($insert_data, (empty($where)?array():$where),array((empty($where)?'INSERT':'UPDATE'), "'".date('Y-m-d H:i:s'))));
            if(empty($where)){
               $var = $this->mf->insert_table_data($insparam);

            }
            else{
                 $var = $this->mf->update_table_data($insparam);
                 $insparam['where_data']       = $where;


            }
            // if($log_file_handle){
            //     @fclose($log_file_handle);
            // }
            // unset($log_csv_file_path, $log_file_handle);
            return $var;

         } 
         public function insertSkipOrder($insert_data,$where='')
       {
            $insparam  =[];
            $insparam['env']        = 'db';
            //$insparam['print_query_exit']        = TRUE;
            $insparam['table_name'] = 'mf_skip_smart_sip_list';

            


            $insparam['data']       = $insert_data;

            if(empty($where)){
               $var = $this->mf->insert_table_data($insparam);

            }
            else{
                 $var = $this->mf->update_table_data($insparam);
                 $insparam['where_data']       = $where;


            }
            return $var;

         } 

          public function updateSmartOrder($update_data,$where=[])
       {        
                if(!empty($where) ){
                  
                  $params_up                   = [];
                  $params_up['env']            = 'db';                   
                  $params_up['table_name']     = 'mf_order_smart_sip';
                  $params_up['update_data']    = $update_data;           
                  $params_up['where']          = TRUE;
                  $params_up['where_data']     =  $where; 
                  //$params_up['print_query_exit']     =  TRUE;
                  $update_status = $this->mf->update_table_data_with_type($params_up);
                }  
                 
                 return $update_status;

         }

          public function updateSmartOrderProcessList($update_data,$where=[])
       {        
                if(!empty($where) ){
                  
                  $params_up                   = [];
                  $params_up['env']            = 'db';                   
                  $params_up['table_name']     = 'mf_order_process_list_smartsip';
                  $params_up['update_data']    = $update_data;           
                  $params_up['where']          = TRUE;
                  $params_up['where_data']     =  $where; 
                  //$params_up['print_query']     =  TRUE;
                  $update_status = $this->mf->update_table_data_with_type($params_up);
                }  
                 
                 return $update_status;

         }


         public function getLastSmartTransaction($client_id ='', $master_id='', $update_id='', $folio_number='')
         {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "opening_eq_units,folio_number,opening_liquid_units,secondary_base_sell_eq_units,secondary_base_buy_liquid_units,units,buy_eq_units,closing_eq_units,closing_eq_units_prev,closing_liquid_units,closing_liquid_units_prev,closing_liquid_units_default,buy_liquid_units,buy_liquid_units_default,rebalance_buy_eq_units,rebalance_sell_liquid_units,opening_eq_units,opening_liquid_units,master_id";
            $params['table_name']       = 'mf_order_smart_sip';
            $params['where']            = TRUE;
            if(!empty($update_id) ){
               $params['where_data']       = array('client_id' => $client_id, 'id' => $update_id,'order_status' => 0);
            }else{
               $params['where_data']       = array('client_id' => $client_id, 'master_id' => $master_id,'order_status' => 0);
            }
            if(!empty($folio_number)){
                $params['where_data']['folio_number'] = $folio_number;
            }
            $params['order_by']         = 'id desc';
            $params['return_array']     = True;
            $params['limit_data']       = 1;
            $params['limit_start']      = 0;
            //$params['print_query_exit'] = TRUE;
            $order               = $this->mf->get_table_data_with_type($params)[0];

            if(empty($order)){ //will occur for first time order(SIP)
              $order = [
              'opening_eq_units' => 0,
              'opening_liquid_units' => 0,
              'secondary_base_sell_eq_units' => 0,
              'secondary_base_buy_liquid_units' => 0,
              'units' => 0,
              'buy_eq_units' => 0,
              'closing_eq_units' => 0,
              'closing_liquid_units' => 0,
              'closing_liquid_units_default' => 0,
              'buy_liquid_units' => 0,
              'buy_liquid_units_default' => 0,
              'rebalance_buy_eq_units' => 0,
              'rebalance_sell_liquid_units' => 0,
              'opening_eq_units' => 0,
              'opening_liquid_units' => 0,
              'master_id' => $master_id
               ];
            }
            return $order;
         }

          public function getPreviousSmartTransaction($client_id, $update_id,$mid)
         {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "opening_eq_units,opening_liquid_units,secondary_base_sell_eq_units,secondary_base_buy_liquid_units,units,buy_eq_units,closing_eq_units,closing_liquid_units,closing_liquid_units_default,buy_liquid_units,buy_liquid_units_default,rebalance_buy_eq_units,rebalance_sell_liquid_units,opening_eq_units,opening_liquid_units,master_id";
            $params['table_name']       = 'mf_order_smart_sip';
            $params['where']            = TRUE;
            $params['where_data']       = array('client_id' => $client_id, 'id <' => $update_id,'order_status' => 0, 'master_id' => $mid);
            $params['order_by']         = 'id desc';
            $params['return_array']     = True;
            $params['limit_data']       = 1;
            //$params['print_query'] = TRUE;
            $order               = $this->mf->get_table_data_with_type($params)[0];

            if(empty($order)){ //will occur for first time order(SIP)
              $order = [
              'opening_eq_units' => 0,
              'opening_liquid_units' => 0,
              'secondary_base_sell_eq_units' => 0,
              'secondary_base_buy_liquid_units' => 0,
              'units' => 0,
              'buy_eq_units' => 0,
              'closing_eq_units' => 0,
              'closing_liquid_units' => 0,
              'closing_liquid_units_default' => 0,
              'buy_liquid_units' => 0,
              'buy_liquid_units_default' => 0,
              'rebalance_buy_eq_units' => 0,
              'rebalance_sell_liquid_units' => 0,
              'opening_eq_units' => 0,
              'opening_liquid_units' => 0,
              'master_id' => $master_id
               ];
            }
            return $order;
         }

         public function getLastSmartTransactionScheme($master_id)
         {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "closing_eq_units,closing_liquid_units,master_id";
            $params['table_name']       = 'mf_chart_smart_sip';
            $params['where']            = TRUE;
            $params['where_data']       = array('master_id' => $master_id,);
            $params['order_by']         = 'id desc';
            $params['return_array']     = True;
            $params['limit_data']       = 1;
            $params['limit_start']      = 0;
            //$params['print_query_exit'] = TRUE;
            $order               = $this->mf->get_table_data_with_type($params)[0];

            if(empty($order)){ //will occur for first time order(SIP)
              $order = [
              'opening_eq_units' => 0,
              'opening_liquid_units' => 0,
              'secondary_base_sell_eq_units' => 0,
              'secondary_base_buy_liquid_units' => 0,
              'units' => 0,
              'buy_eq_units' => 0,
              'closing_eq_units' => 0,
              'closing_liquid_units' => 0,
              'buy_liquid_units' => 0,
              'rebalance_buy_eq_units' => 0,
              'rebalance_sell_liquid_units' => 0,
              'opening_eq_units' => 0,
              'opening_liquid_units' => 0,
              'master_id' => $master_id
               ];
            }
            return $order;
         }

         public function last_insert_id()
         {
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "master_id";
            $params['table_name']       = 'mf_chart_smart_sip';

            $params['order_by']         = 'id desc';
            $params['return_array']     = True;
            $params['limit_data']       = 1;
            $params['limit_start']      = 0;
            //$params['print_query_exit'] = TRUE;
            $master               = $this->mf->get_table_data_with_type($params)[0]['master_id'];

            if(empty($master)){ //will occur for first time order(SIP)
              $master = 1;
            }else{
                $master = $master + 1;
            }
            return $master;
         }


         public function sellLiquidUnits($client_id, $liquid_scheme_code, $unit_conv,$transaction_mode,&$current_units, $all_sell_units, $sell_all_unit = 0, $overriden_folio_number='')
         {
            $data = array();
           //print_r($all_sell_units); die;
            $amount='';
            //$client_id = 'DP17682';
            $qty = '0';

            $scheme_code    = str_replace( '-L0','',$liquid_scheme_code);

            // $presentnav     = $this->input->post('presentnav');
            // $presentunits   = $this->input->post('presentunits');
            $folio = $this->checkFolioNo($scheme_code,$client_id);
            if(!empty($overriden_folio_number)){
              $folio = $overriden_folio_number;
            }
            $qty            = $unit_conv;
            if(!empty($folio)){
                $data['folio_number'] = $folio;
            }



            if(!empty($client_id)){
				$clientinfo = getClientMaster($client_id);
				$data['client_mobile']  = $clientinfo['client_mobile'];
				$data['client_email']   = $clientinfo['client_email'];
			
                if($transaction_mode == 'P'){
                     $data['DPTxn'] = 'P';
                }

               if($sell_all_unit == 1){
                  if($transaction_mode == 'D')
                  {
                      $data['all_redeem'] = '';
                      $data['min_redeem'] = 'Y';
                  }
                  if($transaction_mode == 'P')
                  {
                      $data['all_redeem'] = 'Y';
                      $data['min_redeem'] = '';
                      $qty ='';
                  }

              }

                $data['trans_code'] = 'NEW' ;
                $data['client_id'] = $client_id;
                $data['order_id'] = '';
                $data['scheme_code'] = $scheme_code;
                $data['buy_sell'] = 'R';
                $data['buy_sell_type'] = 'FRESH';
                $data['amount'] = $amount;
                $data['quantity'] = $qty;
                //x($data);

				$arr_client_details = $this->getClientMasterProfileDetails($data['client_id']);
				if($this->order_env == 'MFD' && isset($arr_client_details['ClntBankAccNo']) && !empty($arr_client_details['ClntBankAccNo'])){
					$data['bank_account_number'] = trim($arr_client_details['ClntBankAccNo']);
				}
				if($this->order_env == 'MFD' && isset($arr_client_details['client_mobile']) && !empty($arr_client_details['client_mobile'])){
					$data['client_mobile'] = $arr_client_details['client_mobile'];
				}
				if($this->order_env == 'MFD' && isset($arr_client_details['client_email']) && !empty($arr_client_details['client_email'])){
					$data['client_email'] = $arr_client_details['client_email'];
				}
                //$this->bsestar_mutualfund->k = 1;
                $this->bsestar_mutualfund->k = $this->bsestar_mutualfund_enviroment;

                $res=$this->bsestar_mutualfund->orderEntryParam($data);
             //  x($res);


                $data['order_type'] = 'lumpsum';
                $data['order_id'] = isset($res[2]) ? $res[2] : 0;
                $data['order_status'] = isset($res[7]) ? $res[7] : 1;
                $data['order_response'] = isset($res[6]) ? $res[6] : "Verify Smart/Super SIP Order";
                $data['unique_ref_no'] = isset($res[1]) ? $res[1] : "";
                $data['date_created'] = $date = date('Y-m-d H:i:s');
                $data['master_id'] = $current_units['master_id'];
                $data['base_signal'] = $current_units['base_signal'];
                $data['margin_of_safety'] = $current_units['margin_of_safety'];


                $time = strtotime("3:30 PM");

                $data['process_date'] = $this->tPlusDaysCalculation('','redemption')['expireDate'];
//                $data = array_merge($data,$current_units); //adding units array to be update units columns

                $data['opening_eq_units'] = $current_units['closing_eq_units_original'];
                $data['opening_liquid_units'] = $current_units['closing_liquid_units_original'];
                $data['closing_eq_units'] = $current_units['closing_eq_units'];
                $data['closing_liquid_units'] = $current_units['closing_liquid_units'] - $all_sell_units['rebalance_units'];
                if($data['closing_liquid_units'] <= 0){
                  $data['closing_liquid_units'] = 0;
                }
                $data['sell_liquid_units'] = $all_sell_units['sell_liquid_units'];
                $data['rebalance_sell_liquid_units'] = $all_sell_units['rebalance_units'];
                $data['closing_liquid_units_default'] = $current_units['closing_liquid_units_default'];


                //updating reference for purchasing sip for next fn's to be called
                $current_units['closing_liquid_units'] = $data['closing_liquid_units'];
                $current_units['closing_eq_units'] = $data['closing_eq_units'];

                //y($data);
                $data['comment'] = $current_units['comment'];
                $data['mos_date'] = $current_units['mongo_date'];
                $data['rebal_buy_amt'] = $current_units['rebal_buy_amt'];

                if(isset($data['DPTxn'])){
                    unset($data['DPTxn']); //this field is not in database table thats why removed form array
                }
                if($transaction_mode == 'D' && (!isset($data['order_mode']) || empty($data['order_mode'])))
                {
                    $data['order_mode'] = 'D';
                }
                if($transaction_mode == 'P' && (!isset($data['order_mode']) || empty($data['order_mode'])))
                {
                    $data['order_mode'] = 'P';
                }
                $insert=$this->insertSmartOrder($data);
                //x($insert);

                if( $data['order_status'] == 0){
                    return  $data['order_id'];
                }else{
                    return false;
                }
            }else{
                $return = ['status'=>'success', 'msg'=>'Please Login'];
                echo json_encode($return);
            }
         }


         public function sellEqUnits($client_id, $eq_scheme_code, $unit_conv,$transaction_mode,&$current_units ,$all_sell_units,$sell_all_unit = 0) //sell equity units
         {
            $data = array();

            $amount='';
            $qty = '0';
            $scheme_code    = $eq_scheme_code;


            // $presentnav     = $this->input->post('presentnav');
            // $presentunits   = $this->input->post('presentunits');
            $folio = $this->checkFolioNo($scheme_code,$client_id);
            $qty            = $unit_conv;
            if(!empty($folio)){
                $data['folio_number'] = $folio;
            }

            if($sell_all_unit == 1){
                  if($transaction_mode == 'D')
                  {
                      $data['all_redeem'] = '';
                      $data['min_redeem'] = 'Y';
                  }
                  if($transaction_mode == 'P')
                  {
                      $data['all_redeem'] = 'Y';
                      $data['min_redeem'] = '';
                      $qty ='';
                  }

              }

            if(!empty($client_id)){
				$clientinfo = getClientMaster($client_id);
				$data['client_mobile']  = $clientinfo['client_mobile'];
				$data['client_email']   = $clientinfo['client_email'];
			
                if($transaction_mode == 'P'){
                     $data['DPTxn'] = 'P';
                }

                $data['trans_code'] = 'NEW' ;
                $data['client_id'] = $client_id;
                $data['order_id'] = '';
                $data['scheme_code'] = $scheme_code;
                $data['buy_sell'] = 'R';
                $data['buy_sell_type'] = 'FRESH';
                $data['amount'] = $amount;
                $data['quantity'] = $qty;
                //x($data);
                $this->bsestar_mutualfund->k = 2;
                $res=$this->bsestar_mutualfund->orderEntryParam($data);
                //x($res);
                $data['order_type'] = 'lumpsum';
				$data['order_id'] = isset($res[2]) ? $res[2] : 0;
                $data['order_status'] = isset($res[7]) ? $res[7] : 1;
                $data['order_response'] = isset($res[6]) ? $res[6] : "Verify Smart/Super SIP Order";
                $data['unique_ref_no'] = isset($res[1]) ? $res[1] : "";
                $data['date_created'] = $date = date('Y-m-d H:i:s');
                $data['master_id'] = $current_units['master_id'];
                $data['base_signal'] = $current_units['base_signal'];
                $time = strtotime("3:30 PM");

                $data['process_date'] = $this->tPlusDaysCalculation('','redemption')['expireDate'];
               // $data = array_merge($data,$current_units); //adding units array to be update units columns
                $data['margin_of_safety'] = $current_units['margin_of_safety'];


                $data['opening_eq_units'] = $current_units['closing_eq_units_original'];
                $data['opening_liquid_units'] = $current_units['closing_liquid_units_original'];
                $data['closing_eq_units'] = $current_units['closing_eq_units'];
                $data['closing_liquid_units'] = $current_units['closing_liquid_units'] ;
                $data['sell_eq_units'] = $all_sell_units['sell_eq_units'];
                $data['rebalance_sell_eq_units'] = $all_sell_units['rebalance_sell_eq_units'];
                $data['closing_liquid_units_default'] = $current_units['closing_liquid_units_default'];

                //updating reference for purchasing sip for next fn's to be called
                $current_units['closing_liquid_units'] = $data['closing_liquid_units'];
                $current_units['closing_eq_units'] = $data['closing_eq_units'];

              //print_r($data); die;
                 $data['comment'] = $current_units['comment'];
                 $data['mos_date'] = $current_units['mongo_date'];
                 $data['rebal_buy_amt'] = $current_units['rebal_buy_amt'];

                 if(isset($data['DPTxn'])){
                    unset($data['DPTxn']); //this field is not in database table thats why removed form array
                 }
                if($transaction_mode == 'D' && (!isset($data['order_mode']) || empty($data['order_mode'])))
                {
                    $data['order_mode'] = 'D';
                }
                if($transaction_mode == 'P' && (!isset($data['order_mode']) || empty($data['order_mode'])))
                {
                    $data['order_mode'] = 'P';
                }
                $insert=$this->insertSmartOrder($data);


                if( $data['order_status'] == 0){
                    return  $data['order_id'];
                }else{
                    return false;
                }
            }else{
                $return = ['status'=>'success', 'msg'=>'Please Login'];
                echo json_encode($return);
            }
         }


         public function getCurrentNav($scheme_code, $date='')
         {
            if (empty($date) ){
                $nav = $this->mongo_db->where(array('schemecode' => intval($scheme_code) ))->sort('navdate', "desc")->limit(1)->get('mf_navhistfull_accord')[0];
            }else{
                $navdate = $this->getMongoDate( $date);

              $nav = $this->mongo_db->where(array('schemecode' => intval($scheme_code), 'navdate' => $navdate ))->limit(1)->get('mf_navhistfull_accord')[0];

              while (empty($nav)){
                $date = date('Y-m-d', strtotime($date."+1 day"));
                 $navdate = $this->getMongoDate( $date);
                $nav = $this->mongo_db->where(array('schemecode' => intval($scheme_code), 'navdate' => $navdate ))->get('mf_navhistfull_accord')[0];

              }
            }
            return $nav['navrs'];
         }
         public function getNextDayNav($scheme_code, $date='')
         {
            
            //$nav = $this->mongo_db->where(['schemecode' => intval($scheme_code) ])->where_gt('navdate',$date)->limit(1)->get('mf_navhistfull_accord')[0];
            $nav = $this->mongo_db->where(['schemecode' => intval($scheme_code) ])->where_gt('navdate',$date)->sort('navdate','asc')->limit(1)->get('mf_navhistfull_accord')[0];
            return $nav['navrs'];
         }


         public function getSchemeMinValues($scheme_code,$client_id = '',$transaction_mode = '')
       {    
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "Redemption_Qty_Multiplier,Minimum_Purchase_Amount,Purchase_Amount_Multiplier,Minimum_Redemption_Qty,Additional_Purchase_Amount";
            $params['table_name']       = 'mf_scheme_master';
            $params['where']            = TRUE;
            $params['where_data']       = ['Scheme_Code' => $scheme_code];

            $params['return_array']     = True;
            //$params['print_query_exit'] = TRUE;
            $min               = $this->mf->get_table_data_with_type($params)[0];

            $folio_no = $this->checkFolioNo($scheme_code,$client_id,$transaction_mode);

            if(!empty($folio_no)){
              $min['Minimum_Purchase_Amount'] = $min['Additional_Purchase_Amount'];
            }    
            return $min;     


       }



       /* SMART SIP FETCH SIGNAL FROM MARGIN OF SAFETY
        Author: Dharmesh
        <---Code begin ---->
        */

         function smartSipSettings()
    {
        $this->load->library('mongo_db');
        $this->mongo_db->reconnect(['config_group' => 'default']);
        $result = $this->mongo_db->where(array('status' => 1))->get('smart_sip_settings');
        return $result[0];
    }

    function getBaseSafetyLavel($margin_safety='')
    {
        // ini_set('display_errors',1);error_reporting(E_ALL);
        // $margin_safety = 80;
        $result = $this->smartSipSettings();
        $check_setting = array();
        $new_settings  = array();
        $check_setting['lt']  = '<';
        $check_setting['lte'] = '<=';
        $check_setting['gt']  = '>';
        $check_setting['gte'] = '>=';

        for ($i=1; $i <=4 ; $i++) {
          $split = explode("-",$result['base_safety_level'.$i]);
          $split1 = explode("_",$split[0]);
          $new_settings['base_safety_level'.$i][0] = $check_setting[$split1[0]]." ".$split1[1];
          if($split[1])
          {
            $split2 = explode("_",$split[1]);
            $new_settings['base_safety_level'.$i][1] = $check_setting[$split2[0]]." ".$split2[1];
          }

        }

       $sign1   =  $this->checkSignals($margin_safety,$new_settings['base_safety_level1'][0]);
       $sign2_0 =  $this->checkSignals($margin_safety,$new_settings['base_safety_level2'][0]);
       $sign2_1 =  $this->checkSignals($margin_safety,$new_settings['base_safety_level2'][1]);
       $sign3_0 =  $this->checkSignals($margin_safety,$new_settings['base_safety_level3'][0]);
       $sign3_1 =  $this->checkSignals($margin_safety,$new_settings['base_safety_level3'][1]);
       $sign4   =  $this->checkSignals($margin_safety,$new_settings['base_safety_level4'][0]);

       if($sign1 == 1)
            $signals = "sell";
       else if($sign2_0 == 1 && $sign2_1 == 1)
            $signals = "skip";
       else if($sign3_0 == 1 && $sign3_1 == 1)
            $signals = "invest";
       else if($sign4 == 1)
            $signals = "double_invest";
       return $signals;

    }


    function getRebalanceSafetyLevel($margin_safety)
    {
        // ini_set('display_errors', 1);error_reporting(E_ALL);
        // $margin_safety = 61;
        $result = $this->smartSipSettings();
        $check_setting = array();
        $new_settings = array();
        $check_setting['lt']  = '<';
        $check_setting['lte'] = '<=';
        $check_setting['gt']  = '>';
        $check_setting['gte'] = '>=';

        for ($i=1; $i <=4 ; $i++) {
          $split = explode("-",$result['rebalance_safety_level'.$i]);
          $split1 = explode("_",$split[0]);
          $new_settings['rebalance_safety_level'.$i][0] = $check_setting[$split1[0]]." ".$split1[1];
          if($split[1])
          {
            $split2 = explode("_",$split[1]);
            $new_settings['rebalance_safety_level'.$i][1] = $check_setting[$split2[0]]." ".$split2[1];
          }

        }
        $sign1   =  $this->checkSignals($margin_safety,$new_settings['rebalance_safety_level1'][0]);
        $sign2_0 =  $this->checkSignals($margin_safety,$new_settings['rebalance_safety_level2'][0]);
        $sign2_1 =  $this->checkSignals($margin_safety,$new_settings['rebalance_safety_level2'][1]);
        $sign3_0 =  $this->checkSignals($margin_safety,$new_settings['rebalance_safety_level3'][0]);
        $sign3_1 =  $this->checkSignals($margin_safety,$new_settings['rebalance_safety_level3'][1]);
        $sign4   =  $this->checkSignals($margin_safety,$new_settings['rebalance_safety_level4'][0]);

 // $action ='sell'; //'invest','sell','skip','double_invest'
 //                 $rbalance = 'very_aggressive'; //'very_aggressive', 'aggressive'
 //                 $rebalance_sell = 'aggressive_sell_sell'; // 'aggressive_sell', 'very_aggressive_sell'
        if($sign1 == 1)
            $signals = "very_aggressive_sell";
       else if($sign2_0 == 1 && $sign2_1 == 1)
            $signals = "aggressive_sell";
       else if($sign3_0 == 1 && $sign3_1 == 1)
            $signals = "aggressive";
       else if($sign4 == 1)
            $signals = "very_aggressive";
        // y($result);
       return $signals;
    }

    function getSignals($margin_safety)
    {
        if(!empty($margin_safety))
        {
            $baseSafety      = $this->getBaseSafetyLavel($margin_safety);
            $rebalanceSafety = $this->getRebalanceSafetyLevel($margin_safety);
            $data = array(
                'baseSafetyLavel' => $baseSafety,
                'rebalanceSafetyLevel' => $rebalanceSafety
            );
            return ($data);
        }

    }

    function checkSignals($ms, $cond1) {
        $base_safety = explode(" ", $cond1);
        switch($base_safety[0]) {
            case '<=':
                    if($ms <= $base_safety[1])
                        return $val = 1;
                    else
                        return $val = 0;
            break;
            case '<':
                    if($ms < $base_safety[1])
                        return $val = 1;
                    else
                        return $val = 0;
            break;
            case '>=':
                    if($ms >= $base_safety[1])
                        return $val = 1;
                    else
                        return $val = 0;
            break;
            case '>':
                    if($ms > $base_safety[1])
                        return $val = 1;
                    else
                        return $val = 0;
            break;
        }
    }

   public function getAccordSchemeCode($scheme_code)
      {
          $params['env']              = 'default';
          $params['select_data']      = "schemecode";
          $params['table_name']       = 'mf_aggregated_search_data';
          $params['where']            = TRUE;
          $params['where_data']       =array('mf_aggregated_search_data.Scheme_Code' => $scheme_code);

          $params['return_array']     = True;
          //$params['print_query_exit'] = TRUE;
          $data                = $this->sn->get_table_data_with_type($params);
          return($data[0]['schemecode']);
      }


      function round_nearest($no,$near) // round of to nearest multiple
    {
        return round($no/$near)*$near;
    }
      function round_min($no,$near) // round of to lowest multiple
    {
        return floor($no / $near) * $near;
    }

    function getLiquidSchemeForSmartSIP($bse_scheme_eq = '')
    {
        $arr = ['liquid_scheme_code' => 'ICICI1565-GR-L0',
                'accord_liquid_scheme_code' => '1659',
              ];
        return $arr; // by default ICICI for all schemes
            //
        if(empty($bse_scheme_eq) ){
          $bse_scheme_eq = $this->input->post('scheme_code');
        }
        $params                     = array();
        $params['env']              = 'db';            
        $params['select_data']      = 'a.id,a.schemecode,a.Unique_No,a.rating,a.Incept_date,a.Amc_code';
        $params['table_name']       = 'mf_aggregated_search_data as a';   
        $params['where']            = TRUE;       
        $params['where_data']       = array('a.Scheme_Code'=>$bse_scheme_eq);  
             //,'a.rating >=' => 3  
        
        $get_amc_code       = $this->mf->get_table_data_with_type($params);
        $Amc_code=$get_amc_code[0]->Amc_code;
        $params                     = array();
        $params['env']              = 'db';            
        $params['select_data']      = 'a.id,a.schemecode,a.Unique_No,a.rating,a.Incept_date,sm.Additional_Purchase_Amount,a.Scheme_Code,a.Scheme_Name';
        $params['table_name']       = 'mf_aggregated_search_data as a';   
        $params['where']            = TRUE;
        $params['join']             =TRUE;
        $params['join_table']       ='mf_scheme_master as sm';
        $params['join_on']          ='a.Unique_No = sm.Unique_No';
        $params['join_type']        ='LEFT';       
        $params['where_data']       = array('a.Scheme_Type'=>'LIQUID','a.Dividend_Reinvestment_Flag'=>'Z','a.Amc_code'=>$Amc_code,'primary_scheme'=>'1'); 
        //$params['HAVING']         = 'Additional_Purchase_Amount=100'; 
        //$params['print_query_exit']        =TRUE;

             //,'a.rating >=' => 3  
       
        $allequitySchemesData       = $this->mf->get_table_data_with_type($params);
        foreach ($allequitySchemesData as $key => $value) {
          if($value->Additional_Purchase_Amount <= 100)
          {
           $arr = array('liquid_scheme_code'=>$value->Scheme_Code,'accord_liquid_scheme_code'=>$value->schemecode,'liquid_scheme_name' => $value->Scheme_Name );
           break; 
          }
        }
            //
       if(isset($_POST['scheme_code']))  {
        if(empty($arr['liquid_scheme_name'])){
          $arr['liquid_scheme_name'] = 'ICICI Prudential Liquid Fund - Regular - Growth';
        }
        
        echo json_encode($arr); 
        die;
       }

       unset($arr['liquid_scheme_name']);
       return $arr;
    }

  public function checkMandateAmount()
  { 
         // $date = date("d",strtotime($this->input->post('date')));
         $client_id = $this->session->client_id;
         if(empty($client_id)){
            $client_id = $this->input->post('client_id');
          }
          //$client_id = "RT1001";
          $amount = $this->input->post('amount');
          $client_id = $client_id;
          // x($_POST);
          //$this->updateMandateStatus($client_id);

          //$smartswitch_settings = $this->getSettingsTableValue('IS_LEDGER_FOR_SMARTSWITCH_ENABLED'); 

          $params['env']              = 'default';
          $params['select_data']      = "*";
          $params['table_name']       = 'mf_samco_mandate_regitrations';
          
          $params['where']            = true;
          $params['where_data']       = array('client_id' => $client_id, 'mandate_amt >' => $amount);
          $params['where_not_in']       = true;
          $params['where_not_in_field']  = 'mandate_status';
          $params['where_not_in_array'] = ['rejected','deleted'];
          $params['order_by'] = 'mandate_amt desc';
          $params['return_array'] = true;
          // $params['limit_data'] = 1;

          // $params['print_query_exit'] = TRUE;
          $mandate              = $this->sn->get_table_data_with_type($params);
          if(!empty($mandate) && count($mandate) == 1){ //if only 1 mandate returned

           $params1['env']          = 'default';
           $params1['select_data']  = "*";
           $params1['table_name']   = 'mf_client_order_mfi';
           $params1['where']        = true;
           $params1['where_data']   = array('client_id' => $client_id,'order_type' => 'xsip','order_status' => '0' , 'DAY(start_date)' => $date ); 
           //day only retrieve to not clash with future sip for same day
           $params1['return_array'] = true;
           //$params1['print_query_exit'] = TRUE;
           $order_data              = $this->sn->get_table_data_with_type($params1);
           
            $total = 0;
            if(!empty($order_data)){
              foreach($order_data as $order){
                $total += $order['installment_amount'];

              }
              $total = $total + $amount;

               if($total > $mandate[0]['mandate_amt'] ){
                 $response = array('status'=>'error',  'msg' => 'Mandate Amount exhausted for this day. Please select a different start date' ,'mandate' => $mandate[0]['mandate_id'],'uploaded' => $mandate[0]['uploaded'],'deficiency'=>$mandate[0]['deficiency'], 'mandate_status'=>$mandate[0]['mandate_status'],'mandate_amt'=>$mandate[0]['mandate_amt'],'mandate_type'=>$mandate[0]['type']) ;

              }
              else{
                $response = array('status'=>'ok',  'msg' => 'ok' ,'mandate' => $mandate[0]['mandate_id'],'uploaded' => $mandate[0]['uploaded'],'deficiency'=>$mandate[0]['deficiency'], 'mandate_status'=>$mandate[0]['mandate_status'],'mandate_amt'=>$mandate[0]['mandate_amt'],'mandate_type'=>$mandate[0]['type']);
              }
            }
            else{
                  if($amount > $mandate[0]['mandate_amt'] ){
                $response = array('status'=>'error',  'msg' => 'Mandate Amount exhausted. Your Mandate limit for a day is '.$mandate[0]['mandate_amt'].'.Create new Mandate  ' ,'mandate' => $mandate[0]['mandate_id'],'uploaded' => $mandate[0]['uploaded'],'deficiency'=>$mandate[0]['deficiency'], 'mandate_status'=>$mandate[0]['mandate_status'],'mandate_amt'=>$mandate[0]['mandate_amt'],'mandate_type'=>$mandate[0]['type'])  ;
              }

              else{
                 $response = array('status'=>'ok',  'msg' => 'ok','mandate' => $mandate[0]['mandate_id'],'uploaded' => $mandate[0]['uploaded'],'deficiency'=>$mandate[0]['deficiency'], 'mandate_status'=>$mandate[0]['mandate_status'],'mandate_amt'=>$mandate[0]['mandate_amt'],'mandate_type'=>$mandate[0]['type']);
              }
            }
          }elseif(!empty($mandate) && count($mandate) > 1){
              $html = '';
            $params1 = array();
              $params1['env']              = 'default';
              $params1['select_data']      = "*";
              $params1['table_name']       = 'mf_client_order';
              $params1['where']            = true;
              $params1['where_data']       = array('client_id' => $client_id,'order_type' => 'xsip','order_status' => '0' );

              $params1['return_array'] = true;

            //$params1['print_query_exit'] = TRUE;
            $order_data              = $this->sn->get_table_data_with_type($params1);
            $count = 0;
            $mandateval = 0;
            $mandate_field = '';
            $deficiency = 1;
            $uploaded = '';
            $mandate_status = '';
            $recheck = true;
              foreach($mandate as $value){

                $temp = $value['mandate_amt'];
                  if($temp > $mandateval && $value['deficiency'] == 0)
                      {
                          $mandateval = $value['mandate_amt'];
                          $mandate_field = $value['mandate_id'];
                          $deficiency = $value['deficiency'];
                          $uploaded = $value['uploaded'];
                          $mandate_status = $value['mandate_status'];
                          $mandate_type= $value['type'];
                      }

                foreach($order_data as $order){

                  // if(date("d",strtotime($order['start_date'])) == $date && $order['mandate_id'] == $value['mandate_id'] ){
                  //     $value['mandate_amt'] = $value['mandate_amt'] - $amount;

                  //   }

                   }
                   if($amount <= $value['mandate_amt'] ){

                      $count = $count +1;

                      if($value['uploaded']  != 0 && $value['deficiency'] == 0 && ($value['mandate_amt'] >= $amount)) //mandate sign uploaded
                      {
                          $mandateval = $value['mandate_amt'];
                          $mandate_field = $value['mandate_id'];
                          $deficiency = $value['deficiency'];
                          $uploaded = $value['uploaded'];
                          $mandate_status = $value['mandate_status'];
                          $mandate_type = $value['type'];

                               if(strtolower($value['mandate_status'])  == 'active') //mandate sign uploaded and approved
                          {
                              $mandateval = $value['mandate_amt'];
                              $mandate_field = $value['mandate_id'];
                              $deficiency = $value['deficiency'];
                              $uploaded = $value['uploaded'];
                              $mandate_status = $value['mandate_status'];
                              $mandate_type = $value['type'];
                              break; // no need to check for any more mandates
                          }

                      }
                      //$html .= ' <option value="'.$value['mandate_id'].'"  type="'.$value['mandate_type'].'" upload_type="'.$value['upload_type'].'" uploaded="'.$value['mandate_option'].'" deficiency="'.$value['deficiency'].'"  status="'.$value['mandate_status'].'" amount="'.$value['mandate_amt'].'">RS.'.$value['mandate_amt'].'  ('.$value['mandate_id'].'- '.$value['mandate_type'].'SIP)</option>';

                   }


                }

                if(empty($mandate_field) ){
                          $mandateval = $mandate[0]['mandate_amt'];
                          $mandate_field = $mandate[0]['mandate_id'];
                          $deficiency = $mandate[0]['deficiency'];
                          $uploaded = $mandate[0]['uploaded'];
                          $mandate_status = $mandate[0]['mandate_status'];
                          $mandate_type =  $mandate[0]['type'];
                  }


               if($count == 1){
                   $response = array('status'=>'ok', 'deficiency'=>$deficiency, 'uploaded'=>$uploaded , 'mandate_status' => $mandate_status, 'msg' => $html , 'mandate' => $mandate_field, 'mandate_amt'=>$mandateval, 'count' => $count,'type'=>$mandate_type);

               }
               elseif($count == 0){
                    $response = array('status'=>'error','deficiency'=>$deficiency, 'uploaded'=> $uploaded, 'mandate_status' => $mandate_status,  'msg' => 'Your Maximum mandate amount is Rs '.$mandateval.'.A new mandate will automatically be created if proceeded with this order ' , 'mandate_amt'=>$mandateval, 'mandate' => $mandate_field,'count' => $count,'type'=>$mandate_type );

               }
               else{ // multiple mandates


                $response = array('status'=>'ok', 'deficiency'=>$deficiency, 'uploaded'=>$uploaded , 'mandate_status' => $mandate_status , 'msg' => $html , 'mandate_amt'=>$mandateval,  'mandate' => $mandate_field ,'count' => $count,'type'=>$mandate_type);
               }
           }else{
              //NO MANDATES AVAILABLE
              $flag = $_POST['flag'];
                if($flag == 1){
                  $flagtype = 'SIP+';
                }
                else{
                  $flagtype = 'SmartSIP';
                }
           $response = array('status'=>'error',  'msg' => 'The mandate is necessary to begin your '.$flagtype.'. There are no Mandates registered by you. Please create a new Mandate upon completion of this order.' , 'mandate_amt'=>'', 'uploaded' => '0', 'mandate' => 'NOMANDATE' ,'count' => '1', 'mandate_status' =>'','type'=>'' );
               }
                        //print_r( $amount); die;
               if($response['uploaded'] == '0' || $response['deficiency'] == '1'){
                  $iamt = $amount;
                  $mandate_amt = 10000;
                  $dividend = $iamt/$mandate_amt;
                  if($dividend <1)
                  {
                      $mandate_amt = 10000;
                  }
                  else if($dividend >1 && $dividend <=10){
                     $mandate_amt = 10000 * ceil($dividend);
                  }
                  else if($dividend >10 && $dividend <=99){
                      $mandate_amt = 1000000;
                  }else{
                      $mandate_amt = 10000000;
                  }
                  //print_r( $mandate_amt); die;
                  if($response['mandate'] != 'NOMANDATE' && $response['deficiency'] == '1'){
                      $mandate_amt = $response['mandate_amt'];
                  }

               }else{
                   $response['mandate_link'] = '';
               }
          // return = 1 user return tyhe data for API
          if($this->input->post('return') == 1)
            return $response;
          else
           echo json_encode ($response);

      }

      function OTMPayment($mandate_id,$order_no){
        if(!empty($mandate_id) && !empty($order_no)){
           $data = array(
            'MandateId' => $mandate_id,
            'OrderNo' => $order_no
            );
            $response = $this->bsestar_mutualfund->OTMPaymentInitiate($data,'MFD');
            
            $update_data                 = array();
            $update_data['order_payment_status']     = $response->BSERemarks;

            $where_data                  = array("order_id"=>$order_no);
            $params_up                   = [];
            $params_up['env']            = 'db';
            $params_up['table_name']     = 'mf_order_smart_sip';
            $params_up['update_data']    = $update_data;
            $params_up['where']          = TRUE;
            $params_up['where_data']     =  $where_data;
            $update_status = $this->mf->update_table_data_with_type($params_up);
            return $response;
        }
        
    }

    /* Code Ends */






















































  /*author: Prashant
      Order Listing
     * created: 06/03/2018
     * modified by: Prashant*/
    public function orderListing(){
        $data = array();
        $client_id = $this->session->userdata('client_id');
        // $this->checkOrderStatus($client_id);
        // $this->sipOrderUpdate($client_id);
        //$client_id = 'DP17682';
        if(empty($client_id)){
            redirect(base_url());
        }else{
            // $params                     = array();
            // $params['env']              = 'db';
            // $params['select_data']      = "*";
            // $params['table_name']       = 'mf_client_order';
            // $params['where']            = TRUE;
            // $params['where_data']       = array('date_created >='=>date('Y-m-d'),'date_created <='=>date('Y-m-d'),'client_id' => $client_id);
            // $params['order_by']         = "date_created DESC";
            // $data["clientOrders"]       = $this->mf->get_table_data_with_type($params);

            $data['content'] = $this->load->view('client_reports/mf_orderlisting', $data, true);
            $this->load->view('layout', $data);
        }
    }

    public function order_summary(){
        //ini_set('display_errors',1);error_reporting(E_ALL);
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
           /* $fromdate_arr = explode('/', $this->input->post('from_date'));
            $todate_arr = explode('/', $this->input->post('to_date'));

            $from_date = $fromdate_arr[2] . '-' . $fromdate_arr[1] . '-' . $fromdate_arr[0];
            $to_date = $todate_arr[2] . '-' . $todate_arr[1] . '-' . $todate_arr[0];*/
            $post      = $this->input->post();
            $from_date =  date("Y-m-d",strtotime($this->input->post('from_date')));
            $to_date   =  date("Y-m-d 23:59:59",strtotime($this->input->post('to_date')));

            $client_id = $this->session->userdata('client_id');

             //added by tanvi
            $first_param = $post['first_param'];

            if($first_param == 1){
                $this->checkOrderStatus($client_id);
                // $this->checkOrderStatus($client_id,'MFI');
                $this->getOrderStatusMFI('P',$client_id);
                $this->getOrderStatusMFI('R',$client_id);
                //$this->sipOrderUpdate($client_id); //removed because updated by provisional order status
            }

            //added by tanvi

            //$client_id = 'DP17682';

            if($from_date != '' && $to_date != ''){
                $where_data       = array('date_created >='=>$from_date,
                                    'date_created <='=>$to_date,
                                    'client_id' => $client_id);
            }else{
                $where_data       = array('client_id' => $client_id);
            }

            if(isset($post['order_status']) && $post['order_status'] != '')
                $where_data['order_status'] = $post['order_status'];

            if(isset($post['buy_sell']) && $post['buy_sell'] != '')
                $where_data['buy_sell'] = $post['buy_sell'];

            if(isset($post['order_type']) && $post['order_type'] != ''){
                $params['where_in'] = TRUE;
                $params['where_in_field'] = 'order_type';
                $params['where_in_data'] = explode("-",$post['order_type']);
            }
            //x($params);
            $params['env']              = 'db';
            $params['select_data']      = "mf_client_order.*, mf_aggregated_search_data.Scheme_Name";
            $params['table_name']       = 'mf_client_order';
            $params['where']            = TRUE;
            $params['where_data']       = $where_data;
            $params['order_by']         = "date_created DESC";
            //$params['escape_fields']    = FALSE;
            //$params['print_query_exit'] = TRUE;
            $params['join']=TRUE;
            $params['multiple_joins']=TRUE;
            $params['join_table']=array('mf_aggregated_search_data');
            $params['join_on']=array(
                'mf_client_order.scheme_code = mf_aggregated_search_data.Scheme_Code'
            );

            $params['join_type']=array('INNER');
            $data["clientOrders"]       = $this->mf->get_table_data_with_type($params);


            $params['env']              = 'db';
            $params['select_data']      = "mf_client_order_mfi.*, mf_aggregated_search_data.Scheme_Name";
            $params['table_name']       = 'mf_client_order_mfi';
            $params['where']            = TRUE;
            $params['where_data']       = $where_data;
            $params['order_by']         = "date_created DESC";
            //$params['escape_fields']    = FALSE;
            //$params['print_query_exit'] = FALSE;
            $params['join']=TRUE;
            $params['multiple_joins']=TRUE;
            $params['join_table']=array('mf_aggregated_search_data');
            $params['join_on']=array(
                'mf_client_order_mfi.scheme_code = mf_aggregated_search_data.Scheme_Code'
            );

            $params['join_type']=array('INNER');
            $msiorders                  = $this->mf->get_table_data_with_type($params);

            foreach($msiorders as $msiorder){
                $msiorder->mfi='yes';
            }

            if($data["clientOrders"] && $msiorder)
                $data["clientOrders"] = array_merge($data["clientOrders"],$msiorders);
            else{
                if($msiorder)
                    $data["clientOrders"]= $msiorders;
            }

            //code to check if client's bank exists with bse
            $params                     = [];
            $params['env']              = 'db';
            $params['select_data']      = "client_ifsc";
            $params['table_name']       = 'mf_client_master';
            $params['where']            = TRUE;
            $params['where_data']       = array('client_id'=>$client_id);

            $params['return_array']     = TRUE;
            $client                     = $this->mf->get_table_data_with_type($params);
            $ifsc = strtoupper(substr($client[0]['client_ifsc'], 0, 4));

            $bankparam['env']              = 'db';
            $bankparam['select_data']      = "*";
            $bankparam['table_name']       = 'mf_bank_code';
            $bankparam['where']            = TRUE;
            $bankparam['where_data']       = array('ifsc'=>$ifsc);

            $bankparam['return_array']      = TRUE;
                //$bankparam['print_query_exit'] = TRUE;
            $bank_detail                    = $this->mf->get_table_data_with_type($bankparam);
            $data['bank_detail']            = $bank_detail;
            $content = $this->load->view('client_reports/order_summary_view_ajax', $data, true);
            echo $content;die;
        }
    }
    public function saveCancelLog($insert_data){
        $insparam  =[];
        $insparam['env']        = 'db';
        $insparam['table_name'] = 'mf_order_cancel_log';
        $insparam['data']       = $insert_data;
        $insert_id                   = $this->mf->insert_table_data($insparam);
        return $insert_id;
    }




    public function insertClientMaster($insert_data)
    {
            $insparam  =[];
            $insparam['env']        = 'db';
            $insparam['table_name'] = 'mf_client_master';
            $insparam['data']       = $insert_data;
            $this->mf->insert_table_data($insparam);
            return true;

    }
    public function insertOrderMaster($insert_data,$where='')
    {
            $insparam  =[];
            $insparam['env']        = 'db';
            $insparam['table_name'] = 'mf_client_order';
            if(!isset($insert_data['created_by'])){
                $insert_data['created_by'] = $this->session->userdata('created_by');
                $insert_data['source'] = $this->session->userdata('source');
            }
            $insparam['data']       = $insert_data;


            if(empty($where)){
               $var = $this->mf->insert_table_data($insparam);

            }
            else{
                 $var = $this->mf->update_table_data($insparam);
                 $insparam['where_data']       = $where;


            }
            return $var;

    }

    public function insertOrderMaster_mfi($insert_data,$where='')
    {
            $insparam  =[];
            $insparam['env']        = 'db';
            //$insparam['print_query_exit']        = TRUE;
            $insparam['table_name'] = 'mf_client_order_mfi';
            if(!isset($insert_data['created_by'])){
                $insert_data['created_by'] = $this->session->userdata('created_by');
                $insert_data['source'] = $this->session->userdata('source');
            }

            $insparam['data']       = $insert_data;

            if(empty($where)){
               $var = $this->mf->insert_table_data($insparam);

            }
            else{
                 $var = $this->mf->update_table_data($insparam);
                 $insparam['where_data']       = $where;


            }
            return $var;

    }




    public function getSchemeDetails($column_names,$where_field='',$table_name='mf_scheme_master')
     {
        $params['env']              = 'db';
        $params['select_data']      = $column_names;
        $params['table_name']       = $table_name;
        $params['where']            = TRUE;
        $params['where_data']       = $where_field;
        $params['group_by']         = $column_names;
        $params['escape_fields']    = true;
        $params['where_escape']     = false;
        $params['return_array']     = true;

       // $params['print_query_exit'] = TRUE;
        $data                 = $this->mf->get_table_data_with_type($params);

        return $data;
     }



       public function getScheme($amc_code)
    {
        $amc_code =  urldecode($amc_code);
        $schemes = $this->getSchemeDetails('Scheme_Name',array('AMC_Code'=>$amc_code));

        $this->load->view('mutualfund/jsonresponse', ['data' => $schemes]);


    }

    public function getAmc($val)
    {
        $val =  urldecode($val);

        $amc = $this->getSchemeDetails('AMC_Code',array('Scheme_Type'=>$val));

        $this->load->view('mutualfund/jsonresponse', ['data' => $amc]);
    }

    public function getSchemeListing()
    {
        $amc =$this->input->post('Amc_Code');
        $scheme = $this->input->post('Scheme_Name');
        if(empty($scheme)){
            $where = array('Amc_Code'=>$amc);
        }else{
            $where = array('Amc_Code'=>$amc,'Scheme_Name'=>$scheme);

        }
        $params['env']              = 'db';
        $params['select_data']      = 'Unique_No,Scheme_Code,RTA_Scheme_Code,AMC_Scheme_Code,ISIN,AMC_Code,Scheme_Type,Scheme_Plan,Scheme_Name,Purchase_Allowed,Purchase_Transaction_mode,Minimum_Purchase_Amount,Additional_Purchase_Amount,Maximum_Purchase_Amount,Purchase_Amount_Multiplier,Purchase_Cutoff_Time,Redemption_Allowed,Redemption_Transaction_Mode,Minimum_Redemption_Qty,Redemption_Qty_Multiplier,Maximum_Redemption_Qty,Redemption_Amount_Minimum,Redemption_Amount_Maximum,Redemption_Amount_Multiple,Redemption_Cut_off_Time,RTA_Agent_Code,AMC_Active_Flag,Dividend_Reinvestment_Flag,SIP_FLAG,STP_FLAG,SWP_Flag,Switch_FLAG,SETTLEMENT_TYPE,AMC_IND,Face_Value,Start_Date,End_Date,Exit_Load_Flag,Exit_Load,Lock_in_Period_Flag,Lock_in_Period,Channel_Partner_Code';
        $params['table_name']       = 'mf_scheme_master';
        $params['where']            = TRUE;
        $params['where_data']       = $where;
        $params['escape_fields']    = FALSE;
        $params['where_escape']     = FALSE;



       // $params['print_query_exit'] = TRUE;7
        $data['result']                = $this->mf->get_table_data_with_type($params);

        // get count of assigned to End -----
        $content = $this->load->view('mutualfund/mutualfund_mfa_order_ajax', $data);


    }

 

    public function getPaymentLink()
    {
        $data = array();
        $client_id = $this->session->userdata('client_id');

        if(empty($client_id)){
            redirect(base_url());
        }else{

            //pan_rp
           $data['client_id'] = $this->session->userdata('client_id');
           $data['logout_url'] = 'http://samco.in';
           $result = $this->bsestar_mutualfund->MFAPI($data,'03');
           if($result[0] == '100')
              return $result[1];
          else
             return false;

       }
    }

    /* author: Prashant
       Added for update SIP order number
     * modified: 06/06/2018
     * modified by: Prashant */
    public function sipOrderUpdate($client_id = ''){
       // ini_set('display_errors',1);error_reporting(E_ALL);
        $params                     = array();
        $params['env']              = 'db';
        //$params['select_data']      = 'client_id,trans_code,order_type,order_id,unique_ref_no,buy_sell,buy_sell_type,amount,quantity,mandate_id,start_date,order_status,order_payment_status';
        $params['select_data']      = "id,order_id,sip_reg_id,client_id,order_type,date_created";
        $params['table_name']       = 'mf_client_order_mfi';
        $params['where']            = TRUE;
         if($client_id == '')
            $params['where_data'] = array('order_status'=>'0','order_type'=>'SIP','sip_reg_id !='=> '');
         else
            $params['where_data'] = array('order_status'=>'0','order_type'=>'SIP','client_id'=>$client_id,'sip_reg_id !='=> '' );
        $params['order_by']         = "date_created DESC";
        $clientOrders               = $this->mf->get_table_data_with_type($params);
        if(!empty($clientOrders)){

            foreach($clientOrders as $orders){
                if($orders->order_type == "SIP"){
                    //if($orders->mandate_type == 'I')
                       // $order_type = 'ISIP';
                    //else
                        //$order_type = 'SIP';

                    $data = ['Date'=>date("d M Y",strtotime($orders->date_created)),'SystematicPlanType'=> $orders->order_type,'client_id' => $orders->client_id,'RegnNo' => $orders->sip_reg_id];

                    $res_xsip  = $this->bsestar_mutualfund->ChildOrderDetails($data);
                    //echo "<pre>";var_dump($orders->sip_reg_id,$res_xsip);
                    if(!empty($res_xsip)){
                        $update_data                 = array();
                        $update_data['order_id']     = $res_xsip['OrderNumber'];

                        $where_data                  = array("id"=>$orders->id);
                        $params_up                   = [];
                        $params_up['env']            = 'db';
                        $params_up['table_name']     = 'mf_client_order_mfi';
                        $params_up['update_data']    = $update_data;
                        $params_up['where']          = TRUE;
                        $params_up['where_data']     =  $where_data;
                        $update_status = $this->mf->update_table_data_with_type($params_up);
                    }
                }
            }
        }
    }



     function getSipOrderNo($client_id = '',$reg_id = '' ,$order_type = ''){




                    $this->bsestar_mutualfund->k = 2; // SIP/XSIP FOR MFI ENV ONLY
                    $data = ['Date'=>date("d M Y"),'SystematicPlanType'=> $order_type,'client_id' => $client_id,'RegnNo' => $reg_id];
                    $res_xsip  = $this->bsestar_mutualfund->ChildOrderDetails($data);

                    if(!empty($res_xsip)){
                        $update_data                 = array();
                        $update_data['order_id']     = $res_xsip['OrderNumber'];

                        $where_data                  = array("sip_reg_id"=>$reg_id);
                        $params_up                   = [];
                        $params_up['env']            = 'db';
                        $params_up['table_name']     = 'mf_client_order_mfi';
                        $params_up['update_data']    = $update_data;
                        $params_up['where']          = TRUE;
                        $params_up['where_data']     =  $where_data;
                        //$params_up['print_query_exit']     =  TRUE;
                        $update_status = $this->mf->update_table_data_with_type($params_up);
                     }

                     return $res_xsip['OrderNumber'];


    }

   

   


    function sendOrderConfirmationDetails($param){

 
        $next_t_day = $param['parameters']['Next-SmartSIP-date'];
        $orderno    = $param['parameters']['orderno'];  
        $scheme_name= $param['parameters']['scheme_name'];

        if(!empty($param['client_id']) ){
            $this->load->library('Php_mailer');
            if(isset($param['parameters']['link'])){
                $autologin_url = $param['parameters']['link'];
            }else{
                $encrypt_id = $this->mf->encrypt_decrypt('encrypt', $param['client_id']);
                $autologin_url = RANK_MF_URL.'Mf_client_login/autoLoginmutualfundOrder/'.$encrypt_id;
            }

            $mail_data = array();
            $mail_data['from']      = array('support@samco.in', 'Samco Support');
            $mail_data['to']        = array(array($param['client_email'],$param['client_name']));
            $mail_data['subject']   = "Order Success";
            //$mail_data['message']   = 'Your Order for Scheme '.$param['scheme_code'].' has been succesfully placed..<br>Login to your backoffice and pay if you"ve not already paid '.$autologin_url;
            if(empty($mandateid)){
                $mail_data['message'] = "Hi, Your SmartSIP order vide ".$orderno." for ".$scheme_name." of ".$param['amount']." is successfully placed. Your next SmartSIP date is ".$next_t_day.". - Team RankMF";
            }else{
                $mail_data['message'] = "Hi, Your SmartSIP order for ".$scheme_name." of ".$param['amount']." is incomplete. Please set up your bank mandate to complete your SmartSIP order. ".$autologin_url." - Team RankMF";
            }    

            $params['templateName'] = $param['templateName'];
            $params['channel']      = $param['channel'];

            if(!isset($param['partner_email']) && empty($param['partner_email']))
                $params['to']           = array(array($param['client_email']));
            else
                $params['to']           = array(array($param['client_email']), array($param['partner_email']));
            // $params['to']           = array(array($param['client_email']));
            $params['merge_vars']   = $param['parameters'];
            $params['merge_vars']['link'] = $autologin_url;
            //x($params);
            $this->php_mailer->mandrill_v2($params);


            // send sms
            $msg = $param['sms_text'];

            $sms_data['message']=$msg ;
            $sms_data['numbers']=array($param['client_mobile']);
            $buffer= sendSms($sms_data);//SMS FN API AND WEB


            // save email logs
            $insert_arr = [];
            $insert_arr[]=array(
                            'email'=>$param['client_email'],
                            'data'=> json_encode($param),
                            'for' => 'Order Success Details',
                            );
            insertEmailLogs($insert_arr);
        }

    }

 
   

    public function getSubBasket($basket_id)
    {
      $params2                     = array();
      $params2['env']              = 'db';
      $params2['select_data']      = "*";
      $params2['table_name']       = 'mf_basket_fund_list';
      $params2['where']            = true;
      $params2['where_data']       = array('basket_id' => $basket_id, 'status' => 1);
      $params2['join']             = TRUE;
      $params2['join_table']       = 'mf_aggregated_search_data';
      $params2['join_on']          = 'mf_aggregated_search_data.Unique_No = mf_basket_fund_list.scheme_unique_no';
      $params2['join_type']        = 'INNER';
      $params2['return_array']     = true;
     //$params2['print_query_exit']     = true;

      $data2                       = $this->sn->get_table_data_with_type($params2);
      return($data2);
    }

    public function insertBasketOrder($basket_details)
    {

        $insparam               = [];
        $insparam['env']        = 'db';
        $insparam['table_name'] = 'mf_basket_order';
        $insparam['data']       = $basket_details;
        $insert_id              = $this->mf->insert_table_data($insparam);
        return $insert_id;
    }

   
    

   


  

    public function getBseClientBankDetails($client_id)
    {
             $params                 = [];
            $params['env']          = 'db';
            $params['select_data']  = 'client_id,client_name,client_acc_type,client_acc_no,client_acc_micro,client_ifsc,client_email,client_mobile,bank_name,client_status,client_status_mfi,mandate_option,sign_name';
            $params['table_name']   = 'mf_client_master';
            $params['where']        = TRUE;
             $params['return_array']        = TRUE;
            $params['where_data']   = array('client_id' => $client_id);

            $client_data = $this->mf->get_table_data_with_type($params);

            return($client_data[0]);
    }


     public function getSchemeNamefromCode($scheme_code)
    {
        $params['env']              = 'default';
        $params['select_data']      = "Scheme_Name";
        $params['table_name']       = 'mf_aggregated_search_data';
        $params['where']            = TRUE;
        $params['where_data']       =array('mf_aggregated_search_data.Scheme_Code' => $scheme_code);

        $params['return_array']     = True;
        //$params['print_query_exit'] = TRUE;
        $data                = $this->mf->get_table_data_with_type($params);
        return($data[0]['Scheme_Name']);
    }

    public function getBasketNamefromCode($scheme_code)
    {
        $params['env']              = 'default';
        $params['select_data']      = "basket_name";
        $params['table_name']       = 'mf_basket_master';
        $params['where']            = TRUE;
        $params['where_data']       =array('basket_id' => $scheme_code);

        $params['return_array']     = True;
        //$params['print_query_exit'] = TRUE;
        $data                = $this->mf->get_table_data_with_type($params);
        return($data[0]['basket_name']);
    }

    public function verifySchemeCode($unique_no)
    {   $params = [];
        $params['env']              = 'default';
        $params['select_data']      = "Scheme_Code";
        $params['table_name']       = 'mf_aggregated_search_data';
        $params['where']            = TRUE;
        $params['where_data']       =array('Unique_No' => $unique_no);

        $params['return_array']     = True;
        $params['print_query_exit'] = TRUE;
        $data                = $this->mf->get_table_data_with_type($params);
        return($data[0]['Scheme_Code']);
    }

  

    public function getFolioOrderDetails($folio_number,$scheme_code,$client_id = '')
    {
        if(empty($client_id)){
             $client_id = $this->session->client_id;

        }
        $params = [];
        $params['env']              = 'default';
        $params['select_data']      = "*";
        $params['table_name']       = 'mf_client_order';
        $params['where']            = TRUE;
        $params['where_data']       =array('scheme_code' => $scheme_code, 'client_id' => $client_id, 'folio_number !=' => '');
        $params['return_array']     = True;
        $params['limit_data']     = 1;
        $params['limit_start']     = 0;
        $params['order_by']         = "date_created";
       //$params['print_query_exit'] = TRUE;
        $data_mfd               = $this->sn->get_table_data_with_type($params);
        if(!empty($data_mfd )){
           return($data_mfd[0]);

        }

        $params = [];
        $params['env']              = 'default';
        $params['select_data']      = "*";
        $params['table_name']       = 'mf_client_order_mfi';
        $params['where']            = TRUE;
        $params['where_data']       =array('scheme_code' => $scheme_code, 'client_id' => $client_id, 'folio_number !=' => ''  );
        $params['return_array']     = True;
        $params['limit_data']     = 1;
        $params['limit_start']     = 0;
        $params['order_by']         = "date_created";
        //$params['print_query_exit'] = TRUE;
        $data_mfi               = $this->sn->get_table_data_with_type($params);
        if(!empty($data_mfi )){
           return($data_mfi[0]);

          }

        return false;



   }

   /*
    Created By: Dharmesh
    Purpose: Stop and Redeem Smart SIP
    Created Date: 26-04-2019
    modified by: Mangesh
    modified Date: 25-09-2019
  */

   public function redeemAndStopSM() 
   { 
     $log_file_path = getcwd().'/application/logs/redeemAndStopSM_request_'. date('Y-m-d') .'.txt';
     // @file_put_contents($log_file_path, PHP_EOL ."[". date('Y-m-d H:i:s') ."]\t: posted data=". print_r($_POST, true) . PHP_EOL, FILE_APPEND);
     if (isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
     {
        $post = $this->input->post();
      
        $client_id = $this->session->userdata('client_id');
        if(empty($client_id))
          $client_id = $post['client_id'];
          $order_typeSM = $post['order_type'];
        $type = $post['type']; // 1=redeem, 2=stop

        $params = [];
        $params['env']              = 'db';
        $params['select_data']      = "*";
        $params['table_name']       = 'mf_master_smart_sip';
        $params['where']            = TRUE;
        $params['where_data']       =array('id' => $post['masterid'], 'client_id' => $client_id);
        $params['return_array']     = TRUE;
       //$params['print_query_exit'] = TRUE;
        $masterSmArr                = $this->sn->get_table_data_with_type($params);
        $params_up                   = [];
        $params_up['env']            = 'db';                   
        $params_up['table_name']     = 'mf_master_smart_sip';
        $params_up['update_data']    = array('stop_redeem_request' => $type);
        $params_up['where']          = TRUE;
        $params_up['where_data']     = array('client_id' => $client_id, 'id' => $post['masterid']);
        // $update_rejections = $this->mf->update_table_data_with_type($update_params);
        $stopMsg = 'stop request';
        if($type == 1){
            $stopMsg = 'redeem request';
        }

        $stopRequestLog = array(
            'client_id' => $client_id,
            'order_id' => $post['masterid'],
            'order_type' => $masterSmArr[0]['type'],
            'cancel_status' => 0,
            'cancel_response' => json_encode(array('res'=>$masterSmArr[0]['type'].' '.$stopMsg,'res_table'=>$masterSmArr)),
            'date_created' => date("Y-m-d H:i:s")
        );
        // $params_up['print_query_exit']     =  TRUE;
        // x($masterSmArr[0]['broker_id']);
        // x($this->session->userdata());
        if(!empty($this->session->broker_id)){
            $broker_id = $this->session->broker_id;
        }else{
            $broker_id ='';
        }

        if($this->session->crms_user_name == 'partner'){
           $created_by = $this->session->broker_id;
           $source = $this->session->crms_user_name;
        }
        else
        {
           $created_by = $this->session->client_id;//$masterSmArr[0]['created_by'];
           $source = $this->session->client_id;//$masterSmArr[0]['source'];
        }
        // if($this->session->broker_id)     
        if($type == 1)
        {
            if($masterSmArr[0]['order_status'] == 5)
            { 
               if($this->session->userdata('transaction_mode')=='D')
                {
                    $urlPoaStatus = SAMCO_API_URL.'getPoaStatus';
                    $resultPoaStatus    = $this->get_content_by_curl($urlPoaStatus, array('clientID'=>$client_id));
                    $resultPoaStatusArr = json_decode($resultPoaStatus);
                    if($resultPoaStatusArr->status == "Success" && !empty($resultPoaStatusArr->data->row) && $resultPoaStatusArr->data->row->status == "POA_received")
                    {
                        $update_status = $this->mf->update_table_data_with_type($params_up);
                    }
                    else{
                        $response['status'] = "error";
                        $response['message'] = "Your POA and NACH is not submitted to SAMCO";
                        if($post['api'] == '1.2')
                            return $response;
                          else
                        echo json_encode($response); exit();
                    }
                }else{
                    $update_status = $this->mf->update_table_data_with_type($params_up);
                }
                
                // $insert_params              = [];
                // $insert_params['env']       = 'db';
                // $insert_params['table_name']= 'mf_order_cancel_request';
                // $insert_params['data']      = array(

                //     'client_id'=>$client_id,
                //     'master_order_id'=>$post['masterid'],
                //     'start_date'=>$converted_image_name,
                //     'created_date' => date('Y-m-d H:i:s'),
                //     'order_type'    => date('Y-m-d H:i:s'),
                //     'created_by'=> json_encode($upload_history));

                // $insert_id = $this->mf->insert_table_data($insert_params);
                // x('here');
                if(!empty($update_status))
                {
                    $response['status'] = "success";
                    $response['message'] = "Reedem request is pending";
                    $this->saveCancelLog($stopRequestLog);
                }
                else{
                  $response['status'] = "fail";
                  $response['message'] = "Something went wrong..!";
                }
            }
            else
            {
                $response['status'] = "error";
                $response['message'] = "You need to Stop your SmartSIP before redemption";
            }
            
        }
        elseif($type == 2)
        {  
               
        
            $update_status = $this->mf->update_table_data_with_type($params_up);
            // @file_put_contents($log_file_path, PHP_EOL ."[". date('Y-m-d H:i:s') ."]\t: params_up=". print_r($params_up, true) . PHP_EOL, FILE_APPEND);
            // @file_put_contents($log_file_path, PHP_EOL ."[". date('Y-m-d H:i:s') ."]\t: update_status=". $update_status . PHP_EOL, FILE_APPEND);
            // stop and redeem request need to add in mf_cancel_order_table
            if(!empty($update_status))
            {
              

            
          
              if ($order_typeSM=='smartsip') {
                // Delete documents from the 'client_portfolio_summary' collection
                //$this->mongo_db->where('client_id', $client_id)->delete_all('client_portfolio_summary');
                $this->mongo_db->where('client_id', $client_id)->delete('client_portfolio_summary');
                }

       
                if($masterSmArr[0]['type'] == 'smartsip'){
                    if($masterSmArr[0]['additional_investment'] == 1){
                        $type = SMART_SIP_TOPUP;
                    }   
                    else{
                        $type = SMART_SIP_NO_TOPUP;
                    }
                }
                else{
                    $type = SUPERSIP;
                }

                // JIRA ID: RAN-2676. STARTS
                // Below codes added because, if STOP request coming from an mobile application then "Created BY" & "Source" field values are coming blank, which was causing an error as "Column 'created_by' cannot be null".
                // To sort it out, adding a condition if "$created_by" OR "$source" is BLANK then assigning it as "$client_id"
                if(empty($created_by)){
                    $created_by = $client_id;
                }

                if(empty($source)){
                    $source = $client_id;
                }
                // JIRA ID: RAN-2676. ENDS

                $insert_params              = [];
                $insert_params['env']       = 'db';
                $insert_params['table_name']= 'mf_order_cancel_request';
                $insert_params['data']      = array(
                    // 'id'=>$post['masterid'],
                    'client_id'=>$client_id,
                    'cancel_order_status'=>'0',
                    'master_order_id'=>$post['masterid'],
                    'start_date'=>$masterSmArr[0]['start_date'],
                    'created_date' => date('Y-m-d H:i:s'),
                    'order_type'    => $type,
                    'created_by'=> $created_by,
                    'source'=> $source,
                    'broker_id' => $broker_id
                    );
                $insert_id = $this->mf->insert_table_data($insert_params);
                $this->saveCancelLog($stopRequestLog);
                $response['status'] = "success";
                $response['message'] = "Stop request is pending";
                // @file_put_contents($log_file_path, PHP_EOL ."[". date('Y-m-d H:i:s') ."]\t: insert_params=". print_r($insert_params, true) . PHP_EOL, FILE_APPEND);
                // @file_put_contents($log_file_path, PHP_EOL ."[". date('Y-m-d H:i:s') ."]\t: insert_id=". $insert_id . PHP_EOL, FILE_APPEND);
                // @file_put_contents($log_file_path, PHP_EOL ."[". date('Y-m-d H:i:s') ."]\t: stopRequestLog=". print_r($stopRequestLog, true) . PHP_EOL, FILE_APPEND);


            }
            else{
              $response['status'] = "fail";
              $response['message'] = "Something went wrong..!";
            }
        }
        
        if($post['api'] == '1.2')
            return $response;
          else
        echo json_encode($response);
     }
   }

   function confirmAndSendOtp(){
        if(stripos($_SERVER['REQUEST_METHOD'],'post') !== FALSE){
          $flag = $_POST['flag'];
          $mandate_flag = $_POST['mandate_flag'];
          $folio_no    = $_POST['folio_no'];
          $scheme_code = $_POST['scheme_code'];
          $trans_mode  = $_POST['trans_mode'];
          $check_folio_rta  = $_POST['check_folio_rta'];
          $app_version  = $_POST['app_version'];

          $current_app_version = getSettingsTableValue('CURRENT_APP_VERSION');

          if(!empty($app_version) && $trans_mode == 'mob'){
            $app_version = str_replace('.', '', $app_version);
            $app_version = (int) $app_version;
            $current_app_version = (int) $current_app_version;
            
            if(!empty($app_version) && !empty($current_app_version) && $app_version >= $current_app_version){
                $check_folio_rta = 'true';
            }
            else{
                $check_folio_rta = 'false';
            }

          } 

          if(empty($check_folio_rta)){
            $check_folio_rta = 'false';
          }

          if(empty($trans_mode)){
            $trans_mode = 'web';
          }

          $check_folio_flag_arr = [0, 2, 3, 4, 5, 6, 7];

          if($flag == 0){
            $flagtype = 'SmartSIP Order';
          }
          elseif($flag == 2){
            $flagtype = 'Lumpsum Order';
          }
          else if($flag == 3){
            $flagtype = 'SIP Order';
          }
          else if($flag == 4){
            $flagtype = 'STP Order';
          }
          else if($flag == 5){
            $flagtype = 'SWITCH Order';
          }
          else if($flag == 6){
            $flagtype = 'SWP Order';
          }
          else if($flag == 7){
            $flagtype = 'Redemption Order';
          }
		  else if($flag == 8){
			$flagtype = 'Nominee Add or Update';
		  }
          else {
            $flagtype = 'SIP+ Order';
          }
          $client_id   = $this->session->userdata('client_id');
            if(empty($client_id))
              $client_id = $_POST['client_id'];


            if($mandate_flag == 1){
                $client_id = $_POST['client_id'];
            }
            if($mandate_flag == 2){
                $client_id = $_POST['client_id'];
            }
            if(!empty($client_id)){
                $params                 = [];
                $params['env']          = 'db';
                $params['select_data']  = 'client_id,client_name,client_email,client_mobile,client_status,client_status_mfi,transaction_mode,client_pan';
                $params['table_name']   = 'mf_client_master';
                $params['where']        = TRUE;
                $params['where_data']   = array('client_id' => $client_id);

                $client_data = $this->mf->get_table_data_with_type($params);
                if(!empty($client_data)){
                    if(empty($client_data[0]->transaction_mode) && empty($mandate_flag))
                    {
                        $otp_response =  array('status'=>'error','msg' => 'Transaction mode is not yet selected, this can only be done from RankMF website: Login with your registered account on www.rankmf.com and select your transaction mode.');
                        echo json_encode (array('response_otp'=>$otp_response));die;
                    }

                    //Check RTA folio number client details
                    $folioClientDetail = [];
                    if(!empty($check_folio_flag_arr) && in_array($flag , $check_folio_flag_arr) && $check_folio_rta == 'true'){
                        $params = [];
                        $params['folio_no']    = $folio_no;
                        $params['scheme_code'] = $scheme_code;
                        $params['trans_mode']  = $trans_mode;
                        $params['is_cron']     = (strtolower($trans_mode) == 'web' ? '0' : '1');
                        $folioClientDetail = checkFolioClientDetail($params, $client_data);

                        if(empty($folio_no)){
                            $folio_key = 'FRESH#####'.$scheme_code;
                        }
                        else{
                            $folio_key = $folio_no.'#####'.$scheme_code;
                        }

                        $folio_key_encrypt = $this->mf->encrypt_decrypt('encrypt', $folio_key);

                        if(!empty($folioClientDetail) && !empty($folioClientDetail[$folio_key_encrypt]) && count($folioClientDetail) > 0 && count($folioClientDetail[$folio_key_encrypt])){
                            $folioClientDetail = $folioClientDetail[$folio_key_encrypt];
                            $received_folio_data = $send_folio_data = [];

                            $folio_detail_log = [
                                'client_id'           => $folioClientDetail['client_id'],
                                'folio_no'            => $folioClientDetail['folio_no'],
                                'scheme_code'         => $folioClientDetail['scheme_code'],
                                'client_data' => [
                                    'client_email'  => $folioClientDetail['client_email'], 
                                    'client_mobile' => $folioClientDetail['client_mobile']
                                ],
                                'received_folio_data' => [
                                    'folio_client_email'  => $folioClientDetail['folio_client_email'], 
                                    'folio_client_mobile' => $folioClientDetail['folio_client_mobile']
                                ],
                                'send_folio_data'     => [],
                                'mismatch_flag'       => $folioClientDetail['mismatch_flag'], 
                                'otp'                 => '',   
                                'message'             => $folioClientDetail['message'],
                                'fetch_from'          => $folioClientDetail['fetch_from'],
                                'trans_mode'          => $folioClientDetail['trans_mode']
                            ];

                            if(empty($folioClientDetail['folio_client_mobile']) && !empty($folioClientDetail['folio_client_email'])){
                                $send_folio_data['folio_client_mobile'] = $folioClientDetail['client_mobile'];
                                if(!empty($folioClientDetail['folio_client_email'])){
                                    $send_folio_data['folio_client_email'] = $folioClientDetail['folio_client_email'];
                                }
                            }
                            else if(!empty($folioClientDetail['folio_client_mobile']) && empty($folioClientDetail['folio_client_email'])){
                                if(!empty($folioClientDetail['folio_client_mobile'])){
                                    $send_folio_data['folio_client_mobile'] = $folioClientDetail['folio_client_mobile'];
                                }
                                $send_folio_data['folio_client_email']  = $folioClientDetail['client_email'];
                            }
                            else if(empty($folioClientDetail['folio_client_mobile']) && empty($folioClientDetail['folio_client_email'])){
                                $send_folio_data['folio_client_mobile']  = $folioClientDetail['client_mobile'];
                                $send_folio_data['folio_client_email']  = $folioClientDetail['client_email'];
                            }
                            else if(!empty($folioClientDetail['folio_client_mobile']) && !empty($folioClientDetail['folio_client_email'])){
                                $send_folio_data['folio_client_mobile'] = $folioClientDetail['folio_client_mobile'];
                                $send_folio_data['folio_client_email'] = $folioClientDetail['folio_client_email'];
                            }

                            if(!empty($send_folio_data)){
                                $folio_detail_log['send_folio_data'] = $send_folio_data;
                                $folio_detail_log['created_at']      = $this->getMongoDate(date('Y-m-d H:i:s'));
                            }

                            if(strtolower($trans_mode) == 'web'){
                                $folio_data = $this->session->userdata($folio_key_encrypt);
                                if(!empty($folio_data) && count($folio_data) > 0){
                                    if($folio_data['mismatch_flag'] == true){
                                        //$client_data[0]->client_mobile = $folio_data['folio_client_mobile'];
                                        //$client_data[0]->client_email = $folio_data['folio_client_email'];

                                        if(!empty($folio_data['folio_client_mobile'])){
                                            $client_data[0]->client_mobile = $folio_data['folio_client_mobile'];
                                        }
                                        
                                        if(!empty($folio_data['folio_client_email'])){
                                            $client_data[0]->client_email = $folio_data['folio_client_email'];
                                        }
                                    }
                                }
                            }
                            else{
                                $folio_data = $folioClientDetail;
                                if(!empty($folio_data)){
                                    if(!empty($folio_data['folio_client_mobile'])){
                                        $client_data[0]->client_mobile = $folio_data['folio_client_mobile'];
                                    }
                                    
                                    if(!empty($folio_data['folio_client_email'])){
                                        $client_data[0]->client_email = $folio_data['folio_client_email'];
                                    }
                                }
                            }
                        }
                    }

                        if(!empty($client_data[0]->client_mobile)){
                            $otp = $this->generate_otp(4);

                            if($check_folio_rta == 'true'){
                                //$folio_data['otp']        = $otp;
                                $folio_detail_log['otp']  = $otp;
                                $folio_detail_log['created_at']  = $this->getMongoDate(date('Y-m-d H:i:s'));

                                // $this->mongo_db->insert('mf_client_folio_detail', $folio_data);

                                if(!empty($send_folio_data)){
                                    $this->mongo_db->insert('mf_folio_detail_request_log', $folio_detail_log);
                                    
                                }
                            }
                            
                            // $this->session->set_userdata(array('otp_for_sign_verification' => $otp,'otp_start_time' => time()));
                            //to store otp in db added by pooja
                            $data_in['client_id']         = $client_data[0]->client_id;
                            $data_in['otp']     = $otp;
                            if($flag == 4){
                                $data_in['source']     = 'STP';
                            }
                            if($flag == 5){
                                $data_in['source']     = 'SWITCH';
                            }
                            if($flag == 6){
                                $data_in['source']     = 'SWP';
                            }
                            if($flag == 7){
                                $data_in['source']     = 'Redemption';
                            }
                            if($flag == 8){
                                $data_in['source']     = 'NOMINEE';
                            }

                            if($folio_data['mismatch_flag'] == true){
                                $data_in['source']     = 'mis-match-folio-'.strtolower($folio_data['trans_mode']);
                            }
                            // x($data_in);
                            $insparam               = [];
                            $insparam['env']        = 'db';
                            $insparam['table_name'] = 'mf_otp';
                            $insparam['data']       = $data_in;
                            $insert_id              = $this->mf->insert_table_data($insparam);
                            //insert detiails
                            // send sms
                            // $msgtxt = 'Dear Customer, your OTP for Mandate Signature Confirmation is '.$otp;
                            // $msgtxt = 'Dear Customer, Your OTP is '.$otp.' for '.$flagtype.'  Confirmation.Thanks Team RankMF';
                            //$msgtxt = $otp.' is your one time password for '.$flagtype.'.';
                            $msgtxt=   $otp.' is your one time password for '.$flagtype.'. Do not share this OTP to anyone for security reasons. - RankMF';
                            $subject = 'your '.$flagtype;
                            $content = 'Your OTP is '.$otp.' for '.$flagtype;
                            if($mandate_flag == 1){
                                $subject = 'your Mandate';
                                // $msgtxt = 'Dear Customer, Your OTP is '.$otp.' for Mandate Creation.Thanks Team RankMF';
                                $msgtxt = $otp.' is your one time password for Mandate Creation.';
                                $content = 'Your OTP is '.$otp.' for Mandate Creation.';
                            }

                            if($mandate_flag == 2){
                                $subject = 'your Mandate';
                                // $msgtxt = 'Dear Customer, Your OTP is '.$otp.' for Mandate Upload.Thanks Team RankMF';
                                $msgtxt = $otp.' is your one time password for Mandate Upload.';
                                $content = 'Your OTP is '.$otp.' for Mandate Upload.';
                            }
                            
                            //echo $mobileno.'->'.$msg;

                           $sms_data           = [];

                            $sms_data['message']=$msgtxt ;
                            $sms_data['numbers']=array($client_data[0]->client_mobile);
                            $sms_data['newOtpKey'] = "SMSOTP";
                            $buffer= sendSms($sms_data);//helper function
                            // $bulk_data=array();
                            // $bulk_sms_data[] = array('number' =>$client_data[0]->client_mobile);
                            // $bulk_data['message']=$msgtxt;
                            // $bulk_data['newOtpKey'] = "SMSOTP";

                            // $bulk_data['bulk_sms']=1;
                            // $bulk_data['merg_var']=$bulk_sms_data;
                            // $buffer= sendSms($bulk_data);
                            /*$bulk_data=array();
                            $bulk_sms_data[] = array('number' =>$client_data[0]->client_mobile);
                            $bulk_data['message']=$msgtxt;

                            $bulk_data['bulk_sms']=1;
                            $bulk_data['merg_var']=$bulk_sms_data;
                            $buffer= sendSms($bulk_data)*/;

                            $params['client_email'] = $client_data[0]->client_email;
                            $params['parameters']['ToMail'] = $client_data[0]->client_email;
                            $params['parameters']['otp_subject'] = $subject;
                            $params['parameters']['otp_content'] = $content;
                            $params['parameters']['Name'] =$client_data[0]->client_name;
                            $mailsent = $this->SendOTPMail($params);


                            $mobile = 'XXXXXXX'.substr($client_data[0]->client_mobile,strlen($client_data[0]->client_mobile)-3, 3);
                            $explode = explode('@', $client_data[0]->client_email);

                            $email1 = substr($explode[0],0,2).'XXXXXXX';

                            $email = $email1.'@'.$explode[1];

                           
                            $otp_response = [];
                            //$otp_response = array('status' => 'success','msg' => 'OTP has been sent to your registered mobile number and email ID!','Mobile_no'=>$mobile,'Email_id'=>$email);
                            $otp_response = array('status' => 'success','msg' => 'We have sent an OTP on '.$mobile.' and '.$email, 'Mobile_no'=>$mobile, 'Email_id'=>$email);
                        }else{
                            $otp_response =  array('status'=>'error','msg' => 'Incorrect Mobile number !');
                        }
                   
                }else{
                    $otp_response =  array('status'=>'error','msg' => 'You are not a registered Mutual fund client!');
                }
            }else{
                $otp_response = array('status' => 'error','msg' => 'Please try again!');
            }
            if($this->input->post('return') == 1)
              return $otp_response;
            else
              echo json_encode ($otp_response);
        }
    }


    

    public function verifyOTPforSign(){
        if(stripos($_SERVER['REQUEST_METHOD'],'post') !== FALSE){
            $client_id   = $this->session->userdata('client_id');

            if(empty($client_id))
                $client_id = $this->input->post('client_id');

            $otp  = $this->input->post('otp');
            if(!empty($client_id && $otp)){
                  // $otp_for_sign_verification = $this->session->userdata('otp_for_sign_verification');
                  // $otp_start_time       = $this->session->userdata('otp_start_time');
                  // $otp              = $this->input->post('otp'); // need to remove
                  // x($otp);
                $params = [];
                $params['env']              = 'default';
                $params['select_data']      = "client_id,otp,source,ABS(TIMESTAMPDIFF(Minute,date_created,now())) as diff";
                $params['table_name']       = 'mf_otp';
                $params['where']            = TRUE;
                $params['where_data']       = ['client_id' => $client_id,'is_verified'=>0];
                $params['order_by']         = 'id desc';
                $params['limit_data']       = 1;
                $params['limit_start']      = 0;

                $params['return_array']     = True;
                // $params['print_query_exit'] = TRUE;
                $master_orders                = $this->mf->get_table_data_with_type($params);
                $otp_response = [];

                if((string)$master_orders[0]['otp'] === (string)$otp){
                    if($master_orders[0]['diff'] <= 60){
                        $update_params                  = [];
                        $update_params['env']           = 'db';
                        $update_params['table_name']    = 'mf_otp';
                        $update_params['update_data']   = array('is_verified'=>1,'verified_through' => 1);//otp
                        $update_params['where']         = TRUE;
                        $update_params['where_data']    =  array('client_id' => $client_id,'otp' => $otp);
                        $update_rejections = $this->mf->update_table_data_with_type($update_params);

                        $otp_response = array('status' => 'success','msg' => 'OTP verified!');
                    }else{
                        $otp_response = array('status' => 'error','msg' => 'OTP expired!');
                    }
                    
                } else {//if data found with time less than 60 min
                    $crms_user_name = $this->session->userdata('crms_user_name');
                    $crms_source    = $this->session->userdata('source');
                    //added by tanvi for mpin verification for crms user
                    if(!empty($master_orders[0]['otp']) && !empty($crms_user_name) && (strtolower($crms_source) == 'mpin' || stripos($crms_source, 'master') !== FALSE)) { 
                        $post_arr = [];
                        $post_arr['client_id'] = $client_id;
                        $post_arr['mpin']      = $otp;
                        $post_arr['source']    = MPIN_SRC;
                        $mpin_response = $this->get_content_by_curl(MPIN_URL.'mpin/verifympinRankMF',$post_arr);
                        $mpin_response_decoded = json_decode($mpin_response,true);
                        if($mpin_response_decoded['status'] == 'success'){
                            $update_params                  = [];
                            $update_params['env']           = 'db';
                            $update_params['table_name']    = 'mf_otp';
                            $update_params['update_data']   = array('is_verified'=>1,'verified_through' => 2);//mpin
                            $update_params['where']         = TRUE;
                            $update_params['where_data']    =  array('client_id' => $client_id,'otp' => $master_orders[0]['otp']);
                            $update_rejections = $this->mf->update_table_data_with_type($update_params);

                            $otp_response = array('status' => 'success','msg' => 'OTP verified!');
                        }else{
                            $otp_response = array('status' => 'error','msg' => 'Please Enter Correct OTP/MPIN!');
                        }
                    }else
                        $otp_response = array('status' => 'error','msg' => 'Please Enter Correct OTP!');
                }// if data not found
                
            } else{
                $otp_response = array('status' => 'error','msg' => 'Please Enter OTP!');
            }// if otp not found

            if($this->input->post('return') == 1)
                return $otp_response;
            else
                echo json_encode ($otp_response);
        }
    }

    function generate_otp($digits) {
        $x = $digits - 1;
        $min = pow(10, $x);
        $max = pow(10, $x + 1) - 1;
        return rand($min, $max);
    }


     public function filterHolidays($date,$monthSkip = 1){
        $return_arr = [];
        if(!empty($date)){
            $date_plus = date('Y-m-d',strtotime($date." -1 day"));
            $date_minus = date('Y-m-d',strtotime($date." +1 day"));
            $params = [];
            $params['env'] = 'db';
            $params['select_data'] = 'date';
            $params['table_name']  = 'mf_holidays';
            $params['where']       = TRUE;
            $params['where_data']  = array('is_holiday'=> 1);
            $params['order_by']    = 'date';
            $holiday_data = $this->sn->get_table_data_with_type($params);
            
            $holiday_list = array_column($holiday_data, 'date');
            // x($holiday_list);
            
            if(!empty($holiday_list)){
                for ($i=0; $i <= 7; $i++) { 
                    $return_arr['tplus'.$i] = $this->getTplusWorkingDays($holiday_list,$date_plus,1,'plus');
                    $date_plus = $return_arr['tplus'.$i];
                    $return_arr['tminus'.$i] = $this->getTplusWorkingDays($holiday_list,$date_minus,1,'minus');
                    $date_minus = $return_arr['tminus'.$i];
                }
                if($monthSkip){
                    $month_date = date('Y-m-d',strtotime($date." +1 month"));
                    $return_arr['next_month_date'] = $this->getTplusWorkingDays($holiday_list,$month_date,0,'month'); 
                }
                if($return_arr['tplus0'] != $return_arr['tminus0']){
                    $new_date   = date('Y-m-d',strtotime($date." +1 day"));
                    $return_arr = $this->filterHolidays($new_date,0);
                    return $return_arr;
                }else
                    return $return_arr;
            }
        }
    }

    public function updatesmartSip(){
      
            $params = [];
            $params['env'] = 'db';
            $params['select_data'] = '*';
            $params['table_name']  = 'mf_master_smart_sip';
            $params['return_array'] = true;
            $orders = $this->sn->get_table_data_with_type($params);

            foreach($orders as $order){
             $next_sip_t_day = $this->filterHolidays($order['start_date']);

              $update_master_data = [
                                          'next_t_day' => $next_sip_t_day['tplus0'],
                                          't_1day'     => $next_sip_t_day['tminus1'],
                                          't_3day'     => $next_sip_t_day['tminus3'],
                                          //'order_status' => 2
                                          ];

                    $params_up                  =  [];
                    $params_up['env']           =  'db';                   
                    $params_up['table_name']    =  'mf_master_smart_sip';
                    $params_up['update_data']   =  $update_master_data;           
                    $params_up['where']         =  TRUE;
                    $params_up['where_data']    =  ['id' => $order['id'] ]; 
                    //$params_up['print_query']     =  TRUE;
                    $update_status = $this->mf->update_table_data_with_type($params_up);
            }
    }

     function SendOTPMail($params){
        // x($params);
      $this->load->library('Php_mailer');
      $mail_data = array();
      $mail_data['from']      = array('support@samco.in', 'Samco Support');

      $params['templateName'] = 'otp_validation';
      $params['channel']      = 'otp_validation';
      $params['to']           = array(array($params['client_email']));
      $params['merge_vars']   = $params['parameters'];
      $params['merge_vars']['link'] = $autologin_url;
            // print_r($params);
      $this->php_mailer->mandrill_v2($params);
 // save email logs
      $insert_arr = [];
      $insert_arr[]=array(
        'email'=>$params['client_email'],
        'data'=> json_encode($params),
        'for' => 'OTP email',
    );
      insertEmailLogs($insert_arr);
  }

  function calculateNextInsta($order_date,$start_date){
    $morder['next_t_day'] = $order_date;
    $morder['start_date'] = $start_date;

    $next_date_tb   = date('Y-m', strtotime($morder['next_t_day']) ).'-'.date('d', strtotime($morder['start_date']) );
    
    y($next_date_tb,'Next by start Date');

    $next_month_day = date('Y-m-d',strtotime($next_date_tb."+1 month") );
    y($next_month_day,'Next Installment by start Date');

    $install_diff   = date_diff(date_create($next_month_day),date_create($morder['next_t_day']))->days;
    y($install_diff,'difference bet last installment and next installment');

    if($install_diff > 31)
        $next_month_day = $next_date_tb;
    
    y($next_month_day,'next month installment');

    $next_sip_t_day = $this->filterHolidays($next_month_day);

    $update_master_data = [
                            'next_t_day' => $next_sip_t_day['tplus0'],
                            't_1day'     => $next_sip_t_day['tminus1'],
                            't_3day'     => $next_sip_t_day['tminus3']
                            ];
                        
    x($update_master_data,'Valid Dates smart SIP');
  }
function campaign_trigger_event($data,$type='')
{
      if(!empty($this->session->userdata('cmapid')))
        {
            //$data['scheme_code']="KO485-GR";
            //x($data1['scheme_code']);
            $params                 = [];
            $params['env']          = 'db';
            $params['select_data']  = 'Scheme_Name';
            $params['table_name']   = 'mf_aggregated_search_data';
            $params['where']        = TRUE;
            $params['where_data']   = array('Scheme_Code' => $data['scheme_code']);
            $params['return_array'] = TRUE;
            $schemeTypeData = $this->mf->get_table_data_with_type($params);
            //x($schemeTypeData[0]['Scheme_Name']);
            $this->compain_trigger_action(array('product'=>$type.'_'.$schemeTypeData[0]['Scheme_Name'].'|'.$data['amount'],'remark'=>$data['scheme_code']));
        }
    //end of emandate_complete_link_send
}
public function insert_log($req,$res,$client_id){
        $insert_arr = array(
                        'request'         =>$req,
                        'response'        =>$res,
                        'client_code'=>$client_id,
                        'uploaded_on'   => $this->getMongoDate(date('Y-m-d H:i:s')),
                        'current'       => 1);
            $this->mongo_db->insert('mf_alltment_units_done_log',$insert_arr);
    }
function trmipschme($value)
    {
        if(strlen($value)>=30)
        {
                    $value=substr($value,0,25);
                    $value=$value.'...';
        }
        return $value;
    }


    function get_lower_nearest_value($input_number, $input_multiplier = 0, $decimal_precision = 3){
        $original_input_number = $input_number;
        if(!isset($input_multiplier) || (isset($input_multiplier) && empty($input_multiplier)) || (isset($input_multiplier) && !is_numeric($input_multiplier))){
            $input_multiplier = 1;
        }

        if(empty($input_number) || !is_numeric($input_number)){
            $input_number = 0;
        }

        if(intval($input_multiplier) > 0 && ($input_number % $input_multiplier) !== 0){
            $input_number = intval($input_number / $input_multiplier) * $input_multiplier;
        }

        $decimal_value = bcsub($input_number, intval($input_number), $decimal_precision);
        $input_number = intval($input_number) + $decimal_value;

        // in case if lower nearest multiplier value goes above the input number value then re-setting it to the original value which earlier stored separately just for reference
        if($input_number > $original_input_number){
            $input_number = $original_input_number;
        }
        return $input_number;
    }


    function getMandateId($client_id='',$amount=''){
           
          $query = "select t.* from 
              (SELECT client_id,mandate_id,mandate_status,mandate_amt, 'samco' as mandate_type, type as mandatetype 
              FROM `mf_samco_mandate_regitrations` 
              WHERE `client_id` = '$client_id' AND `mandate_status` = 'active' AND `mandate_amt` >= '$amount' 
              ORDER BY `mandate_amt` DESC LIMIT 1) as t 
              union all 
              select t1.* from 
              (SELECT client_id,mandate_id,mandate_status,mandate_amt, 'OTM' as mandate_type, type as mandatetype 
              FROM `mf_mandate_regitrations_mfd` 
              WHERE `client_id` = '$client_id' AND `mandate_status` = 'APPROVED' AND `mandate_amt` >= '$amount' 
              ORDER BY `mandate_amt` DESC LIMIT 1) as t1 
              ORDER BY mandate_amt DESC LIMIT 1 ";

          $params['env']                = 'default';
          $params['query']              = $query;
            
          $params['return_array'] = true;
          //$params['print_query_exit'] = TRUE;
          $mdata   = $this->mf->get_result_execute_query($params);
          return $mdata[0];
    }


    public function getBankInfo($client_id='')
    {
        $params                     = [];
        $params['env']              = 'db';
        $params['select_data']      = "client_acc_no, client_ifsc, client_id, client_name, client_mobile, client_email";
        $params['table_name']       = 'mf_client_master';
        $params['where']            = TRUE;
        $params['where_data']       = array('client_id'=>$client_id);

        $params['return_array']     = TRUE;
        //$params['print_query_exit']   = TRUE;        
        $clientdata                     = $this->mf->get_table_data_with_type($params);
        //x($clientdata);

        $ifsc = substr($clientdata[0]['client_ifsc'], 0, 4);
        $params2                     = [];
        $params2['env']              = 'db';
        $params2['select_data']      = "bank_name";
        $params2['table_name']       = 'mf_bank_code';
        $params2['where']            = TRUE;
        $params2['where_data']       = array('ifsc'=>$ifsc);
        $params2['return_array']     = TRUE;
        //$params2['print_query_exit'] = TRUE;        
        $bankinfo                    = $this->mf->get_table_data_with_type($params2);
        
        $clientinfo = array_merge($clientdata[0],$bankinfo[0]);
        // $params2['print_query_exit']   = TRUE;        
        return $clientinfo;
        
    }
 
    /* author: Rajesh
      purpose: send switch signal via email 
      params : 1 = available by bse ,2 = not available
      created: 05/04/2022*/ 
    function sendSwitchCommunication($signal='double_invest',$client_id='')
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        $params                     = [];
        $params['env']              = 'db';
        $params['select_data']      = "id,client_id, installment_amount, from_scheme_code, to_scheme_code, margin_of_safety, number_of_unit, base_signal, smartsip_order_number";
        $params['table_name']       = 'mf_smart_sip_switch_process_list';
        $params['where']            = TRUE;
        if(!empty($client_id)){
            $params['where_data']   = array('client_id'=>$client_id, 'mail_sent'=>0, 'base_signal'=>$signal);
        }else{
            $params['where_data']   = array('mail_sent'=>0, 'base_signal'=>$signal);
        }
        /**$params['where_in']      = TRUE;
        $params['where_in_field']   = 'base_signal';
        $params['where_in_data']    = ['sell','double_invest'];*/
        
        $params['return_array']     = TRUE;
        //$params['print_query_exit'] = TRUE;        
        $switchdata                 = $this->mf->get_table_data_with_type($params);
        

        if(!empty($switchdata)){
            $this->load->library('Bsestar_mutualfund');   
            $filename = 'SwitchCommunication'.date('Y-m-d').'.csv';   
            $filepath = FCPATH.'assets/elastic_email/'.$filename;  
            $fp = fopen($filepath, 'w');
            
            $merge_vars = array_map('trim', array('ToMail','Name','Scheme-name','MosDex-value','Investment-amount','Bank-name','Equity-Scheme','Bank-account-number','Units','Approve-link','client_id','Liquid-scheme','unit-per'));

            fputcsv($fp,$merge_vars);    
            $sms_array_success = array();  
            $params_up                  =  [];
            $params_up['env']           =  'db';
            $params_up['table_name']    =  'mf_smart_sip_switch_process_list';            
            $params_up['where_key']     =  'id';
            $params_up['batch']     =  TRUE;

            $update_email_flag = [];
            foreach($switchdata as $val){        
                $csv_data = array(); 
                $data = array();
                $unitper = "";
                $scheme_name = $this->getSchemeNamefromCode($val['from_scheme_code']);

                $ord_param = [];
                $ord_param['env']          = 'db';
                $ord_param['select_data']  = 'liquid_scheme_code';
                $ord_param['table_name']   = 'mf_master_smart_sip';
                $ord_param['where']        = TRUE;
                $ord_param['where_data']   = ['id'=> $val['smartsip_order_number']];
                $ord_param['return_array'] = TRUE;
                $clientOrder  = $this->mf->get_table_data_with_type($ord_param);
 

                $client_id = $val['client_id'];
                if(!empty($client_id)){
                    $margin_safety = number_format((float)$val['margin_of_safety'], 2, '.', '');
                    if($val['base_signal']=='double_invest'){ 

                        $template = "rankmf-smartsip-signal-doubleinvest-1648122851"; 
                        if($margin_safety>=105){ $unitper=35; }
                        if($margin_safety>110){ $unitper=100; }
                        $lq_scheme = $val['from_scheme_code'];
            
                    }
                            
                    if($val['base_signal']=='sell'){
                        $template = "rankmf-smartsip-signal-sell-1648122991"; 
                        if($margin_safety< 60){ $unitper=35; }
                        if(($margin_safety>=60) && ($margin_safety<80)){ $unitper=30; }
                        $lq_scheme = $val['to_scheme_code'];
                    }

                    $lqschemecode = trim($lq_scheme, '-L0');
                    
                    $liquid_scheme_name = $this->getSchemeNamefromCode($lqschemecode);
                    
                    
                    $clientinfo = $this->getBankInfo($client_id);                    
                
                    $url = RANK_MF_URL_SHORT;
                    $autologin_url = getUrlShort($url,$client_id,41);
                    $autologin_url = $autologin_url."/".$val['id'];  
                    $bank_accno = 'XXXXXX' . substr($clientinfo['client_acc_no'], strlen($clientinfo['client_acc_no']) - 4, 4); 
                    $csv_data[] = $clientinfo['client_email'];
                    $csv_data[] = ucwords(strtolower($clientinfo['client_name']));
                    $csv_data[] = $scheme_name;
                    $csv_data[] = $margin_safety;
                    $csv_data[] = $val['installment_amount'];
                    $csv_data[] = $clientinfo['bank_name'];
                    $csv_data[] = $scheme_name;
                    $csv_data[] = $bank_accno;
                    $csv_data[] = $unitper; //$val['number_of_unit'];
                    $csv_data[] = $autologin_url; //link
                    $csv_data[] = $client_id;
                    $csv_data[] = $liquid_scheme_name;
                    $csv_data[] = $unitper;
                    fputcsv($fp, $csv_data); 

                    $update_email_flag[] = ['id'=>$val['id'],'mail_sent'=>1];    
                    
                }//x($csv_data);
                //x($update_email_flag);
                //$update_switch_status = ['status' => 1];
    
                //$params_up['where_data']    =  ['id' => $val['id'], 'client_id' => $client_id];
                
            }           
            //x($update_email_flag);
            $postdata['template_name'] = $template;
            $postdata['channel_name']  = $template;

            $params_up['update_data']    = $update_email_flag;
            //$params_up['print_query_exit']     =  TRUE;
            $this->mf->update_table_data_with_type($params_up);
                         
            $this->sendMailToUserCron($filename,$postdata);
            fclose($fp); 

        }

    }

    /**
     * Author : Prasad Wargad
     * Purpose: Function to update next_t_day for those smartsip/supersip which have schemes with flag Purchase_Allowed = N or pause_sip = 1. JIRA ID: RAN-3037
     * Created: 05/04/2022
     * Modified:
     * Modified by:
     */
    public function update_next_tday_for_records_of_smartsip_table($process_date='', $order_type='smartsip', $query_type='purchase_flag_no', $input_client_id=''){
        if(empty($process_date) || strtotime($process_date) === FALSE){
            $process_date = date('Y-m-d');
        }

        if(empty($order_type)){
            $order_type = 'smartsip';
        }

        if($query_type == 'paused_smartsip'){
            // retrieving orders whose pause_sip flag value is 1
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "ss.*";
            $params['table_name']       = 'mf_master_smart_sip AS ss';
            $params['where']            = TRUE;
            $params['where_data']       = ['ss.next_t_day' => $process_date, 'ss.order_status' => 2, 'ss.stop_redeem_request' => 0, 'ss.type' => $order_type, 'ss.pause_sip' => 1];

            // checking input parameter client_id present or not. If present then read only SmartSIP/SuperSIP records for that specific client
            if(isset($input_client_id) && !empty($input_client_id)){
                $params['where_data']['ss.client_id'] = trim(strip_tags($input_client_id));
            }

            $params['return_array']     = True;
            // $params['print_query']   = TRUE;
            $master_orders              = $this->mf->get_table_data_with_type($params);
        }
        else{
            // retrieving orders whose Purchase_Allowed field value is N
            $params = [];
            $params['env']              = 'default';
            $params['select_data']      = "ss.*, scheme_master.Redemption_Qty_Multiplier";
            $params['table_name']       = 'mf_master_smart_sip AS ss';
            $params['join']             = TRUE;
            $params['multiple_joins']   = TRUE;
            $params['join_table']       = array('mf_scheme_master as scheme_master');
            $params['join_on']          = array('ss.scheme_code = scheme_master.Scheme_Code');
            $params['join_type']        = array('INNER');
            $params['where']            = TRUE;
            $params['where_data']       = ['ss.next_t_day' => $process_date, 'ss.order_status' => 2, 'ss.stop_redeem_request' => 0, 'ss.type' => $order_type, 'scheme_master.Purchase_Allowed' => 'N'];

            // checking input parameter client_id present or not. If present then read only SmartSIP/SuperSIP records for that specific client
            if(isset($input_client_id) && !empty($input_client_id)){
                $params['where_data']['ss.client_id'] = trim(strip_tags($input_client_id));
            }

            $params['return_array']     = True;
            // $params['print_query']   = TRUE;
            $master_orders              = $this->mf->get_table_data_with_type($params);
        }

        if(isset($master_orders) && is_array($master_orders) && count($master_orders) > 0){
            foreach($master_orders as $morder){
                // updating next t day for given set of record
                $next_date_tb = date('Y-m', strtotime($morder['next_t_day']) ).'-'.date('d', strtotime($morder['start_date']) );
                $next_month_day = date('Y-m-d',strtotime($next_date_tb."+1 month") );
                $install_diff = date_diff(date_create($next_month_day),date_create($morder['next_t_day']))->days;
                if($install_diff > 31)
                    $next_month_day = $next_date_tb;

                $next_sip_t_day = $this->filterHolidays($next_month_day);
                $update_master_data = [
                                      'next_t_day' => $next_sip_t_day['tplus0'],
                                      't_1day'     => $next_sip_t_day['tminus1'],
                                      't_3day'     => $next_sip_t_day['tminus3']
                                      ];

                $params_up                  =  [];
                $params_up['env']           =  'db';
                $params_up['table_name']    =  'mf_master_smart_sip';
                $params_up['update_data']   =  $update_master_data;
                $params_up['where']         =  TRUE;
                $params_up['where_data']    =  ['id' => $morder['id'], 'client_id' => $morder['client_id'], 'scheme_code' => $morder['scheme_code'], 'type' => $morder['type']];
                //$params_up['print_query']     =  TRUE;
                $update_status = $this->mf->update_table_data_with_type($params_up);
            }
            unset($morder);
        }
        unset($master_orders);
    }

    public function getProceedSwitch($clientid='',$order_no='')
    { 
        $this->load->model('mutual_funds_model', 'mf');   

        $data = array();
        $data['client_id'] = $this->mf->encrypt_decrypt("decrypt",$clientid);
        $data['order_no']  = $this->mf->encrypt_decrypt("decrypt",$order_no); 
        
        $params                     = [];
        $params['env']              = 'db';
        $params['select_data']      = "*";
        $params['table_name']       = 'mf_smart_sip_switch_process_list';
        $params['where']            = TRUE;
        $params['where_data']       = array('id'=>$data['order_no']);
         
        $params['return_array']     = TRUE;
        //$params['print_query_exit'] = TRUE;        
        $switchdata                 = $this->mf->get_table_data_with_type($params);
        $data['switchdata'] = $switchdata[0];
        //x($data['switchdata']);
        if($switchdata[0]['base_signal']=='sell'){
            
            $data['eq_scheme'] = $this->getSchemeNamefromCode($switchdata[0]['from_scheme_code']);
            $data['lq_scheme'] = $this->getSchemeNamefromCode($switchdata[0]['to_scheme_code']);
            $data['content'] = $this->load->view('order/smartsip_partially_sell_investment_alert', $data, true);

        }
        
        if($switchdata[0]['base_signal']=='double_invest'){
            
            $data['lq_scheme'] = $this->getSchemeNamefromCode($switchdata[0]['from_scheme_code']);
            $data['eq_scheme'] = $this->getSchemeNamefromCode($switchdata[0]['to_scheme_code']);
            $data['content'] = $this->load->view('order/smartsip_double_investment_alert', $data, true);
        }           
        //x($data);
        $this->load->view('layout', $data);

    }


    function testsms()
    {
        $word = "SmartSIP";
        $orderno = "10021";
        $new_scheme_name='mannu';
        $sms_amount='20000';
        $data_in['next_t_day']="2022-05-06";
        $sms_deficiency_url='wwww.google.com';


        $detail['sms_text']=" Hi, Your ".$word." order vide ".$orderno." for ".$new_scheme_name." of ".$sms_amount." is successfully placed. Your next SmartSIP date is ".$data_in['next_t_day'].".  - Team RankMF";
        $detail['sms_text']="Hi, Your ".$word." order for ".$new_scheme_name." of ".$sms_amount." is incomplete. Please set up your bank mandate to complete your ".$word." order. ".$sms_deficiency_url." - Team RankMF";

        $msg = $detail['sms_text'];
        echo $msg;
                $sms_data['message']=$detail['sms_text'] ;
                $sms_data['numbers']=array('9766608045');
                $buffer= sendSms($sms_data);
        x($buffer);
    }
 
     /* author: Rajesh
      purpose: Update Child Order      
      created: 05/04/2022*/ 
    function updateChildOrder($client_id='')
    {
        error_reporting(-1);
        ini_set('display_errors', 1);
        $params                 = [];
        $params['env']          = 'db';
        $params['select_data']  = "id,client_id,scheme_code,folio_number,date_created";
        $params['table_name']   = 'mf_order_smart_sip';
        $params['where']        = TRUE;        
        $params['where_data']   = array('buy_sell'=>'P','order_id'=>'');   
        if(!empty($client_id)){
            $params['where_data'] = array('client_id'=>$client_id,'buy_sell'=>'P','order_id'=>'');
        }else{
            $params['where_data'] = array('buy_sell'=>'P','order_id'=>'');
        }
              
        $params['return_array'] = TRUE;
        //$params['print_query_exit']     =  TRUE;
        $redemption_pur_data    = $this->mf->get_table_data_with_type($params);
        
        if(!empty($redemption_pur_data)){
            $orderNumber='';
            foreach($redemption_pur_data as $row){        
                $client_id  = $row['client_id'];     
                $scheme_code= $row['scheme_code'];
                $foliono    = $row['folio_number']; 
                $createddate= date("Y-m-d", strtotime($row['date_created']));  
                

                $params2                 = [];
                $params2['env']          = 'db';
                $params2['select_data']  = "*";
                $params2['table_name']   = 'mf_child_order_details';
                $params2['where']        = TRUE;        
                $params2['where_data']   = array('ClientCode'=>$client_id, 'SchemeCode'=>$scheme_code, 'FolioNo'=>$foliono,'date_created >'=>$createddate,'OrderType'=>'SI');
                $params2['return_array'] = TRUE;
                //$params['print_query_exit']     =  TRUE;
                $redemption_pur_data    = $this->mf->get_table_data_with_type($params2);

                $orderNumber = $redemption_pur_data[0]['OrderNumber'];

                //x($orderNumber);
                if(!empty($orderNumber)){
                    $update_master_data = ['order_id' => $orderNumber];

                    $params_up                  =  [];
                    $params_up['env']           =  'db';
                    $params_up['table_name']    =  'mf_order_smart_sip';
                    $params_up['update_data']   =  $update_master_data;
                    $params_up['where']         =  TRUE;
                    $params_up['where_data']    =  ['id' => $row['id']];
                    //$params_up['print_query']     =  TRUE;
                    $update_status = $this->mf->update_table_data_with_type($params_up);    
                }
                
            }           

        }

    }

	public function getClientMasterProfileDetails($client_id){
        $arr_client_details = array();
        try{
            if(isset($client_id) && !empty($client_id)){
                $params = [];
                $params['env'] = 'db';
                $params['select_data'] = '*, client_acc_no as ClntBankAccNo';
                $params['table_name'] = 'mf_client_master';
                $params['where'] = TRUE;
                $params['where_data'] = ['client_id' => $client_id];
                $params['limit_start'] = 0;
                $params['limit_data'] = 1;
                $params['return_array'] = TRUE;
                $client_data = $this->mf->get_table_data_with_type($params);
                if(is_array($client_data) && count($client_data) > 0){
                    $arr_client_details = $client_data[0];
                }
                unset($client_data);
            }
        }
        catch(Exception $e){

        }
        return $arr_client_details;
    }

	public function addTDAYEntries($date = 0, $client_id = 0 ){

		if($date == 0){
			$date = date('Y-m-d');
		}
		$params2                 = [];
		$params2['env']          = 'db';
		$params2['select_data']  = "mf_billdesk_transactions_tday.*,scheme_master.Redemption_Qty_Multiplier, ss.scheme_code as scheme_code, ss.order_mode";
		$params2['table_name']   = 'mf_billdesk_transactions_tday';
		$params2['where']        = TRUE;
		$params2['where_data']   = array('order_executed_day' => $date, 'transaction_status'=>'success');
		if($client_id != 0 ){
			$params2['where_data']['client_id']   = array('client_id' => $client_id);
		}
		$params2['join']             	= TRUE;
		$params2['multiple_joins']  	= TRUE;
		$params2['join_table']       	= array('mf_master_smart_sip AS ss','mf_scheme_master as scheme_master');
		$params2['join_on']          	= array('ss.id = mf_billdesk_transactions_tday.master_order_id','ss.scheme_code = scheme_master.Scheme_Code');
		$params2['join_type']        	= array('INNER','INNER');
		$params2['return_array'] = TRUE;
		//$params2['print_query_exit']     =  TRUE;
		$mf_billdesk_transactions_tday_data    = $this->mf->get_table_data_with_type($params2);
		//y($mf_billdesk_transactions_tday_data, '$mf_billdesk_transactions_tday_data');

		if(!empty($mf_billdesk_transactions_tday_data)){
			foreach($mf_billdesk_transactions_tday_data as $morder){
				//$where_navdate = $this->getMongoDate($morder['t_1day']);
				$where_navdate = $this->mongo_db->lte($this->getMongoDate($morder['t_1day']));
				$where_navdate_order = 'desc';

				if(isset($fixed_date) && !empty($fixed_date)){
					$where_condn = array('schemecode' => $morder['accord_scheme_code'],'nav_date' => $where_navdate);
				}else{
					$where_condn = array('schemecode' => $morder['accord_scheme_code']);
				}
				$result = $this->mongo_db->where($where_condn)->sort('nav_date', $where_navdate_order)->limit(1)->get('mf_smart_sip_eq_momentum')[0]; // getting Margin of Safety for todays date for schemecode

				if(empty($result)){
					$response = array('order_status' => 'error', 'msg' => 'No Mosdex Data found for that day');

					$this->load->view('jsonresponse', ['data' => $response])  ;
					continue;
				}
				$mongo_nav_date   = (array)$result['nav_date'];
				$mongo_nav_date    = date('Y-m-d',$mongo_nav_date['milliseconds']/1000) ;
				$mos = $morder['margin_of_safety'] = $result['margin_safety'];

				$current_units = $this->getLastSmartTransaction($morder['client_id'], $morder['master_order_id']);
				$current_units['closing_liquid_units_original'] = $current_units['closing_liquid_units'];
				$current_units['closing_eq_units_original'] = $current_units['closing_eq_units'];
				$current_units['base_signal'] = $morder['base_signal'] = $morder['t_day_signal'];
				$current_units['order_mode'] = $morder['order_mode'];
				$current_units['margin_of_safety'] = $morder['margin_of_safety'];

				$current_units['mongo_date'] = $mongo_nav_date;
				//$current_units['mongo_date'] = $mongo_nav_date;
				$current_liquid_nav = $this->getCurrentNav($morder['accord_liquid_scheme_code']) ; //,date
				$current_liquid_nav_default = '4397.63'; //$this->getCurrentNav($morder['accord_liquid_scheme_code_default'], $date) ;
				$current_eq_nav = $this->getCurrentNav($morder['accord_scheme_code']);

				$min_eq_amt_flag = $this->getSchemeMinValues($morder['scheme_code'],$morder['client_id']); //storing min purchase, multiplier flags
				$min_liquid_amt_flag = $this->getSchemeMinValues($morder['liquid_scheme_code'],$morder['client_id']);//storing min purchase, multiplier flags
				$liquid_scheme_redemption_multiplier = 0.001;
				$min_liquid_amt_flag_default = $this->getSchemeMinValues($morder['liquid_scheme_code_default'],$morder['client_id']);//storing min purchase, multiplier flags for default liquid

				$current_units['rebalance_buy_eq_units'] = intval($morder['amount']) / $current_eq_nav ;
				$current_units['buy_eq_units'] = $morder['amount'] / $current_eq_nav;
				$morder['units'] = $current_units['buy_eq_units'];

				$c_unitsliquid = $this->get_lower_nearest_value($current_units['closing_liquid_units'],$liquid_scheme_redemption_multiplier);
				$current_units['order_id'] = 0;
				//y($current_units,'$current_units');

				if(empty($c_unitsliquid) || $c_unitsliquid <= ($min_liquid_amt_flag['Minimum_Redemption_Qty']) ){
					$morder['liquid_units'] = 0;
					$current_units['comment'] = json_encode(array('purchase' => 'No Liquid units or less thatn redemption qty'));
					$current_units['buy_eq_units'] = $morder['amount'] / $current_eq_nav;
					$morder['units'] = $current_units['buy_eq_units'];
				}
				//$this->purchaseSmartLumpsumProcessList($morder,$current_units);
				$this->purchaseSmartLumpsumProcessListTDAY($morder,$current_units);
			}
			//x(count($mf_billdesk_transactions_tday_data)." Number of order placed");
		}else{

			//x("No Data Found in Billdesk Transaction TDAY");
		}

	}

	public function purchaseSmartLumpsumProcessListTDAY($morder,&$current_units,$type = 'equity',$env ='MFI'){
		$data = array();
		$client = $morder['client_id'];
		$scheme_code = $morder['scheme_code'];
		$today = date('Y-m-d');
		if(empty($client)){
			redirect(base_url());
		}else{
			$data['trans_code'] = 'NEW' ;
			$data['client_id'] = $client;
			$data['order_id'] = '';

			$data['scheme_code'] = $scheme_code;
			$data['buy_sell'] = 'P';
			$folio = $this->checkFolioNo($scheme_code,$client,$morder['order_mode']);

			if($type == 'equity' && $morder['amount'] >= 200000 ){
				$data['scheme_code'] = $scheme_code.'-L1';
			}

			if(!empty($folio) ){
				$data['buy_sell_type'] = 'ADDITIONAL';
				$data['folio_number']  = $folio;
			}else
				$data['buy_sell_type'] = 'FRESH';

			if($morder['order_mode'] == 'P'){ //added for physical order
				$data['DPTxn'] = 'P'; //p for physical
				$data['order_mode'] = 'P';
			}
			else{
				$data['DPTxn'] = 'C';
				$data['order_mode'] = 'D';
			}

			$data['amount'] = intval($morder['amount']);
			$data['quantity'] = '';
			$data['order_type'] = 'lumpsum';
			$data['order_id'] = '';
			$data['order_status'] = '';
			$data['order_response'] = '';
			$data['unique_ref_no'] = '';
			$data['date_created'] = $date = date('Y-m-d H:i:s');
			$data['created_by'] = $morder['client_id'];
			$data['source']     = $morder['client_id'];
			$data['master_id']     = $morder['master_id'];
			$data['base_signal']     = $morder['base_signal'];
			$data['margin_of_safety']     = $current_units['margin_of_safety'];

			if(isset($data['DPTxn']))
				unset($data['DPTxn']); //this field is not in database table thats why removed form array
			/* $params['date'] = date('Y-m-d');
			$params['client_id'] = $client;
			$params['TransType'] = 'P';
			$params['order_id'] = $res[2]; */
			$data['master_id'] = $morder['master_order_id'];
			//$prov_order = $this->bsestar_mutualfund->ProvOrderStatus($params,'MFI');
			if(true){

				$data['opening_eq_units'] = $current_units['closing_eq_units'];
				$data['opening_liquid_units'] = $current_units['closing_liquid_units'];

				if($type == 'equity'){
					$data['buy_eq_units'] = $current_units['buy_eq_units'];
					//$data['units'] = $current_units['units'] + $morder['units'];
					$data['closing_eq_units'] = $current_units['closing_eq_units'] + $current_units['rebalance_buy_eq_units'] + $current_units['secondary_base_buy_eq_units'];
					$data['closing_liquid_units'] = $current_units['closing_liquid_units'] + $morder['liquid_units'];
					$data['rebalance_buy_eq_units'] = $current_units['rebalance_buy_eq_units'];
					$data['secondary_base_buy_eq_units'] = $current_units['secondary_base_buy_eq_units'];
					$data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'];
				}

				if($type == 'liquid'){
					$data['units'] = $current_units['units'] + $morder['units'];
					$data['buy_liquid_units'] = $current_units['buy_liquid_units'] ;
					$data['closing_liquid_units'] = $current_units['closing_liquid_units']  + $current_units['rebalance_buy_liquid_units'] ;
					$data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
					$data['rebalance_buy_liquid_units'] = $current_units['rebalance_buy_liquid_units'];
					$data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] ;
				}

				if($type == 'default_liquid'){

					$data['closing_liquid_units_default'] =  $current_units['closing_liquid_units_default'] + $current_units['buy_liquid_units_default'] ;
					$data['closing_eq_units'] = $current_units['closing_eq_units'] + $morder['units'];
					$data['closing_liquid_units'] = $current_units['closing_liquid_units'] ;

					$data['buy_liquid_units_default'] = $current_units['buy_liquid_units_default'];

				}

			}

			$data['comment'] = $current_units['comment'];
			$data['mos_date'] = $current_units['mongo_date'];
			$data['order_status'] = 5; //could be executed
			$data['order_executed_day'] = '0000-00-00'; //TBD update after process list
			$insert_id = $this->insertSmartOrder($data);

			/* $next_sip_t_day = $this->filterHolidays($morder['next_t_day']);
			if($type == 'liquid'){ // selling eq to buy liquid t+3
				$process_date = $next_sip_t_day['tplus3'];
			}else{ // selling liquid to buy equity t+1
				$process_date = $next_sip_t_day['tplus1'];
			} */
			$process_date = $morder['order_executed_day'];
			//y($process_date, '$data_sm before');
			if(empty($folio)){
				$params = [];
				$params['env']              = 'default';
				$params['select_data']      = "*";
				$params['table_name']       = 'mf_order_process_list_smartsip';
				$params['where']            = TRUE;
				$params['where_data']       =array( 'client_id' => $client, 'process_date' => $process_date);
				if(!empty($smartsip_order_id) && is_numeric($smartsip_order_id)){
					$params['where_data']['master_id'] = $smartsip_order_id;
				}
				$params['where_in']             =    TRUE;
				$params['where_in_field']             =    'scheme_code';
				$params['where_in_data']        = array($scheme_code,$scheme_code.'-L1',$scheme_code.'-L0');
				$params['where_not_in']         = TRUE;
				$params['return_array']     = True;
				$params['limit_data']     = 1;
				$params['limit_start']     = 0;
				$params['order_by']         = "date_created";
				//$params['print_query'] = TRUE;
				$data_sm               = $this->sn->get_table_data_with_type($params);
				//return($data_sm[0]['folio_number']);
				/* if(!empty($data_sm[0])){
					$next_sip_t_day = $this->filterHolidays(date("Y-m-d", strtotime($morder['next_t_day'] . ' +2 day')));
					if($type == 'liquid'){ // selling eq to buy liquid t+3
						$process_date = $next_sip_t_day['tplus3'];
					}else{ // selling liquid to buy equity t+1
						$process_date = $next_sip_t_day['tplus1'];
					}
				} */
			}
			$current_units['to_be_executed'] = 1;
			if(isset($current_units['to_be_executed']) ){
				$current_units['order_id'] = 0;
			}

			$p_order = [    'master_id' => $morder['master_order_id'],
							'client_id' => $client,
							'order_type' => 'lumpsum',
							'order_id' => '',
							'redemption_order_id' => $current_units['order_id'],
							'smart_order_p_id' => $insert_id,
							'scheme_code' => $data['scheme_code'],
							'folio_number' => $folio,
							'amount' => $data['amount'],
							'order_date' => date('Y-m-d'),
							'process_date' => $process_date,//$this->calculateProcessDate(),
							'mfi_order_id' => '',
							'order_mode' => $morder['order_mode'],
							'to_be_executed' => 1,
							'pg_reference_number' => $morder['bill_no']

						];
			//$current_units['to_be_executed'] = 1;
			//x($current_units);
			/* if(isset($current_units['to_be_executed']) ){ // for T day mandate orders that will be needed to be processed
				$p_order['to_be_executed'] = 0;
				$p_order['process_date'] = $next_sip_t_day['tplus2'];
				$current_units['order_id'] = 0;
			} */
			$insparam  =[];
			$insparam['env']        = 'db';
			$insparam['table_name'] = 'mf_order_process_list_smartsip';
			$insparam['data']       = $p_order;
			$lid = $this->mf->insert_table_data($insparam);
			//y($insparam,'insert mf_order_process_list_smartsip');

			if(isset($current_units['to_be_executed']) ){ // code to update tday mandate table to link process list order
				$update_master_data = [];
				$update_master_data = [
										'linked_order' => $lid,
										//'order_executed_day' => $next_sip_t_day['tplus2']

										];

				$params_up                  =  [];
				$params_up['env']           =  'db';
				$params_up['table_name']    =  'mf_billdesk_transactions_tday';
				$params_up['update_data']   =  $update_master_data;
				$params_up['where']         =  TRUE;
				//$params_up['print_query']         =  TRUE;
				$params_up['where_data']    =  ['master_order_id' => $morder['master_order_id'] ,'t_day' => $morder['t_day'] ];
				$update_status = $this->mf->update_table_data_with_type($params_up);

			}
			//updating units
			$current_units['closing_eq_units'] =  $data['closing_eq_units'];
			$current_units['closing_liquid_units'] =  $data['closing_liquid_units'];

			//return $res[2];

		}
	}

	/*
		author: Amey
		purpose: send switch signal via email
		params : 1 = available by bse ,2 = not available
		created: 05/08/2022
	*/
	function sendSwitchCommunicationEightPM($client_id=0, $signal='NULL', $sendEmail = 1){
		error_reporting(-1);
		ini_set('display_errors', 1);

		$params                     = [];
		$params['env']              = 'db';
		$params['select_data']      = "mf_smart_sip_switch_process_list.id,mf_smart_sip_switch_process_list.client_id, installment_amount, from_scheme_code, to_scheme_code, margin_of_safety, number_of_unit, base_signal, smartsip_order_number, ss.scheme_code as equity_scheme_code,ss.liquid_scheme_code as liquid_scheme_code";
		$params['table_name']       = 'mf_smart_sip_switch_process_list';
		$params['join']             	= TRUE;
		$params['multiple_joins']  	= TRUE;
		$params['join_table']       	= array('mf_master_smart_sip AS ss');
		$params['join_on']          	= array('ss.id = mf_smart_sip_switch_process_list.smartsip_order_number');
		$params['join_type']        	= array('INNER');
		$params['where']            = TRUE;
		$params['where_data'] = array('mail_sent'=>1, 'expiry_datetime >'=>date('Y-m-d H:i:s'));
		if(!empty($client_id) && ($client_id != 0 || $client_id != '0')){
			$params['where_data']['mf_smart_sip_switch_process_list.client_id']   = $client_id;
		}

		if($signal != 'NULL'){
			$params['where_data']['base_signal']   = $signal;
		}


		$params['return_array']     = TRUE;
		//$params['print_query'] = TRUE;
		$switchdata                 = $this->mf->get_table_data_with_type($params);

		if(!empty($switchdata)){
			$this->load->library('Bsestar_mutualfund');
			$filename_double_invest = 'SwitchCommunication_double_invest_'.date('Y-m-d').'.csv';
			$filepath_double_invest = FCPATH.'assets/elastic_email/'.$filename_double_invest;
			$fp_double_invest = fopen($filepath_double_invest, 'w');

			$filename_sell = 'SwitchCommunication_sell_'.date('Y-m-d').'.csv';
			$filepath_sell = FCPATH.'assets/elastic_email/'.$filename_sell;
			$fp_sell = fopen($filepath_sell, 'w');

			$merge_vars = array_map('trim', array('ToMail','Name','Scheme-name','MosDex-value','Investment-amount','Bank-name','Equity-Scheme','Bank-account-number','Units','Approve-link','client_id','Liquid-scheme','unit-per'));

			fputcsv($fp_double_invest,$merge_vars);
			fputcsv($fp_sell,$merge_vars);
			$sms_array_success = array();
			$params_up = [];
			$params_up['env'] = 'db';
			$params_up['table_name'] = 'mf_smart_sip_switch_process_list';
			$params_up['where_key'] = 'id';
			$params_up['batch'] = TRUE;

			$update_email_flag = [];
			$double_invest = 0;
			$sell = 0;
			foreach($switchdata as $val){
				$csv_data = array();
				$data = array();
				$unitper = "";
				$scheme_name = $this->getSchemeNamefromCode($val['from_scheme_code']);

				$client_id = $val['client_id'];
				if(!empty($client_id)){
					$margin_safety = number_format((float)$val['margin_of_safety'], 2, '.', '');
					if($val['base_signal']=='double_invest'){
						$template = "rankmf-smartsip-signal-doubleinvest-1648122851";
						if($margin_safety>=105){ $unitper=35; }
						if($margin_safety>110){ $unitper=100; }
						$lq_scheme = $val['from_scheme_code'];
						$double_invest++;
					}

					if($val['base_signal']=='sell'){
						$template = "rankmf-smartsip-signal-sell-1648122991";
						if($margin_safety< 60){ $unitper=35; }
						if(($margin_safety>=60) && ($margin_safety<80)){ $unitper=30; }
						$lq_scheme = $val['to_scheme_code'];
						$sell++;
					}

					$lqschemecode = trim($lq_scheme, '-L0');

					$liquid_scheme_name = $this->getSchemeNamefromCode($val['liquid_scheme_code']);
					$equity_scheme_name = $this->getSchemeNamefromCode($val['equity_scheme_code']);


					$clientinfo = $this->getBankInfo($client_id);

					$url = RANK_MF_URL_SHORT;
					$autologin_url = getUrlShort($url,$client_id,41);
					$autologin_url = $autologin_url."/".$val['id'];
					$bank_accno = 'XXXXXX' . substr($clientinfo['client_acc_no'], strlen($clientinfo['client_acc_no']) - 4, 4);
					$csv_data[] = $clientinfo['client_email'];
					$csv_data[] = ucwords(strtolower($clientinfo['client_name']));
					$csv_data[] = $scheme_name;
					$csv_data[] = $margin_safety;
					$csv_data[] = $val['installment_amount'];
					$csv_data[] = $clientinfo['bank_name'];
					$csv_data[] = $equity_scheme_name;
					$csv_data[] = $bank_accno;
					$csv_data[] = $unitper; //$val['number_of_unit'];
					$csv_data[] = $autologin_url; //link
					$csv_data[] = $client_id;
					$csv_data[] = $liquid_scheme_name;
					$csv_data[] = $unitper;

					if($val['base_signal']=='sell'){
						fputcsv($fp_sell, $csv_data);
					}elseif($val['base_signal']=='double_invest'){
						fputcsv($fp_double_invest, $csv_data);
					}

					$update_email_flag[] = ['id'=>$val['id'],'mail_sent'=>1];

				}
			}

			$postdata_double_invest['template_name'] = 'rankmf-smartsip-signal-doubleinvest-1648122851';
			$postdata_double_invest['channel_name']  = 'rankmf-smartsip-signal-doubleinvest-1648122851';
			$postdata_sell['template_name'] = 'rankmf-smartsip-signal-sell-1648122991';
			$postdata_sell['channel_name']  = 'rankmf-smartsip-signal-sell-1648122991';

			//$params_up['update_data']    = $update_email_flag;
			//$params_up['print_query_exit']     =  TRUE;
			//$this->mf->update_table_data_with_type($params_up);
            if($sendEmail == 1){
				if($double_invest > 0){
					$this->sendMailToUserCron($filename_double_invest,$postdata_double_invest);
				}

				if($sell > 0){
					$this->sendMailToUserCron($filename_sell,$postdata_sell);
				}
			}
			fclose($fp_double_invest);
			fclose($fp_sell);

		}

	}
	public function logToFile($filename, $msg){
		$file_location = $_SERVER['DOCUMENT_ROOT'] . "/tmp/smartSIP_logs/" . $filename;

		$path = $_SERVER['DOCUMENT_ROOT'].'/tmp/';
		if ( !file_exists($path)){
			mkdir ($path, 0777);
		}
		$value='smartSIP_logs';
		if(!file_exists($path.$value)){
			mkdir ($path.$value, 0777);
		}
		$file = fopen($file_location, 'a+');
		$msg .= "\n\n\n";
		fwrite($file, $msg);
		fclose($file);
	}
}
