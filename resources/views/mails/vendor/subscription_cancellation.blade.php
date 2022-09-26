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
                            <td style="padding: 40px 40px 20px 40px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:16px; font-weight:600; letter-spacing:0.07em; line-height:2em;">
                                Hi {{$name}} !
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 0 10px 10px 10px; text-align: center">
                                <img src="{{URL::asset('/public/front/img/unsubscribe@2x.png')}}" aria-hidden="true"
                                width="130"
                                height="130" alt="alt_text"
                                border="0" class="center-on-narrow"
                                style="width: 100%; max-width: 130px; height: auto; background: #ffffff; font-family: sans-serif; font-size: 15px; line-height: 20px; color: #555555;">
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:0 40px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:13.5px; font-weight:300; letter-spacing:0.07em; line-height:2em;">
                                    Your membership at Agency Dashboard has been cancelled.
                                <br><br>
                                    @if($package_type == 'year')
                                    Membership Level: {{$package_name.' ('.$currency.($package_price/12).') '.' per month'}}
                                    @else
                                    Membership Level: {{$package_name.' ('.$currency.$package_price.') '.' per month'}}
                                    @endif
                                    <br><br>
                                    If you did not request this cancellation and would like more information please contact us at <a href="mailto:support@agencydashboard.io">support@agencydashboard.io</a>
                                <br><br>
                            </td>
                        </tr>                      
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</td>
</tr>

<!-- Email Body : END -->
@include('mails.includes.footer')