<html>
<head>
    <title>Email Verification</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
<body>
    <table cellspacing="0" border="0" align="center" cellpadding="0" width="600" style="border:1px solid #efefef; margin-top:10px; margin-bottom: 30px;">
        <tr>
            <td>
                <table cellspacing="0" border="0" align="center" cellpadding="20" width="100%">
                    <tr align="center" style=" background: #0282B7;" >
                        <td style="font-family:arial; padding:20px;"><strong style="display:inline-block;">
                            <img src="{{URL::asset('public/front/img/logo.png')}}" alt="agencydashboard">
                        </strong></td>
                    </tr>
                </table>
                <table cellspacing="0" border="0" align="center" cellpadding="10" width="100%" style="padding:40px;">
                    <tr>
                        <td style="padding:0px;">
                            <h3 style="font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px;">Hello {{$name}},</h3>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0px;">
                            <p style="font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px;">
                                Please click the button below to verify your email address.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0px;">
                            <p style="font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px;">
                                <a href="{{$link}}"><button class="btn btn-primary">Verify Email Address</button></a>
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0px;">
                            <p style="font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 15px;">
                                If you did not create an account, no further action is required.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0px;">
                                <tr>
                                    <td>
                                        <p style="font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 10px;">Regards,</p>
                                        <p style="font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 0px;">Agency Dashboard</p>
                                    </td>
                                </tr>
                        </td>
                    </tr>
                </table>
            </td>   
        </tr>
    </table>

    <table cellspacing="0" border="0" align="center" cellpadding="0" width="600">
        <tr>
            <td style="text-align: center; margin: 0 auto; width: 100%"> 
                <h3 style="color:#0282B7; font-family: 'Roboto', sans-serif; font-weight: 400; margin-top: 0px; margin-bottom: 5px;"> <img src="{{URL::asset('public/front/img/logo.png')}}" alt="agencydashboard"></h3>
            </td> 
        </tr>

        <tr>
            <td style="text-align: center; margin: 0 auto; width: 100%"> 
                <h5 style="color:#000; font-size: 13px; font-family: 'Roboto', sans-serif; font-weight: 400;"> Copyright &copy; 2020 Agency Dashboard</h5>
            </td> 
        </tr>
    </table>
</body>
</html>