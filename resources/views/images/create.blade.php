<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Image store</title>
</head>
<body>

<div>
    <form action="/" method="post" enctype="multipart/form-data">
    @csrf
        <!-- <input type="file" name="image" id="image"> -->
        <input type="file" name="imageFile[]" id="images" multiple="multiple">
        <button type="submit">Upload</button>
    </form>
</div>
    
</body>
</html>





