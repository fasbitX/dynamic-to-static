<?php if (!defined('BASE_PATH')) die('Forbidden.');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $page_title; ?> :: <?php echo $config->site_name ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="<?php \App\Util\Helper::url('css/main.css') ?>" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="<?php \App\Util\Helper::url() ?>"><?php echo $config->site_name; ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
            aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item <?php echo $page_title === 'Configurations' ? ' active':'' ?>">
                <a class="nav-link" href="<?php \App\Util\Helper::url() ?>">Configurations</a>
            </li>
            <li class="nav-item<?php echo $page_title === 'DNS Records' ? ' active':'' ?>">
                <a class="nav-link" href="<?php \App\Util\Helper::url('dns-records.php') ?>">DNS Records</a>
            </li>
        </ul>
    </div>
</nav>

<main role="main" class="container">