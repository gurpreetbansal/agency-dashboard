@include('mails.includes.header')

<!-- Email Body : BEGIN -->
<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center"
width="100%" style="max-width: 680px;" class="email-container">


<!-- Email Body : BEGIN -->

<tr>
    <td bgcolor="#ffffff">
        <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0"
        width="100%">
        <tbody>
            <tr>
                <!-- dir=ltr is where the magic happens. This can be changed to dir=rtl to swap the alignment on wide while maintaining stack order on narrow. -->
                <td dir="ltr" bgcolor="#ffffff" align="center" height="100%" valign="top" width="100%"
                style="padding: 10px 0;">
                        <!--[if mso]>
                        <table role="presentation" aria-hidden="true" border="0" cellspacing="0" cellpadding="0"
                               align="center"
                               width="660">
                            <tr>
                                <td align="center" valign="top" width="660">
                                <![endif]-->
                                <table role="presentation" aria-hidden="true" border="0" cellpadding="0" cellspacing="0"
                                align="center"
                                width="100%" style="max-width:660px;">
                                <tbody>
                                    <tr>
                                        <td align="center" valign="top" style="font-size:0; padding: 10px 0;">
                                    <!--[if mso]>
                                    <table role="presentation" aria-hidden="true" border="0" cellspacing="0"
                                           cellpadding="0"
                                           align="center" width="660">
                                        <tr>
                                            <td align="left" valign="top" width="220">
                                            <![endif]-->
                                            <div style="display:inline-block; margin: 0 -2px; max-width: 200px; min-width:160px; vertical-align:top; width:100%;"
                                            class="stack-column">
                                            <table role="presentation" aria-hidden="true" cellspacing="0"
                                            cellpadding="0" border="0"
                                            width="100%">
                                            <tbody>
                                                <tr>
                                                    <td dir="ltr" style="padding: 0 10px 10px 10px;">
                                                        <img src="{{URL::asset('/public/front/img/invoice@2x.png')}}"
                                                        aria-hidden="true" width="130" height="130"
                                                        border="0" alt="invoice-img" class="center-on-narrow"
                                                        style="width: 100%; max-width: 130px; height: auto; background: #ffffff; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!--[if mso]>
                                    </td>
                                    <td align="left" valign="top" width="440">
                                    <![endif]-->
                                    <div style="display:inline-block; margin: 0 -2px; min-width:320px; vertical-align:top;" class="stack-column">
                                        <table role="presentation" aria-hidden="true" cellspacing="0"
                                        cellpadding="0" border="0"
                                        width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="padding: 40px 40px 20px 40px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:16px; font-weight:600; letter-spacing:0.07em; line-height:2em;">
                                                    Hi Admin !
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 40px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:13.5px; font-weight:300; letter-spacing:0.07em; line-height:2em;">
                                                    Invoice Date: {{$start}}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                    <!--[if mso]>
                                    </td>
                                    </tr>
                                    </table>
                                <![endif]-->
                            </td>
                        </tr>
                    </tbody>
                </table>
                        <!--[if mso]>
                        </td>
                        </tr>
                        </table>
                    <![endif]-->
                </td>
            </tr>

            <tr>
                <td style="padding:0 40px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:13.5px; font-weight:300; letter-spacing:0.07em; line-height:2em;">
                    @if(strpos($description,"Trial")  !== false)
                    {{$agency_name}} subscription has been purchased
                    @else
                    {{$agency_name}} subscription has been renewed
                    @endif
                    <br>
                    Plan Details:
                </td>
            </tr>

            <tr>
                <td bgcolor="#ffffff">
                    <table role="presentation" cellpadding="0" cellspacing="0"
                    style="font-size:0px; width: 88% !important; min-width: 88%; max-width: 88%;"
                    align="center" border="0">
                    <tbody>
                        <tr>
                            <td style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:20px 0px;">
                                    <!--[if mso | IE]>
                                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style="vertical-align:top;width:600px;">
                                            <![endif]-->
                                            <div class="mj-column-per-100 outlook-group-fix"
                                            style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                            <table role="presentation" cellpadding="0" cellspacing="0" width="100%"
                                            border="0">
                                            <tbody>
                                                <tr>
                                                    <td style="word-break:break-word;font-size:0px;">,,
                                                        <table style="color:#b9b9b9; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; font-size:13px;line-height:22px;"
                                                        border="0" width="100%">
                                                        <thead>
                                                            <tr style="border-bottom:1px solid #ecedee;">
                                                                <th colspan="2" style="font-weight:700;padding:10px 20px;text-align:left;text-transform:uppercase;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;">
                                                                    Product Name
                                                                </th>
                                                                <th style="font-weight:700;padding:10px 20px;text-align:left;text-transform:uppercase;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px; text-align: right">
                                                                    Price
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2" style="font-weight:500;line-height:1;color:#747474;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:14px;padding:10px 20px;text-align:left;word-break:break-all;">
                                                                    {{$package_name}}
                                                                    <?php 
                                                                    if(strpos($description,"Trial") !== false){
                                                                        echo 'Trial for 14 days ('.$start.' - '.$end.')';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td style="font-weight:500;line-height:1;color:#747474;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:14px;padding:10px 20px;text-align:right;">

                                                                    @if($currency == 'inr')
                                                                    <i class="fa fa-inr"></i> {{$package_price}}
                                                                    @else
                                                                    {{"$".$package_price}}
                                                                    @endif

                                                                </td>
                                                                
                                                            </tr>
                                                            <tr style="border-top:3px solid #bcbdbe; border-bottom: 3px solid #bcbdbe">
                                                                <th colspan="2"
                                                                style="font-weight:700;padding:10px 20px;text-align:left;text-transform:uppercase;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;color:#747474">
                                                                Discount:
                                                            </th>
                                                            <td style="font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:13px;font-weight:700;line-height:22px;padding:10px 20px;text-align:right; color:#747474">
                                                               @if($currency == 'inr')
                                                               <i class="fa fa-inr"></i> {{$discounted_value}}
                                                               @else
                                                               {{"$".$discounted_value}}
                                                               @endif
                                                           </td>
                                                           <tr style="border-top:3px solid #bcbdbe; border-bottom: 3px solid #bcbdbe">
                                                            <th colspan="2"
                                                            style="font-weight:700;padding:10px 20px;text-align:left;text-transform:uppercase;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:13px;line-height:22px;color:#747474">
                                                            Total:
                                                        </th>
                                                        <td style="font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:13px;font-weight:700;line-height:22px;padding:10px 20px;text-align:right; color:#747474">
                                                          
                                                           @if($currency == 'inr')
                                                           <i class="fa fa-inr"></i> {{$package_price}}
                                                           @else
                                                           {{"$".$package_price}}
                                                           @endif
                                                       </td>
                                                   </tr>

                                               </tbody>
                                               <tfoot>
                                                <tr>
                                                    <td colspan="2" style="font-weight:500;line-height:1;color:#747474;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:14px;padding:10px 20px;text-align:left;word-break:break-all;">
                                                        Charged Now:
                                                    </td>
                                                    <td style="font-weight:500;line-height:1;color:#747474;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:14px;padding:10px 20px;text-align:right;">
                                                        @if($currency == 'inr')
                                                        <i class="fa fa-inr"></i> {{$amount_paid}}
                                                        @else
                                                        {{"$".$amount_paid}}
                                                        @endif

                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                                    <!--[if mso | IE]>
                                    </td></tr></table>
                                <![endif]--></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            @if(strpos($description,"Trial") !== false)
            <tr>
                <td style="padding:0 40px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:13.5px; font-weight:300; letter-spacing:0.07em; line-height:2em;">
                    <p>Invoice will be charged after the trial ends.</p>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</td>
</tr>

<!-- Email Body : END -->
@include('mails.includes.footer')