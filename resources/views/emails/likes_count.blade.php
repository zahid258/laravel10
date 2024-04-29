<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Likes Count Email</title>
</head>
<body>
<h1>Users with More Than 20 Likes</h1>
<ul>
    @foreach($names as $name)
        <li>{{ $name }}</li>
    @endforeach
</ul>
</body>
</html>
