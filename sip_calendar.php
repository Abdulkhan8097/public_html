<style type="text/css">
  .smartsip  {
  background: url("images/smart-sip-logo-icon.png") no-repeat left;
  height: 16px;
}
.supersip  {
  background: url("images/supersip_icon_reverse.png") no-repeat left;
  height: 16px;
}
#example tr td .smartsip_plus{width: 100px;}
    #example tr td .smartsip{width: 80px;}
    #example tr td .sip_plus{width: 40px;}
#example tr td .stepup_sip {vertical-align: sub; width: 85px;}
    @media (max-width:768px){
        #example tr td .smartsip_plus{width: 80px;max-width: inherit;}
        #example tr td .smartsip{width: 60px;max-width: inherit;}
        #example tr td .sip_plus{width: 45px;max-width: inherit;}
        #example tr td .stepup_sip{width: 60px;max-width: inherit;}
    }
</style>
<section class="white pt_35 page_title"> 
			<div class="container">
                <?php 
                    //$text= ['page'=>'SIP calendar']; 
                    //$this->load->view('breadcrumbs', $text); 
                ?> 
				<div class="row pb_25"> 
          <div class="col l8 offset-l2">
					<div id="event_calendar">
            <div class="modal-content">  
                 <div id='calendar'></div>
           
            </div>
        </div>
</div>
 <div id="summary" class="col s12 mt_20">

        <?php 
        if(!empty($order_data) || !empty($smartsip_order_data)){ ?>
            <div class="table_h_scroll">
        <table id="example" class="mdl-data-table">
            <thead>
                <tr>
                    <th>Scheme Name</th>
                    <th>Invested Amount</th>
                    <th>Start Date</th>
                   <th>Order Type</th>
                    <th>Cancel Request</th>  
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($order_data as $order){ ?>
                        <tr>                                    
                            <td><?php echo $order['Scheme_Name']; ?></td>
                            <td><?php echo $order['installment_amount']; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($order['start_date']));  ?></td>
                            <?php 
                              if($order['order_type'] == 'stepup sip') { 
                            ?>
                                <td><img src="<?php CDN_URL?>/images/step-up-sip.svg" alt="StepUp SIP" class="responsive-img stepup_sip"></td>
                            <?php 
                              } 
                              else{
                            ?>
                                <td><?php echo strtoupper($order['order_type']); ?></td>
                            <?php
                              }
                            ?>
                            <td>
                              <?php if($order['cancel_request_status'] == 'pending'){ ?>
                                <p class="red-text">Request is pending</p>
                              <?php }else{ ?>
                              <button class="btn red btn-small cancel" data-order="<?php echo $order['order_type']; ?>" data-id="<?php echo $order['sip_reg_id']; ?>" data-order_id="<?php echo $order['master_id'] ?>" data-start_date="<?php echo $order['start_date'] ?>" >Cancel</button>
                            <?php } ?>
                            </td>
                            
                        </tr>
                    <?php } ?>

                    <?php
                    // x($smartsip_order_data);              
                    foreach ($smartsip_order_data as $order){ ?>
                        <tr>                                    
                            <td><?php echo $order['Scheme_Name']; ?></td>
                            <td><?php echo $order['installment_amount']; ?></td>
                            <td><?php echo date('d-m-Y',strtotime($order['start_date']));  ?></td>
                            <td>
                              <?php 
                                // echo $order['type_text'];
                              if($order['type_text'] == SMART_SIP_TOPUP){
                                    echo '<img width="100px" src="'.CDN_URL.'/images/smartsip_logos/smartsip_plus.png" alt="" class="responsive-img smartsip_plus">';
                                }else if($order['type_text'] == SMART_SIP_NO_TOPUP){
                                    echo '<img width="80px" src="'.CDN_URL.'/images/smart-sip.png" alt="" class="responsive-img smartsip">';
                                }else if($order['type_text'] == SUPERSIP){
                                    echo '<img width="50px" src="'.CDN_URL.'/images/smartsip_logos/sip_plus_logo.png" alt="" class="responsive-img sip_plus">';
                                }
                              ?>
                            </td>
                            <?php if($order['order_status'] == 2) {?>
                              <td>
                                <?php if($order['stop_redeem_request'] == 2) {
                                  echo '<p class="red-text">Request is pending</p>';
                                }
                                  else{
                                    $next_t_3_day = date('Y-m-d', strtotime($order['next_t_day']. ' + 3 days'));
                                  ?>
                                  <?php if(!($order['t_3day'] <= date('Y-m-d') 
                                  && $next_t_3_day >= date('Y-m-d')) || $order['pause_sip'] == 1){ 
                                    ?>

                                    <?php if ($order['order_type'] == 'smartsip'){ ?>
                                        <!-- smartsip -->
                                        <button class="btn red btn-small stopSM" data-master="<?php echo $order['id']; ?>" data-order_type="smartsip">Cancel</button>
                                    <?php }else{ ?>
                                    <button class="btn red btn-small stopSM" data-master="<?php echo $order['id']; ?>">Cancel</button>
                                  <?php }}else{ ?>
                                    <p>--</p>
                              <?php } }?>
                              </td>
                            <?php }else{?> 
                            <td>
                              <?php if($order['cancel_request_status'] == 'pending'){ ?>
                                <p class="red-text">Request is pending</p>
                              <?php }else{ ?>
                              <button class="btn red btn-small cancel" data-order="<?php echo $order['type_text']; ?>" data-id="<?php echo $order['sip_reg_id']; ?>" data-order_id="<?php echo $order['id'] ?>" data-start_date="<?php echo $order['start_date'] ?>" >Cancel</button>
                            </td>
                            <?php } ?>
                            <?php } // td close else?>
                            
                        </tr>
                    <?php } ?>

                    
                   
            </tbody>
        </table>
    </div>
        <?php }else{ ?>
            <p class="red-text">No Records Found</p>
        <?php } ?>

      </div>
  </div><!--/.row-->

        </div><!--/.container-->
    </section>
<link href='<?php echo CDN_URL; ?>/css/fullcalendar.css' rel='stylesheet' /> 
<script src='<?php echo CDN_URL; ?>/js/calendar-moment.min.js'></script>
<script src='<?php echo CDN_URL; ?>/js/fullcalendar.js'></script>
<script type="text/javascript">
$(document).ready(function () {
	 $.ajax({
            url: base_url + "Scheme_listing/disableDatesSIP",
            data: 
            {
                'mandate_amount'             :  '1000000',
                'installment_amount'         :  '200',
                'scheme_code'                :  '02G',
                'order_type'                 :  'normal'
                
            },
            dataType: "json",
            type:  "post",
            success: function(data) 
            {   
                
                var data1 = data.disabled_dates;
                var i = 0;
                var dates = [];
                var disableDays = function (day) {
                    console.log(day)
                      // An array of dates.
                        //new Date(2018,04,19).toDateStri   ng()
                        
                          for (var res in data1) {
                                 
                              dates[i] =  new Date(day.getFullYear(), day.getMonth(), data[res]).toDateString();
                               i = i+ 1;
                          }
                         
          
                      // Check to see if this date is in our array.
                      if (dates.indexOf(day.toDateString()) >= 0) {
                        return true; // Disables date.
                      }
                      return false; // Date is availble.
                 }
                 //calendar render events
                 var today = new Date(); 
                 var today = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
                 $('#calendar').fullCalendar({
                      header: {
                        left: 'prev,next',
                        center: 'title',
                        right: 'month'
                      },
                      // defaultDate: today,
                      // navLinks: true, // can click day/week names to navigate views
                       selectable: true,
                      // selectHelper: true,
                      validRange: {
                        start: new Date()
                      },
                       // viewRender: function (view, element) {
                       //  console.log(view)
                       //  $('#calendar').find('table').find('table').find('thead').find('tr').find('td').find('a').attr('data-goto','dasdas')
                       // },
                      select: function(start, end) {
                        //var title = prompt('Event Title:');
                        var eventData;
                        var start_date= new Date(start);
                        console.log(start_date);
                        // var d = new Date('2018-12-18'); //
                         var monthh = start_date.getMonth();
                         console.log(start_date.getFullYear(), start_date.getMonth() + 1, start_date.getDate());
                         var sip_date = moment(start_date).format("YYYY-MM-DD");
                         console.log(sip_date);
                          console.log(data.disabled_dates);

                          var array = data.disabled_dates;
                          function checkDate(check_date) {
                                return (check_date == start_date.getDate())
                             } 

                         if(array.some(checkDate)){
                          swal('','Date Is Disabled, Pick some other date');
                         }
                         else{
                            sip_date = moment(sip_date).format("Do MMM YYYY");
                          $('#date').val(sip_date)
                          $('#event_calendar').modal('close')
                         }
                        // if (title) {
                        //   eventData = {
                        //     title: title,
                        //     start: start,
                        //     end: end
                        //   };
                        //   $('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
                        // }
                        // $('#calendar').fullCalendar('unselect');
                      },
                      editable: true,
                      eventLimit: true, // allow "more" link when too many events
                      eventMouseover: function(calEvent, jsEvent) {
                              console.log(calEvent.start._i ); 
                             
                         // var start_date= new Date(start);
                         // start_date.setMonth(start_date.getMonth() + 1);
                         // var monthh = start_date.getMonth() + 1;
                         // var sip_date = start_date.getFullYear()+'-'+monthh+'-'+start_date.getDate();

                    $('.tooltipevent').remove();

                    var tooltip = '<div class="fc-popover fc-more-popover tooltipevent" style="top: 262.203px; left: 250px; z-index: 9999999; background: white; "><div class="fc-header fc-widget-header"><span class="fc-close fc-icon fc-icon-x"></span><span class="fc-title" style="white-space: normal;">'+calEvent.start._i+'</span><div class="fc-clear"></div></div><div class="fc-body fc-widget-content"><div class="fc-event-container"><a class="fc-day-grid-event fc-h-event fc-event fc-not-start fc-end fc-draggable"><div class="fc-content"> <span class="fc-title" style="white-space: normal;">'+calEvent.description+'</span></div></a><a class="fc-day-grid-event fc-h-event fc-event fc-start fc-end fc-draggable"><div class="fc-content"><span class="fc-time"></span> <span class="fc-title" style="white-space: normal;" >'+calEvent.type+'</span></div></a></div></div></div>';
                    
                    $('#event_calendar').find('.modal-content').append(tooltip);
                    var docW = $(document).width();
                   $('.tooltipevent').css('left',jsEvent.pageX - (docW/6));      // <<< use pageX and pageY
                    $('.tooltipevent').css('top',jsEvent.pageY - 150);      // <<< use pageX and pageY
                   
                    $('.fc-close').on('click',function(){ $(this).parent('div').parent('div').remove()})
                        
                    
                    // $(this).mouseover(function(e) {
                     
                    // }).mousemove(function(e) {
                        
                    //     $('.tooltipevent').remove();
                    // });
                },
                 eventMouseout: function(calEvent, jsEvent) {
                    console.log('out')
                         $('.tooltipevent').remove();
                   },
                   

                      events:  data.event
                        
                      
                    });

               



                
            } //ajax success
        });

    $('.cancel').on('click',function(){
      var obj_this = $(this);
      swal({
                title: "Are you sure you want to cancel?",
                text: '',
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "red",
                confirmButtonText: "Yes!",
                closeOnConfirm: false
            },
            function(){
              obj_this.prop('disabled',true);
                $.ajax({
                  url: base_url + "mfbo/OrderStatus/sendCancelRequest",
                  data: 
                  {
                      'sip_reg_id'             :  obj_this.attr('data-id'),
                      'order_type'             :  obj_this.attr('data-order'),
                      'order_id'             :  obj_this.attr('data-order_id'),
                      'start_date'             :  obj_this.attr('data-start_date')
                      
                      
                  },
                  dataType: "json",
                  type:  "post",
                   beforeSend: function ()
                  {
                    
                  },
                  success: function(data) 
                  {   
                    obj_this.replaceWith( "<p class='red-text'>Request is pending</p>" );
                    if(data.status == 'success')
                    {
                      swal('','Your Request was successfully recieved. We will get back to you shortly.')
                    }else{
                      swal('','Something went wrong. Please try later')
                    }
                  }
              }); 
            });
           
    });

    $('body').on('click','.stopSM',function(){
        var obj = $(this);
        var masterid = obj.data('master');
        var order_type = obj.data('order_type');
        // alert(masterid);
        swal({
                title: "Are you sure you want to cancel?",
                text: '',
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "red",
                confirmButtonText: "Yes!",
                closeOnConfirm: false
            },
            function(){
              obj.prop('disabled',true);
                $.ajax({
                    type: 'post',
                    url: base_url + 'Smart_sip_order/redeemAndStopSM',
                    data:{masterid:masterid,type:2,order_type:order_type},
                    success:function(response){
                        $('#img_loader').remove();
                        // console.log(response);
                        var jsonObj = jQuery.parseJSON(response);
                        obj.replaceWith( "<p class='red-text'>Request is pending</p>" );
                        if(jsonObj.status == 'success')
                        {
                          swal('','Your Request was successfully recieved. We will get back to you shortly.')
                        }else{
                          swal('','Something went wrong. Please try later')
                        }
                        
                    }
                });
            });
    });
});


</script>
