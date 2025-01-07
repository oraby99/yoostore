<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Products</title>
    <!-- Add any CSS or JS files here -->
</head>
<body>
    <h1>Import Products</h1>

    <!-- Display success message if the import was successful -->
    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Display error message if there was a validation error -->
    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form for uploading the CSV file -->
    <form action="{{ route('import.products') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="file">Upload CSV File:</label>
        <input type="file" name="file" accept=".csv,.txt" required>
        <br><br>
        <button type="submit">Import Products</button>
    </form>
</body>
</html>