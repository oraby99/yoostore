<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('site.mail.title') }}</title>
        <link rel="icon" href="{{ asset('images/yoostore.png') }}">
    </head>
    <body class="registration-verification-body">
        <div class="container">
            <section class="registration-verification">
                <div class="logo">
                    <img
                        alt="{{ __('site.mail.alt-logo') }}"
                        src="{{ asset('images/yoostore.png') }}"
                        class="logo"
                    />
                </div>

                <div class="content">
                    <div class="verification-code">
                        <h3>
                            {{ __('site.mail.verification-code-title') }}
                        </h3>
                        <p class="font-weight-bold">
                            <strong>
                                {{ $code }}
                            </strong>
                        </p>
                        <span>
                            {{ __('site.mail.verification-code-note') }}
                        </span>
                        <div>
                            <strong>
                                Regards,
                                Yoo Store Team
                            </strong>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </body>
</html>
