<?php include ADMIN_PATH_TPL . '/modules/befor-body.php'; ?>
<?php include ADMIN_PATH_TPL . '/modules/nav.php'; ?>
<div class="container" id="container">
    <div class="omb_login">
        <h3 class="omb_authTitle">Login</h3>
        <div class="row col-sm-offset-3">
            <div class="col-xs-12 col-sm-8">
                <?php include ADMIN_PATH_TPL. '/modules/block_error.html.php'; ?>
                <!-- #login-form -->
                <form id="login-form" class="omb_loginForm" action="?mode=login" autocomplete="off" method="POST">
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" name="username" placeholder="Username" maxlength="" value="">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" maxlength="" value="">
                        </div>
                        <span class="help-block"></span>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-lg btn-primary btn-block bn-action submit" type="submit">Login</button>
                    </div>
                    <div class="row col-sm-offset-3">
                        <div class="col-xs-12 col-sm-6">
                            <label class="checkbox">
                                <!-- <input type="checkbox" value="remember-me">Remember Me -->
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<?php include ADMIN_PATH_TPL . '/modules/script.php'; ?>
<script>
/* Placeholder polyfill for IE9-
 * @see https://github.com/mathiasbynens/jquery-placeholder
 */
// var is_mobile = <?php echo $is_mobile ? 'true' : 'false' ?> ;
$(function() {
    

});
</script>
<?php include ADMIN_PATH_TPL . '/modules/after-body.php'; ?>