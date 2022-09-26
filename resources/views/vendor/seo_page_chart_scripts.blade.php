
 <script type="text/javascript">
        var base_url = "<?php echo url('/') ?>";
		var color = Chart.helpers.color;
		var Keywordconfig = {
			type: 'bar',
			data: {
				labels: [],
				datasets: [
				{
					label: 'Total Keywords',
					backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
					borderColor: window.chartColors.red,
					borderWidth: 1,
					data: [],
				}
				]
			},
			options: {
			  responsive: true,
			  title: {
				display: false,
				text: ''
			  }
			}
		};
		
		var KeywordChartConfig = {
			 type: 'pie',
			 data: {
			 datasets: [{
				 data: [],
				 backgroundColor: [
					color(window.chartColors.red).alpha(1.0).rgbString(),
					color(window.chartColors.red).alpha(0.25).rgbString(),
					color(window.chartColors.orange).alpha(1.0).rgbString(),
					color(window.chartColors.orange).alpha(0.25).rgbString(),
					color(window.chartColors.yellow).alpha(1.0).rgbString(),
					color(window.chartColors.yellow).alpha(0.25).rgbString(),
					color(window.chartColors.green).alpha(1.0).rgbString(),
					color(window.chartColors.green).alpha(0.25).rgbString(),
					color(window.chartColors.blue).alpha(1.0).rgbString(),
					color(window.chartColors.blue).alpha(0.25).rgbString(),
					color(window.chartColors.purple).alpha(1.0).rgbString(),
					color(window.chartColors.purple).alpha(0.25).rgbString(),

				  ],
				  label: ''
			}],
			labels: []
		  },
		  options: {
			responsive: true,
			  legend: {
				  position: 'right'
			  },
		  }
		};
		
	
		
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#google_organic_keywords').DataTable({
                processing: true,
                serverSide: true,
                "deferRender": true,
                "order": [[1, "asc"]],
                'ajax': {
                    'url': base_url + '/ajaxOrganicKeywords',
                    'data': function (data) {
                        data.campaign_id = $('.campaignID').val();

                    }
                }
            });

			$('#LiveKeywordTrackingTable').DataTable({
				processing: true,
                serverSide: true,
				async: false,
				'ajax': {
                    'url': base_url + '/ajaxLiveKeywordTrackingData',
                    'data': function (data) {
                        data.campaign_id = $('.campaignID').val();
                    }
                } ,
				"columnDefs":[
					{ "targets":[11], "orderable":false }
				]
			});

			$('#backlink_profile').DataTable({
				"processing":true,
				"serverSide":true,
				"ajax":{
					'url':base_url + "/ajax_backlink_profile_datatable",
					'data': function (data) {
                        data.campaign_id = $('.campaignID').val();
                    }
				},

				"order": [[ 1, "asc" ]]
			});

			
			
			
        });

		
	


$(document).ready(function(){
	
var campaignId = $('.campaignID').val();
	$.ajax({
		type: "GET",
		url: $('.base_url').val()+"/keywordsMetricBarChart",
		data: {campaignId:campaignId},
		dataType: 'json',
		success: function(result){
			var ctxs = document.getElementById('keywordsCanvas').getContext('2d');
			window.myLineKeyword = new Chart(ctxs, Keywordconfig);
			
			Keywordconfig.data.labels =  JSON.parse(result['names']);
			Keywordconfig.data.datasets[0].data = JSON.parse(result['values']);
			window.myLineKeyword.update();
		}
	});
	
	$.ajax({
		type: "GET",
		url: $('.base_url').val()+"/keywordsMetricPieChart",
		data: {campaignId:campaignId},
		dataType: 'json',
		success: function(result){
			var ctxPie = document.getElementById('keywordsCanvasChartArea').getContext('2d');
			window.myLinePie = new Chart(ctxPie, KeywordChartConfig);
			
			KeywordChartConfig.data.labels =  JSON.parse(result['names']);
			KeywordChartConfig.data.datasets[0].data = JSON.parse(result['values']);
			window.myLinePie.update();
		}
	});
	
	
	
});

	
	
	
    </script>