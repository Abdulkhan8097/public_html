<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

class Api_model extends CI_Model
{
	public function setData($table, $data)
    {
        if (!$this->db->insert($table,$data)) {
            log_message('error', print_r($this->db->error(), true));
        }
        $id = $this->db->insert_id();
		return $id;
		#print $this->db->last_query();exit;
    }
    
	public function UpdateData($table, $data, $cond)
    {
		$this->db->where($cond);
        if (!$this->db->update($table,$data)) {
            log_message('error', print_r($this->db->error(), true));
        }
        #$id = $this->db->insert_id();
		#print $this->db->last_query();exit;
    }
    
	public function _usersessioncheck($session,$mobile)
    {
        $this->db->where('user.session', $session);
        $this->db->where('user.phone', $mobile);
        $query = $this->db->select('*')->get('user');
        #print $this->db->last_query();exit;
        if(sizeof($query->result_array()) == 0)//session not found
        {
            $code = 200;          
            $this->give_response($code, FALSE,'Invalid Session, Please login again');
        }else{
            return $query;    
        } 
		
    }
	
	public function getGUID(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			/*
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12)
				.chr(125);// "}"        
			
			
			$uuid = 
				 substr($charid, 0, 5).$hyphen
				.substr($charid, 5, 5).$hyphen
				.substr($charid,10, 5).$hyphen
				.substr($charid,15, 5).$hyphen
				.substr($charid,20,5);
			*/

			$uuid = 
				substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12);
				
				
			$uuid = strtolower($uuid);
			return $uuid;
		}
	}
	
	public function user_details($data)
	{
		
		$response = array();
        $mobile = $data['mobile'];
        $pass = $data['pass'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('user_id,customer_id,	first_name,	last_name, password,testac,country');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('user.phone', $mobile);
			//$this->db->where('user.password', md5($pass));
			//$this->db->where('user.user_type', 2);
			$this->db->where('user.isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('user', $limit);
			$datec = date("Y-m-d H:i:s");
			
			#print $this->db->last_query();exit;
			#print $result->num_rows();exit;
			
			if($result->num_rows() ==1)//found
			{
				$oldpass = $result->result()[0]->password;
				$testac = $result->result()[0]->testac;
				$country = $result->result()[0]->country;
				$otp = rand(1111,9999);
				if($testac == 1)
				{
					$otp = 1234;
				}				

				#print_r($oldpass);exit;
				#print "hi <br>$oldpass <br>" . md5($pass);exit;
				if($oldpass == md5($pass))
				{
					$this->UpdateData('user', array('otp' => $otp,'otp_datec'=>$datec), array('phone' => $mobile,'isActive' =>
				'1'));

					if($testac != 1)
					{
						#$mobile = "9820397227";
						$message = "Your OTP is $otp Use this OTP to verify your mobile number\nINDOEVEREST";
						
						if($country == 'IN')
						{
							$this->sendsms3( $mobile, $message);
						}
						if($country == 'NP')
						{
							$this->sendsmsnepal( $mobile, $message);
						}
					}				

				}else{
					return "0";
				}
				
			}
			#print $this->db->last_query();exit;
			return $result->result_array();
		#}else{
		#	return $response;
		#}		
	}

	public function user_details_forgot($data)
	{
		
		$response = array();
        $mobile = $data['mobile'];
        #$pass = $data['pass'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('user_id,customer_id,	first_name,	last_name, country');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('user.phone', $mobile);
			#$this->db->where('user.password', md5($pass));
			#$this->db->where('user.user_type', 2);
			$this->db->where('user.isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('user', $limit);
			
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;
			$otp = rand(1111,9999);
			#$otp = 1234;
			if($result->num_rows() ==1)//found
			{
				#$mobile = "9820397227";
				#$pass = rand(1111,9999);
				$country = 'IN';
				foreach ($result->result() as $row)
				{
					$country= $row->country;
				}
				
				$message = "Your OTP is $otp Use this OTP to verify your mobile number\nINDOEVEREST";
				if($country == 'IN')
				{
					$this->sendsms3( $mobile, $message);
				}
				if($country == 'NP')
				{
					$this->sendsmsnepal( $mobile, $message);
				}
				
				$this->UpdateData('user', array('otp' => $otp,'otp_datec'=>$datec), array('phone' => $mobile,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			return $result->result_array();
		#}else{
		#	return $response;
		#}		
	}
	
	public function user_details_forgotget($data)
	{
		
		$response = array();
        $mobile = $data['mobile'];
        $otp = $data['otp'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('user_id,customer_id,	first_name,	last_name');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('user.phone', $mobile);
			$this->db->where('user.otp', $otp);
			//$this->db->where('user.user_type', 2);
			$this->db->where('user.isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('user', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;
			$pass = rand(11111,99999);
			$password2 = md5($pass);
			#$password2 = md5(1234);
			#$otp = 1234;
			if($result->num_rows() ==1)//found
			{
				#$message = "Dear User, \nMobile No: $mobile\nYour password $pass\nINDOEVEREST";
				$message = "Your OTP is $pass Use this OTP to verify your mobile number\nINDOEVEREST";
				$this->sendsms3( $mobile, $message);

				$this->UpdateData('user', array('password' => $password2,'otp_datec'=>$datec), array('phone' => $mobile,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			return $result->result_array();
		#}else{
		#	return $response;
		#}		
	}
	
	public function user_login($data)
	{
		
		$response = array();
        $mobile = $data['mobile'];
        $pass = $data['pass'];
        $otp = $data['otp'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('user_id,customer_id,	first_name,	last_name');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('user.phone', $mobile);
			$this->db->where('user.password', md5($pass));
			$this->db->where('user.otp', $otp);
			#$this->db->where('user.user_type', 2);
			$this->db->where('user.isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('user', $limit);
			$datec = date("Y-m-d H:i:s");
			$otp2 = rand(1111,9999);
			#$otp2 = 1234;
			#print $result->num_rows() . ' ' . $this->db->last_query();exit;
			#print $result->num_rows();exit;
			$session = $this->getGUID();

			if($result->num_rows() ==1)//found
			{
				$user_id = $result->result()[0]->user_id;
				$row2=array();
				$row2['user_id']=$user_id;
				$row2['otp']=$otp;
				$row2['session']=$session;
				$row2['login_time']=$datec;
				$row2['logout_time']=NULL;
				
				$this->setData('user_login', $row2);
				#print $this->db->last_query();exit;
				$this->UpdateData('user', array('session' => $session,'otp' => $otp2), array('phone' => $mobile,'isActive' =>
				'1'));
			}
			#print $this->db->last_query();exit;
			
			$this->db->select('user_id,customer_id,	first_name,	last_name,session');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('user.phone', $mobile);
			#$this->db->where('user.password', md5($pass));
			#$this->db->where('user.otp', $otp);
			#$this->db->where('user.user_type', 2);
			$this->db->where('user.isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result2 = $this->db->get('user', $limit);
			
			return $result2->result_array();
		#}else{
		#	return $response;
		#}		
	}
	
	public function product_details($data)
	{
		
		$response = array();
        $product_id = $data['product_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			#print_r($query);exit;
			$discount = $query->result()[0]->discount;
			#print $discount;exit;
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('*');
			#$this->db->select('`product`.*, `product_series`.*, `series`.*, `product`.`product_id`');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('product.product_id', $product_id);
			#$this->db->where('isActive', '1');
			#$this->db->join('product_series ', 'product_series.product_id = product.product_id', 'left');
			#$this->db->join('series ', 'series.series_id =  product_series.series_id', 'left');
			$this->db->join('category', 'category.category_id =  product.category_id', 'left');
			#$result = $this->db->group_by("product.product_id"); // Produces: GROUP BY product_id
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;
			$pc = $query->result()[0]->Price_Comparison_Aceess;

			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($x);
			  #print_r($val['product_id']);
			  #print " $x ";exit;
			  
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }

			  $product_id = $val['product_id'];
			  $q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
			  $current_stock = @$q0[0]->current_stock;
			  if(empty($current_stock)) $current_stock =0;
			  #print $current_stock;exit;
			  $val['stock'] = $current_stock;
			  #$val['series_search'] = $val['series_name'];
			  //landing_price
			  $gst = $val['gst'];
			  $landing_price = $val['landing_price'];
			  $landing_price2 = $val['mrp'] * $discount/100;
			  $landing_price2 = round($val['mrp'] - $landing_price2,0);
			  
			  $val['landing_price'] = "₹" . $landing_price2 . "/-";
			  $val['discounted_price'] = "₹" . $landing_price2 . "/-";
			  $val['mrp'] = "₹" . $val['mrp'] . "/-";
			  $gstrate = '';
			  if($gst>0)
			  {
				  $gstrate =  round($landing_price2 + ($landing_price2 * $gst/100),0);
				  $val['discounted_price'] = "₹" . $landing_price2  . "/-" . " (With GST ₹ $gstrate/-)";
			  }

			  $result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
			  #print $this->db->last_query();
			  $series_name = '';
			  foreach($result4->result() as $r2)
			  {
				  $series_name .= "<br />" . $r2->series_name;
			  }
			  $series_name = substr($series_name,6);
			  #print $series_name;exit;
  			  #$val['series_search'] = $val['series_name'];			  
			  $val['series_search'] = $series_name;
			  $val['currency'] = "₹";
			  $val['pc'] = $pc;
			  
			  $extra11 = '';			  
			  $partno = $val['eve_part_no'];


			  $q1 = $this->db->query("select * from product where eve_part_no='$partno' limit 1 ")->result();
			  #print $this->db->last_query();exit;
			  #$info2 = $this->db->query("select * from everest_discount order by everest_discount_id desc limit 1 ")->result();
			  #$discount=@$info2[0]->discount;						
			  #print sizeof($q1);exit;
			  #$data['fk_com_master_id'] = $id;
			  #$data['discount'] = $discount;
			  
			  $product_id = @$q1[0]->product_id;

			  $mrp = @$q1[0]->mrp;
				$landingprice = ($mrp - $mrp*$discount/100);
				
				$infod = $comp2 = array();
				$infod['product_id'] = $product_id;
				$infod['partno'] = $partno;
				$infod['mrp'] = $mrp;
				$infod['discount'] = $discount;
				$infod['landingprice'] = round($landingprice,0);
				#$infod['currency'] = "Rs";
				#$infod['pc'] = $pc;
				
				$q2 = "select * from product_competitor
	inner join competitor on competitor.competitor_id = product_competitor.competitor_id 
	inner join competitor_master on competitor_master.id = competitor.fk_com_master_id
	WHERE `product_id` = $product_id";
				#print $q2;exit;
				$q2row = $this->db->query($q2)->result();
				$j=0;

				foreach($q2row as $row2)
				{
					$competitor_id = $row2->fk_com_master_id;
					$company_name = $row2->company_name;
					$comp_part_no = $row2->comp_part_no;
					$remark = $row2->remark;
					#$eff_date = $row2->eff_date;
					$mrp2 = $row2->mrp;
					#print $competitor_id . ' * ';
					#
					#print $this->db->last_query();exit;
					$discount2 = 0;
					$q11 = $this->db->query("select * from sales_competitor_discount where user_id=$user_id and fk_com_master_id=$competitor_id	")->result();
					#print $this->db->last_query() . "\n";//exit;
					if(sizeof($q11) ==0) //0 means not found
					{					
						$q21 = $this->db->query("select * from competitor_discount where fk_com_master_id=$competitor_id")->result();
						#print $this->db->last_query();exit;
						if(sizeof($q21) ==0)
						{
							$discount2 = 0;
						}
					}else{
						$discount2 = $q11[0]->discount1;
					}
					#print $discount2;exit;
					$eff_date = $row2->eff_date;
					$eff_date2 = '';
					if(!empty($eff_date))
					{
						$eff_date2 = "Wef: " . date('d-M-Y', strtotime($eff_date));
					}

					$landingprice2 = round(($mrp2 - $mrp2*$discount2/100),0);
					$gain = round($landingprice2 - $landingprice);

					if(empty($mrp2))
					{
						$gain = $landingprice2 = '-';
					}else{
						//$gain = "Rs $gain";
						$landingprice2 = "Rs $landingprice2";
						$mrp2 = "Rs $mrp2";
					}
					$comp2[$j]['srno'] = $j+1;
					$comp2[$j]['company_name'] = $company_name;
					$comp2[$j]['comp_part_no'] = $comp_part_no;
					$comp2[$j]['remark'] = $row2->remark;
					$comp2[$j]['eff_date'] = $eff_date2;
					$comp2[$j]['mrp2'] = $mrp2;
					$comp2[$j]['discount2'] = $discount2;
					$comp2[$j]['landingprice'] = $landingprice2;
					$comp2[$j]['gain'] = $gain;

					if($gain>0)
					{
						$gain = "<font color=\"green\"><b>Rs $gain</b></font>";
					}
					if($gain<0)
					{
						$gain = "<font color=\"red\"><b>Rs $gain</b></font>";
					}
					if(!empty($remark))
					{
						$remark = "Remark: $remark";
					}
					if(!empty($eff_date))
					{
						$eff_date = "wef: $eff_date2";
					}
					//print $gain;exit;
					if($j==0)
					{
						//$extra11 .= "<table cellspacing=\"1\" cellpading=\"5\" border=\"1\">\n\n<tr><th>Sr No.</th><th>Company Name</th><th>Part No</th><th>MRP (Rs)</th><th>Discount (%)</th><th>Landing Price (Rs.)</th><th>Gain (+) / Loss (-)</th></tr>\n";
						$extra11 .= "<table cellspacing=\"0\" cellpading=\"5\" border=\"1\">\n\n<tr style=\"background-color: #dceca1;\"><th>Sr#</th><th>Company Name / Part No</th><th>MRP (Rs)</th><th>Dis (%)</th><th>LP (Rs.)</th><th>Gain (+) / Loss (-)</th></tr>\n";
					}
					$k = $j+1;
					$extra11 .= "<tr><td>$k</td><td><b>$company_name</b><br>$comp_part_no<br>$remark</td><td align=\"right\">$mrp2<br>$eff_date2</td><td align=\"right\">$discount2</td><td align=\"right\">$landingprice2</td><td align=\"right\">$gain</td></tr>\n";

					$j++;

				}
				$extra11 .= "</table>\n\n";
				#print_r($comp2);exit;
				#print $extra11;exit;
				#$val['extra11'] = 'Hello World';
				$val['extra11'] = base64_encode($extra11);
			  $darry[$x]=$val;
			}
			#print_r($darry);exit;
			return $darry;
			
		}	
	}
	
	public function product_detailsrelated($data)
	{
		
		$response = array();
        $product_id = $data['product_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');
			
			$this->db->select('*');
			#$this->db->join('retail_list', 'retail_list.product_id=product.product_id', 'inner');
			$this->db->where('retail_list.product_id', $product_id);
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('retail_list', $limit);
			#print $this->db->last_query();exit;
			
			$c = '';
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $c .= ', ' . $val['child_id'];
			}
			$c = substr($c,1);
			#$c = "10,11";
			#print $c;exit;
			
			if(empty($c)) return array();
			$this->db->select('*');
			#$this->db->join('retail_list', 'retail_list.product_id=product.product_id', 'inner');
			$this->db->where("product.product_id in($c)");
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result2 = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result2->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			foreach($result2->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $darry[$x]=$val;
			}
			#print_r($darry);
			#exit;
			return $darry;
			
		}	
	}
	
	public function product_parts($data)
	{
		
		$response = array();
        $partno = $data['partno'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('*');
			$this->db->select('*');
			//$this->db->select('product.product_id,category_name, eve_part_no,profile_img,mrp, product.category_id');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('eve_part_no', $partno);
			#$this->db->where('isActive', '1');
			#$this->db->join('product_series ', 'product_series.product_id = product.product_id', 'left');
			#$this->db->join('series', 'series.series_id =  product_series.series_id', 'left');
			$this->db->join('category ', 'category.category_id =  product.category_id', 'left');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }

			  $product_id = $val['product_id'];
			  $q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
				$current_stock = @$q0[0]->current_stock;
				if(empty($current_stock)) $current_stock =0;
				#print $current_stock;exit;
				$val['stock'] = $current_stock;
			  //$darry[$x]=$val;
			  $category_id = $val['category_id'];
			  #print $category_id;exit;

			  $extra11 = '';
			  $a = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = '$category_id' AND `display` = 'Yes' ORDER BY `display_order`")->result();
			  //print $this->db->last_query();
			  $kk=0;
			  if(sizeof($a)>=0)
				{
					foreach($a as $rr)
					{
						//print $rr->parameter_name . "<hr>\n";
						//print $rr->related . "<hr>\n";
						$bb = $val[$rr->related];
						//<div class="food-price">'+dd+' : '+msg.data.products[i][dd2]+' </div>'
						//$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";

						if($rr->related == 'series_search')
						{
							#print "found series";exit;
							$product_id = $val['product_id'];
							$result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
							#print $this->db->last_query();
							$series_name = '';
							foreach($result4->result() as $r2)
							{
								$series_name .= "<br />" . $r2->series_name;
							}
							$series_name = substr($series_name,6);
							//print $series_name;exit;
							#$val['series_search'] = $val['series_name'];			  
							//$val['series_search'] = $series_name;
							$bb = $series_name;
						}
						$kk++;
						if($kk%2 ==0)
						{
							$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}else{
							$extra11 .= "<div class=\"food-price\" style=\"background-color: #dceca1;\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}
					}
				}
				#print $extra11;exit;
			  $val['extra11'] = base64_encode($extra11);

			  $darry[$x]=$val;			  
			}
			#print_r($darry);
			#exit;
			return $darry;
			
		}	
	}
	
	public function product_cparts($data)
	{
		
		$response = array();
        $partno = $data['partno'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('*');
			$this->db->select('*');
			$this->db->join('product_competitor', 'product.product_id = product_competitor.product_id', 'inner');
			$this->db->join('competitor', 'competitor.competitor_id = product_competitor.competitor_id', 'inner');
			$this->db->join('competitor_master', 'competitor_master.id = competitor.fk_com_master_id', 'inner');
			#$this->db->join('product_series ', 'product_series.product_id = product.product_id', 'left');
			#$this->db->join('series ', 'series.series_id =  product_series.series_id', 'left');
			$this->db->join('category ', 'category.category_id =  product.category_id', 'left');
			$this->db->where('comp_part_no', $partno);
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }

			  $product_id = $val['product_id'];
			  $q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
			  $current_stock = @$q0[0]->current_stock;
			  if(empty($current_stock)) $current_stock =0;
			  #print $current_stock;exit;
			  $val['stock'] = $current_stock;

			  //$darry[$x]=$val;
			  $category_id = $val['category_id'];
			  #print $category_id;exit;
			  $extra11 = '';
			  $a = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = '$category_id' AND `display` = 'Yes' ORDER BY `display_order`")->result();
			  if(sizeof($a)>=0)
				{
					$kk=0;
					foreach($a as $rr)
					{
						#print $rr->parameter_name . "<hr>\n";
						#print $rr->related . "<hr>\n";
						$bb = $val[$rr->related];
						//<div class="food-price">'+dd+' : '+msg.data.products[i][dd2]+' </div>'
						//$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						if($rr->related == 'series_search')
						{
							#print "found series";exit;
							$product_id = $val['product_id'];
							$result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
							#print $this->db->last_query();
							$series_name = '';
							foreach($result4->result() as $r2)
							{
								$series_name .= "<br />" . $r2->series_name;
							}
							$series_name = substr($series_name,6);
							//print $series_name;exit;
							#$val['series_search'] = $val['series_name'];			  
							//$val['series_search'] = $series_name;
							$bb = $series_name;
						}						
						$kk++;
						if($kk%2 ==0)
						{
							$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}else{
							$extra11 .= "<div class=\"food-price\" style=\"background-color: #dceca1;\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}
					}
				}
				#print $extra11;
				#exit;
			  $val['extra11'] = base64_encode($extra11);
			  $darry[$x]=$val;			  
			}
			#print_r($darry);
			#exit;
			return $darry;
			
		}	
	}
	
	public function product_series($data)
	{
		
		$response = array();
        $series = $data['series'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('*');
			$this->db->select('*');
			//$this->db->select('product.product_id,series_name, category_name, eve_part_no,profile_img,mrp');
			$this->db->join('product_series', 'product_series.product_id=product.product_id', 'inner');
			$this->db->join('series', 'series.series_id=product_series.series_id', 'inner');
			$this->db->join('category', 'category.category_id=product.category_id', 'inner');
			$this->db->where('series_name', $series);
			#$this->db->where('series.series_id', $series);
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$this->db->order_by("sequence_no,eve_part_no"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }

			  $product_id = $val['product_id'];
			  $q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
			  $current_stock = @$q0[0]->current_stock;
			  if(empty($current_stock)) $current_stock =0;
			  #print $current_stock;exit;
			  $val['stock'] = $current_stock;

			  //$darry[$x]=$val;
			  $category_id = $val['category_id'];
			  #print $category_id;exit;
			  $extra11 = '';
			  $a = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = '$category_id' AND `display` = 'Yes' ORDER BY `display_order`")->result();
			  if(sizeof($a)>=0)
				{
					$kk=0;
					foreach($a as $rr)
					{
						#print $rr->parameter_name . "<hr>\n";
						#print $rr->related . "<hr>\n";
						$bb = $val[$rr->related];
						//<div class="food-price">'+dd+' : '+msg.data.products[i][dd2]+' </div>'
						//$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						if($rr->related == 'series_search')
						{
							#print "found series";exit;
							$product_id = $val['product_id'];
							$result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
							#print $this->db->last_query();
							$series_name = '';
							foreach($result4->result() as $r2)
							{
								$series_name .= "<br />" . $r2->series_name;
							}
							$series_name = substr($series_name,6);
							//print $series_name;exit;
							#$val['series_search'] = $val['series_name'];			  
							//$val['series_search'] = $series_name;
							$bb = $series_name;
						}						
						$kk++;
						if($kk%2 ==0)
						{
							$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}else{
							$extra11 .= "<div class=\"food-price\" style=\"background-color: #dceca1;\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}
					}
				}
				#print $extra11;
				#exit;
			  $val['extra11'] = base64_encode($extra11);
			  $darry[$x]=$val;			  
			}
			#print_r($darry);
			#exit;
			return $darry;
		}	
	}
	
	public function product_competitor($data)
	{
		
		$response = array();
        $comppartno = $data['comppartno'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('*');
			$this->db->select('product.product_id,series_name, eve_part_no,profile_img,mrp');
			$this->db->join('product_series', 'product_series.product_id=product.product_id', 'inner');
			$this->db->join('series', 'series.series_id=product_series.series_id', 'inner');
			$this->db->where('series_name', $series);
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $darry[$x]=$val;
			}
			#print_r($darry);
			#exit;
			return $darry;
			
		}	
	}
	
	public function product_brand($data)
	{
		
		$response = array();
        #$product_id = $data['product_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select("vm_id,image,'' vehicle_make_name");
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			#$this->db->where('product_id', $product_id);
			$this->db->where('status', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->order_by("vm_sequence, vehicle_make_name"); // Produces: GROUP BY orderid
			$result = $this->db->get('vehicle_make', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  
			  $img = $val['image'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['image'] = $img2;
			  }
			  
			  $darry[$x]=$val;
			  $val['vehicle_make_name'] = '';
			}
			#print_r($darry);
			#exit;
			return $darry;
			
		}	
	}
	
	public function product_model($data)
	{
		
		$response = array();
        $vm_id = $data['vm_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('*');
			$this->db->join('vehicle_make', 'vehicle_make.vm_id=tbl_model_category.fk_vm_category_id', 'inner');
			$this->db->where('fk_vm_category_id', $vm_id);
			$this->db->where('tbl_model_category.status', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->order_by("category_sequence, model_category_name"); // Produces: GROUP BY orderid
			$result = $this->db->get('tbl_model_category', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			
			foreach($result->result_array() as $x => $val) {
			  //echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x: $x ";
			  #print_r($val);exit;
			  $category_model_id = $val['category_model_id'];
			  $fk_vm_category_id = $val['fk_vm_category_id'];
			  $q2 = "select * from model where fk_vm_id=$fk_vm_category_id and fk_model_category_id=$category_model_id order by model_name";
			  #print "img: $img <br>\n";
			  $a = $this->db->query($q2)->result();
			  #print $this->db->last_query() . "<hr>";//exit;

			  $a2 = '';
			  $xx =0;
				foreach($a as $row1)
				{
					$model_id =  $row1->model_id;
					$model_name =  $row1->model_name;
					$image =  $row1->image;

					//$a2 .= $model_name . "<br>";
					$image2 = '';
					if(!empty($image))
					{
						$image2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $image;
					}
					
					if($xx %2 == 0)
					{
						$a2 .= '<div class="row">' . "\n\n";	
					}
					$model_name2 = base64_encode($model_name);
					$a2 .= '<div class="col-50" onclick="get_product_by_model_id(' . $model_id . ",'" . $model_name2 ."'" .');">' . "\n";	
					$a2 .='	<div class="card brand2" style="font-size: 10px;height: 120px;text-align: center;">' . "\n";	
					$a2 .= '		<div class="card-content card-content-padding" style="color:#1f2c55;font-weight: 900;padding: 0px">' . "\n";	
					$a2 .= '			<div style="padding: 10px 30px;" class="card-header align-items-flex-end">' . "\n";	
					if(!empty($image2))
					{
						$a2 .= '				<img src="' . $image2 . '">' . "\n";	
					}
					$a2 .= '			</div>' . "\n";	
					$a2 .= '			' . $model_name . "\n";	
					$a2 .= '		</div>' . "\n";	
					$a2 .= '	</div>' . "\n";	
					$a2 .= '</div>' . "\n";	
					if($xx %2 == 1)
					{
						$a2 .= "\n\n" . '</div>' . "\n\n";	
					}

					#print $a2;exit;
					/**/
					#$a2 = rand() . "<br>";		
					$xx++;		
				}
				if($xx %2 == 0)
				{
					$a2 .= '</anildiv>' . "\n\n";	
				}
				#print $xx . "<hr>\n\n" . $a2;exit;

				$a2 = base64_encode($a2);
				$val['ext_details']=$a2;
			    $darry[$x]=$val;
			}
			#print "<pre>";print_r($darry);
			#exit;
			return $darry;
			
		}	
	}	
	
	public function product_modelbrand($data)
	{
		
		$response = array();
        $model_id = $data['model_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('*');
			$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			$this->db->join('category ', 'category.category_id = product.category_id', 'left');
			#$this->db->join('product_series ', 'product_series.product_id = product.product_id', 'left');
			#$this->db->join('series ', 'series.series_id =  product_series.series_id', 'left');
			$this->db->where('model_product.model_id', $model_id);
			#$this->db->where('isActive', '1');
			$this->db->group_by('product.product_id');
			$this->db->order_by('category.sequence_no,eve_part_no');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }

			  $product_id = $val['product_id'];
			  $q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
			  $current_stock = @$q0[0]->current_stock;
			  if(empty($current_stock)) $current_stock =0;
			  #print $current_stock;exit;
			  $val['stock'] = $current_stock;

			  //$darry[$x]=$val;
			  $category_id = $val['category_id'];
			  #print $category_id;exit;
			  $extra11 = '';
			  $a = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = '$category_id' AND `display` = 'Yes' ORDER BY `display_order`")->result();
			  if(sizeof($a)>=0)
				{
					$kk=0;
					foreach($a as $rr)
					{
						#print $rr->parameter_name . "<hr>\n";
						#print $rr->related . "<hr>\n";
						$bb = $val[$rr->related];
						//<div class="food-price">'+dd+' : '+msg.data.products[i][dd2]+' </div>'
						//$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						if($rr->related == 'series_search')
						{
							#print "found series";exit;
							$product_id = $val['product_id'];
							$result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
							#print $this->db->last_query();
							$series_name = '';
							foreach($result4->result() as $r2)
							{
								$series_name .= "<br />" . $r2->series_name;
							}
							$series_name = substr($series_name,6);
							//print $series_name;exit;
							#$val['series_search'] = $val['series_name'];			  
							//$val['series_search'] = $series_name;
							$bb = $series_name;
						}						
						$kk++;
						if($kk%2 ==0)
						{
							$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}else{
							$extra11 .= "<div class=\"food-price\" style=\"background-color: #dceca1;\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}
					}
				}
				#print $extra11;
				#exit;
			  $val['extra11'] = base64_encode($extra11);
			  $darry[$x]=$val;			  			  
			}
			#print_r($darry);
			#exit;
			return $darry;
		}	
	}
	
	public function product_upcoming($data, $status)
	{
		
		$response = array();
        #$model_id = $data['model_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('product.product_id,series_name,profile_img,category_id,eve_part_no');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			$this->db->join('category ', 'category.category_id = product.category_id', 'left');

			#$this->db->join('product_series ', 'product_series.product_id = product.product_id', 'left');
			#$this->db->join('series ', 'series.series_id =  product_series.series_id', 'left');

			if($status == '2')
			{
				$this->db->where('type', 'New Product');
			}			
			if($status == '1')
			{
				$this->db->where('type', 'Upcoming Product');
			}
			$this->db->group_by('product.product_id');
			$this->db->order_by('eve_part_no');
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('product', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;				  
			  }
			  #if($val['series_name'] == null) 			  $val['series_name'] = '';

			  $product_id = $val['product_id'];
				$q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
				$current_stock = @$q0[0]->current_stock;
				if(empty($current_stock)) $current_stock =0;
				#print $current_stock;exit;
				$val['stock'] = $current_stock;

			  $category_id = $val['category_id'];
			  #print $category_id;exit;
			  $extra11 = '';
			  $a = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = '$category_id' AND `display` = 'Yes' ORDER BY `display_order`")->result();
			  if(sizeof($a)>=0)
				{
					$kk=0;
					foreach($a as $rr)
					{
						
						#print $rr->parameter_name . "<hr>\n";
						#print $rr->related . "<hr>\n";
						$bb = $val[$rr->related];
						//<div class="food-price">'+dd+' : '+msg.data.products[i][dd2]+' </div>'//dceca1
						if($rr->related == 'series_search')
						{
							#print "found series";exit;
							$product_id = $val['product_id'];
							$result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
							#print $this->db->last_query();
							$series_name = '';
							foreach($result4->result() as $r2)
							{
								$series_name .= "<br />" . $r2->series_name;
							}
							$series_name = substr($series_name,6);
							//print $series_name;exit;
							#$val['series_search'] = $val['series_name'];			  
							//$val['series_search'] = $series_name;
							$bb = $series_name;
						}						
						$kk++;
						if($kk%2 ==0)
						{
							$extra11 .= "<div class=\"food-price\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}else{
							$extra11 .= "<div class=\"food-price\" style=\"background-color: #dceca1;\"><b style=\"color: black;\">" . $rr->parameter_name . " : </b>" . @$bb . "</div>\n";
						}
						
					}
				}
				#print $extra11;
				#exit;
			  $val['extra11'] = base64_encode($extra11);
			  $darry[$x]=$val;
			
			  //$darry[$x]=$val2;
			  

			  //$val['extra11'] = 'Hello world';
			}
			//array_push($darry,"extra11","Hello world");
			#print "<pre>";
			#print_r($darry);
			#exit;
			return $darry;
		}	
	}
	
	public function downloads($data)
	{
		
		$response = array();
        #$model_id = $data['model_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			
			#if($status == '1')
			{
				#$this->db->where('type', 'New Product');
			}			
			#if($status == '2')
			{
				#$this->db->where('type', 'Upcoming Product');
			}
			$this->db->order_by('download_type_name');
			$this->db->where('status', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('downloads', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['files_name'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  #$img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $img2 = "https://indoeverestecats.com/kk.php?file=" . $img;
				  #$img2 = "https://indoeverestecats.com/uploads/admin/images/user/down.php?file=" . $img;
				  $val['files_name'] = $img2;
			  }
			  $image = $val['image'];
			  #print "img: $img <br>\n";
			  if(!empty($image))
			  {
				  $img3 = "https://indoeverestecats.com/uploads/admin/images/user/" . $image;
				  $val['image'] = $img3;
			  }
			  $darry[$x]=$val;
			}
			#print_r($darry);
			#exit;
			return $darry;
		}	
	}

	public function product_category($data)
	{
		
		$response = array();
        #$model_id = $data['model_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			
			
			#$this->db->order_by('eve_part_no');
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			#$result = $this->db->get('product', $limit);
#			$result = $this->db->query("select distinct product.category_id, category_name from product
#inner join category on category.category_id = product.category_id order by category_name ");
			$result = $this->db->query("select category_id, category_name,category_image from category where `status` = '1' order by sequence_no ");
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			#$darry['category'] = $result->result_array();
			
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['category_image'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['category_image'] = $img2;
			  }
  			  #$val['series_search'] = $val['series_name'];
			  $darry[$x]=$val;
			}
			#print_r($darry);
			#exit;
			#$xarry['category'] = $darry;
			return $darry;
		}	
	}
	
	public function product_category_par($data)
	{
		
		$response = array();
        $category_id = $data['category_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
        $lastcount = $data['lastcount'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$result1 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and search_dimension ='yes' GROUP BY parameter_name ORDER BY advance_flag,`parameter_order`, `parameter_name` ");
			$result10 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and display ='yes' GROUP BY parameter_name ORDER BY `display_order`, `parameter_name` limit 100 ");
			#print $this->db->last_query();exit;

			$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			#$this->db->join('category', 'category.category_id=product.category_id', 'inner');
			
			
			#$this->db->group_by('parameter_name');
			$this->db->order_by('eve_part_no');
			$this->db->where('category_id', $category_id);
			$result20 = $this->db->get('product');			

			$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			#$this->db->join('category', 'category.category_id=product.category_id', 'inner');
			
			
			#$this->db->group_by('parameter_name');
			$this->db->order_by('eve_part_no');
			$this->db->where('category_id', $category_id);
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("product.product_id"); // Produces: GROUP BY product_id
			$lastcount = $lastcount-1;
			$stdcount = 10;
			if($lastcount >= 0)
			{
				$this->db->limit($stdcount, $lastcount);
			}else{
				$this->db->limit($limit=2000, 0);
			}
			$result2 = $this->db->get('product');

			#print $result20->num_rows() . " " . $result2->num_rows();exit;
			#print $this->db->last_query();exit;

			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result2->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#return $result->result_array();
			
			$param01 = array();
			
			$y0=1;
			foreach($result1->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param01['srno']=$y0;
			  $val0['srn0'] = $y0;
			  $param01[$x0]=$val0;
			  $y0++;
			}
			#print_r($param0);
			#exit;
			
			$param02 = array();
			
			$y1=1;
			foreach($result10->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y1;
			  $param02[$x0]=$val0;
			  $y1++;
			}
			#print_r($param0);
			#exit;
			
			$darry = array();
			$darry['pcount'] =$result20->num_rows();
			$darry['lastcount'] =$lastcount+$stdcount+1;
			$darry['parameter'] = $param01;
			$darry['displayfield'] = $param02;
			

			#$darry['parameter'] = $result1->result_array();
			#$darry['products'] = $result2->result_array();
			#print  print_r($result2->result_array());exit;
			
			#print $result1->result_array()[0]['related'];exit;
			$firstpara = @$result1->result_array()[0]['related'];
			
			/*
			if(empty($firstpara))
			{
				$result3 = $this->db->query("SELECT distinct product_id FROM `product` WHERE `category_id` = $category_id ");
			}
			else{				
			$result3 = $this->db->query("SELECT distinct $firstpara FROM `product` WHERE `category_id` = $category_id order by $firstpara");
			}
			*/
			
			$ord = $cond = '';	
			if(!empty($firstpara))
			{
				if($firstpara == 'series_search')
				{
					$result3 = $this->db->query("SELECT distinct series_name series_search FROM `product_series` 
					inner join series on series.series_id = product_series.series_id 
					inner join product on product.product_id = product_series.product_id
					WHERE product.category_id=$category_id $cond order by series_name");

				  $series_name = '';
				  foreach($result3->result() as $r2)
				  {
					  $series_name .= "<br />" . $r2->series_search;
				  }
				  $series_name = substr($series_name,6);					
				}
				else{
					$ord = "order by $firstpara";
					$result3 = $this->db->query("SELECT distinct $firstpara FROM `product` WHERE `category_id` = $category_id $cond $ord");
				}
				#print $this->db->last_query();exit;
				//print_r($result3->result_array());
				
				$farr2 = array();
					$ik=0;
				foreach( $result3->result_array() as $row ) {
					#print_r($row);
					foreach( $row as $key => $val ) {
						#print "ik: $ik <br>\n";
						#print_r($row);
					//$key => $val
						#print "key: $key <br>\n";
						#print "val: $val <br>\n";
						$farr2[$ik][$key] = base64_encode($val);
					}
					//
					$ik++;					
				}
				
				#print_r($farr2);
				#exit;
				#$darry['firstval'] = $result3->result_array();
				$darry['firstval'] = $farr2;
			}else{
				$darry['firstval'] = array();
				$result3 = $this->db->query("SELECT distinct eve_part_no FROM `product` WHERE `category_id` = $category_id $cond $ord");
				#print "hi $firstpara ok";
			}			
			#print $this->db->last_query();exit;
			
			#$darry['firstval'] = $result3->result_array();
			$firstval = array();
			foreach($result3->result_array() as $fr)
			{
				#print_r($fr);exit;
				foreach($fr as $key => $value)
				{
					#print "key: $key * value: $value <br>\n";
					$firstval[][$key] = base64_encode($value);
				}
			}
			$darry['firstval'] = $firstval;
			#print_r($darry['firstval']);exit;
				
			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$counter = 0;
			$xarry = array();
			foreach($result2->result_array() as $x => $val) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  $product_id = $val['product_id'];
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $product_id = $val['product_id'];
			  $q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
			  $current_stock = @$q0[0]->current_stock;
			  if(empty($current_stock)) $current_stock =0;
			  #print $current_stock;exit;
			  $val['stock'] = $current_stock;

			  $result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
			  #print $this->db->last_query();
			  $series_name = '';
			  foreach($result4->result() as $r2)
			  {
				  $series_name .= ", " . $r2->series_name;
			  }
			  $series_name = substr($series_name,2);
			  #print $series_name;exit;
  			  #$val['series_search'] = $val['series_name'];			  
			  $val['series_search'] = $series_name;
			  $xarry[$x]=$val;

			}
			#print_r($xarry);exit;
			/**/
			$darry['products'] =$xarry;
			return $darry;
		}	
	}
	
	public function product_category_value($data)
	{
		$response = array();
        $category_id = $data['category_id'];
        #$field = $data['field'];
        #$val = $data['val'];
        $par = $data['par'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			$par2 = json_decode($par, true);
			#print_r($par2);exit;
			
			#print sizeof($par2);exit;
			$z = sizeof($par2)-1;
			#print "z: $z<br>\n";
			#$field = $par2[$z];
			
			$field = $val = '';
			$z1=0;
			foreach($par2 as $x1 => $val1)
			{
				#print $x1 . " ** " . $val1;exit;
				#print $z1 . " ** " . $z . "<br>\n";
				if($z1 == $z)
				{
					$field = $x1;
					$val = $val1;
				}
				$z1++;
			}
			#print "field: $field ** val: $val<br>\n";
			
			$limit =1000;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$result1 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and search_dimension ='yes' GROUP BY parameter_name ORDER BY advance_flag,`parameter_order`, `parameter_name` ");

			$result10 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and display ='yes' GROUP BY parameter_name ORDER BY `display_order`, `parameter_name` limit 100 ");

			$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			
			
			$this->db->order_by('eve_part_no');
			$this->db->where('category_id', $category_id);
			#$this->db->where("$field", $val);
			$pid =array();
			
			foreach($par2 as $x1 => $val1)
			{
				#print $x1 . " ** " . $val1 ."\n";//exit;
				#print $z1 . " ** " . $z . "<br>\n";
				if($x1 == 'series_search')
				{
					#print "hi";exit;
					$val22 = base64_decode($val1);
					$result30 = $this->db->query("SELECT distinct product.product_id FROM `product_series` 
					inner join series on series.series_id = product_series.series_id 
					inner join product on product.product_id = product_series.product_id
					WHERE product.category_id=$category_id and series_name='$val22' ");
					#print $this->db->last_query();exit;
				  #$pid = '';
				  foreach($result30->result() as $r2)
				  {
					  $pid[]= $r2->product_id;
				  }
				 # $pid = substr($pid,2);
				}else{
					$b2 = base64_decode($val1);
					if(!empty($b2))
					{
						$this->db->where("$x1", $b2);
					}
				}
			}
			#print_r($pid);exit;
			if(!empty($pid))
			{
				$this->db->where_in('product_id', $pid);
			}
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result2 = $this->db->get('product', $limit);
			#print $this->db->last_query();exit;
			
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result2->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$param0 = array();
			
			$y0=1;
			foreach($result1->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y0;
			  $param0[$x0]=$val0;
			  $y0++;
			}
			#print_r($param0);
			#exit;
			
			$param02 = array();
			
			$y1=1;
			foreach($result10->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y1;
			  $param02[$x0]=$val0;
			  $y1++;
			}
			
			$darry = array();
			$darry['parameter'] = $param0;
			$darry['displayfield'] = $param02;
			
			#$darry['parameter'] = $result1->result_array();
			#$darry['products'] = $result2->result_array();
			#print  print_r($result2->result_array());exit;
			
			$firstpara = '';
			$x = $y = 0;
			$pp = array();
			foreach($result1->result_array() as $aa)
			{
				$f2 = $aa['related'];
				#print $f2 . "<br>\n";
				
				if($f2 == $field)
				{
					#print "found <br>";
					$y = $x;
				}
				$pp[$x] = $f2;	
				$x++;
			}
			#print "x: $x * y: $y <br>";
			#print_r($pp);
			$firstpara = @$result1->result_array()[$y+1]['related'];
			#print $firstpara;exit;
			
			$join = '';
			$cond = '';
			foreach($par2 as $x1 => $val1)
			{
				$val1 = base64_decode($val1);
				#print $x1 . " ** " . $val1;exit;
				#print $z1 . " ** " . $z . "<br>\n";
				
				if($x1 == 'series_search')
				{
					#print "series found";exit;
					
					$join = "inner join product_series on product.product_id = product_series.product_id
						  inner join series on series.series_id = product_series.series_id";
					$cond .= " and `series_name` = '$val1'";
				}else{
				
					$cond .= " and `$x1` = '$val1'";
				}
				
			}
			#$cond = substr($cond,1);
			#print $cond;exit;
			#print "hi1 $firstpara";exit;

			$ord = '';
			if(!empty($firstpara))
			{
				if($firstpara == 'series_search')
				{
					$result3 = $this->db->query("SELECT distinct series_name series_search FROM `product_series` 
					inner join series on series.series_id = product_series.series_id 
					inner join product on product.product_id = product_series.product_id
					WHERE product.category_id=$category_id $cond order by series_name");

				  $series_name = '';
				  foreach($result3->result() as $r2)
				  {
					  $series_name .= "<br />" . $r2->series_search;
				  }
				  $series_name = substr($series_name,6);					
				}
				else{
					$ord = "order by $firstpara";
					$result3 = $this->db->query("SELECT distinct $firstpara FROM `product` $join WHERE `category_id` = $category_id $cond $ord");
				}
				#print $this->db->last_query();exit;
				//print_r($result3->result_array());
				
				$farr2 = array();
					$ik=0;
				foreach( $result3->result_array() as $row ) {
					#print_r($row);
					foreach( $row as $key => $val ) {
						#print "ik: $ik <br>\n";
						#print_r($row);
					//$key => $val
						#print "key: $key <br>\n";
						#print "val: $val <br>\n";
						#$farr2[$ik][$key] = base64_encode($val);
						$farr2[$ik] = base64_encode($val);
					}
					//
					$ik++;					
				}
				
				#print_r($farr2);
				#exit;
				#$darry['firstval'] = $result3->result_array();
				$darry['firstval'] = $farr2;
			}else{
				$darry['firstval'] = array();
			}
			
			#print_r($darry['firstval']);exit;
			/*
			$firstval = array();
			foreach($result3->result_array() as $fr)
			{
				#print_r($fr);exit;
				foreach($fr as $key => $value)
				{
					#print "key: $key * value: $value <br>\n";
					$firstval[][$key] = base64_encode($value);
				}
			}
			$darry['firstval'] = $firstval;
			*/
			
			#print_r($darry['firstval']);exit;
			#print $this->db->last_query();exit;

			$query= $this->Api_model->_usersessioncheck($session, $mobile);
			#print_r($query);exit;
			$user_id = $query->result()[0]->user_id;
			$discount = $query->result()[0]->discount;

			$xarry = array();
			foreach($result2->result_array() as $x => $val) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  $product_id = $val['product_id'];
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }

			  $product_id = $val['product_id'];
				$q0 = $this->db->query("select * from tbl_inventory_master where fk_product_id='$product_id' and user_id='$user_id' limit 1 ")->result();
				$current_stock = @$q0[0]->current_stock;
				if(empty($current_stock)) $current_stock =0;
				#print $current_stock;exit;
				$val['stock'] = $current_stock;

			  $result4 = $this->db->query("SELECT * FROM `product_series` inner join series on series.series_id = product_series.series_id WHERE product_id = $product_id");
			  #print $this->db->last_query();
			  $series_name = '';
			  foreach($result4->result() as $r2)
			  {
				  $series_name .= "<br />" . $r2->series_name;
			  }
			  $series_name = substr($series_name,6);
			  #print $series_name;exit;
  			  #$val['series_search'] = $val['series_name'];			  
			  $val['series_search'] = $series_name;			  
			  $xarry[$x]=$val;
			}
			#print_r($xarry);exit;
			/**/
			$darry['products'] =$xarry;
			return $darry;
		}	
	}	
		
	public function product_category_par2($data)
	{
		
		$response = array();
        $category_id = $data['category_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$result1 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and search_dimension ='yes' GROUP BY parameter_name ORDER BY `parameter_order`, `parameter_name` ");
			
			$result10 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and display ='yes' GROUP BY parameter_name ORDER BY `parameter_order`, `parameter_name` limit 100 ");
			#print $this->db->last_query();exit;

			$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			
			
			#$this->db->group_by('parameter_name');
			$this->db->order_by('eve_part_no');
			$this->db->where('category_id', $category_id);
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result2 = $this->db->get('product', $limit);
			#print $this->db->last_query();exit;
			
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result2->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$param01 = array();
			
			$y0=1;
			foreach($result1->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y0;
			  $param01[$x0]=$val0;
			  $y0++;
			}			
			
			$param02 = array();
			
			$y1=1;
			foreach($result10->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y1;
			  $param02[$x0]=$val0;
			  $y1++;
			}
			#print_r($param0);
			#exit;
			
			$darry = array();
			$darry['parameter'] = $param01;
			$darry['displayfield'] = $param02;
			#$darry['parameter'] = $result1->result_array();
			#$darry['products'] = $result2->result_array();
			#print  print_r($result2->result_array());exit;
			
			#print $result1->result_array()[0]['related'];exit;
			#$firstpara = $result1->result_array()[0]['related'];
			
			#$result3 = $this->db->query("SELECT distinct $firstpara FROM `product` WHERE `category_id` = $category_id order by $firstpara");
			
			$firstval = array();
			foreach($result1->result_array() as $para)
			{
				$firstpara = $para['related'];
				$result3 = $this->db->query("SELECT distinct $firstpara FROM `product` WHERE `category_id` = $category_id order by $firstpara");
				
				$k2 = array();
				foreach($result3->result_array() as $k1)
				{
					#print $k1["$firstpara"];exit;
					$k2[] = $k1["$firstpara"];
				}
				$firstval["$firstpara"] = $k2;
				#print_r($firstval["$firstpara"]);exit;
				#print $this->db->last_query() . "<hr>";//exit;			
			}
			#print_r($firstval);
			#exit;
			
			#$darry['firstval'] = $result3->result_array();
			$darry['firstval'] = $firstval;
				
			$xarry = array();
			foreach($result2->result_array() as $x => $val) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $xarry[$x]=$val;
			}
			#print_r($xarry);exit;
			/**/
			$darry['products'] =$xarry;
			return $darry;
		}	
	}
	
	public function product_category_value2($data)
	{
		
		$response = array();
        $category_id = $data['category_id'];
        #$field = $data['field'];
        #$val = $data['val'];
        $par = $data['par'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			$par2 = json_decode($par, true);
			#print_r($par2);exit;
			
			#print sizeof($par2);exit;
			$z = sizeof($par2)-1;
			#print "z: $z<br>\n";
			#$field = $par2[$z];
			
			$field = $val = '';
			$z1=0;
			foreach($par2 as $x1 => $val1)
			{
				#print $x1 . " ** " . $val1;exit;
				#print $z1 . " ** " . $z . "<br>\n";
				if($z1 == $z)
				{
					$field = $x1;
					$val = $val1;
				}
				$z1++;
			}
			#print "field: $field ** val: $val<br>\n";
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$result1 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and search_dimension ='yes' GROUP BY parameter_name ORDER BY `parameter_order`, `parameter_name` ");
			
			$result10 = $this->db->query("SELECT * FROM `parameter_search` WHERE `category_id` = $category_id and display ='yes' GROUP BY parameter_name ORDER BY `parameter_order`, `parameter_name` limit 100 ");

			$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			
			
			$this->db->order_by('eve_part_no');
			$this->db->where('category_id', $category_id);
			#$this->db->where("$field", $val);
			
			foreach($par2 as $x1 => $val1)
			{
				#print $x1 . " ** " . $val1;exit;
				#print $z1 . " ** " . $z . "<br>\n";
				$this->db->where("$x1", $val1);
			}
			
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result2 = $this->db->get('product', $limit);
			
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result2->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$param01 = array();
			
			$y0=1;
			foreach($result1->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y0;
			  $param01[$x0]=$val0;
			  $y0++;
			}			
			
			$param02 = array();
			
			$y1=1;
			foreach($result10->result_array() as $x0 => $val0) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  #$img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  
			  #print_r($val0);exit;
			  #$param0['srno']=$y0;
			  $val0['srn0'] = $y1;
			  $param02[$x0]=$val0;
			  $y1++;
			}
			#print_r($param0);
			#exit;
			
			$darry = array();
			$darry['parameter'] = $param01;
			$darry['displayfield'] = $param02;
			#$darry['parameter'] = $result1->result_array();
			#$darry['products'] = $result2->result_array();
			#print  print_r($result2->result_array());exit;
			
			$firstpara = '';
			$x = $y = 0;
			$pp = array();
			foreach($result1->result_array() as $aa)
			{
				$f2 = $aa['related'];
				#print $f2 . "<br>\n";
				
				if($f2 == $field)
				{
					#print "found <br>";
					$y = $x;
				}
				$pp[$x] = $f2;	
				$x++;
			}
			#print "x: $x * y: $y <br>";
			#print_r($pp);
			$firstpara = @$result1->result_array()[$y+1]['related'];
			#print $firstpara;exit;
			
			$cond = '';
			foreach($par2 as $x1 => $val1)
			{
				#print $x1 . " ** " . $val1;exit;
				#print $z1 . " ** " . $z . "<br>\n";
				$cond .= " and `$x1` = '$val1'";
			}
			#$cond = substr($cond,1);
			#print $cond;exit;
			
			$products = array();
			$ord = '';
			#if(!empty($firstpara))
			#{
				$ord = "order by $firstpara";
				$result4 = $this->db->query("SELECT * FROM `product` WHERE `category_id` = $category_id $cond ");
				#print "hi";exit;
				#print $this->db->last_query();exit;
				$products = $result4->result_array();
				#$darry['firstval'] = $result3->result_array();
			#}else{
				#$darry['firstval'] = array();
			#}
			
			$firstval = array();
			foreach($result1->result_array() as $para)
			{
				$firstpara = $para['related'];
				$result3 = $this->db->query("SELECT distinct $firstpara FROM `product` WHERE `category_id` = $category_id order by $firstpara");
				
				$k2 = array();
				foreach($result3->result_array() as $k1)
				{
					#print $k1["$firstpara"];exit;
					$k2[] = $k1["$firstpara"];
				}
				$firstval["$firstpara"] = $k2;
				#print_r($firstval["$firstpara"]);exit;
				#print $this->db->last_query() . "<hr>";//exit;			
			}
			#print_r($firstval);
			#exit;
			
			#$darry['firstval'] = $result3->result_array();
			$darry['firstval'] = $firstval;
			
			#print $this->db->last_query();exit;
				
			$xarry = array();
			foreach($products as $x => $val) {
			  #echo "$x = $val<br>";exit;
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['profile_img'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['profile_img'] = $img2;
			  }
			  $xarry[$x]=$val;
			}
			#print_r($xarry);exit;
			/**/
			$darry['products'] =$xarry;
			return $darry;
		}	
	}	
	
	public function banner_value($data)
	{
		
		$response = array();
        $type = $data['type'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('*');
			#$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('page', $type);
			$this->db->where('status', '1');
			$this->db->order_by('order');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('banner', $limit);
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $img = $val['image'];
			  #print "img: $img <br>\n";
			  if(!empty($img))
			  {
				  $img2 = "https://indoeverestecats.com/uploads/admin/images/user/" . $img;
				  $val['image'] = $img2;
			  }
			  $darry[$x]=$val;
			}
			#print_r($darry);
			#exit;
			return $darry;
			
		}	
	}
	
	public function series($data)
	{
		
		$response = array();
        #$model_id = $data['model_id'];
        $mobile = $data['mobile'];
        $session = $data['session'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($session, $mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			
			$limit =100;
			#$this->db->order_by('franchisee_master.locationname', 'ASC');

			#$this->db->select('*');
			#$this->db->join('model_product', 'model_product.product_id=product.product_id', 'inner');
			#$this->db->join('model', 'model.model_id=model_product.model_id', 'inner');
			#$this->db->join('vehicle_make', 'vehicle_make.vm_id=model.fk_vm_id', 'inner');
			
			
			#$this->db->order_by('eve_part_no');
			#$this->db->where('isActive', '1');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			#$result = $this->db->get('product', $limit);
			$result = $this->db->query("select * from tbl_series_category where status='1' order by series_category_sequence,series_category_name ");
			//$result2 = $this->db->query("select * from series order by series_sequence,series_name ");
			$datec = date("Y-m-d H:i:s");
			#print $result->num_rows();exit;

			if($result->num_rows() ==1)//found
			{
				//$this->UpdateData('user', array('password' => $password,'otp_datec'=>$datec), array('phone' => $mobile,'user_type' => 2,'isActive' =>'1'));
			}
			#print $this->db->last_query();exit;
			#return $result->result_array();
			
			$darry = array();
			#$darry['category'] = $result->result_array();
			
			foreach($result->result_array() as $x => $val) {
			  #echo "$x = $val<br>";
			  #print_r($val['product_id']);
			  #print " x ";
			  $val['level'] = "1";
			  
			  $series_category_name = $val['series_category_name'];
			  $val['series_name'] = $series_category_name;
			  unset($val['series_category_name']);

			  $category_series_id = $val['category_series_id'];
			  $val['series_id'] = $category_series_id;
			  unset($val['category_series_id']);

			  $series_category_sequence = $val['series_category_sequence'];
			  $val['series_sequence'] = $series_category_sequence;
			  unset($val['series_category_sequence']);
				
			  $val['fk_series_category_id']=0;
			  //$darry[$x]=$val;

				$q2 = "select * from series where fk_series_category_id=$category_series_id order by series_sequence,series_name ";
				#print "$q2 <br>";
			  $result2 = $this->db->query($q2);
			  $a2 = '';
			  foreach($result2->result_array() as $x2 => $val2) {
				$series_name = $val2['series_name'];
				$series_name = str_replace("\r\n","",$series_name);
				#echo "$x = $val<br>";
				#print_r($val['product_id']);
				#print " x ";
				$val2['level'] = "2";
				//$darry[$x2]=$val2;
				//array_push($darry, $val2);
				$a2 .= '<div style="color:#ffffff;font-weight: 500;font-size:12px;background-color: #1f2c55;padding-top: 10px;line-height: 15px;padding-bottom: 9px;border-radius: 5px;margin-bottom:5px;" onclick="get_model_by_series2('. "'" . $series_name . "'" . ')">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $series_name . '</div>';
			  }
			  $a2 = base64_encode($a2);
			  #print $a2;exit;

			  $val['ext_details']=$a2;
			  array_push($darry, $val);

			}
			/**/
			#print "<pre>";print_r($darry);
			#exit;
			
			return $darry;
		}	
	}
	
    public function getProductsCount($shopID, $lang)
    {

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products_translations.abbr', $lang);
        $this->db->where('products.for_id', NULL);
        
        $query = $this->db->select('products_translations.title')->get('products');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getProducts($shopID, $lang, $order, $page=0)
    {
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
        #print $this->db->last_query();exit;
        $result0 = $query->result_array();
        $search_count = $result0[0]['search_count'];
		#print $search_count;exit;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}else{
            $this->db->order_by("title","ASC");
        }

        if(empty($page))
        {
            $this->db->limit($search_count,0);     
        }else{
            $counter = $search_count * $page;
            $start = $search_count * ($page-1);
            $this->db->limit($search_count,$start);     
        }
        
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock as outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.p_uid, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res')->get('products');
        #print $this->db->last_query();exit;
        #return $query->result_array();
		
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			$parray[$pcount]['inttype'] = 1;
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['inttype'] = 3;
			}
			if(sizeof($buy_option)==0)
			{
				$var1 = $this->getProductsVariant($shopID, $product_id);
				//$varcount = sizeof($var1);
				if(sizeof($var1)>0)
				{
					$parray[$pcount]['inttype'] = 2;
				}
			}
			
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			
			#print count($parray[$pcount]['buy_option']);exit;
			if(count($parray[$pcount]['variant'])>0 || count($parray[$pcount]['buy_option'])>0 || count($parray[$pcount]['attribute'])>0)
			{
				$complex =1;
			}else{
				$complex =0;
			}
			$parray[$pcount]['complex'] = $complex;
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;				
    }
	
    public function getProductsSearchCount($shopID, $lang, $keyword)
    {

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products_translations.abbr', $lang);
        if(!empty($keyword))
        {
            #$this->db->group_start() 
            $this->db->like('products_translations.title', $keyword);
            #$this->db->or_like('products_translations.description', $keyword);
            #$this->db->group_end(); 
        }

        $query = $this->db->select('products_translations.title')->get('products');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getProductsSearch($shopID, $lang, $keyword, $order, $page=0)
    {
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
        #print $this->db->last_query();exit;
        $result0 = $query->result_array();
        $search_count = $result0[0]['search_count'];

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($keyword))
		{
			#$this->db->group_start() 
			$this->db->like('products_translations.title', $keyword);
			#$this->db->or_like('products_translations.description', $keyword);
			#$this->db->group_end(); 
		}
		if(!empty($order))
        {
            $p2 = explode("-", $order);
            $this->db->order_by($p2[1], $p2[0]);
        }else{
            $this->db->order_by("title","ASC");
        }

        if(empty($page))
        {
            $this->db->limit($search_count,0);     
        }else{
            $counter = $search_count * $page;
            $start = $search_count * ($page-1);
            $this->db->limit($search_count,$start);     
        }

        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock as outofstock,products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res,products.p_uid')->get('products');
        #return $query->result_array();
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			if(count($parray[$pcount]['variant'])>0 || count($parray[$pcount]['buy_option'])>0 || count($parray[$pcount]['attribute'])>0)
			{
				$complex =1;
			}else{
				$complex =0;
			}
			$parray[$pcount]['complex'] = $complex;
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;		
    }

    public function getProductsVariant($shopID, $pid)
    {
        #$this->db->select('products_translations.*');
        $this->db->select('products.id, title,title variant,price,old_price');
        $this->db->where('products.for_id', $pid);
        $this->db->where('products.shopID', $shopID);
        $this->db->join('products', 'products_translations.for_id = products.id', 'INNER');
        $query = $this->db->get('products_translations');
		#print $this->db->last_query();exit;
		
		#print_r($query->result_array());exit;
        return $query->result_array();
        #return $parray;
    }

    public function getProductsAttribute($shopID, $pid)
    {
        $this->db->select('product_attribute.att_id,attribute_name,	attribute_val,complusory');
        #$this->db->where('shop_attributes.for_id', $pid);
        $this->db->where('product_attribute.pid', $pid);
        $this->db->where('product_attribute.shopID', $shopID);
        $this->db->join('shop_attributes', 'shop_attributes.att_id = product_attribute.att_id', 'INNER');
        $query = $this->db->get('product_attribute');
		#print $this->db->last_query();exit;
		
		$pcount = 0;
		$attarray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $val2 = explode(",", $value);
			  $att = array();
			  foreach($val2 as $v2)
			  {
				$att[] = $v2;
			  }
			  if($key == 'attribute_val')
			  {
				$attarray[$pcount][$key] = $att;
			  }else{
				  $attarray[$pcount][$key] = $value;
			  }
			}

			$pcount++;
		}
		#print "<pre>";
		#print_r($attarray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $attarray;
    }
	
    public function getProductsRelated2($shopID, $pid)
    {
        $this->db->select('dest_pid');
        #$this->db->select('*');
        #$this->db->where('shop_attributes.for_id', $pid);
        $this->db->where('product_relation.source_pid', $pid);
        $this->db->where('product_relation.shopID', $shopID);
        $this->db->join('product_relation', 'product_relation.source_pid = products.id', 'INNER');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $query = $this->db->get('products');
		#print $this->db->last_query();exit;
		
		$pids = '';
		foreach($query->result() as $rr)
		{
			#print $rr->dest_pid;
			$pids .= ', ' . $rr->dest_pid;
		}
		$pids = substr($pids,2);
		//print $pids;
		//exit;
		$data5 = array();
		if(!empty($pids))
		{
			$this->db->select("products.id, title, price,old_price, weight,qty_res,skuid,	saccode, '1' minqty,'50' maxqty, '1' step	");
			#$this->db->select('*');
			#$this->db->where('shop_attributes.for_id', $pid);
			#$this->db->where('product_relation.source_pid', $pid);
			$this->db->where('products.shopID', $shopID);
			$this->db->where_in('products.id', $pids, FALSE);
			#$this->db->join('product_relation', 'product_relation.source_pid = products.id', 'INNER');
			$this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
			$query2 = $this->db->get('products');
			
			#print $this->db->last_query();exit;
			$ii=0;
			foreach($query2->result_array() as $row5)
			{
				$id = $row5['id'];
				#print $id . '<hr>';
				$data5[$ii]['id'] = $id;
				$data5[$ii]['title'] = $row5['title'];
				$q5 = "select products.id id2, products.*, products_translations.* from products inner join products_translations on products.id = products_translations.for_id where products.for_id =$id ";
				$row6 = $this->db->query($q5);
				
				#print $this->db->last_query();exit;
				$inarry = array();
				$jj=0;
				$title = $row5['title'];
				foreach($row6->result_array() as $row6)
				{
					$title2 = $row6['title'];
					$title2 = str_replace($title,'',$title2);
					$title2 = str_replace("(",'', $title2);
					$title2 = str_replace(")",'', $title2);
					$title2 = trim($title2);
					#print $row6['id'] . ' ' . $row6['title'] . ' ' . $row6['price'] . ' ' .$row6['old_price'] . '<br>';
					$inarry[$jj]['id'] = $row6['id2'];
					$inarry[$jj]['title'] = $title2;
					$inarry[$jj]['price'] = $row6['price'];
					$inarry[$jj]['old_price'] = $row6['old_price'];
					$jj++;
				}
				$data5[$ii]['options'] = $inarry;
				$ii++;
			}
			
			#print "<pre>";
			#print_r($data5);
			#exit;
			#print "<pre>";
			#print_r($attarray);exit;
			#print_r($query->result_array());exit;
			#return $query2->result_array();
			return $data5;
		}else{
			return $query->result_array();
		}
        #return $attarray;
    }
	
    public function getProductsFeatured($shopID, $lang, $order)
    {
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.featured', 'Y');
        $this->db->where('products.for_id', NULL);
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id,products.p_uid, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock,  products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res')->get('products');
		#print $this->db->last_query();exit;
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;
    }
	
    public function getCategoryDetails($shopID, $categoryID, $lang, $order)
    {
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'inner');
        $this->db->where('shop_categories.shopID', $shopID);
        #$this->db->where('products.shop_categorie', $categoryID);
        $this->db->where('shop_categories.cat_url', $categoryID);
        #$this->db->where('shop_categories_translations.name', $categoryID);
        #$this->db->where('products.featured', 'Y');
        $this->db->where('shop_categories_translations.abbr', $lang);
        $query = $this->db->select('name, cat_url,abbr')->get('shop_categories');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getProductsCategoryCount($shopID, $categoryID, $lang, $page=0)
    {

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products.visibility', 1);
		
		$this->db->group_start(); //this will start grouping
        $this->db->or_where('shop_categories.cat_url', $categoryID);
        $this->db->or_where('shop_categories.c_uid', $categoryID);
		$this->db->group_end(); //this will end grouping

        $this->db->where('products_translations.abbr', $lang);

        $query = $this->db->select('products_translations.title')->get('products');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getProductsCategory($shopID, $categoryID, $lang, $order,$page=0)
    {
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
        #print $this->db->last_query();exit;
        $result0 = $query->result_array();
        $search_count = $result0[0]['search_count'];

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products.visibility', 1);
        #$this->db->where('products.shop_categorie', $categoryID);
		$this->db->group_start(); //this will start grouping
        $this->db->or_where('shop_categories.cat_url', $categoryID);
        $this->db->or_where('shop_categories.c_uid', $categoryID);
		$this->db->group_end(); //this will end grouping        #$this->db->where('shop_categories_translations.name', $categoryID);
        #$this->db->where('products.featured', 'Y');
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}else{
            $this->db->order_by("title","ASC");
        }
        if(empty($page))
        {
            $this->db->limit($search_count,0);     
        }else{
            $counter = $search_count * $page;
            $start = $search_count * ($page-1);
            $this->db->limit($search_count,$start);     
        }        
		
		#print "$search_count";exit;
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res,products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword,products.p_uid')->get('products');
		#print $this->db->last_query();exit;
        #return $query->result_array();
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;		
    }
	
    public function getProductsCategoryCount2($shopID, $categoryID, $lang, $page=0)
    {
		$cat1 = explode("~",$categoryID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
		
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        $this->db->where_in('shop_categories.id', $cat2,FALSE);
		$this->db->group_end(); //this will end grouping

        $this->db->where('products_translations.abbr', $lang);

        $query = $this->db->select('products_translations.title')->get('products');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getProductsCategory2($shopID, $categoryID, $lang, $order,$page=0)
    {
		$cat1 = explode("~",$categoryID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;
		
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
        #print $this->db->last_query();exit;
        $result0 = $query->result_array();
        $search_count = $result0[0]['search_count'];
		$search_count = 500;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        #$this->db->where('products.shop_categorie', $categoryID);
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        #$this->db->or_where('shop_categories.c_uid', $categoryID);
        $this->db->where_in('shop_categories.id', $cat2,FALSE);
		$this->db->group_end(); //this will end grouping        #$this->db->where('shop_categories_translations.name', $categoryID);
        #$this->db->where('products.featured', 'Y');
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}else{
            $this->db->order_by("title","ASC");
        }
        if(empty($page))
        {
            $this->db->limit($search_count,0);     
        }else{
            $counter = $search_count * $page;
            $start = $search_count * ($page-1);
            $this->db->limit($search_count,$start);     
        }        
		
		#print "$search_count";exit;
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale,products.outofstock outofstock,  products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res,products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword,products.p_uid')->get('products');
		#print $this->db->last_query();exit;
        #return $query->result_array();
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;		
    }

    public function getProductsOrder($shopID, $orderid, $lang, $order,$page=0)
    {
		$cat2 = 1;
		
		$this->db->join('orders', 'orders.orderid = order_details.orderid');
        $this->db->where('order_details.shopID', $shopID);
        $this->db->where('orders.o_uid', $orderid);
        #$this->db->where('shop_categories_translations.abbr', $lang);
        $this->db->order_by('pid', 'asc');
        $query = $this->db->select('*')->get('order_details');
        #print $this->db->last_query();exit;
        $result0 = $query->result();
		$pids = "";
		foreach($result0 as $rr)
		{
			#print $rr->pid . '<br>';
			$pids .= ", " . $rr->pid;
		}
		$pids = substr($pids,2);
		#print $pids;exit;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        #$this->db->where('products.shop_categorie', $categoryID);
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        #$this->db->or_where('shop_categories.c_uid', $categoryID);
        $this->db->where_in('products.id', $pids,FALSE);
		$this->db->group_end(); //this will end grouping        #$this->db->where('shop_categories_translations.name', $categoryID);
        #$this->db->where('products.featured', 'Y');
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}else{
            $this->db->order_by("title","ASC");
        }

		#print "$search_count";exit;
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res,products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword,products.p_uid')->get('products');
		#print $this->db->last_query();exit;
        #return $query->result_array();
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;		
    }


    public function getProductsCount2($shopID, $categoryID, $lang, $page=0)
    {
		$cat1 = explode("~",$categoryID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
		
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        $this->db->where_in('products.id', $cat2,FALSE);
		$this->db->group_end(); //this will end grouping

        $this->db->where('products_translations.abbr', $lang);

        $query = $this->db->select('products_translations.title')->get('products');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getProducts2($shopID, $categoryID, $lang, $order,$page=0)
    {
		$cat1 = explode("~",$categoryID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;
		
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
        #print $this->db->last_query();exit;
        $result0 = $query->result_array();
        $search_count = $result0[0]['search_count'];
		
		$search_count = 500;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        #$this->db->where('products.shop_categorie', $categoryID);
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        #$this->db->or_where('shop_categories.c_uid', $categoryID);
        $this->db->where_in('products.id', $cat2,FALSE);
		$this->db->group_end(); //this will end grouping        #$this->db->where('shop_categories_translations.name', $categoryID);
        #$this->db->where('products.featured', 'Y');
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}else{
            $this->db->order_by("title","ASC");
        }
        if(empty($page))
        {
            $this->db->limit($search_count,0);     
        }else{
            $counter = $search_count * $page;
            $start = $search_count * ($page-1);
            $this->db->limit($search_count,$start);     
        }        
		
		#print "$search_count";exit;
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res,products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword,products.p_uid')->get('products');
		#print $this->db->last_query();exit;
        #return $query->result_array();
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =0;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
        return $parray;		
    }


    public function getbannerdetailsCount2($shopID, $categoryID, $lang, $page=0)
    {
		$cat1 = explode("~",$categoryID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
		
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        $this->db->where_in('shop_categories.id', $cat2,FALSE);
		$this->db->group_end(); //this will end grouping

        $this->db->where('products_translations.abbr', $lang);

        $query = $this->db->select('products_translations.title')->get('products');
        print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function getbannerdetails2($shopID, $categoryID, $lang, $order,$page=0)
    {
		$cat1 = explode("~",$categoryID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;
		
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
        #print $this->db->last_query();exit;
        $result0 = $query->result_array();
        $search_count = $result0[0]['search_count'];

        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        #$this->db->where('products.shop_categorie', $categoryID);
		$this->db->group_start(); //this will start grouping
        #$this->db->or_where('shop_categories.cat_url', $categoryID);
        #$this->db->or_where('shop_categories.c_uid', $categoryID);
        $this->db->where_in('shop_categories.id', $cat2,FALSE);
		$this->db->group_end(); //this will end grouping        #$this->db->where('shop_categories_translations.name', $categoryID);
        #$this->db->where('products.featured', 'Y');
        $this->db->where('products_translations.abbr', $lang);
		if(!empty($order))
		{
			$p2 = explode("-", $order);
			$this->db->order_by($p2[1], $p2[0]);
		}else{
            $this->db->order_by("title","ASC");
        }
        if(empty($page))
        {
            $this->db->limit($search_count,0);     
        }else{
            $counter = $search_count * $page;
            $start = $search_count * ($page-1);
            $this->db->limit($search_count,$start);     
        }        
		
		#print "$search_count";exit;
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,featured,qty_res,products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword,products.p_uid')->get('products');
		#print $this->db->last_query();exit;
        return $query->result_array();
    }


	
    public function getProduct($shopID,$lang, $id)
    {
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products_translations.abbr', $lang);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products.id', $id);
        $this->db->limit(1);
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description,skuid,qty_res,featured,products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword')->get('products');
        #return $query->row_array();
		
		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =1;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
		//$parray = array_shift($parray);

        return $parray;		
    }
	
    public function getProductsUID($shopID,$lang, $uid)
    {
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products_translations.abbr', $lang);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.p_uid', $uid);
        $this->db->limit(1);
		$query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description, skuid,qty_res,featured, products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword')->get('products');		
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			#print $product_id . $buy_option;exit;
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =1;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
		$parray = array_shift($parray);
        return $parray;		
    }    
	
    public function getProductsUIDXX($shopID,$lang, $uid)
    {
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products_translations.abbr', $lang);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.p_uid', $uid);
        $this->db->limit(1);
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description, skuid,qty_res,featured, products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword')->get('products');
		#print $this->db->last_query();
        return $query->row_array();	
    }    

	public function getProductsurl($shopID,$lang, $url)
    {
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('shop_categories', 'shop_categories.id = products.shop_categorie', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        $this->db->where('products_translations.abbr', $lang);
        $this->db->where('products.shopID', $shopID);
        $this->db->where('products.for_id', NULL);
        $this->db->where('products.visibility', 1);
        $this->db->where('products.url', $url);
        $this->db->limit(1);
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.id as product_id, products.image as product_image, products.time as product_time_created, products.time_update as product_time_updated, products.visibility as sale, products.outofstock outofstock, products.shop_categorie as product_category, products.quantity as product_quantity_available, products.procurement as product_procurement, products.url as product_url, products.virtual_products, products.brand_id as product_brand_id, products.position as product_position , products_translations.title, products_translations.description, products_translations.price, products_translations.old_price, products_translations.basic_description, skuid,qty_res,featured, products.enquiryonly, shop_categories_translations.name catname, products.seo_title,products.seo_description,products.seo_keyword')->get('products');
		#print $this->db->last_query();
        #return $query->row_array();		

		#print "<pre>";
		$pcount = 0;
		$parray = array();
		foreach($query->result_array() as $pp)
		{
			#print_r($pp);exit;
			#print $pp['p_uid'];
			foreach($pp as $key => $value){
			  #echo $key . ' : ' . $value . "<br>\n";
			  $parray[$pcount][$key] = $value;
			  if($key == 'product_id') $product_id = $value;
			}
			#print $product_id;exit;
			$buy_option = $this->getProductsRelated2($shopID, $product_id);
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['buybutton'] =1;
			}else{
				$parray[$pcount]['buybutton'] =1;
			}
			#print sizeof($buy_option);exit;
			
			$parray[$pcount]['inttype'] = 1;
			if(sizeof($buy_option)>0)
			{
				$parray[$pcount]['inttype'] = 3;
			}
			if(sizeof($buy_option)==0)
			{
				$var1 = $this->getProductsVariant($shopID, $product_id);
				//$varcount = sizeof($var1);
				if(sizeof($var1)>0)
				{
					$parray[$pcount]['inttype'] = 2;
				}
			}
			$parray[$pcount]['gallery'] = $this->getProductGallery($shopID, $product_id);
			$parray[$pcount]['variant'] = $this->getProductsVariant($shopID, $product_id);
			$parray[$pcount]['buy_option'] = $buy_option;
			$parray[$pcount]['attribute'] = $this->getProductsAttribute($shopID, $product_id);
			
			#print count($parray[$pcount]['buy_option']);exit;
			if(count($parray[$pcount]['variant'])>0 || count($parray[$pcount]['buy_option'])>0 || count($parray[$pcount]['attribute'])>0)
			{
				$complex =1;
			}else{
				$complex =0;
			}
			$parray[$pcount]['complex'] = $complex;			
			$pcount++;
		}
		#print "<pre>";
		#print_r($parray);exit;
		#print_r($query->result_array());exit;
        #return $query->result_array();
		$parray = array_shift($parray);

        return $parray;		
    }    
	
    public function getProductGallery($shopID,$pid)
    {
        $this->db->where('products_images.shopID', $shopID);
        $this->db->where('products_images.pid', $pid);
        $this->db->limit(100);
        $this->db->order_by('forder', 'asc');
        $query = $this->db->select('filename,fdesc,forder')->get('products_images');
        #print $this->db->last_query();
       # print_r($query->result());exit;
        return $query->result();
    }
	
    public function setProduct($post)
    {
        if (!isset($post['brand_id'])) {
            $post['brand_id'] = null;
        }
        if (!isset($post['virtual_products'])) {
            $post['virtual_products'] = null;
        }
        $this->db->trans_begin();
        $i = 0;
        foreach ($_POST['translations'] as $translation) {
            if ($translation == MY_DEFAULT_LANGUAGE_ABBR) {
                $myTranslationNum = $i;
            }
            $i++;
        }
        if (!$this->db->insert('products', array(
                    'image' => $post['image'],
                    'shop_categorie' => $post['shop_categorie'],
                    'quantity' => $post['quantity'],
                    'in_slider' => $post['in_slider'],
                    'position' => $post['position'],
                    'virtual_products' => $post['virtual_products'],
                    'folder' => time(),
                    'brand_id' => $post['brand_id'],
                    'time' => time()
                ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        $id = $this->db->insert_id();

        $this->db->where('id', $id);
        if (!$this->db->update('products', array(
                    'url' => except_letters($_POST['title'][$myTranslationNum]) . '_' . $id
                ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        $this->setProductTranslation($post, $id);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    private function setProductTranslation($post, $id)
    {
        $i = 0;
        $current_trans = $this->getTranslations($id);
        foreach ($post['translations'] as $abbr) {
            $arr = array();
            $emergency_insert = false;
            if (!isset($current_trans[$abbr])) {
                $emergency_insert = true;
            }
            $post['title'][$i] = str_replace('"', "'", $post['title'][$i]);
            $post['price'][$i] = str_replace(' ', '', $post['price'][$i]);
            $post['price'][$i] = str_replace(',', '', $post['price'][$i]);
            $arr = array(
                'title' => $post['title'][$i],
                'basic_description' => $post['basic_description'][$i],
                'description' => $post['description'][$i],
                'price' => $post['price'][$i],
                'old_price' => $post['old_price'][$i],
                'abbr' => $abbr,
                'for_id' => $id
            );

            if (!$this->db->insert('products_translations', $arr)) {
                log_message('error', print_r($this->db->error(), true));
            }
            $i++;
        }
    }

    private function getTranslations($id)
    {
        $this->db->where('for_id', $id);
        $query = $this->db->get('products_translations');
        $arr = array();
        foreach ($query->result() as $row) {
            $arr[$row->abbr]['title'] = $row->title;
            $arr[$row->abbr]['basic_description'] = $row->basic_description;
            $arr[$row->abbr]['description'] = $row->description;
            $arr[$row->abbr]['price'] = $row->price;
            $arr[$row->abbr]['old_price'] = $row->old_price;
        }
        return $arr;
    }

    public function deleteProduct($id)
    {
        $this->db->trans_begin();
        $this->db->where('for_id', $id);
        if (!$this->db->delete('products_translations')) {
            log_message('error', print_r($this->db->error(), true));
        }

        $this->db->where('id', $id);
        if (!$this->db->delete('products')) {
            log_message('error', print_r($this->db->error(), true));
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }
    public function getPage($shopID, $id, $lang)
    {
        $this->db->join('textual_pages_tanslations', 'textual_pages_tanslations.for_id = active_pages.id', 'left');
        $this->db->join('seo_pages_translations', 'seo_pages_translations.page_type = active_pages.name', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('active_pages.shopID', $shopID);
        $this->db->where('active_pages.enabled', 1);
        $this->db->where('active_pages.name', $id);
        $this->db->where('active_pages.name != ', 'blog');
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('textual_pages_tanslations.menupos', 'asc');
        $query = $this->db->select('active_pages.id,textual_pages_tanslations.name name,active_pages.name page_url,topmenu,  footermenu,  menupos,textual_pages_tanslations.description,seo_pages_translations.title t1, seo_pages_translations.description d1')->get('active_pages');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

    public function page_general($shopID, $lang)
    {
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

		$this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder, domain')->get('shop_master');
		
		$domain = '';
		foreach($query->result_array() as $rr)
		{
			$domain = $rr['domain'];
			#print $domain;exit;
			
		}
        $this->db->join('textual_pages_tanslations', 'textual_pages_tanslations.for_id = active_pages.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('active_pages.shopID', $shopID);
        $this->db->where('active_pages.enabled', 1);
        $this->db->where('active_pages.name != ', 'blog');
        #$this->db->where('shop_categories_translations.abbr', $lang);

        $this->db->order_by('textual_pages_tanslations.menupos', 'asc');
        $query = $this->db->select("active_pages.id,textual_pages_tanslations.name name,concat('$domain','page/',active_pages.name) page_url,topmenu,  footermenu,  menupos")->get('active_pages');
        #print $this->db->last_query();exit;
        return $query->result_array();
    }

	public function shop_general($shopID, $lang)
	{
       # $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_master.shopID', $shopID);
        #$this->db->where('shop_categories_translations.abbr', $lang);

		$this->db->order_by('shopname', 'asc');
        $query = $this->db->select('shopname,support_mobile,support_emailid,op_hours,add1,add2,landmark,city,pincode,contactperson,geolocation,search_count,minorder')->get('shop_master');
		#print $this->db->last_query();exit;
        return $query->result_array();
	}
	
	public function shop_banner($shopID, $lang)
	{
        #$this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_banner.shopID', $shopID);
        $this->db->where('shop_banner.isActive', 'Y');
        #$this->db->where('shop_categories_translations.abbr', $lang);

		$this->db->order_by('position', 'asc');
        $query = $this->db->select('shop_banner.id, ban_type,banner_title, banner_image, external_link link')->get('shop_banner');
        #return $query->result_array();
		
		$response = array();
		foreach($query->result_array() as $rr)
		{
			#print_r($rr);//exit;
			
			$id = $rr['id'];
			$ban_type = $rr['ban_type'];
			#print $rr['ban_type'];exit;
			$rr['details'] = '';
			
			if($ban_type != 'external')
			{
				$this->db->where('shop_bannerdetails.for_id', $id);
				$this->db->order_by('id', 'asc');
				$query2 = $this->db->select('*')->get('shop_bannerdetails');
				
				$details = array();
				foreach($query2->result_array() as $rr2)
				{
					#print_r($rr2);//exit;
					$details[] = $rr2['detail_id'];
				}
				#print_r($details);exit;
				$details2 = implode("~", $details);
				$rr['details'] = $details2;
			}
			$response[] = $rr;
		}
		
		return $response;
		
	}
	
	public function shop_category($shopID, $lang)
	{
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_categories.shopID', $shopID);
        $this->db->where('shop_categories.isActive', 'Y');
        #$this->db->where('shop_categories.homepage', 'Y');
        $this->db->where('shop_categories_translations.abbr', $lang);

		$this->db->order_by('position, name', 'asc');
        $query = $this->db->select('shop_categories.id, name,abbr, cat_url,c_uid, cat_image, homepage')->get('shop_categories');
		#print $this->db->last_query();exit;
        return $query->result_array();
	}
	
	public function shop_categoryAll($shopID, $lang)
	{
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_categories.shopID', $shopID);
        $this->db->where('shop_categories.isActive', 'Y');
        #$this->db->where('shop_categories.homepage', 'Y');
        $this->db->where('shop_categories_translations.abbr', $lang);

		$this->db->order_by('position, name', 'asc');
        $query = $this->db->select('shop_categories.id, name,abbr, cat_url,c_uid, cat_image, homepage')->get('shop_categories');
		#print $this->db->last_query();exit;
        return $query->result_array();
	}	
	public function shop_banner2($shopID, $bannerID, $lang)
	{
		$cat1 = explode("~",$bannerID);
		
		#print_r($cat1);
		
		$cat2 = '';
		foreach($cat1 as $c)
		{
			#$cat2 .= ",'". $c . "'";
			$cat2 .= ",". $c . "";
		}
		$cat2 = substr($cat2,1);
		#print $cat2;exit;
		
        #$this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_banner.shopID', $shopID);
        $this->db->where('shop_banner.isActive', 'Y');
        $this->db->where_in('shop_banner.id', $cat2,FALSE);


		$this->db->order_by('position', 'asc');
        $query = $this->db->select('shop_banner.id, ban_type,banner_title, banner_image, external_link link')->get('shop_banner');
        #return $query->result_array();exit;
		
		$response = array();
		foreach($query->result_array() as $rr)
		{
			#print_r($rr);//exit;
			
			$id = $rr['id'];
			$ban_type = $rr['ban_type'];
			#print $rr['ban_type'];exit;
			$rr['details'] = '';
			
			if($ban_type != 'external')
			{
				$this->db->where('shop_bannerdetails.for_id', $id);
				$this->db->order_by('id', 'asc');
				$query2 = $this->db->select('*')->get('shop_bannerdetails');
				
				$details = array();
				foreach($query2->result_array() as $rr2)
				{
					#print_r($rr2);//exit;
					$details[] = $rr2['detail_id'];
				}
				#print_r($details);exit;
				$details2 = implode("~", $details);
				$rr['details'] = $details2;
			}
			$response[] = $rr;
		}		
		return $response;		
	}
	
	public function delivery_charge($shopID, $lang)
	{
        #$this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('delivery_charge.shopID', $shopID);
		#$this->db->where('shop_paymentoptions.isEnable', 'Y');
		$this->db->where('delivery_charge.isActive', 'Y');

		$this->db->order_by('del_charge', 'asc');
        $query = $this->db->select('de_id, del_desc,del_charge,isActive')->get('delivery_charge');
        return $query->result_array();
	}
	
	public function shop_paymode($shopID, $lang)
	{
        #$this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_paymentoptions.shopID', $shopID);
		#$this->db->where('shop_paymentoptions.isEnable', 'Y');
		$this->db->where('shop_paymentoptions.isshow', 'Y');

		$this->db->order_by('disp_ord', 'asc');
        $query = $this->db->select('payrid, payimage, paymenttype,paynotes, extracharge, isOffline,isshow,isEnable')->get('shop_paymentoptions');
        return $query->result_array();
	}
	
	public function shop_area($shopID, $lang)
	{
        $this->db->join('area_shop', 'area_shop.area_id = area_master.area_id', 'inner');
        #$this->db->where('products.visibility', 1);
        #$this->db->where('shop_paymentoptions.shopID', $shopID);
		#$this->db->where('shop_paymentoptions.isEnable', 'Y');
		#$this->db->where('shop_paymentoptions.isshow', 'Y');

		$this->db->order_by('pincode', 'asc');
        $query = $this->db->select('area_master.area_id, areaname, pincode')->get('area_master');
        return $query->result_array();
	}

	public function shop_featured($shopID, $lang)
	{
        $this->db->join('shop_categories_translations', 'shop_categories_translations.for_id = shop_categories.id', 'left');
        #$this->db->where('products.visibility', 1);
        $this->db->where('shop_categories.shopID', $shopID);
        $this->db->where('shop_categories_translations.abbr', $lang);

		$this->db->order_by('name', 'asc');
        $query = $this->db->select('shop_categories.id, name,abbr, cat_url,c_uid, cat_image')->get('shop_categories');
        return $query->result_array();
	}
	


	
    public function getMobile($shopID, $lang, $mobile)
    {
        $this->db->where('shop_master.shopID', $shopID);
        $query0 = $this->db->select('*')->get('shop_master');
		
		$shopname = '';
		foreach($query0->result_array() as $ss)
		{
			#print_r($ss['shopname']);exit;
			$shopname = $ss['shopname'];
		}
		#print $shopname;exit;
		
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('client_id,first_name')->get('client_master');
		
		if(sizeof($query->result_array()) == 0)
		{
			//print "new record generate otp";exit;
		}
		$datec = date("Y-m-d H:i:s");
		$validupto = date('Y-m-d H:i:s',strtotime($datec . ' +15 minutes'));

		$q = "select * from otp_history where shopID=$shopID and mobileno='$mobile' and validupto between '$datec' and '$validupto' order by otphid desc limit 1";
		$res = $this->db->query($q)->result();
		#print $this->db->last_query();exit;
		#print sizeof($res);
		$otp = rand(1111,9999);
        #$otp = 1234;
		if(sizeof($res) == 0)//new than otp insert
		{
			$data = array();
			$ip = $this->input->ip_address();
			$browser = $this->agent->browser().' '.$this->agent->version();
			$data['shopID'] = $shopID;
			$data['mobileno'] = $mobile;
			$data['otp'] = $otp;
			$data['used'] = 'N';
			$data['ip'] = $ip;
			$data['browser'] = $browser;
			$data['datec'] = $datec;
			$data['validupto'] = $validupto;
			#print_r($data);exit;
			
			$this->Api_model->setData('otp_history', $data);
			
		}else{
			$otp = $res[0]->otp;
		}
		
		$message = "Your $shopname otp is $otp ph $mobile";
		$server_flag = sendSMS($shopID, $mobile, $message);
		
		$data2 = array();
		$ip = $this->input->ip_address();
		$browser = $this->agent->browser().' '.$this->agent->version();
		$data2['shopID'] = $shopID;
		$data2['mobile'] = $mobile;
		$data2['gw'] = 1;
		$data2['message'] = $message;
		$data2['server_flag'] = $server_flag;
		$data2['datec'] = $datec;
		#print_r($data);exit;
		
		$this->Api_model->setData('sms_history', $data2);
		#print $otp;exit;
		#print $this->db->last_query();exit;
        return $query->result_array();
    }

	
    public function Registration($shopID, $lang, $mobile, $data)
    {
		#phpinfo();exit;
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');
		
		$otp = $data['otp'];
		$datec = $data['datec'];
		$validfrom = date('Y-m-d H:i:s',strtotime($datec . ' -15 minutes'));
		$validupto = date("Y-m-d H:i:s");;
		unset($data['otp']);
		
		$cadda = array();
		#$cadda['is_new_addr'] = $data['is_new_addr'];
		$cadda['contactperson'] = $data['contactperson'];
		$cadda['address'] = $data['address'];
		$cadda['address2'] = $data['address2'];
		$cadda['state'] = @$data['state'];
		$cadda['city'] = $data['city'];
		$cadda['pincode'] = $data['pincode'];
		#$cadda['referral'] = $data['referral'];
		$cadda['landmark'] = $data['landmark'];
		$cadda['contactno'] = $data['contactno'];
		$cadda['shopID'] = $data['shopID'];
		
		unset($data['is_new_addr']);
		unset($data['contactperson']);
		unset($data['address']);
		unset($data['address2']);
		unset($data['city']);
		unset($data['state']);
		unset($data['pincode']);
		unset($data['landmark']);
		unset($data['contactno']);

		#print_r($data);exit;
		if(sizeof($query->result_array()) == 0)//new record
		{
			#phpinfo();exit;
			#print "shopID: $shopID * mobile: $mobile * otp: $otp * validupto: $validupto";exit;
			$q = "select * from otp_history where shopID=$shopID and mobileno='$mobile' and otp='$otp' and validupto >= '$validupto' order by otphid desc limit 1";
			#print $q;exit;
			$res = $this->db->query($q)->result();
			#print $this->db->last_query();exit;
			#print sizeof($res);exit;
			if(sizeof($res) == 0)//otp not matched
			{
				return $query->result_array();
			}else{//otp matched -> insert set session login
				#print "hello";exit;
				
				$this->Api_model->setData('client_master', $data);
				$response = array();
				$response['id'] = $this->db->insert_id();
				$response['name'] = $data['first_name'] . ' ' . $data['last_name'];
				$response['emailid'] = $data['emailid'];
				$response['mobile'] = $data['mobile'];
				#$response['id'] = 100;
				
				#print_r($data);exit;
				
				$client_id =$response['id'];
				
				$data1 = array();
				
				$data1['session'] = $data['session'];
				$data1['ip_addr'] = $data['ip_addr'];
				$data1['datec'] = $data['datec'];
				$data1['os'] = @$data['os'];
				$data1['browser'] = @$data['browser'];
				$data1['client_id'] = $client_id;
				$data1['mobile'] = $data['mobile'];
				$data1['shopID'] = $data['shopID'];
				/**/
						
				#print_r($data1);exit;
				$this->Api_model->setData('client_session', $data1);
				
				//address
				$cadda['client_id'] = $client_id;
				$this->Api_model->setData('client_address', $cadda);
				
				
				//print_r($response);exit;
				return $response;
			}
		}
		#print $otp;exit;
		#print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function Login($shopID, $lang, $mobile, $data)
    {
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');

		$otp = $data['otp'];
		$datec = $data['datec'];
		$validfrom = date('Y-m-d H:i:s',strtotime($datec . ' -15 minutes'));
		$validupto = date("Y-m-d H:i:s");;
		unset($data['otp']);
		if(sizeof($query->result_array()) != 0)//existing record
		{
			$q = "select * from otp_history where shopID=$shopID and mobileno='$mobile' and otp='$otp' and validupto >= '$validupto' order by otphid desc limit 1";
			$res = $this->db->query($q)->result();
			#print $this->db->last_query();exit;
			#print sizeof($res);exit;
			if(sizeof($res) == 0)//otp not matched
			{
				#return $query->result_array();
                return '';
			}else{//otp matched -> insert set session login
				#print_r($query);exit;
				#print_r($query->result());exit;
				$client_id = @$query->result()[0]->client_id;
				$first_name = @$query->result()[0]->first_name;
				$last_name = @$query->result()[0]->last_name;
				$emailid = @$query->result()[0]->emailid;
				#print $client_id;exit;
				$data1 = array();
				$data1['session'] = $data['session'];
				$data1['ip_addr'] = $data['ip_addr'];
				$data1['datec'] = $data['datec'];
				$data1['os'] = $data['os'];
				$data1['browser'] = $data['browser'];
				$data1['client_id'] = $client_id;
				$data1['mobile'] = $data['mobile'];
				$data1['shopID'] = $data['shopID'];
				
				unset($data['shopID']);
				unset($data['mobile']);
				unset($data['ip_addr']);
				unset($data['browser']);
				unset($data['os']);
				unset($data['datec']);
				
				$this->Api_model->UpdateData('client_master', $data, array('shopID' => $shopID, 'mobile' => $mobile));
				

				$this->Api_model->setData('client_session', $data1);
				
				$response = array();
				#$response['id'] = 1;
				$response['id'] = $client_id;
				$response['name'] = $first_name . ' ' . $last_name;
				$response['emailid'] = $emailid;
				$response['mobile'] = $mobile;
				
				return $response;
			}
		}
		#print $otp;exit;
		#print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function Logout($shopID, $lang, $mobile, $data)
    {
		$session = $data['session'];
        $this->db->where('client_master.session', $session);
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');

		#$otp = $data['otp'];
		$datec = $data['datec'];
		$validfrom = date('Y-m-d H:i:s',strtotime($datec . ' -15 minutes'));
		$validupto = date("Y-m-d H:i:s");;
		unset($data['otp']);
		if(sizeof($query->result_array()) != 0)//existing record
		{
			unset($data['shopID']);
			unset($data['mobile']);
			unset($data['ip_addr']);
			unset($data['browser']);
			unset($data['datec']);
			$data['session'] = NULL;
			
			$this->Api_model->UpdateData('client_master', $data, array('shopID' => $shopID, 'mobile' => $mobile));
			$response = array();
			$response['id'] = 1;
			return $response;
			
		}
		#print $otp;exit;
		#print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function AddressList($shopID, $lang, $data)
    {
		/*
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $this->db->where('client_master.session', $session);
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');
		#print $this->db->last_query();exit;
		
		if(sizeof($query->result_array()) != 0)//session found
		*/
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			$abc = $query->result_array();
			$client_id = $abc[0]['client_id'];
			$this->db->where('client_address.client_id', $client_id);
			#$this->db->where('client_address.mobile', $mobile);
			$this->db->where('client_address.shopID', $shopID);
			$query = $this->db->select('*')->get('client_address');
			#print $this->db->last_query();exit;
			return $query->result_array();
		}else{
			return $response;
		}
		#print $otp;exit;
		#print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function AddressAdd($shopID, $lang, $data)
    {
        /*
		$response = array();
        $session = $data['session'];
        $mobile = $data['mobile'];
        $this->db->where('client_master.session', $session);
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');
        #print $this->db->last_query();exit;
        */
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
        if(sizeof($query->result_array()) != 0)//session found
        {
            $abc = $query->result_array();
            $client_id = $abc[0]['client_id'];
            unset($data['mobile']);
            unset($data['session']);
            $data['client_id'] = $client_id;
            #print_r($data);exit;
            $this->Api_model->setData('client_address', $data);
            
            $response['id'] = $this->db->insert_id();
            return $response;
        }else{
            return $response;
        }
        #print $otp;exit;
        #print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function AddressDel($shopID, $lang, $data)
    {
        /*
		$response = array();
        $session = $data['session'];
        $mobile = $data['mobile'];
        $this->db->where('client_master.session', $session);
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');
        #print $this->db->last_query();exit;
		*/
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
        
        if(sizeof($query->result_array()) != 0)//session found
        {
            $abc = $query->result_array();
            $client_id = $abc[0]['client_id'];
            $this->db->where('client_address.client_id', $client_id);
            $this->db->where('client_address.cadd_id', $data['cadd_id']);
            $this->db->where('client_address.shopID', $shopID);
            #$query = $this->db->select('*')->get('client_address');
            $this->db->from('client_address');

            $query = $this->db->delete();
            #print $this->db->last_query();exit;
            #return $query->result_array();
            return 1;
        }else{
            return $response;
        }
        #print $otp;exit;
        #print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function AddressDetail($shopID, $lang, $data)
    {
        /*
		$response = array();
        $session = $data['session'];
        $mobile = $data['mobile'];
        $this->db->where('client_master.session', $session);
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');
        #print $this->db->last_query();exit;
		*/
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
        
        if(sizeof($query->result_array()) != 0)//session found
        {
            $abc = $query->result_array();
            $client_id = $abc[0]['client_id'];
            $cadd_id = $data['cadd_id'];
            $this->db->where('client_address.client_id', $client_id);
            $this->db->where('client_address.cadd_id', $cadd_id);
            $this->db->where('client_address.shopID', $shopID);
            $query = $this->db->select('*')->get('client_address');
            #print $this->db->last_query();exit;
            return $query->result_array();
        }else{
            return $response;
        }
        #print $otp;exit;
        #print $this->db->last_query();exit;
        //return $query->result_array();
    }

    public function AddressUpdate($shopID, $lang, $data)
    {
        /*
		$response = array();
        $session = $data['session'];
        $mobile = $data['mobile'];
        $this->db->where('client_master.session', $session);
        $this->db->where('client_master.mobile', $mobile);
        $this->db->where('client_master.shopID', $shopID);
        $query = $this->db->select('*')->get('client_master');
        #print $this->db->last_query();exit;
		*/
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
        
        if(sizeof($query->result_array()) != 0)//session found
        {
            $abc = $query->result_array();
            $client_id = $abc[0]['client_id'];
            unset($data['mobile']);
            unset($data['session']);
            $data['client_id'] = $client_id;
            $cond['cadd_id'] = $data['cadd_id'];
            unset($data['cadd_id']);
            #print_r($data);exit;
            $this->Api_model->UpdateData('client_address', $data, $cond);
			#print $this->db->last_query();exit;
            
            $response['id'] = $cond['cadd_id'];
            return $response;
        }else{
            return $response;
        }
        #print $otp;exit;
        #print $this->db->last_query();exit;
        //return $query->result_array();
    }

	public function pincodelist($shopID,$lang, $data)
	{
		
		$response = array();
		$pincode = $data['pincode'];
		$limit =100;
		
		#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'
		$this->db->select('*');
		$this->db->where('pincode_shop.shopID', $shopID);
		$this->db->where('pincode_shop.pincode', $pincode);
		$result = $this->db->get('pincode_shop', $limit);
		#print $this->db->last_query();exit;
		return $result->result_array();
		
		
	}

	public function deliverylist($shopID,$lang, $data)
	{
		
		$response = array();
		$date = $data['date'];
		$products = $data['products'];
		#print $products;exit;

		$limit =100;
		$ordervalue = 0;
		$totalqty = 0;
		$del_desc = '';
		
		$c_date = date("Y-m-d");
		$c_time = date("H:i");
		$dates = array();
		$timing = array();
		
		if(empty($date)) $date = $c_date;
		
		#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'
		#$this->db->select('eqd,book_date,slot_from,slot_to');
		
		//same day
		$samedaycount = 0;
		if($date ==$c_date)//same date
		{
			$this->db->select('book_date');
			$this->db->where('equedetails.shopID', $shopID);
			$this->db->where('equedetails.status', 'N');			

			$this->db->where('equedetails.slot_from >=', $c_time);
			
			$this->db->where('equedetails.book_date =', $date);

			$this->db->group_by('book_date');
			$result = $this->db->get('equedetails', $limit);
			#print $this->db->last_query() . "\n\n---------------------\n\n";exit;
			
			$samedaycount = sizeof($result->result());
			#print $samedaycount;exit;
		}
		//
		#print "same day count: $samedaycount <br>\n";exit;
		$this->db->select('book_date');
		$this->db->where('equedetails.shopID', $shopID);
		$this->db->where('equedetails.status', 'N');

		//$this->db->group_start();
		$this->db->where('equedetails.book_date>=', $date);
		if($samedaycount == 0)
		{
			$this->db->where('equedetails.book_date !=', $c_date);
		}
		
		//$this->db->or_group_start();

		//$this->db->where('equedetails.book_date>=', $date);
		//$this->db->group_end();
		//$this->db->group_end();

		$this->db->group_by('book_date');
		$result = $this->db->get('equedetails', $limit);
		#print $this->db->last_query() . "\n\n---------------------\n\n";//exit;

		$cfound = 1;
		foreach($result->result() as $rr)
		{
			$book_date = $rr->book_date;
			#$dates[] =  array($book_date, date('d-M-Y', strtotime($book_date)));
			#$dates[] =  array(('book_date' => $book_date), ('a' => date('d-M-Y', strtotime($book_date))));
			$dates[] =  array('date' => $book_date, 'book_date' => date('d-M-Y', strtotime($book_date)));
			#break;
			$cfound++;
		}
		
		$i =0;
		foreach($result->result() as $rr)
		{
			$book_date = $rr->book_date;
			#print $book_date;exit;
			
			$this->db->select('slot_from, slot_to');
			$this->db->where('equedetails.shopID', $shopID);
			$this->db->where('equedetails.status', 'N');

			$this->db->group_start();
			$this->db->where('equedetails.book_date =', $book_date);
			
			#$this->db->or_group_start();
			$this->db->group_start();

			if($book_date ==$c_date)//same date
			{
				$this->db->where('equedetails.slot_from >=', $c_time);
			}
			$this->db->where('equedetails.book_date=', $book_date);
			$this->db->group_end();
			$this->db->group_end();

			$this->db->group_by('slot_from');
			$result2 = $this->db->get('equedetails', $limit);
			#print $this->db->last_query() . "\n\n---------------------\n\n";//exit;
			
			
			foreach($result2->result() as $rr2)
			{
				$slot_from = $rr2->slot_from;
				$slot_to = $rr2->slot_to;
				
				$slot_from =  date('h:i A', strtotime($slot_from));
				$slot_to =  date('h:i A', strtotime($slot_to));
				#print "slot_from: $slot_from = slot_to: $slot_to <br>";
				#$timing[] = array($slot_from . ' - ' . $slot_to, $slot_from . ' - ' . $slot_to);
				$timing[] =  array('time' => $slot_from . ' - ' . $slot_to, 'timing' => $slot_from . ' - ' . $slot_to);

			}
			$i++;
			
			if($i == 1)break;
		}
		
		if($this->isJSON($products))		 
		{			
			$pp = json_decode($products);
			$prodNew= array();
			#print_r($pp);exit;
			$data4 = array();
			#$ii=0;
			$ordervalue = 0;
			$ii =0;

			foreach($pp as $prods)
			{
				#print_r($prods);exit;
				$pid = @$prods->pid;
				$qty = @$prods->qty;
				$extra = @$prods->qty;
				$relation = @$prods->relation;
				$totalqty = $totalqty+$qty;
				#print_r($relation);//exit;
				if(is_array($relation))
				{
					foreach($relation as $rr)
					{
						$id2 = $rr->id;
						$qt2 = $rr->qty;
						$totalqty = $totalqty+$qt2;
						#print $id2 . ' ' .$qt2 . "\n";
						
						$q2_0 = "select * from products inner join products_translations on products.id= products_translations.for_id where products_translations.abbr='en' and products.shopID=$shopID and products.id=$id2 limit 1";
						#$q2 = "select * from products WHERE products.shopID=$shopID and products.id=$pid limit 1";
						$res2_0 = $this->db->query($q2_0)->result();
						#print $this->db->last_query();//exit;
						#print sizeof($res2);
						$price2 = $qty_res2 = 0;
						if(sizeof($res2_0) == 0)//product not found
						{
							#print "error";exit;
							$this->give_response(200,FALSE,'product2 not found');
						}else{
							$price2 = $res2_0[0]->price;
							$qty_res2= $res2_0[0]->qty_res;
						}
						$ordervalue = $ordervalue + ($price2 * $qt2);

					}
				}
				#exit;
				#print $prods->pid . ' * ' . $prods->qty . "\n";exit;
				
				$q2 = "select * from products inner join products_translations on products.id= products_translations.for_id where products_translations.abbr='en' and products.shopID=$shopID and products.id=$pid limit 1";
				#$q2 = "select * from products WHERE products.shopID=$shopID and products.id=$pid limit 1";
				$res2 = $this->db->query($q2)->result();
				#print $this->db->last_query();//exit;
				#print sizeof($res2);
				$price = $qty_res = 0;
				if(sizeof($res2) == 0)//product not found
				{
					#print "error";exit;
					$this->give_response(200,FALSE,'product not found');
				}else{
					$price = $res2[0]->price;
					$qty_res = $res2[0]->qty_res;
				}
				$ordervalue = $ordervalue + ($price * $prods->qty);
				#print "price: $price \n\n";
				
				if($qty>$qty_res)
				{
					#print "qty_res: $qty_res is for product $pid => $qty\n\n";exit;
					$this->give_response(200,FALSE, "qty_res: $qty_res is for product $pid => $qty");
				}
				
				$prodNew[$ii]['pid'] = $pid;
				$prodNew[$ii]['qty'] = $qty;
				$prodNew[$ii]['price'] = $price;
				$ii++;
				
			}
		}
		#$minorder =100;
		#$ordervalue = $ordervalue+$deliverycharge;//@$minorder
		#print "ordervalue: $ordervalue \n\n";exit;		
		#print "ordervalue: $ordervalue \n totalqty: $totalqty \n\n";exit;		
		
		$mydata = array();
		$mydata['schedule']['date']=$dates;
		$mydata['schedule']['timing']=$timing;
		$paymode = $this->shop_paymode($shopID,$lang);
		#$deliverycharge = $this->delivery_charge($shopID,$lang);
		#print_r($deliverycharge);exit;
		//if($ordervalue >=200)	$deliverycharge =0;		
		
		$q3_d = "select * from delivery_charge WHERE delivery_charge.shopID=$shopID and isActive='Y' and '$c_date' between val_from and val_to";
		$res2_d = $this->db->query($q3_d)->result();
		#print $this->db->last_query();exit;
		
		$deliverycharge = array();
		$deliverycharge[0]['de_id'] = 1;
		$deliverycharge[0]['del_desc'] = 'Delivery Charge';
		$deliverycharge[0]['del_charge'] = 0;
		//$deliverycharge[0]['isActive'] = 'Y';

		$delcharge2 = 0;
		
		foreach($res2_d as $rrd)
		{
			$del_method = $rrd->del_method;
			$del_desc = $rrd->del_desc;
			#print $del_method;
			#$del_method = 'Q';
			if($del_method == 'V')
			{
				$from1 = $rrd->from1;
				$to1 = $rrd->to1;
				$del_charge = $rrd->del_charge;
				$del_charge_min = $rrd->del_charge_min;
				if($ordervalue >= $from1 && $ordervalue <= $to1)
				{
					$delcharge2 = $del_charge;
					$deliverycharge[0]['del_charge'] = $delcharge2;
				}
			}
			#print $del_method;exit;
			if($del_method == 'Q')
			{
				#$totalqty =9;
				$from1 = $rrd->from1;
				$to1 = $rrd->to1;
				$del_charge = $rrd->del_charge;
				$del_charge_min = $rrd->del_charge_min;
				#print "totalqty: $totalqty from1: $from1 to1 : $to1 ";exit;
				
				if($totalqty >= $from1 && $totalqty <= $to1)
				{
					$delcharge2 = $totalqty * $del_charge;
					#print $delcharge2 . " $del_charge_min";exit;
					$deliverycharge[0]['del_charge'] = $delcharge2;
					if($delcharge2 < $del_charge_min)
					{
						$delcharge2 = $del_charge_min;
						$deliverycharge[0]['del_charge'] = $delcharge2;
					}
					
					#print "delcharge2: $delcharge2 * totalqty: $totalqty ";exit;
				}
			}
			
			$deliverycharge[0]['del_desc'] = $del_desc;
		}		

		$mydata['paymode']=$paymode;
		$mydata['coupon']= '0';
		$mydata['deliverycharge']=$deliverycharge;
		//$mydata['del_desc']=$del_desc;
		#print_r($deliverycharge);
		#print "hi deliverycharge: $delcharge2";exit;
				
		if($cfound ==0)
		{
			$mydata = array();
		}
		
		#print "<pre>";
		#print_r($mydata);exit;
		return $mydata;
		#return $result->result_array();
		
		
	}
	
	public function couponcodelist($shopID,$lang, $data)
	{
		
		$response = array();
		$couponcode = $data['couponcode'];
		$products = @$data['products'];
		$couponcode = trim($couponcode);

		$limit =100;
		$totalqty = 0;
		$ordervalue = 0;
		$amount = 0;

		if(empty($products))
		{
			$this->give_response(200,FALSE,'product not found');
		}
		if($this->isJSON($products))		 
		{			
			$pp = json_decode($products);
			$prodNew= array();
			#print_r($pp);exit;
			$data4 = array();
			#$ii=0;
			$ii =0;

			foreach($pp as $prods)
			{
				#print_r($prods);exit;
				$pid = @$prods->pid;
				$qty = @$prods->qty;
				$relation = @$prods->relation;
				$totalqty = $totalqty+$qty;
				#print_r($relation);//exit;
				if(is_array($relation))
				{
					foreach($relation as $rr)
					{
						$id2 = $rr->id;
						$qt2 = $rr->qty;
						$totalqty = $totalqty+$qt2;
						#print $id2 . ' ' .$qt2 . "\n";
						
						$q2_0 = "select * from products inner join products_translations on products.id= products_translations.for_id where products_translations.abbr='en' and products.shopID=$shopID and products.id=$id2 limit 1";
						#$q2 = "select * from products WHERE products.shopID=$shopID and products.id=$pid limit 1";
						$res2_0 = $this->db->query($q2_0)->result();
						#print $this->db->last_query();//exit;
						#print sizeof($res2);
						$price2 = $qty_res2 = 0;
						if(sizeof($res2_0) == 0)//product not found
						{
							#print "error";exit;
							$this->give_response(200,FALSE,'product2 not found');
						}else{
							$price2 = $res2_0[0]->price;
							$qty_res2 = $res2_0[0]->qty_res;
						}
						$ordervalue = $ordervalue + ($price2 * $qt2);

					}
				}
				#print $prods->pid . ' * ' . $prods->qty . "\n";exit;
				
				$q2 = "select * from products inner join products_translations on products.id= products_translations.for_id where products_translations.abbr='en' and products.shopID=$shopID and products.id=$pid limit 1";
				#$q2 = "select * from products where products.shopID=$shopID and products.id=$pid limit 1";
				$res2 = $this->db->query($q2)->result();
				#print $this->db->last_query();exit;
				#print sizeof($res2);
				$price = $qty_res = 0;
				if(sizeof($res2) == 0)//product not found
				{
					#print "error";exit;
					$this->give_response(200,FALSE,'product not found');
				}else{
					$price = $res2[0]->price;
					$qty_res = $res2[0]->qty_res;
				}
				$ordervalue = $ordervalue + ($price * $prods->qty);
				#print "price: $price \n\n";
				
				if($qty>$qty_res)
				{
					#print "qty_res: $qty_res is for product $pid => $qty\n\n";exit;
					$this->give_response(200,FALSE, "qty_res: $qty_res is for product $pid => $qty");
				}
				
				$prodNew[$ii]['pid'] = $pid;
				$prodNew[$ii]['qty'] = $qty;
				$prodNew[$ii]['price'] = $price;
				$ii++;
				
			}
		}
		
		#print "totalqty: $totalqty ** ordervalue: $ordervalue \n";exit;
		#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'
		#$this->db->select('*');
		/*
		$this->db->select('type,code,min_order,amount');
		$this->db->where('discount_codes.shopID', $shopID);
		$this->db->where('discount_codes.code', $couponcode);
		$this->db->where('discount_codes.status', 1);
		$result = $this->db->get('discount_codes', $limit);
		#print $this->db->last_query();exit;
		return $result->result_array();
		*/
		$cfound = 0;
		$mydata = array();
		
		$this->db->select('type,code,min_order,amount');
		$this->db->where('discount_codes.shopID', $shopID);
		$this->db->where('discount_codes.code', $couponcode);
		$this->db->where('discount_codes.status', 1);
		$result = $this->db->get('discount_codes', $limit);
		#print $this->db->last_query();exit;
		foreach($result->result() as $rr)
		{
			$type = $rr->type;
			$code = $rr->code;
			$min_order = $rr->min_order;
			$amount1 = $rr->amount;
			#print "ordervalue: $ordervalue == type: $type * code: $code * min_order: $min_order * amount: $amount1\n";
			if($min_order < $ordervalue)// apply coupon
			{
				if($type == 'fixed')
				{
					$amount = $amount1;
				}
				if($type == 'percent')
				{
					#$amount1 = 12.33;
					$amount = ceil($ordervalue*$amount1/100);
				}
				#$amount = 5;
				#print $amount;exit;
				$mydata[0]['code'] = $code;
				$mydata[0]['type'] = $type;
				$mydata[0]['amount'] = $amount;
				$cfound++;
			}
		}
		
		#print $amount;exit;
		if($cfound ==0)
		{
			$mydata = array();
		}
		
		#print "<pre>";
		#print_r($mydata);exit;
		return $mydata;
			
	}
	
	public function reviewlist($shopID,$lang, $data)
	{
		
		$response = array();
		#$couponcode = $data['couponcode'];
		$limit =100;
		
		#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'
		$this->db->select('*');
		$this->db->where('reviews.shopID', $shopID);
		#$this->db->where('reviews.code', $couponcode);
		$this->db->where('reviews.approved', 'Y');
		$this->db->order_by('disporder', 'ASC');
		$result = $this->db->get('reviews', $limit);
		#print $this->db->last_query();exit;
		return $result->result_array();		
	}	
	
	public function statelist($shopID,$lang, $data)
	{
		
		$response = array();
		#$couponcode = $data['couponcode'];
		$limit =100;
		
		#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'
		$this->db->select('gststatecode statecode, statename');
		#$this->db->where('state_master.shopID', $shopID);
		#$this->db->where('reviews.code', $couponcode);
		$this->db->where('state_master.enable', 'Y');
		$this->db->order_by('statename', 'ASC');
		$result = $this->db->get('state_master', $limit);
		#print $this->db->last_query();exit;
		return $result->result_array();		
	}	
		
	public function pin2list($shopID,$lang, $data)
	{
		
		$response = array();
		$pincode = $data['pincode'];
		$limit =100;
		
		#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'
		$this->db->select("office, district city,	gststatecode statecode, state, concat(district,' - ',state) citydisplay");
		#$this->db->where('pincode_master.shopID', $shopID);
		$this->db->where('pincode_master.pincode', $pincode);
		$this->db->join('state_master', 'state_master.statename = pincode_master.state');
		$this->db->group_by("pincode_master.pincode ");
		$result = $this->db->get('pincode_master', $limit);
		#print $this->db->last_query();exit;
		return $result->result_array();
			
	}
	
    public function OrderAdd($shopID, $lang, $data)
    {
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
		$payment_type = $data['payment_type'];
		$deliverycharge = $data['deliverycharge'];
		#print $deliverycharge;exit;
		if(empty($deliverycharge)) $deliverycharge =0;
        #$this->db->where('client_master.session', $session);
        #$this->db->where('client_master.mobile', $mobile);
        #$this->db->where('client_master.shopID', $shopID);
        #$query = $this->db->select('*')->get('client_master');
		#print $this->db->last_query();exit;
		#print_r($data);exit;
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		#print $this->db->last_query();exit;
		if(sizeof($query->result_array()) == 0)//session found
		{
			return $response;
		}

		$q = "select * from shop_master where shopID=$shopID order by shopID desc limit 1";
		$res = $this->db->query($q)->result();
		#print $this->db->last_query();exit;
		#print sizeof($res);
		$minorder = $ordersrno = 0;
		$shopname = $emailid = '';
		if(sizeof($res) == 0)//shop not found
		{
			#print "error";exit;
			$this->give_response(200,FALSE,'shop not found');
		}else{
			$minorder = $res[0]->minorder;
			$shopname = $res[0]->shopname;
			$emailid = $res[0]->emailid;
			$ordersrno = $res[0]->ordersrno;
		}
	
		#print "minorder: $minorder \n\n";
		
		$qp = "select * from shop_paymentoptions where shopID=$shopID and payrid = '$payment_type' ";
		$resp = $this->db->query($qp)->result();
		#print $this->db->last_query();exit;
		#print sizeof($res);
		$paymenttype = $paymode = '';
		$extracharge = 0;
		$pptype = $paybaseurl = $isOffline = '';
		if(sizeof($resp) == 0)//shop not found
		{
			#print "error";exit;
			$this->give_response(200,FALSE,"Payment gateway $payment_type not found");
		}else{
			$paymode = $resp[0]->paymenttype;
			$paymenttype = $resp[0]->payrid;
			$pptype = $resp[0]->pptype;
			$isOffline = $resp[0]->isOffline;
			$paybaseurl = $resp[0]->baseurl;
			$extracharge = $resp[0]->extracharge;
		}
		
		#print "isOffline: $isOffline";exit;
		$products = $data['products'];
		
		$pp = json_decode($products);
		$prodNew= array();
		$products1 = '';
		#print_r($pp);
		$data4 = array();
		#$ii=0;
		$ordervalue = 0;
		$ii =0;
		foreach($pp as $prods)
		{
			$pid = $prods->pid;
			$qty = $prods->qty;
			#print $prods->pid . ' * ' . $prods->qty . "\n";
			$relation = @$prods->relation;
			$extra = @$prods->extra;
			#$totalqty = $totalqty+$qty;
			#print_r($relation);//exit;
			if(is_array($relation))
			{
				foreach($relation as $rr)
				{
					$id2 = $rr->id;
					$qt2 = $rr->qty;
					#$totalqty = $totalqty+$qt2;
					#print $id2 . ' ' .$qt2 . "\n";
					
					$q2_0 = "select * from products inner join products_translations on products.id= products_translations.for_id where products_translations.abbr='en' and products.shopID=$shopID and products.id=$id2 limit 1";
					#$q2 = "select * from products WHERE products.shopID=$shopID and products.id=$pid limit 1";
					$res2_0 = $this->db->query($q2_0)->result();
					#print $this->db->last_query();//exit;
					#print sizeof($res2);
					$price2 = $qty_res2 = 0;
					if(sizeof($res2_0) == 0)//product not found
					{
						#print "error";exit;
						$this->give_response(200,FALSE,'product2 not found');
					}else{
						$price2 = $res2_0[0]->price;
						#$qty_res2 = $res2_0[0]->qty_res;
					}
					$ordervalue = $ordervalue + ($price2 * $qt2);

				}
			}
				
			$q2 = "select * from products inner join products_translations on products.id= products_translations.for_id where products_translations.abbr='en' and products.shopID=$shopID and products.id=$pid limit 1";
			#$q2 = "select * from products where products.shopID=$shopID and products.id=$pid limit 1";
			$res2 = $this->db->query($q2)->result();
			#print $this->db->last_query();exit;
			#print sizeof($res2);
			$price = $qty_res = 0;
			if(sizeof($res2) == 0)//product not found
			{
				#print "error";exit;
				$this->give_response(200,FALSE,'product not found');
			}else{
				$price = $res2[0]->price;
				$qty_res = $res2[0]->qty_res;
			}
			$ordervalue = $ordervalue + ($price * $prods->qty);
			#print "price: $price \n\n";
			
			if($qty>$qty_res)
			{
				#print "qty_res: $qty_res is for product $pid => $qty\n\n";exit;
				$this->give_response(200,FALSE, "qty_res: $qty_res is for product $pid => $qty");
			}
			
			$products1 .= $res2[0]->title . " (Qty: $qty)<br>\n";
			
			$prodNew[$ii]['pid'] = $pid;
			$prodNew[$ii]['qty'] = $qty;
			$prodNew[$ii]['price'] = $price;
			$prodNew[$ii]['relation'] = $relation;
			$prodNew[$ii]['extra'] = $extra;
			$ii++;
			
		}
		#$products1 = substr($products1,2);
		#print $products1;exit;
		
		if($ii == 0)//no products
		{
			$this->give_response(200,FALSE, "Product is empty, Please add few product into cart");
		}
		#$minorder =100;
		#$ordervalue = $ordervalue+$deliverycharge;
		#print "ordervalue: $ordervalue $minorder\n\n";exit;
		
		#print_r($prodNew);exit;
		if(empty($deliverycharge))
		{
			if($ordervalue < $minorder)
			{
				#print "minimum order should be $minorder\n\n";exit;
				$this->give_response(200,FALSE, "minimum order should be $minorder");
			}
		}	
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        #$this->db->where('client_master.session', $session);
        #$this->db->where('client_master.mobile', $mobile);
        #$this->db->where('client_master.shopID', $shopID);
        #$query = $this->db->select('*')->get('client_master');
		#print $this->db->last_query();exit;
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		//print_r($query->result_array());
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			#print "session found";exit;
			$abc = $query->result_array();
			$client_id = $abc[0]['client_id'];
			$mobile = $data['mobile'];
			unset($data['mobile']);
			unset($data['session']);
			$data['client_id'] = $client_id;
			#print_r($data);exit;
			#$this->Api_model->setData('client_address', $data1);
			
			//insert into client_address & order_client
			
			$cadd_id = $data['cadd_id'];
			$name2 = '';
			if(empty($cadd_id))//new address -> than add in client address & order_client
			{
				#print "new address -> than add in client address & order_client\n\n";
				
				$data1 = array();
				$data1['shopID'] = $data['shopID'];
				$data1['client_id'] = $client_id;
				$data1['datec'] = $data['datec'];
				$data1['contactperson'] = $data['contactperson'];
				#$data1['last_name'] = '';
				#$data1['email'] = $abc[0]['emailid'];
				$data1['contactno'] = $data['contactno'];
				$data1['address'] = $data['address'];
				$data1['address2'] = $data['address2'];
				$data1['landmark'] = $data['landmark'];
				$data1['state'] = @$data['state'];
				$data1['city'] = $data['city'];
				$data1['pincode'] =$data['pincode'];
				#$data1['notes'] = $data['specialinstruction'];
				#print_r($data1);exit;
				
				$name2 = $data1['contactperson'] . "<br>" . $data1['address'] .', ' . $data1['address2'] . ', ' . $data1['landmark']. '<br>' . $data1['city'] . '-' . $data1['pincode'] .'<br>' . 'Mobile: ' . $data1['contactno'];
				
				$pincheck = $this->pincodelist($shopID,'', array('pincode' => $data1['pincode']));
				#print_r($pincheck);exit;
				#print sizeof($pincheck);exit;
				if(sizeof($pincheck) ==0)//not found
				{
					$this->give_response(200,FALSE, "We are not delivering to PIN: ". $data1['pincode'] );
				}
				#$cadd_id = 1;
				$this->Api_model->setData('client_address', $data1);
				$cadd_id = $this->db->insert_id();
				#print_r($data1);
								
				$data2 = array();
				$data2['shopID'] = $data['shopID'];
				$data2['client_id'] = $client_id;
				$data2['first_name'] = $data['contactperson'];
				$data2['last_name'] = '';
				$data2['email'] = @$abc[0]['emailid'];
				#$data2['phone'] = $data['contactno'];
				if($data['contactno'] == '')
				{
					$data2['phone'] = $mobile;
				}else{
					$data2['phone'] = $data['contactno'];
				}
				#print_r($data2);exit;
				$data2['address'] = $data['address'];
				$data2['address2'] = $data['address2'];
				$data2['landmark'] =$data['landmark'];
				$data2['state'] = @$data['state'];
				$data2['city'] = $data['city'];
				$data2['post_code'] =$data['pincode'];
				$data2['notes'] = $data['specialinstruction'];
				$data2['for_id'] = $cadd_id;
				
				
				if($isOffline == 'Y')
				{
					$this->Api_model->setData('order_client', $data2);
				}else{
					$this->Api_model->setData('tmp_order_client', $data2);
				}
				$cid = $this->db->insert_id();

				#print_r($data2);
				
			}else{ // insert in order_client
				#echo "insert in order_client\n\n";exit;
				
				$res2 = $this->db->query("select * from client_address where cadd_id=$cadd_id")->result_array();
				if(sizeof($res2) == 0)//not found
				{
					$this->give_response(200,FALSE, "Invalid Cadd_id" );
				}
				#print $this->db->last_query();exit;
				
				$data2 = array();
				$data2['shopID'] = $data['shopID'];
				$data2['client_id'] = $client_id;
				$data2['first_name'] = $data['contactperson'];
				$data2['last_name'] = '';
				$data2['email'] = @$abc[0]['emailid'];
				#$data2['phone'] = $data['contactno'];
				if($data['contactno'] == '')
				{
					$data2['phone'] = $mobile;
				}else{
					$data2['phone'] = $data['contactno'];
				}
				#print_r($data2);exit;
				$data2['address'] = $res2[0]['address'];
				$data2['address2'] = $res2[0]['address2'];
				$data2['landmark'] = $res2[0]['landmark'];
				$data2['state'] = @$res2[0]['state'];
				$data2['city'] = $res2[0]['city'];
				$data2['post_code'] =$res2[0]['pincode'];
				$data2['notes'] = $data['specialinstruction'];
				$data2['for_id'] = $cadd_id;
				
				$name2 = $data2['first_name'] . "<br>" . $data2['address'] .', ' . $data2['address2'] . ', ' . $data2['landmark']. '<br>' . $data2['city'] . '-' . $data2['post_code'] .'<br>' . 'Mobile: ' . $data2['phone'];


				$pincheck = $this->pincodelist($shopID,'', array('pincode' => $data2['post_code']));
				#print_r($pincheck);exit;
				#print sizeof($pincheck);exit;
				if(sizeof($pincheck) ==0)//not found
				{
					$this->give_response(200,FALSE, "We are not delivering to PIN: ". $data2['post_code'] );
				}
				
				if($isOffline == 'Y')
				{
					$this->Api_model->setData('order_client', $data2);
				}else{
					$this->Api_model->setData('tmp_order_client', $data2);
				}
				
				//$this->Api_model->setData('order_client', $data2);
				$cid = $this->db->insert_id();
				#print_r($data2);
			}
			
			//insert into orders
			
			$discount_amt = $data['discount_amt'];
			if(empty($discount_amt)) $discount_amt = 0;
			if(empty($deliverycharge)) $deliverycharge = 0;
			$ordervalueorig = $ordervalue;
			$ordervalue = $ordervalue + $deliverycharge + $extracharge - $discount_amt;
			
			$extra3 = @$data['extra'];
			if(is_array($extra3))
			{
				$extra4 = json_encode($extra3);
			}else{
				$extra4 = '';
			}	
				
			$oid = getGUID();
			$rnd = rand(1111,9999);
			$data3 = array();
			$data3['shopID']= $data['shopID'];
			$data3['cid']= $cid;
			$data3['user_id']= 1;
			$data3['products']= NULL;
			$data3['date']= $data['datec'];
			$data3['randchr']= $rnd;
			$data3['referrer']= NULL;
			$data3['clean_referrer']= NULL;
			#$data3['payment_type']= @$data['payment_type'];
			$data3['extra']= @$extra4;
			$data3['payment_type']= @$paymenttype;
			$data3['discount_code']= @$data['couponcode'];
			$data3['discount_amt']= $discount_amt;
			$data3['paypal_status']= NULL;
			$data3['processed']= 0;
			$data3['viewed']= 0;
			$data3['confirmed']= 0;
			$data3['deliverycharge']= $deliverycharge;
			#$data3['discount_code']= NULL;
			$data3['eqd']= NULL;
			$data3['ordervalue']= $ordervalue;
			$data3['ordervalue_shop']= 0;
			$data3['specialinstruction']= $data['specialinstruction'];
			#$data3['extra']= $data['extra'];
			$data3['ostatus']= 'N';
			$data3['pmtstatus']= 'N';
			$data3['o_uid']= $oid;
			
			$data3['delivereydate']= $data['book_date'];
			$data3['deliverytime']= $data['timing'];

			#print_r($data3);
			//$this->Api_model->setData('orders', $data3);

			if($isOffline == 'Y')
			{				
				//o_uid == ordersrno
				$ordersrno = $ordersrno+1;
				$data3['o_uid']= $ordersrno;
				$qup2 = "UPDATE shop_master set ordersrno=$ordersrno where shopID=$shopID";
				#print $qup2;exit;
				$this->db->query($qup2);
				$this->Api_model->setData('orders', $data3);
			}else{
				$ordersrno = "T-$oid";
				$this->Api_model->setData('tmp_orders', $data3);
			}
			$orderid = $this->db->insert_id();
				
			//insert into order_details
			
			#print "hello";
			foreach($prodNew as $prods2)
			{
				$extra = @$prods2['extra'];
				$relation = @$prods2['relation'];
				if(is_array($extra))
				{
					$extra1 = json_encode($extra);
				}else{
					$extra1 = '';
				}
				#print_r($relation);exit;
				if(is_array($relation))
				{
					$related1 = json_encode($relation);
				}else{
					$related1 = '';
				}				
				#print $related1;exit;
				#print $extra1;exit;
				#print_r($prods2);exit;
				#print $prods2['pid'] . ' * ' . $prods2['qty'] . "\n";exit;
				$data4['shopID']= $data['shopID'];
				$data4['orderid']= $orderid;
				#$data4['orderid']= 1;
				$data4['pid']= $prods2['pid'];
				$data4['qty']= $prods2['qty'];
				$data4['oprice']= $prods2['price'];
				$data4['related']= $related1;
				$data4['extra']= $extra1;
				$data4['qty_delivered']= 0;
				$data4['oprice_final']= 0;
				$data4['o_updatedby']= 0;
				$data4['odstatus']= 'N';
				$data4['notes']= NULL;
				#print_r($data4);exit;
				//$this->Api_model->setData('order_details', $data4);
				if($isOffline == 'Y')
				{
					$this->Api_model->setData('order_details', $data4);
				}else{
					$this->Api_model->setData('tmp_order_details', $data4);
				}
			}
			#exit;
			
			#print $pptype;exit;
			$complete = (empty($pptype))? 'Y':'N';
			$rnd2 = rand(11111,99999);
			$url = '';
			if(strtolower($pptype) == strtolower('paytm'))
			{
				#$amt = $ordervalue + $deliverycharge;
				$amt = $ordervalue;
				$url = $paybaseurl . "?app=1&amt=$amt&orderno=$rnd-$orderid-$rnd2&custno=$client_id";
			}
			if(strtolower($pptype) == strtolower('razorpay'))
			{
				#$amt = $ordervalue + $deliverycharge;
				$amt = $ordervalue;
				$url = $paybaseurl . "?app=1&amt=$amt&orderno=$rnd-$orderid-$rnd2&custno=$client_id";
			}
			//	$amt = @$_GET['amt'];
	
			#$response['id'] = $orderid . '-'. $rnd;
			#$response['redirect'] = '/account/orderdetails/' . $orderid . '-'. $rnd;;
			#$response['id'] = $oid;
			$response['id'] = $data3['o_uid'];
			$response['oid'] = $orderid;
			$response['complete'] = $complete;
			$response['url'] = $url;
			#$response['redirect'] = '/account/orderdetails/' . $oid;
			if(empty($url))
			{
				$response['redirect'] = '/account/orderdetails/' . $oid;
			}else{
				$response['redirect'] = $url;
			}

			$body = '';
			$filename = "umart.html";
			$handle = fopen(base_url() . $filename, "r");
			$body = fread($handle, filesize($filename));
			fclose($handle);
			$name = "";
			$body = str_replace("#orderid","$ordersrno",$body);
			$body = str_replace("#name","$name2",$body);
			#$body = str_replace("#amount","$ordervalue",$body);
			$body = str_replace("#paymode","$paymode",$body);
			$body = str_replace("#products","$products1",$body);
			$body = str_replace("#specialinstruction",$data['specialinstruction'],$body);
			$body = str_replace("#discount","$discount_amt",$body);
			$body = str_replace("#delivery","$deliverycharge",$body);
			$body = str_replace("#extracharge","$extracharge",$body);
			$body = str_replace("#amount","$ordervalueorig",$body);
			$body = str_replace("#total","$ordervalue",$body);
			$body = str_replace("#date",@$data['book_date'] . ' ' . @$data['timing'],$body);
			#print $body;exit;
		
			$subject = "New order- $ordersrno @ $shopname";
			$this->sendmail($shopname,$emailid,$body,$subject);
			return $response;
		
		}else{
			return $response;
		}
		#print $otp;exit;
		#print $this->db->last_query();exit;
        //return $query->result_array();
    }

	public function orderlist($shopID,$lang, $data)
	{
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			$abc = $query->result_array();
			$client_id = $abc[0]['client_id'];
			
			$limit =100;
			
			#$this->db->select('concat(orders.orderid,"-",orders.randchr) oid, orders.*, order_client.first_name,'/o_uid
			/*
			$this->db->select("o_uid oid, orders.*, order_client.first_name,"
					. ' order_client.last_name, order_client.email, order_client.phone,order_client.landmark, '
					. 'order_client.address, order_client.city, order_client.post_code,'
					. ' order_client.notes, discount_codes.type as discount_type, discount_codes.amount as discount_amount, "Y" repeat');
			$this->db->join('order_client', 'order_client.cid = orders.cid', 'inner');
			$this->db->join('discount_codes', 'discount_codes.code = orders.discount_code', 'left');
			$this->db->where('orders.shopID', $shopID);
			$this->db->where('order_client.client_id', $client_id);
			$result = $this->db->get('orders', $limit);
			*/
			
			
			$this->db->select("o_uid orderid,concat(randchr,'-',orderid) ord_display, orders.date order_date,
			
			CASE 
			WHEN delivereydate IS NULL THEN ''
			ELSE delivereydate
			END
			delivereydate,
			CASE 
			WHEN deliverytime IS NULL THEN ''
			ELSE deliverytime
			END
			deliverytime,			
			
			CASE
    WHEN ostatus ='N' THEN 'Ordered'
    WHEN ostatus ='C' THEN 'Cancelled'
    WHEN ostatus ='D' THEN 'Delivered'
		END
		
			ostatus,
			CASE
    WHEN pmtstatus ='N' THEN 'Pending'
    WHEN pmtstatus ='F' THEN 'Failed'
    WHEN pmtstatus ='S' THEN 'Success'
		END
			 pmtstatus,
			 CASE
				WHEN deliverycharge is NULL THEN ordervalue
				WHEN deliverycharge >=0 THEN (deliverycharge+ordervalue-discount_amt)
			END
			 			 

			  ordervalue,deliverycharge,discount_amt,
			  			 
			 order_client.first_name,"
					. ' order_client.last_name, order_client.email, order_client.phone,order_client.landmark, '
					. 'order_client.address, order_client.address2, order_client.city, order_client.post_code,'
					. ' order_client.notes, discount_code, discount_codes.type as discount_type, discount_codes.amount as discount_amount, "Y" repeat, "N" retrypayment, paymenttype payment_type, o_uid');
			$this->db->join('order_client', 'order_client.cid = orders.cid', 'inner');
			$this->db->join('discount_codes', 'discount_codes.code = orders.discount_code', 'left');
			$this->db->join('shop_paymentoptions', 'shop_paymentoptions.payrid = orders.payment_type', 'left');
			$this->db->where('orders.shopID', $shopID);
			$this->db->where('orders.o_uid !=', '');
			$this->db->where('order_client.client_id', $client_id);
			$this->db->order_by('orders.orderid', 'DESC');
			$result = $this->db->get('orders', $limit);
			
			$mydata = array();
			
			foreach($result->result_array() as $key => $value)
			{
				#print_r($key);exit;
				$mydata[$key] = $value;
				$inv = "http://www.africau.edu/images/default/sample.pdf";
				$r = rand(1,2);
				if($r == 1)
				{
					$mydata[$key]['invoice'] = $inv;
				}else{
					$mydata[$key]['invoice'] = '';
				}
				$mydata[$key]['invoice'] = '';
			}
				
			#print_r($mydata);exit;
			#print_r($result->result_array());exit;
			/**/
			#print $this->db->last_query();exit;
			#return $result->result_array();
			return $mydata;
		}else{
			return $response;
            #return array('login' => false);
		}
		
		
	}

	public function orderdetails($shopID,$lang, $data)
	{
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $oid = $data['orderid'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			$abc = $query->result_array();
			$client_id = $abc[0]['client_id'];
			
			$limit =100;
			$this->db->order_by('order_details.orderid', 'DESC');

			$this->db->select('orders.orderid,p_uid,title,qty,oprice,qty_delivered,oprice_final,odstatus,image');
			$this->db->join('orders', 'orders.orderid=order_details.orderid', 'inner');
			$this->db->join('order_client', 'orders.cid=order_client.cid', 'inner');
			$this->db->join('products', 'products.id = order_details.pid', 'inner');
			$this->db->join('products_translations', 'products_translations.for_id = products.id', 'inner');
			$this->db->where('order_details.shopID', $shopID);
            #$this->db->where('orders.randchr', $randchr);
			#$this->db->where('order_details.orderid', $orderid);
			$this->db->where('orders.o_uid', $oid);
			#$this->db->where('orders.orderid', $oid);
			$this->db->where('order_client.client_id', $client_id);
			#$this->db->where('order_client.client_id', $client_id);
			$result = $this->db->get('order_details', $limit);
			#print $this->db->last_query();exit;
			return $result->result_array();
		}else{
			return $response;
		}
		
	}

	public function orderdetails2($shopID,$lang, $data)
	{
		
		$response = array();
		$session = $data['session'];
		$mobile = $data['mobile'];
        $oid = $data['orderid'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        $query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		if(sizeof($query->result_array()) != 0)//session found
		{
			$abc = $query->result_array();
			$client_id = $abc[0]['client_id'];
			
			$limit =100;
			$this->db->order_by('order_details.orderid', 'DESC');

			$this->db->select('date, o_uid,orders.orderid, first_name,email,phone,landmark,address,address2, city,post_code, ordervalue, ostatus,pmtstatus,specialinstruction,payment_type,paymenttype');
			$this->db->join('orders', 'orders.orderid=order_details.orderid', 'inner');
			$this->db->join('order_client', 'orders.cid=order_client.cid', 'inner');
			$this->db->join('products', 'products.id = order_details.pid', 'inner');
			$this->db->join('products_translations', 'products_translations.for_id = products.id', 'inner');
			$this->db->join('shop_paymentoptions', 'shop_paymentoptions.payrid = orders.payment_type', 'inner');
			$this->db->where('order_details.shopID', $shopID);
            #$this->db->where('orders.randchr', $randchr);
			#$this->db->where('order_details.orderid', $oid);
			$this->db->where('orders.o_uid', $oid);
			$this->db->where('order_client.client_id', $client_id);
			#$this->db->where('order_client.client_id', $client_id);
			$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('order_details', $limit);
			#print $this->db->last_query();exit;
			return $result->result_array();
		}else{
			return $response;
		}		
	}
	
	public function officecity_details($shopID,$lang, $data)
	{
		
		$response = array();
        $otype = $data['otype'];
        #$city = $data['city'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			$this->db->order_by('location_master.city', 'ASC');

			$this->db->select("distinct city", false);
			#$this->db->join('state_master', 'state_master.gststatecode=location_master.city', 'inner');
			$this->db->where('location_master.shopID', $shopID);
            #$this->db->where('orders.randchr', $randchr);
			#$this->db->where('order_details.orderid', $oid);
			$this->db->where('location_master.isActive', 'Y');
			$this->db->where('location_master.loc_type', $otype);
			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('location_master', $limit);
			#print $this->db->last_query();exit;
			return $result->result_array();
		#}else{
		#	return $response;
		#}		
	}

	
	public function office_details($shopID,$lang, $data)
	{
		
		$response = array();
        $otype = $data['otype'];
        $city = $data['city'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			$this->db->order_by('location_master.locationname', 'ASC');

			$this->db->select('*');
			$this->db->join('state_master', 'state_master.gststatecode=location_master.state', 'inner');
			$this->db->where('location_master.shopID', $shopID);
            #$this->db->where('orders.randchr', $randchr);
			#$this->db->where('order_details.orderid', $oid);
			$this->db->where('location_master.loc_type', $otype);
			$this->db->where('location_master.city', $city);
			$this->db->where('location_master.isActive', 'Y');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('location_master', $limit);
			#print $this->db->last_query();exit;
			return $result->result_array();
		#}else{
		#	return $response;
		#}		
	}

	public function franchisee_details($shopID,$lang, $data)
	{
		
		$response = array();
        $otype = $data['otype'];
        $city = $data['city'];
		#$oid2 = explode("-", $oid);
        #$randchr = $oid2[0];
		#$orderid = $oid2[1];
        
        #$query= $this->_usersessioncheck($shopID,$session,$mobile);
		
		#if(sizeof($query->result_array()) != 0)//session found
		#{
			
			$limit =100;
			$this->db->order_by('franchisee_master.locationname', 'ASC');

			$this->db->select('*');
			$this->db->join('state_master', 'state_master.gststatecode=franchisee_master.state', 'inner');
			$this->db->where('franchisee_master.shopID', $shopID);
            #$this->db->where('orders.randchr', $randchr);
			#$this->db->where('order_details.orderid', $oid);
			$this->db->where('franchisee_master.loc_type', $otype);
			$this->db->where('franchisee_master.city', $city);
			$this->db->where('franchisee_master.isActive', 'Y');

			#$result = $this->db->group_by("order_details.orderid"); // Produces: GROUP BY orderid
			$result = $this->db->get('franchisee_master', $limit);
			#print $this->db->last_query();exit;
			return $result->result_array();
		#}else{
		#	return $response;
		#}		
	}

	function sendsms3( $mobile, $message)
	{	
		// http://136.243.176.144/domestic/sendsms/bulksms.php?username=indoet&password=Indo@123&type=TEXT&sender=IEVRST&entityId=1501447850000036694&templateId=&mobile=9820397227&message=Your%20OTP%20is%201234%20Use%20this%20OTP%20to%20verify%20your%20mobile%20number%0AINDOEVEREST
	
		// $url="http://my.bluapps.in/corephp/smsapi.php";
		$url="http://vas.themultimedia.in/domestic/sendsms/bulksms.php";

		//Prepare you post parameters
		$postData = array(
			'username' => 'indoet',
			'password' => 'Indo@123',
			'type' => 'Text',
			'sender' => 'IEVRST',
			'entityId' => '1501447850000036694',
			'templateId' => '',
			'mobile' => $mobile,
			'message' => $message,
		);
	
		// init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			//,CURLOPT_FOLLOWLOCATION => true
		));
	
		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
		//get response
		$output = curl_exec($ch);
	
		//Print error if any
		if(curl_errno($ch))
		{
			echo 'error:' . curl_error($ch);
		}
	
		curl_close($ch);
	
		$data2['datec'] = date("Y-m-d H:i:s");
		$data2['mobileno'] = $mobile;
		$data2['message'] = urldecode($message);
		$data2['url'] = $url;
		$data2['data'] = implode(",", $postData);
		$data2['response'] =$output;
		
		$this->Api_model->setData('sms_history', $data2);		
		$smsid = $this->db->insert_id();
				
		return  $output;
	
	}
	
	function sendsmsnepal( $mobile, $message)
	{	
		// http://176.9.29.10/sendsms/bulksms.php?username=indoet&password=Indo1234&type=TEXT&sender=INDIET&mobile=9779814423220&message=Testing%20HTTP%20API
	
		// $url="http://my.bluapps.in/corephp/smsapi.php";
		$url="http://176.9.29.10/sendsms/bulksms.php";
	
		//Prepare you post parameters
		$postData = array(
			'username' => 'indoet',
			'password' => 'Indo1234',
			'type' => 'TEXT',
			'sender' => 'INDIET',
			'mobile' => "977" . $mobile,
			'message' => $message,
		);
	
		// init the resource
		$ch = curl_init();
		curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => $postData
			//,CURLOPT_FOLLOWLOCATION => true
		));
	
		//Ignore SSL certificate verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
		//get response
		$output = curl_exec($ch);
	
		//Print error if any
		if(curl_errno($ch))
		{
			echo 'error:' . curl_error($ch);
		}
	
		curl_close($ch);
	
		$data2['datec'] = date("Y-m-d H:i:s");
		$data2['mobileno'] = $mobile;
		$data2['message'] = urldecode($message);
		$data2['url'] = $url;
		$data2['data'] = implode(",", $postData);
		$data2['response'] =$output;
		
		$this->Api_model->setData('sms_history', $data2);		
		$smsid = $this->db->insert_id();
	
		return  $output;	
	}











    private function give_response($code,$status,$message)
    {
        header("HTTP/1.1 ".$code);
    	$response = array();
    	$response['status'] = $status;
    	$response['message'] = $message;
    	
    	print json_encode($response);exit;	
    			
    }

	private function isJSON($string){
	   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}

	public function sendmail($fromname,$to,$body,$subject)
	{		
		$body = preg_replace("/\s+|\n+|\r/", ' ', $body);
		$body = str_replace('"', '\"', $body);
		
		$url = "https://api.sendinblue.com/v3/smtp/email";
		$ch = curl_init();
		$headers = array();
		$headers[] = "api-Key: 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";
		$headers[] = "Content-Type: application/json";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	  
		#$to = "phpzeal@gmail.com";
		#$to = "kk@k2s2consultants.com";
		$cc = "indoeverestsales@gmail.com";
		$bcc = "infoline@osinfotech.com";
		$json = '{  
	   "sender":{  
		  "name":"'.$fromname .'",
		  "email":"admin@indoeverest.com"
	   },
	   "to":[  
		  {  
			 "email":"'.$to . '",
			 "name":"'.$fromname . '"
		  }
	   ],
	   "cc":[  
		  {  
			 "email":"'.$cc . '",
			 "name":"'.$fromname . '"
		  }
	   ],
	   "bcc":[  
		  {  
			 "email":"'.$bcc . '",
			 "name":"'.'Anil' . '"
		  }
	   ],
	   "subject":"'. $subject .'",
	   "body":"",
	   "htmlContent":"'.$body.'"
	}';
	  #print $json;exit;
	  
	  curl_setopt($ch,CURLOPT_URL,$url);
	  curl_setopt($ch,CURLOPT_POST, 1);                //0 for a get request
	  curl_setopt($ch,CURLOPT_POSTFIELDS,$json);
	  curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	  curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	  $response = curl_exec($ch);
	  #print $response;exit;
	  curl_close ($ch);
	}

}

