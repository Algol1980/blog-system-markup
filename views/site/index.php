<?php
    require "header.php";
?>

    <div class="container">

        <?php if(!isset($_SESSION['user'])): ?>
            <div class="jumbotron">
                <h1>Introduce cool blog system!</h1>
                <p>It's very cool and fun, try it!</p>
                <p><a class="btn btn-primary" href="/site/register">Start using!</a> or <a href="/site/login" class="btn btn-primary">Log in</a></p>
            </div>
        <?php endif; ?>

        <h2>List of bloggers</h2>
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Имя</th>
                <th>Количество записей</th>
                <th>Количество фото</th>
            </tr>
            </thead>
            <tbody>

            <?php
            /* @var $users UserModel[] */
            foreach($users as $user):
                ?>
                <tr>
                    <td>
                        <a href="/blog/index/<?php echo $user->id; ?>"><?php echo $user->firstName . ' ' . $user->lastName; ?></a>
                    </td>
                    <td>
                        <?php echo $user->countPosts; ?>
                    </td>
                    <td>
                        <?php echo $user->countImages; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    </div>
<?php
require "footer.php";
?>