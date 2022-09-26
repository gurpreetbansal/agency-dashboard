<html>
    <head>
        <title>Invoice-{{@$invoice->invoice_number}}.pdf</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no">
    	<meta http-equiv="x-ua-compatible" content="ie=edge">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
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
										Invoice No.: <strong style="font-weight: normal;">{{@$invoice->invoice_number}}</strong></p>
									<p style="display: block; line-height: 1; margin: 0; font-weight: bold; color: #242424;">
										Date: <strong style="font-weight: normal;">{{date('M d, Y',strtotime(@$invoice->invoice_created_date))}}</strong>
									</p>
								</td>
							</tr>
						</table>					
					</div>
					<div style="padding: 30px 20px 10px 20px;">
						<p style="font-family: 'Roboto', sans-serif; font-size:14px; margin: 0; font-weight: bold; color: #242424">
							Bill To: <strong style="font-weight: normal;">{{@$user->name}}</strong>
						</p>
					</div>
					<div style="font-family: 'Roboto', sans-serif; font-size: 14px; padding: 0 20px 30px 20px;">
						<p style="display: block; line-height: 1; margin: 0 0 2px 0; font-weight: bold; color: #242424;">
							<span>Address:</span> 
							<strong style="font-weight: normal;">{{@$user->UserAddress->address_line_1.' '.@$user->UserAddress->address_line_2}}</strong>
						</p>
						<p style="display: block; line-height: 1; margin: 0 0 2px 0; font-weight: bold; color: #242424;">
							<span style="opacity: 0; visibility: hidden;">Address:</span> 
							<strong style="font-weight: normal;">{{@$user->UserAddress->city.','.@$user->UserAddress->zip}}</strong>
						</p>
						<p style="display: block; line-height: 1; margin: 0 0 2px 0; font-weight: bold; color: #242424">
							<span style="opacity: 0; visibility: hidden;">Address:</span> 
							<strong style="font-weight: normal;">{{@$user->UserAddress->country_name}}</strong>
						</p>
					</div>
					<table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="margin-bottom: 50px;">
						<tr>
							<td style="padding:0px;">
								<table cellspacing="0" border="0" cellpadding="0" width="100%">
									<thead style="background: #327aee;">
										<tr>
											<td style="width:47%; font-family: 'Roboto', sans-serif; padding: 15px 20px 15px 20px; color:#fff; font-size:14px; font-weight: bold;"><strong>Description</strong></td>
											<td style="width:35%; font-family: 'Roboto', sans-serif; padding: 15px 20px 15px 20px; color:#fff; font-size:14px; font-weight: bold;"></td>										
											<td style="width:20%; font-family: 'Roboto', sans-serif; padding: 15px 20px 15px 20px; color:#fff; font-size:14px; font-weight: bold;"><strong>Amount (INR)</strong></td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												{{@$invoice->invoices_item->description}}
												<br>
												{{date('M d, Y',strtotime($invoice->current_period_start)) .' - '.date('M d, Y',strtotime($invoice->current_period_end))}}
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												<span style=" display: inline-block; align-items: center; white-space: nowrap;vertical-align: middle;"><i class="fa fa-rupee" style="position: relative;line-height: 1;top: 2px;"></i>
												<?php
												if($invoice->subscription->subscription_interval == '1 year'){
								                    echo number_format($invoice->subscription->amount);
								                }else{
								                     echo number_format($invoice->subscription->amount);
								                }
								                ?>
								                </span>
											</td>
										</tr>
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												<strong style="font-weight: bold;">Subtotal</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; border-bottom: 1px solid rgba(0,0,0,0.1); font-weight: normal;">
												<span style=" display: inline-block; align-items: center; white-space: nowrap;vertical-align: middle;"><i class="fa fa-rupee" style="position: relative;line-height: 1;top: 2px;"></i>
												@if($invoice->discount != 0)
												{{number_format(@$invoice->amount_paid - @$invoice->discount)}}
												@else
												{{number_format(@$invoice->amount_paid)}}
												@endif
												</span>
											</td>
										</tr>
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<strong style="display: block; font-weight: bold;">Total</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<span style=" display: inline-block; align-items: center; white-space: nowrap;vertical-align: middle;"><i class="fa fa-rupee" style="position: relative;line-height: 1;top: 2px;"></i>
												{{number_format(@$invoice->amount_paid)}}
											</span>
											</td>
										</tr>
									</tbody>
									<tfoot style="background: rgba(50, 122, 238, 0.1);">
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<strong style="display: block; font-weight: bold;">Amount paid</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<span style=" display: inline-block; align-items: center; white-space: nowrap;vertical-align: middle;"><i class="fa fa-rupee" style="position: relative;line-height: 1;top: 2px;"></i>
													{{number_format(@$invoice->amount_paid)}}
												</span>
											</td>
										</tr>
									</tfoot>
									<tfoot style="background: rgba(50, 122, 238, 0.1);">
										<tr>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;"></td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<strong style="display: block; font-weight: bold;">Amount Due</strong>
											</td>
											<td style="font-family: 'Roboto', sans-serif; font-size:14px; padding: 15px 20px 15px 20px; color:#242424; font-weight: normal;">
												<span style=" display: inline-block; align-items: center; white-space: nowrap;vertical-align: middle;"><i class="fa fa-rupee" style="position: relative;line-height: 1;top: 2px;"></i>
													{{number_format(@$invoice->amount_remaining)}}
												</span>
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