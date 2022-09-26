@include('mails.includes.header')
<!-- Email Body : BEGIN -->
<table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="max-width: 680px;" class="email-container rankSection">
    <!-- Email Body : BEGIN -->
    <tr>
        <td bgcolor="#ffffff" style="padding:20px;" class="smalSpace">
            <table role="presentation" aria-hidden="true" cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td
                            style="padding:0 0px 10px 0px; text-align: left; font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif; color:#2e343b; font-size:13.5px; font-weight:300; letter-spacing:0.07em; line-height:2em;">
                            <table class="rankTopSection" cellpadding="0" cellsapcing="0" width="100%">
                                <tr>
                                    <td style="letter-spacing: 0px;">
                                        Hi {{$value->SemrushUserData->clientName}},
                                        <p class="rank-mSpace" style="background:#fafcdf; padding:10px; font-size:13px; margin:5px 0 20px 0; display:block; line-height: 1.6;font-weight: 400 !important;" valign="middle">
                                            Following alerts were triggered during recent rank check
                                        </p>
                                        <p style="font-size:13px; margin: 0 0 10px 0 !important; line-height: 1.5;font-weight: 400 !important;"><strong style="font-weight: 500">Project:</strong> {{$value->SemrushUserData->domain_name.'/ '. $value->SemrushUserData->host_url}}</p>
                                        <p style="font-size:13px; margin: 0 0 10px 0 !important; line-height: 1.5;font-weight: 400 !important;"><strong style="font-weight: 500">Date:</strong> {{date('F d, Y')}}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="randMidSection" style="background:#dfeffc; padding:10px; margin-top:15px; display:block; font-size: 13px; letter-spacing: 0px; font-weight: 400;" valign="middle">
                                        <img src="{{URL::asset('/public/front/img/noti-icon.png')}}" style="display:inline-block; vertical-align:middle; margin-right:3px;">
                                        Alert triggered for {{count($result)}} keywords 
                                        <a href="{{$viewkey_link}}">
                                            <button style="float:right;color: #fff;background: #327aee;border: solid 1px transparent; border-radius: 16px; height: 26px;">
                                                View key
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#ffffff">
                            <table role="presentation" cellpadding="0" cellspacing="0"
                                style="font-size:0px; width: 100% !important; min-width: 100%; max-width: 100%;"
                                align="center" border="0">
                                <tbody>
                                    <tr>
                                        <td
                                            style="text-align:center;vertical-align:top;direction:ltr;font-size:0px;padding:0;">
                                            <div class="mj-column-per-100 outlook-group-fix"
                                                style="vertical-align:top;display:inline-block;direction:ltr;font-size:13px;text-align:left;width:100%;">
                                                <table class="rankTable" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="word-break:break-word;font-size:0px;">,,
                                                                <table
                                                                    style="color:#555; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;"
                                                                    border="0" width="100%">
                                                                    <thead>
                                                                        <tr style="border-bottom:1px solid #ecedee;">
                                                                            <th
                                                                                style="font-weight:600;padding:5px 5px;text-align:left;text-transform:capitalize;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;line-height:14px;">
                                                                                Keyword
                                                                            </th>
                                                                            <th
                                                                                style="font-weight:600;padding:5px 5px;text-align:center; text-transform:capitalize;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;line-height:14px;">
                                                                                Pos. on <br> {{$previous_date}}
                                                                            </th>
                                                                            <th
                                                                                style="font-weight:600;padding:5px 5px;text-align:center; text-transform:capitalize;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;line-height:14px;">
                                                                                Pos. on <br> {{$today}}
                                                                            </th>

                                                                            <th
                                                                                style="font-weight:600;padding:5px 5px;text-align:center;text-transform:capitalize;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;line-height:14px;">
                                                                                Diff
                                                                            </th>
                                                                            <th
                                                                                style="font-weight:600;padding:5px 5px;text-align:center;text-transform:capitalize;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;line-height:14px;">
                                                                                Search Vol.
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($result as $key=>$value)
                                                                        <tr
                                                                            <?php if($value->oneday_position > 0){ echo 'bgcolor="#f6fff8"';}elseif($value->oneday_position < 0){ echo 'bgcolor="#fef9fa"'; }?>>
                                                                            <td
                                                                                style="font-weight:400;line-height:14px;color:#000;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;padding:7px 5px;">

                                                                                <img src="{{$value->regional_flag}}" style="vertical-align:middle; width: 10px; height: auto">
                                                                                {{$value->keyword}}
                                                                            </td>

                                                                            <td
                                                                                style="font-weight:400;line-height:14px;color:#000;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;padding:7px 5px; text-align:center;">
                                                                                {{($value->position === 0 || $value->position === null)?(100 + $value->oneday_position):($value->position + $value->oneday_position)}}
                                                                            </td>

                                                                            <td
                                                                                style="font-weight:400;line-height:14px;color:#000;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;padding:7px 5px; text-align:center;">
                                                                                {{$value->position}}
                                                                            </td>

                                                                            <td
                                                                                style="font-weight:400;line-height:14px;color:#000;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;padding:7px 5px; text-align:center;">
                                                                                <?php if($value->oneday_position > 0){ ?>
                                                                                <img src="{{URL::asset('/public/front/img/up.png')}}"
                                                                                    aria-hidden="true" width="8" alt="up" border="0">
                                                                                <?php echo $value->oneday_position ;}else{ ?>
                                                                                <img src="{{URL::asset('/public/front/img/down.png')}}"
                                                                                    aria-hidden="true" width="8" alt="down" border="0">
                                                                                <?php echo str_replace('-','',$value->oneday_position); } ?>
                                                                            </td>

                                                                            <td
                                                                                style="font-weight:400;line-height:14px;color:#000;font-family:'Poppins', -apple-system, BlinkMacSystemFont, Helvetica, Arial, sans-serif;font-size:10px;padding:7px 5px; text-align:center;">
                                                                                {{$value->sv}}
                                                                            </td>
                                                                        </tr>
                                                                        @if($key == 19) @break; @endif
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <!--[if mso | IE]>
                                    </td></tr></table>
                                <![endif]-->
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <!-- Ship to -->
                </tbody>
            </table>
        </td>
    </tr>

<!-- Email Body : END -->
@include('mails.includes.footer')