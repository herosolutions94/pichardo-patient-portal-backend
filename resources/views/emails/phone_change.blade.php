<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $content['subject'] }}</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
    </style>
</head>

<body style="background: #f8f9ff; margin: 0; padding: 20px 0;">
    <table
        style="width: 600px;background: #fff; color: #2f2a49;  margin: 0 auto; border-spacing: 0; font-size: 14px; font-family: 'Poppins', sans-serif;">
        <tbody>
            <tr>
                <td style="padding: 0;">
                    <table
                        style="width: 100%; background: #fff; box-shadow: 0 1rem 2rem -0.3rem rgb(47 42 73 / 5%); border-spacing: 0; border-radius: 4px;">
                        <tbody>
                            <tr>
                                <td style="padding: 20px; box-sizing: border-box;">
                                    <table style="width: 100%; border-spacing: 0;">
                                        <tbody>
                                            <tr>
                                                <td style="padding: 0;">
                                                    <a href="{{ config('app.react_url') }}"
                                                        style="display: block; width: 60px;   border-radius: 16px; padding: 10px;margin: 10px auto 0; text-decoration: none;">
                                                        <img src="{{ get_site_image_src('images', $site_settings->site_logo) }}"
                                                            alt="{{ $site_settings->site_name }} Logo"
                                                            style="display: block; width: 100%;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100%; padding: 20px; box-sizing: border-box;">
                                    <table style="width: 100%; border-spacing: 0;">
                                        <tbody>
                                            @if (!empty($content['send_otp']) && $content['send_otp'] == 1)
                                                <tr>
                                                    <td style="padding: 0;">
                                                        <p>Hi {{ $content['email_to_name'] }}!</p>
                                                        <p>We have received a request to update your phone number. </p>
                                                        <!-- <p>Your current phone number is {{ $content['old_phone'] }}</p> -->
                                                        <!-- <p>Your new phone number will be {{ $content['phone_change'] }} -->
                                                        </p>
                                                        <p>To confirm the update, please enter the one-time password below in the requested form:</p>
                                                        
                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($content['phone_changed']) && $content['phone_changed'] == 1)
                                                <tr>
                                                    <td style="padding: 0;">
                                                        <p>Hi {{ $content['email_to_name'] }}!</p>
                                                        <p>Your phone number has been successfully updated. The number ending in {{ substr($content['old_phone'], -4) }} is no longer associated with your Loftus account. All future mobile updates will be sent to your new phone number.</p>
                                                        

                                                    </td>
                                                </tr>
                                            @endif
                                            @if (!empty($content['code']))
                                                <tr>
                                                    <td style="padding: 0; text-align: center;">
                                                        <div
                                                            style='width:150px;padding:10px 20px;border:1px solid #ddd;border-radius:4px;text-align:center;margin:0 auto'>
                                                            {{ $content['code'] }}</div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding:0">
                                                        <p>If you did not request this change, please contact us immediately at support@liveloftus.com. Please note we will never ask for your password over email or phone. </p>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 0;">
                                                     <p style="margin-top: 0;">Questions? Check out our <a
                                                            href="{{ config('app.react_url') }}/faq">Frequently Asked Questions</a>.</p>
                                                    <p>Sincerely,<br>
                                                        The Loftus Team
                                                    </p>
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

        </tbody>
    </table>
</body>

</html>
