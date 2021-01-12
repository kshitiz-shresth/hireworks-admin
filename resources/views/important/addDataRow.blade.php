<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Data</title>
</head>
<body>
    <form action="/postDataRow" method="post">
        @csrf
        <label for="datatype"><strong>Select DataType</strong>:</label>
        <select name="data_type_id" id="datatype">
            @foreach (\App\Models\DataType::all() as $item)
                <option value="{{ $item->id }}">{{ $item->slug }}</option>
            @endforeach
        </select><br><br>
        <label for="field"><strong>Field:</strong></label>
        <input type="text" name="field"><br><br>
        <label for="type"><strong>Type:</strong></label>
        <input type="text" name="type"><br><br>
        <label for="display_name"><strong>Display Name:</strong></label>
        <input type="text" name="display_name"><br><br>
        <label for="required"><strong>Required:</strong></label>
        <input type="checkbox" name="required"><br><br>
        <label for="browse"><strong>Browse:</strong></label>
        <input type="checkbox" checked name="browse"><br><br>
        <label for="read"><strong>Read:</strong></label>
        <input type="checkbox" checked name="read"><br><br>
        <label for="edit"><strong>Edit:</strong></label>
        <input type="checkbox" checked name="edit"><br><br>
        <label for="add"><strong>Add:</strong></label>
        <input type="checkbox" checked name="add"><br><br>
        <label for="add"><strong>Delete:</strong></label>
        <input type="checkbox" checked name="delete"><br><br>
        <label for="type"><strong>Order:</strong></label>
        <input type="text" name="order"><br><br>
        <input type="submit" value="submit">
        
    </form>
</body>
</html>