<?php include ADMIN_PATH_TPL . '/modules/befor-body.php'; ?>
<?php include ADMIN_PATH_TPL . '/modules/nav.php'; ?>
<?php
// pr($_HTML['CATEORYPARENT4OPTION']);
?>
<div class="container" id="container">
    <h2><?php echo $_HTML['PAGE_TITLE'] ?></h2>

    <form id="list-form" class="list-form" action="?mode=list" method="get">
        <p>
            <input type="hidden" name="orderby" value="<?php echo $orderby ?>">
            <input type="hidden" name="sortdir" value="<?php echo $sortdir ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
        </p>
    </form>

    <div id="list-data">
        <p>
            <a class="btn btn-primary" target="_blank" href="/backend/award.php?mode=export">匯出</a>
        </p>
        <?php if ($_HTML[ 'count']> 0): ?>

        <table class="data table table-striped" cellspacing="0">
            <thead>
                <tr>
                    <th class="<?php echo $_HTML['col:vote_log_id'] ?>" id="col-vote_log_id" style="width:7%">編號</th>
                    <th class="<?php echo $_HTML['col:fb_id'] ?>" id="col-fb_id" style="width:20%">FB_ID</th>
                    <th class="" id="col-username">姓名</th>
                    <th class="" id="col-phone">電話</th>
                    <th class="" id="col-ip">IP</th>
                    <th class="<?php echo $_HTML['col:created'] ?>" id="col-created">時間</th>
                </tr>
            </thead>
            <tbody>
                <?php $tr_class='even' ; foreach ($_HTML[ 'rows'] as $row) { $tr_class=( $tr_class=='even' ) ? 'odd' : 'even'; ?>
                <tr class="<?php echo $tr_class?>">
                    <td class="numeric">
                        <?php echo $row[ 'vote_log_id'] ?>
                    </td>
                    <td>
                        <?php echo $row[ 'fb_id'] ?>
                    </td>
                    <td>
                        <?php echo $row[ 'username'] ?>
                    </td>
                    <td>
                        <?php echo $row[ 'phone'] ?>
                    </td>
                    <td>
                        <?php echo $row[ 'ip'] ?>
                    </td>
                    <td>
                        <?php echo $row[ 'created'] ?>
                    </td>
                </tr>

                <?php } // end foreach ?>

            </tbody>
        </table>
        <div class="row paging">
            <div class="col-md-12">
                <?php echo getPaging($page, $count_records, $records_per_page); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php include ADMIN_PATH_TPL . '/modules/script.php'; ?>
<script type="text/javascript">
$(document).ready(function(){
    kp.init();
});
</script>
<?php include ADMIN_PATH_TPL . '/modules/after-body.php'; ?>
