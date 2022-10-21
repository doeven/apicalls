<!DOCTYPE html><?php $settings = settings(); ?>
<html lang="en" class="js">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- StyleSheets  -->
    <style>
        /*! Email Template */.email-wraper{background:#f5f6fa;font-size:14px;line-height:22px;font-weight:400;color:#8094ae;width:100%}.email-wraper a{color:{!! $settings->color_prim !!};word-break:break-all}.email-wraper .link-block{display:block}.email-ul{margin:5px 0;padding:0}.email-ul:not(:last-child){margin-bottom:10px}.email-ul li{list-style:disc;list-style-position:inside}.email-ul-col2{display:flex;flex-wrap:wrap}.email-ul-col2 li{width:50%;padding-right:10px}.email-body{width:96%;max-width:620px;margin:0 auto;background:#fff;padding: 10px 20px;}.email-success{border-bottom:#1ee0ac}.email-warning{border-bottom:#f4bd0e}.email-btn{background:{!! $settings->color_prim !!};border-radius:4px;color:#fff!important;display:inline-block;font-size:13px;font-weight:600;line-height:44px;text-align:center;text-decoration:none;text-transform:uppercase;padding:0 30px}.email-btn-sm{line-height:38px}.email-footer,.email-header{width:100%;max-width:620px;margin:0 auto; background: {!! $settings->color_prim !!}; }.email-logo{height:70px}.email-title{font-size:13px;color:{!! $settings->color_prim !!};padding-top:12px}.email-heading{font-size:18px;color:{!! $settings->color_prim !!};font-weight:600;margin:0;line-height:1.4}.email-heading-sm{font-size:24px;line-height:1.4;margin-bottom:.75rem}.email-heading-s1{font-size:20px;font-weight:400;color:#526484}.email-heading-s2{font-size:16px;color:#526484;font-weight:600;margin:0;text-transform:uppercase;margin-bottom:10px}.email-heading-s3{font-size:18px;color:{!! $settings->color_prim !!};font-weight:400;margin-bottom:8px}.email-heading-success{color:#1ee0ac}.email-heading-warning{color:#f4bd0e}.email-note{margin:0;font-size:13px;line-height:22px;color:#8094ae}.email-copyright-text{font-size:13px}.email-social li{display:inline-block;padding:4px}.email-social li a{display:inline-block;height:30px;width:30px;border-radius:50%;background:#fff}.email-social li a img{width:30px}@media (max-width:480px){.email-preview-page .card{border-radius:0;margin-left:-20px;margin-right:-20px}.email-ul-col2 li{width:100%}}
    </style>
</head>
<body class="nk-body bg-white has-sidebar ">
    <div class="nk-app-root">

            <table class="email-wraper">
                <tr>
                    <td class="py-5">
                        <table class="email-header">
                            <tbody>
                                <tr>
                                    <td class="text-center pb-4">
                                        <a href="{{ $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS'] }}"><img class="email-logo" src="{!! $settings->logo !!}" alt="logo"></a>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="email-body text-center">
                            <tbody>
                                <tr>
                                    <td class="px-3 px-sm-5 pt-3 pt-sm-5 pb-3">
                                        <h2 class="email-heading text-success">Password Successfully Changed</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 px-sm-5">
                                        <p>Hi {{ $user->fname }},</p>
                                        <p>You have successfully reset your password.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-3 px-sm-5 pt-4 pb-3 pb-sm-5">
                                        <p class="email-note">This is an automatically generated email please do not reply to this email. If you face any issues, please contact us at {{settings()->email}}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="email-footer" style="background: #000 !important">
                            <tbody>
                                <tr>
                                    <td class="text-center pt-4">
                                        <p class="email-copyright-text">Copyright © {{ date('Y') }} {{ settings()->title }}</p>
                                        <ul class="email-social" style="display:none">
                                            <li><a href="{{settings()->facebook}}"><img src="{{ $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS'] }}/images/socials/facebook.png" alt=""></a></li>
                                            <li><a href="{{settings()->twitter}}"><img src="{{ $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS'] }}/images/socials/twitter.png" alt=""></a></li>
                                            <li><a href="{{settings()->youtube}}"><img src="{{ $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS'] }}/images/socials/youtube.png" alt=""></a></li>
                                            <li><a href="{{settings()->instagram}}"><img src="{{ $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS'] }}/images/socials/medium.png" alt=""></a></li>
                                        </ul>
                                        <p class="fs-12px pt-4">This email was sent to you as a registered member of {{ settings()->title }}.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>


        </div>
    </body>
</html>