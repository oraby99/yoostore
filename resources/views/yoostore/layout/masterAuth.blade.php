<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link  rel="stylesheet"  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{asset('yoostore/css/product.css') }}" />
    
    
    <!-- header link -->
    <link rel="stylesheet" href="{{asset('yoostore/css/all.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/brands.min.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/brands.css') }}" />
    <link rel="stylesheet" href="{{asset('yoostore/css/footer.css') }}" /> 
    <link rel="stylesheet" href="{{asset('yoostore/css/header.css') }}" /> 


    @yield('css')
    @livewireStyles
    
</head>
<body>


@yield('content')





@livewireScripts



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>