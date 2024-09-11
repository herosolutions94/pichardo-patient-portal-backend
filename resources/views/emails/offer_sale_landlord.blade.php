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
                                            <tr>
                                                <td style="padding: 0;">
                                                    <p>Hi {{ $content['email_to_name'] }}!</p>
                                                    <p>Good news! Your {{$content['type']}} listing for {{ $content['property_address'] }} has received an offer! </p>

                                                    <p>
                                                        If you have any questions about us or our website (or just want
                                                        to say hello), please drop us a note!
                                                    </p>
                                                    <p>
                                                        Please visit the offers page in Loftus or click the button below to view the details of the offer:
                                                    </p>
                                                </td>
                                            </tr>
                                            @if (!empty($content['link']))
                                                <tr>
                                                    <td style="padding: 0; text-align: center;">
                                                        <a href="{{ $content['link'] }}"
                                                            style="display: inline-block; text-align: center; margin-bottom: 10px; background: #ff6c40; color: #fff; text-decoration: none; padding: 10px 30px; border-radius: 50px; font-weight: 500;">View Offers</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td style="padding: 0;">
                                                    <p>If you’re not satisfied with the offer, please connect with the buyer using our chat feature to discuss their willingness to meet on agreeable terms.</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 0;">
                                                    <p>Questions? Check out our <a
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
