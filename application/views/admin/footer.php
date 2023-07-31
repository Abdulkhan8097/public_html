<footer id="page-header" style="height: 60px;margin-top: 0px;padding: 20px;">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-12">
                <div class="copyright">
                    &copy; <?php echo date('Y'); ?> Copyright <strong><a href="<?php echo FRONT_HOME_URL; ?>"
                            target="_blank"><?php echo SITE_FULL_NAME; ?></a></strong>. All Rights Reserved.
                </div>
            </div>
        </div>
    </div>
</footer><!-- #footer -->
<!-- JAVASCRIPT FILES -->
<script type="text/javascript">
var plugin_path = '<?php echo ADMIN_ASSETS; ?>assets/plugins/';
</script>
<script type="text/javascript" src="<?php echo ADMIN_ASSETS; ?>assets/plugins/jquery/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_ASSETS; ?>assets/js/app.js"></script>
<!-- Data Table Javascript Files Start -->
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>


<script>
    $(document).ready(function() {
        $('.example').DataTable({
                
                "oTableTools": {},
                "pageLength": 50
            } );
       
    });
</script>


<!-- Data Table Javascript Files End -->
<!-- PAGE LEVEL SCRIPT -->
<script type="text/javascript">
/* 
				Toastr Notification On Load 

				TYPE:
					primary
					info
					error
					success
					warning

				POSITION
					top-right
					top-left
					top-center
					top-full-width
					bottom-right
					bottom-left
					bottom-center
					bottom-full-width
					
				false = click link (example: "http://www.stepofweb.com")
			*/

<?php if ($this->uri->segment(2) == 'dashboard') { ?>
_toastr("Welcome Back", "top-right", "info", false);
<?php } ?>
<?php if ($this->session->flashdata('success') != '') { ?>
_toastr("<?php echo $this->session->flashdata('success'); ?>", "top-right", "success", false);
<?php } ?>
<?php if ($this->session->flashdata('error') != '') { ?>
_toastr("<?php echo $this->session->flashdata('error'); ?>", "top-right", "error", false);
<?php } ?>
<?php if ($this->session->flashdata('warning') != '') { ?>
_toastr("<?php echo $this->session->flashdata('warning'); ?>", "top-right", "warning", false);
<?php } ?>
<?php if ($this->session->flashdata('info') != '') { ?>
_toastr("<?php echo $this->session->flashdata('info'); ?>", "top-right", "info", false);
<?php } ?>




/** SALES CHART
 ******************************************* **/
<?php if ($this->uri->segment(2) == 'dashboard') { ?>
loadScript(plugin_path + "chart.flot/jquery.flot.min.js", function() {
    loadScript(plugin_path + "chart.flot/jquery.flot.resize.min.js", function() {
        loadScript(plugin_path + "chart.flot/jquery.flot.time.min.js", function() {
            loadScript(plugin_path + "chart.flot/jquery.flot.fillbetween.min.js", function() {
                loadScript(plugin_path + "chart.flot/jquery.flot.orderBars.min.js",
                    function() {
                        loadScript(plugin_path +
                            "chart.flot/jquery.flot.pie.min.js",
                            function() {
                                loadScript(plugin_path +
                                    "chart.flot/jquery.flot.tooltip.min.js",
                                    function() {

                                        if (jQuery("#flot-sales").length >
                                            0) {

                                            /* DEFAULTS FLOT COLORS */
                                            var $color_border_color =
                                                "#eaeaea",
                                                /* light gray 	*/
                                                $color_second =
                                                "#5a6667"; /* blue      	*/


                                            var d = [
                                                [1196463600000, 0],
                                                [1196550000000, 0],
                                                [1196636400000, 0],
                                                [1196722800000, 77],
                                                [1196809200000, 3636],
                                                [1196895600000, 3575],
                                                [1196982000000, 2736],
                                                [1197068400000, 1086],
                                                [1197154800000, 676],
                                                [1197241200000, 1205],
                                                [1197327600000, 906],
                                                [1197414000000, 710],
                                                [1197500400000, 639],
                                                [1197586800000, 540],
                                                [1197673200000, 435],
                                                [1197759600000, 301],
                                                [1197846000000, 575],
                                                [1197932400000, 481],
                                                [1198018800000, 591],
                                                [1198105200000, 608],
                                                [1198191600000, 459],
                                                [1198278000000, 234],
                                                [1198364400000, 4568],
                                                [1198450800000, 686],
                                                [1198537200000, 4122],
                                                [1198623600000, 449],
                                                [1198710000000, 468],
                                                [1198796400000, 392],
                                                [1198882800000, 282],
                                                [1198969200000, 208],
                                                [1199055600000, 229],
                                                [1199142000000, 177],
                                                [1199228400000, 374],
                                                [1199314800000, 436],
                                                [1199401200000, 404],
                                                [1199487600000, 544],
                                                [1199574000000, 500],
                                                [1199660400000, 476],
                                                [1199746800000, 462],
                                                [1199833200000, 500],
                                                [1199919600000, 700],
                                                [1200006000000, 750],
                                                [1200092400000, 600],
                                                [1200178800000, 500],
                                                [1200265200000, 900],
                                                [1200351600000, 930],
                                                [1200438000000, 1200],
                                                [1200524400000, 980],
                                                [1200610800000, 950],
                                                [1200697200000, 900],
                                                [1200783600000, 1000],
                                                [1200870000000, 1050],
                                                [1200956400000, 1150],
                                                [1201042800000, 1100],
                                                [1201129200000, 1200],
                                                [1201215600000, 1300],
                                                [1201302000000, 1700],
                                                [1201388400000, 1450],
                                                [1201474800000, 1500],
                                                [1201561200000, 1510],
                                                [1201647600000, 1510],
                                                [1201734000000, 1510],
                                                [1201820400000, 1700],
                                                [1201906800000, 1800],
                                                [1201993200000, 1900],
                                                [1202079600000, 2000],
                                                [1202166000000, 2100],
                                                [1202252400000, 2200],
                                                [1202338800000, 2300],
                                                [1202425200000, 2400],
                                                [1202511600000, 2550],
                                                [1202598000000, 2600],
                                                [1202684400000, 2500],
                                                [1202770800000, 2700],
                                                [1202857200000, 2750],
                                                [1202943600000, 2800],
                                                [1203030000000, 3245],
                                                [1203116400000, 3345],
                                                [1203202800000, 3000],
                                                [1203289200000, 3200],
                                                [1203375600000, 3300],
                                                [1203462000000, 3400],
                                                [1203548400000, 3600],
                                                [1203634800000, 3700],
                                                [1203721200000, 3800],
                                                [1203807600000, 4000],
                                                [1203894000000, 4500]
                                            ];

                                            for (var i = 0; i < d.length; ++
                                                i) {
                                                d[i][0] += 60 * 60 * 1000;
                                            }

                                            var options = {

                                                xaxis: {
                                                    mode: "time",
                                                    tickLength: 5
                                                },

                                                series: {
                                                    lines: {
                                                        show: true,
                                                        lineWidth: 1,
                                                        fill: true,
                                                        fillColor: {
                                                            colors: [{
                                                                opacity: 0.1
                                                            }, {
                                                                opacity: 0.15
                                                            }]
                                                        }
                                                    },
                                                    //points: { show: true },
                                                    shadowSize: 0
                                                },

                                                selection: {
                                                    mode: "x"
                                                },

                                                grid: {
                                                    hoverable: true,
                                                    clickable: true,
                                                    tickColor: $color_border_color,
                                                    borderWidth: 0,
                                                    borderColor: $color_border_color,
                                                },

                                                tooltip: true,

                                                tooltipOpts: {
                                                    content: "Sales: %x <span class='block'>$%y</span>",
                                                    dateFormat: "%y-%0m-%0d",
                                                    defaultTheme: false
                                                },

                                                colors: [$color_second],

                                            };

                                            var plot = jQuery.plot(jQuery(
                                                    "#flot-sales"), [d],
                                                options);
                                        }

                                    });
                            });
                    });
            });
        });
    });
});
<?php } ?>


loadScript(plugin_path + "datatables/js/jquery.dataTables.min.js", function() {
    loadScript(plugin_path + "datatables/dataTables.bootstrap.js", function() {

        $(document).ready(function() {
            $('.datatable_sample').DataTable();
        });
        /*
        		if (jQuery().dataTable) {

        			
        			var table = jQuery('#datatable_sample');
        			table.dataTable({
        				"columns": [{
        					"orderable": false
        				}, {
        					"orderable": true
        				}, {
        					"orderable": false
        				}, {
        					"orderable": false
        				}, {
        					"orderable": true
        				}, {
        					"orderable": false
        				}],
        				"lengthMenu": [
        					[5, 15, 20, -1],
        					[5, 10,15, 20, "All"] // change per page values here
        				],
        				// set the initial value
        				"pageLength": 10,            
        				"pagingType": "bootstrap_full_number",
        				"language": {
        					"lengthMenu": "  _MENU_ records",
        					"paginate": {
        						"previous":"Prev",
        						"next": "Next",
        						"last": "Last",
        						"first": "First"
        					}
        				},
        				"columnDefs": [{  // set default column settings
        					'orderable': false,
        					'targets': [0]
        				}, {
        					"searchable": false,
        					"targets": [0]
        				}],
        				"order": [
        					[1, "asc"]
        				] // set first column as a default sort by asc
        			});

        			var tableWrapper = jQuery('#datatable_sample_wrapper');

        			table.find('.group-checkable').change(function () {
        				var set = jQuery(this).attr("data-set");
        				var checked = jQuery(this).is(":checked");
        				jQuery(set).each(function () {
        					if (checked) {
        						jQuery(this).attr("checked", true);
        						jQuery(this).parents('tr').addClass("active");
        					} else {
        						jQuery(this).attr("checked", false);
        						jQuery(this).parents('tr').removeClass("active");
        					}
        				});
        				jQuery.uniform.update(set);
        			});

        			table.on('change', 'tbody tr .checkboxes', function () {
        				jQuery(this).parents('tr').toggleClass("active");
        			});

        			tableWrapper.find('.dataTables_length select').addClass("form-control input-xsmall input-inline"); // modify table per page dropdown

        		}*/

    });
});
</script>
<!-- STYLESWITCHER - REMOVE -->
<!-- <script async type="text/javascript" src="<?php echo ADMIN_ASSETS; ?>assets/plugins/styleswitcher/styleswitcher.js"></script> -->
</body>

</html>