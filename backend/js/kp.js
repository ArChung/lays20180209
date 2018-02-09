//core
var kp = {
    conf: {
        isUpload: false,
        addNextMsg: '',
        addStayMsg: '',
        addNextUrl: '',
        editStayMsg: ''
    },
    sending: false,
    sent: 0
        // generic init function
        ,
    init: function() {

        if ($('#list-form').length == 1) {
            $('#list-submit-btn').click(function() {
                kp.listFormSearch();
                return false;
            });
            $('#list-form').submit(function() {
                kp.listFormSearch();
                return false;
            });
            $('.pagination a').click(function() {
                kp.listFormGoTo($(this).text());
                return false;
            });
            $('table.data th.sortable').click(kp.listFormSort);

            $('#list-form .numrows').on('change', function(){
                kp.listFormSearch();
                return false;
            });
        } // end data-form init

        $('#list-data .checkAll').on('click', function(){
            if( $(this).is(':checked') ){
                $('#list-data .checkItem').addClass('Checked').prop('checked', true);
            }else{
                $('#list-data .checkItem').removeClass('Checked').prop('checked', false);
            }
        });

        $('input.date').datepicker({
            dateFormat: "yy-mm-dd"
        });

        // keepalive
        // setInterval(kp.keepalive, 90000);

    },
    listFormSearch: function() {
        var f = document.forms['list-form'];
        f.page.value = "1";
        f.submit();
        return false;
    },
    listFormGoTo: function(s) {
        var f = document.forms['list-form'],
            p = parseInt(f.page.value, 10);
        switch (s) {
            case '上一頁':
                f.page.value = (p - 1);
                break;
            case '下一頁':
                f.page.value = (p + 1);
                break;
            default:
                f.page.value = s;
        }
        f.submit();
    },
    listFormSort: function() {
        var f = document.forms['list-form'],
            orderby = f.orderby.value,
            sortdir = f.sortdir.value;
        var col = $(this).attr('id').split('-')[1];
        if (col == orderby) {
            f.sortdir.value = (sortdir == "ASC") ? "DESC" : "ASC";
        } else {
            f.orderby.value = col;
            f.sortdir.value = "ASC";
        }
        f.page.value = "1";
        f.submit();
        //console.log("id:"+$(this).attr('id')+"; class:"+$(this).attr('class'));
    }

    ,
    keepalive: function() {
            $.get('/api/keepalive', {
                ts: kp._now()
            }, function(res) {
                if (res.success == 1 && res.is_admin == 0 && !$('body').hasClass('page-login')) { // no longer logged in
                    alert("連線逾時，您的帳號已自動登出。");
                    location.href = './';
                }
            });
        }
        // utilities
        ,
    _now: function() {
        return Math.round((new Date()).getTime() / 1000);
    },
    _isArray: function(a) {
        return Object.prototype.toString.apply(a) === '[object Array]';
    },
    _log: function(s) { //attempts console.log if applicable
        if (typeof console != 'undefined' && console.log) try {
            console.log(s)
        } catch (e) {}
    }
}



kp.generic = {

    setup: function(opts) {
        for (var key in opts) {
            kp.conf[key] = opts[key];
        }
        $('input.date').datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('.fancybox').fancybox();
        // keepalive
        // setInterval(kp.keepalive, 90000);
    },
    save: function() {
            var now = kp._now(),
                form = document.forms['data-form'];
            try {
                kp.wysiwyg.saveAll();
                // console.log('saveAll');
            } catch (e) {
                console.log(e);
            }
            if (kp.sending && (now - kp.sent) < 5) {
                alert("傳送中，請稍候");
                return;
            }
            kp.sending = true;
            kp.sent = now;
            $('#loading-msg').css({
                visibility: 'visible'
            });
            if (kp.conf.isUpload === true) {
                form.submit();
                return
            }
            $.post('?ajax=1', $('#data-form').serialize(), function(res) {
                kp.generic.saveResult(res);
            }, 'json');
        } //kp.generic.save

    ,
    saveByMode: function() {
            var now = kp._now(),
                form = document.forms['data-form'];
            try {
                kp.wysiwyg.saveAll();
                // console.log('saveAll');
            } catch (e) {
                console.log(e);
            }
            if (kp.sending && (now - kp.sent) < 5) {
                alert("傳送中，請稍候");
                return;
            }
            kp.sending = true;
            kp.sent = now;
            $('#loading-msg').css({
                visibility: 'visible'
            });
            if (kp.conf.isUpload === true) {
                form.submit();
                return
            }
            // console.log(form.action);
            // console.log(kp.conf.editStayMsg);

            $.post(form.action+form.mode.value+'?ajax=1', $('#data-form').serialize(), function(res) {
                if(res.success==1) {
                    alert("已成功儲存");
                    // console.log(form.action);
                    // console.log(form.listpage.value + '/?orderby=updated&sortdir=DESC');

                    if (kp.conf.editStayMsg != "") {
                        if(confirm(kp.conf.editStayMsg)) {
                            // console.log(res);
                            window.location.href = "?mode="+res.mode+"&id=" + res.id;
                            return;
                        }else{
                            location.href = form.listpage.value+'/?orderby=updated&sortdir=DESC';
                        }
                    }else{
                        location.href = form.listpage.value+'/?orderby=updated&sortdir=DESC';
                    }
                }else{
                    alert(res.msg);
                    $('#loading-msg').css({
                        visibility: 'hidden'
                    });
                }
            }, 'json');
        } //kp.generic.save

    ,
    saveResult: function(res) {
            kp.sending = false;
            kp.sent = 0;
            $('#loading-msg').css({
                visibility: 'hidden'
            });
            var now = kp._now(),
                form = document.forms['data-form'],
                mode = form.mode.value;
            if (res.success == 1) {
                if (mode == 'add' && kp.conf.addNextMsg != "") {
                    if (confirm(kp.conf.addNextMsg)) {
                        window.location.href = (kp.conf.addNextUrl != "" ? kp.conf.addNextUrl : "?mode=add-form");
                        return;
                    }
                } else if (mode == 'add' && kp.conf.addStayMsg != "") {
                    if (confirm(kp.conf.addStayMsg)) {
                        window.location.href = "?mode=edit-form&id=" + res.id;
                        return;
                    }
                } else if (mode == 'edit' && kp.conf.editStayMsg != "") {
                    if (confirm(kp.conf.editStayMsg)) {
                        window.location.href = "?mode=edit-form&id=" + res.id;
                        return;
                    }
                } else {
                    alert("已成功儲存");
                }
                window.location.href = '?mode=list&orderby=updated&sortdir=DESC';
            } else {
                alert((typeof(res.msg) == 'string' ? res.msg : "不明的錯誤"));
            }
        } //kp.generic.saveResult

    ,
    del: function(record_id) {
        if (!confirm("資料刪除後將無法復原，確認要刪除？")) return;
        var now = kp._now();
        if (kp.sending && (now - kp.sent) < 5) {
            alert("系統忙碌，請稍候再試一次。");
            return;
        }
        kp.sending = true;
        kp.sent = now;
        $('#loading-msg').css({
            visibility: 'visible'
        });
        $.post('?ajax=1', {
            mode: "del",
            "id": record_id
        }, function(res) {
            kp.sending = false;
            $('#loading-msg').css({
                visibility: 'hidden'
            });
            window.location.href = '?mode=list&orderby=updated&sortdir=DESC';
        }, 'json');
    } //kp.generic.del

}; //kp.generic

// set default locale
$.datepicker.setDefaults($.datepicker.regional["zh-TW"]);
