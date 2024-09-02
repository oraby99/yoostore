<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('mail.registration-verification.title') }}</title>

        <link rel="icon" href="{{ asset('portal/images/OIG__36_-removebg') }}">
        <link rel="stylesheet" href="{{ asset('portal/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('portal/css/mail.css') }}">
    </head>
    <body class="registration-verification-body">
        <div class="container">
            <section class="registration-verification">
                <div class="logo">
                    <img
                        alt="{{ __('mail.registration-verification.alt-logo') }}"
                        src="{{ asset('storage/global/Frame.png') }}"
                        class="logo"
                    />
                </div>

                <div class="content">
                    <div class="verification-code">
                        <h3>
                            {{ __('mail.registration-verification.verification-code-title') }}
                        </h3>
                        <p class="font-weight-bold">
                            <strong>
                                {{ $code }}
                            </strong>
                        </p>
                        <span>
                            {{ __('mail.registration-verification.verification-code-note') }}
                        </span>
                        <div>
                            <strong>
                                Regards,
                                Teams Layered Team
                            </strong>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>
