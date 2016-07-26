<nav>
    <?php if ($pagination->buttons) { ?>
    <ul class="pagination">
        <?php foreach ($pagination->buttons as $value) {
            if ($value['isActive']) { ?>
                <li>
                    <a href="<?php echo $pagination->path . $value['page'] ?>">
                        <span aria-hidden="true"><?php echo $value['text']; ?></span>
                    </a>
                </li>
            <?php } else { ?>
                <li class="disabled">
                    <span aria-hidden="true"><?php echo $value['text']; ?></span>
                </li>
            <?php }
        } ?>
    </ul>
<?php } ?>


</nav>