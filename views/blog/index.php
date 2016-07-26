<?php
require "header.php";
?>

    <div class="container">

        <?php
        /* @var $posts PostModel[] */


            foreach($posts as $post) {
                ?>

                <div class="post">
                    <h1 class="post-header"><?php echo $post->title ?></h1>
                    <?php if($post->image) { ?>
                        <hr />
                        <img src="/img/<?php echo $post->image; ?>" />
                    <?php } ?>
                    <hr />
                    <p class="post-content">
                        <?php echo $post->body; ?>
                    </p>
                    <hr />
                    <p class="post-when-by">
                        <?php echo $post->createdAt->format('Y-m-d H:i:s'); ?>
                    </p>
                </div>
            <?php


            }


        ?>

    </div>


<?php

include "footer.php";

?>