<?php
$_CFG['admins.nav'] = [
    'award',
    'vote_list',
    'invoice'
];
?>
<nav class="navbar-custom navbar navbar-default" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo WWW_PATH;?>">Lays</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php
                foreach ($_CFG['admins.nav'] as $sec_label => $sec) {

                // reset($sec);
                echo '<li class="sec"><a href="'.$sec.'.php" class="" data-toggle="" role="button" aria-expanded="false">' . $sec . '<span class="caret"></span></a>';
                echo '</li>';
                }
                ?>
                <li><a href="<?php echo WWW_PATH;?>/backend/login.php?mode=logout">登出</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>
