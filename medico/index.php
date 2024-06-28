<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 300px;
        }
    </style>
</head>
<body>
    <form method="post" action="solicitacao.php" enctype="multipart/form-data">
        <label for="documento">Upload Document:</label>
        <input type="file" id="documento" name="documento" required>
        <input type="submit" value="Assinar">
    </form>
</body>
</html>
