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
                                                        style="display: block; width: 60px; margin: 10px auto 0; text-decoration: none;">
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
                                            <tr>
                                                <td style="padding: 0;">
                                                    Hi
                                                    {{ $content['email_to_name'] }},
                                                    <p>Thank you for your patience as we reviewed your property transfer request for {{ $content['property_address'] }}. After careful examination, we have denied your request. We did not find sufficient evidence to support your ownership of the property. If you would like to further pursue this request, please contact us at  <a href="mailto:support@liveloftus.com">support@liveloftus.com</a>. </p>
                                                    
                                                </td>
                                            </tr>
                                            
                                             <tr>
                                                <td style="padding:0">
                                                    <p>If you did not initiate this request, please contact us immediately at <a href="mailto:support@liveloftus.com">support@liveloftus.com</a>.
                                                        Please note we will never ask for your password over email or phone.  </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 0;">
                                                    <p>Questions? Check out our <a
                                                            href="{{ config('app.react_url') }}/faq">Frequently Asked Questions</a>.</p>
                                                    <p >Sincerely,<br>
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
