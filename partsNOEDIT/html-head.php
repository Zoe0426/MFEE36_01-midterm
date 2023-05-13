<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Document</title>

    <style>
        .nav-pills .nav-item {
            border-radius: 6px;
            transition: 0.5s all;
        }


        .nav-pills .nav-item.active {
            background-color: salmon;
            color: #fff;
            font-weight: 800;
        }

        .navbar {
            top: 0;
            width: 100%;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 72px;
            z-index: 99;
        }

        .content {
            margin-top: 72px;
            padding-left: 250px;
        }
    </style>
</head>

<body>