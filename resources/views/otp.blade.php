<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        body {
     background-color: #BA68C8
 }

 .main-verification-input {
     background: #fff;
     padding: 0 120px 0 0;
     border-radius: 1px;
     margin-top: 6px
 }

 .fl-wrap {
     float: left;
     width: 100%;
     position: relative;
     border-radius: 4px
 }

 .main-verification-input:before {
     content: '';
     position: absolute;
     bottom: -40px;
     width: 50px;
     height: 1px;
     background: rgba(255, 255, 255, 0.41);
     left: 50%;
     margin-left: -25px
 }

 .main-verification-input-item {
     float: left;
     width: 100%;
     box-sizing: border-box;
     border-right: 1px solid #eee;
     height: 50px;
     position: relative
 }

 .main-verification-input-item input:first-child {
     border-radius: 100%
 }

 .main-verification-input-item input {
     float: left;
     border: none;
     width: 100%;
     height: 50px;
     padding-left: 20px
 }

 .main-verification-button {
     background: #4DB7FE
 }

 .main-verification-button {
     position: absolute;
     right: 0px;
     height: 50px;
     width: 120px;
     color: #fff;
     top: 0;
     border: none;
     border-top-right-radius: 4px;
     border-bottom-right-radius: 4px;
     cursor: pointer
 }

 .main-verification-input-wrap {
     max-width: 500px;
     margin: 20px auto;
     position: relative;
     margin-top: 129px
 }

 .main-verification-input-wrap ul {
     background-color: #fff;
     padding: 27px;
     color: #757575;
     border-radius: 4px
 }

 a {
     text-decoration: none !important;
     color: #9C27B0
 }

 :focus {
     outline: 0
 }

 @media only screen and (max-width: 768px) {
     .main-verification-input {
         background: rgba(255, 255, 255, 0.2);
         padding: 14px 20px 10px;
         border-radius: 10px;
         box-shadow: 0px 0px 0px 10px rgba(255, 255, 255, 0.0)
     }

     .main-verification-input-item {
         width: 50%;
         border: 1px solid #eee;
         height: 50px;
         border: none;
         margin-bottom: 10px
     }

     .main-verification-input-item input {
         border-radius: 6px !important;
         background: #fff
     }

     .main-verification-button {
         position: relative;
         float: left;
         width: 100%;
         border-radius: 6px
     }
 }
    </style>
</head>
<body>
    <div class="row">
        <div class="col-md-12">
            <div class="main-verification-input-wrap">
                <ul>
                    <li>You will recieve a OTP verification code on your email after you login. Enter that code below. The code will expire in 5 minutes.</li>
                <li>If somehow, you did not recieve the verification email then <a href="{{ route('resend_otp') }}">resend the verification email</a></li>
                </ul>
                <form action="{{ route('test_otp') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="main-verification-input fl-wrap">
                            <div class="main-verification-input-item">
                                <input type="text" value=""  name="code" placeholder="Enter OTP" style="font-size: 2em;">
                            </div>
                            <button value="Submit" class="main-verification-button">Verify Now</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
