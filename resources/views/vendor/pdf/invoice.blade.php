<html>
    <head>
        <title>Invoice-{{@$number}}.pdf</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    	<meta http-equiv="x-ua-compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    </head>
    <body style="margin: 0; padding: 30px 0 0 0; border: 1px solid rgba(0,0,0,0.1);">
		<table cellspacing="0" border="0" align="center" cellpadding="0" width="100%" style="max-width: 650px;">
			<tr>
				<td>
					<div style="margin-top: 0; padding: 0 0 35px 0; text-align: center;">
						<img src="https://agencydashboard.io/public/front/img/logo.png" alt="agencydashboard-logo" width="227" height="35" />
					</div>
					<div style="border-top: 5px solid #327aee; padding: 30px 30px; background: rgba(50, 122, 238, 0.1);">
						<table cellspacing="0" border="0" cellpadding="0" width="100%" style="table-layout: fixed;">
							<tr>
								<td align="left" style="padding: 0;">
									<h1 style="font-family: 'Roboto', sans-serif; font-size: 35px; color: #327aee; margin: 0; text-transform: uppercase;">Invoice</h1>
								</td>
								<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 0; color: #327aee;">
									<p style="display: block; line-height: 1; margin: 0 0 5px 0; font-weight: bold; color: #242424;">
										Invoice No.: <strong style="font-weight: normal;">{{@$number}}</strong></p>
									<p style="display: block; line-height: 1; margin: 0; font-weight: bold; color: #242424;">
										Date: <strong style="font-weight: normal;">{{date('M d, Y',@$created)}}</strong>
									</p>
								</td>
							</tr>
						</table>					
					</div>
					<div style="padding: 30px 20px 10px 20px;">
						<p style="font-family: 'Roboto', sans-serif; font-size:14px; margin: 0; font-weight: bold; color: #242424">
							Bill To: <strong style="font-weight: normal;">{{@$customer_shipping['name']}}</strong>
						</p>
					</div>
					<div style="font-family: 'Roboto', sans-serif; font-size: 14px; padding: 0 20px 30px 20px;">
						<p style="display: block; line-height: 1; margin: 0 0 2px 0; font-weight: bold; color: #242424;">
							<span>Address:</span> 
							<strong style="font-weight: normal;">{{@$customer_address['line1'].' '.@$customer_address['line2']}}</strong>
						</p>
						<p style="display: block; line-height: 1; margin: 0 0 2px 0; font-weight: bold; color: #242424;">
							<span style="opacity: 0; visibility: hidden;">Address:</span> 
							<strong style="font-weight: normal;">{{@$customer_address['city'].','.@$customer_address['postal_code']}}</strong>
						</p>
						<p style="display: block; line-height: 1; margin: 0 0 2px 0; font-weight: bold; color: #242424">
							<span style="opacity: 0; visibility: hidden;">Address:</span> 
							<strong style="font-weight: normal;">{{@$customer_address['country']}}</strong>
						</p>
					</div>
					<table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="margin-bottom: 50px;">
						<tr>
							<td style="padding:0px;">
								<table cellspacing="0" border="0" cellpadding="0" width="100%">
									<thead style="background: #327aee;">
										<tr>
											<td style="width:47%; font-family: 'Roboto', sans-serif; padding: 15px 20px 15px 20px; color:#fff; font-size:14px; font-weight: bold;">
												<strong>Description</strong>
											</td>
											<td style="width:35%; font-family: 'Roboto', sans-serif; padding: 15px 20px 15px 20px; color:#fff; font-size:14px; font-weight: bold;"></td>										
											<td style="width:20%; font-family: 'Roboto', sans-serif; padding: 15px 20px 15px 20px; color:#fff; font-size:14px; font-weight: bold;">
												<strong>Amount</strong>
											</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												{{@$lines['data'][0]['description']}}
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												@if($lines['data'][0]['plan']['interval'] == 'year')
												${{number_format((@$lines['data'][0]['plan']['amount']*12/100))}}
												@else
												${{number_format((@$lines['data'][0]['plan']['amount']/100))}}
												@endif
											</td>
										</tr>
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												<strong style="font-weight: bold;">Subtotal</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												@if($lines['data'][0]['plan']['interval'] == 'year')
												${{number_format((@$lines['data'][0]['amount']*12/100))}}
												@else
												${{number_format((@$lines['data'][0]['amount']/100))}}
												@endif
											</td>
										</tr>
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<strong style="display: block; font-weight: bold;">Paid</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												@if($lines['data'][0]['plan']['interval'] == 'year')
													@if(isset($total_discount_amounts) && !empty($total_discount_amounts))
													${{number_format((@$lines['data'][0]['plan']['amount'] *12/100) - (@$total_discount_amounts[0]['amount']/100))}}
													@else
													${{number_format(@$amount_paid*12/100)}}
													@endif
												@else
													@if(isset($total_discount_amounts) && !empty($total_discount_amounts))
													${{number_format((@$lines['data'][0]['plan']['amount']/100) - (@$total_discount_amounts[0]['amount']/100))}}
													@else
													${{number_format(@$amount_paid/100)}}
													@endif
												@endif
											</td>
										</tr>
									</tbody>
									<tfoot style="background: rgba(50, 122, 238, 0.1);">
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<strong style="display: block; font-weight: bold;">Amount due</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												${{number_format(@$amount_remaining/100)}}
											</td>
										</tr>
									</tfoot>
								</table>
							</td>
						</tr>
					</table>
					<div style="border-top: 1px solid rgba(0,0,0,0.1); padding: 20px 20px 0 20px; position: fixed; bottom: 70px; width: 100%; left: 0;">
						<table cellspacing="0" border="0" cellpadding="10" width="100%" style="table-layout: fixed;">
							<tr>
								<td align="left" style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 0 20px 0 0; color: #242424;">
									<a href="tel:(0172) 466-6470" style="color: #000; text-decoration: none;">(0172) 466-6470</a>
								</td>
								<td align="center" style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 0 10px 0 10px; color: #242424;">
									<a href="mailto: support@agencydashboard.io" style="color: #000; text-decoration: none;">support@agencydashboard.io</a>
								</td>
								<td align="right" style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 0 0 0 20px; color: #242424;">
									<a href="mailto:support@agencydashboard.io" style="color: #000; text-decoration: none;">www.agencydashboard.io</a>
								</td>
							</tr>
						</table>
					</div>
				</td>   
			</tr>
		</table>
	</body>
</html>