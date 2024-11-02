<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link  rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{asset('yoostore/css/product.css') }}" />
    
    <link rel="stylesheet" href="faq.css">
    <!-- <script
      defer
      src="https://unpkg.com/alpinejs@3.2.3/dist/cdn.min.js"
    ></script> -->
    <link
      href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css"
      rel="stylesheet"
    />
    <!-- header link -->
    <link rel="stylesheet" href="{{asset('yoostore/css/all.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/brands.min.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/brands.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/footer.css') }}" /> 
    <link rel="stylesheet" href="{{asset('yoostore/css/header.css') }}" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" /> 


    @yield('css')
    @livewireStyles
    
</head>
<body>

@include('yoostore.layout.header')

@yield('content')
@include('yoostore.layout.footer')





@livewireScripts

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>