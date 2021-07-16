<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <form action="/posts" method="post" enctype="multipart/form-data">
        @csrf
        <p>
            <input type="file" name="datafile">
        </p>
        <p>
            {{Form::select('category_id', $categories, 6)}}
        </p>
        
        <div class='submit'>
            <p>
                <input type="submit" value="投稿">
            </p>
        </div>
    </form>
</body>
</html>