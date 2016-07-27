<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <!--<link rel="stylesheet" href="css/bootstrap.min.css" />-->
    <script type="text/javascript" src="/js/jquery-2.2.2.min.js" ></script>
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-simplex.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <title>Blog</title>
</head>
<body>
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

                <?php
                //TODO: check blog.php from $_SERVER
                if(/*isset($_SESSION['user']) &&*/ isset($_GET['user-id'])) {
                    if ($currentUser = getUserById($_GET['user-id'])) {

                        echo
                           '<a class="navbar-brand" href="">' . $currentUser['firstName'] . " " . $currentUser['lastName'] .
                           '</a>';
                    }
                } else {
                ?>
            <a class="navbar-brand" href="/">Blog System</a>
                <?php } ?>

        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if(isset($currentUser)) { ?>
                <?php //TODO: implement active li ?>
                <li class="active"><a href="blog.php?user-id=<?php echo $currentUser['id'] ?>">Posts <span class="sr-only">(current)</span></a></li>
<!--                <li><a href="photos.php?user-id=--><?php //echo $currentUser['id'] ?><!--">Photos</a></li>-->
                <?php } ?>
            </ul>
            <form class="navbar-form navbar-left" role="search" method="GET" action="search/">
                <div class="form-group">
                    <div class="input-group">
                        <?php if(isset($currentUser)) {
                            echo "<input type=\"hidden\" value=\"{$currentUser['id']}\" name=\"user-id\" />";
                        } ?>
                        <input name="search" type="text" class="form-control" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit">Go!</button>
      </span>
                    </div><!-- /input-group -->
                </div>
                <!--<button type="submit" class="btn btn-default">Submit</button>-->
            </form>
            <ul class="nav navbar-nav navbar-right">
                <?php if(!isset($_SESSION['user'])) { ?>
                <li><a href="/site/login">Log in</a></li>
                <li><a href="/site/register">Sign up</a></li>
                <?php } else { ?>
                <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION['firstName'] . " " . $_SESSION['lastName']; ?>
                    <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="/blog/index/<?php echo $_SESSION['userId'] ?>">My Blog</a></li>
                    <li><a href="/blog/add">Add post</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="/site/logout">Logout</a></li>
                </ul>
                </li>
                <?php } ?>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>