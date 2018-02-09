var isTest = true;
var userfbId = 12312321;
var vote_id;
var fbPopUpWindow;
var keychain;
var videoIdArr = ['lJHerKsNw8M', 'IiYbBaVv1Zk', 'lPbk70WFhus']
var isFBing = false;

var plistaIMG = '<img src="https://farm-tw.plista.com/activity2;domainid:718737;campaignid:717458;event:31" style="width:1px;height:1px;" alt="" /><img src="https://farm-tw.plista.com/activity2;domainid:718737;campaignid:717459;event:31" style="width:1px;height:1px;" alt="" /><img src="https://farm-tw.plista.com/activity2;domainid:718737;campaignid:717457;event:31" style="width:1px;height:1px;" alt="" /><img src="https://farm-tw.plista.com/activity2;domainid:718737;campaignid:717460;event:31" style="width:1px;height:1px;" alt="" >';

$(document).ready(function() {

    // document.location.href = '/test.html';
    initAni();

    initVoteBox();
    initInvoiceBox();
    initIndex();
    initFormOne();
    initFormTwo();

    getDate();
    getVoteNum();
    getWinner();
    initPerfectScrollBar();

    if (ChungTool.getUrlParameter('page') == 'rule1') {
        $('#coverIndex').addClass('hide');
        $('#votePage').removeClass('hide');
        $('#votePage .rulePage').removeClass('hide').siblings('.subPage').addClass('hide');
    } else if (ChungTool.getUrlParameter('page') == 'rule2') {
        $('#coverIndex').addClass('hide');
        $('#billPage').removeClass('hide');

        $('#billPage .container').animate({
            scrollTop: $('#billPage').find('.rulePage').offset().top - $('#billPage').find('.pricePage').offset().top
        }, 0);
    }


    var page = ChungTool.getUrlParameter('page');
    // console.log(page)
    if (page == 'invoiceDone') {
        $('#coverIndex').find('.sb2').trigger('click');
        $('.invoicePop').removeClass('hide');
    }



    // initDataForm($("#voteForm"))
})

function checkUrl() {

    if (!ChungTool.isPhone()) {
        return;
    }

    var fbid = ChungTool.getUrlParameter('id');
    var voteId = $.jStorage.get('voteId')
        // console.log('url_fb_id: ' + fbid);
        // console.log('jStorage_voteId: ' + voteId);

    if (fbid != '' && fbid != null) {
        userfbId = fbid;
        if (voteId != '' && voteId != null) {
            // console.log('switchVoteMan');
            switchVoteMan(voteId);
            $('#coverIndex').addClass('hide');
            $('#votePage .subPage').addClass('hide');
            $('#votePage').removeClass('hide');
            $('#votePage .votePage').removeClass('hide');

        } else {
            switchVoteMan(1);
            $('#coverIndex').addClass('hide');
            $('#votePage .subPage').addClass('hide');
            $('#votePage').removeClass('hide');
            $('#votePage .votePage').removeClass('hide');
        }
    }



}
var ani1, ani2;

function initAni() {
    var ip = $('.voteIndex');
    ani1 = new TimelineMax();
    ani2 = new TimelineMax();


    if (!ChungTool.isPhone()) {
        ani1.call(function() {
                var pp = $('.voteIndex .winners');
                setInterval(function() {

                    pp.css('left', parseInt(pp.css('left')) - 1);
                    if (parseInt(pp.css('left')) <= -pp.width()) {
                        pp.css('left', 300);
                    }
                }, 20)

            }).set(ip.find('.mc1 .snack'), { rotation: -20, marginLeft: 10, marginTop: -30 })
            .set(ip.find('.mc2 .snack'), { marginTop: -150 })
            .set(ip.find('.mc3 .snack'), { rotation: 20, marginLeft: -180, marginTop: -30 })
            .set(ip.find('.mc1 .man'), { marginLeft: 50 })
            .set(ip.find('.mc2 .man'), { marginLeft: -90, marginTop: -30 })
            .set(ip.find('.mc3 .man'), { marginLeft: -200, marginTop: -30 })
            // .from(ip.find('.mc1 .snack'), 1, { marginTop: '-=50', autoAlpha: 0, marginLeft: '-=50' }, 'start')
            // .from(ip.find('.mc2 .snack'), 1, { marginTop: '-=50', autoAlpha: 0 }, 'start')
            // .from(ip.find('.mc3 .snack'), 1, { marginTop: '-=50', autoAlpha: 0, marginLeft: '+=50' }, 'start')
            // .from(ip.find(' .man'), 0.8, { marginTop: '+=50', autoAlpha: 0 }, 0.3, 'manstart')
            .call(function() {
                TweenMax.delayedCall(0.6, function() {
                    for (var i = 0; i < 3; i++) {
                        var num = ip.find('.flipNum').eq(i).attr('data-num');
                        initFlipNum(ip.find('.flipNum').eq(i), num);
                    }
                })

            })
            .to(ip.find('.snack'), 2, { rotation: 0, marginLeft: -107, marginTop: 0, ease: Power4.easeInOut }, 'set')
            .to(ip.find('.mc1 .man'), 2, { marginLeft: 0, marginTop: 0, ease: Power4.easeInOut }, 'set')
            .to(ip.find('.mc2 .man'), 2, { marginLeft: 0, marginTop: 0, ease: Power4.easeInOut }, 'set')
            .to(ip.find('.mc3 .man'), 2, { marginLeft: -30, marginTop: 0, ease: Power4.easeInOut }, 'set')

        .from(ip.find('.voteNum'), 2, { marginTop: '+=80', autoAlpha: 0 }, 'set+=0.3')
            .from(ip.find('.hoverLeftBtn'), 2, { autoAlpha: 0 }, 'set+=0.8')
            .pause();

    } else {
        ani1.call(function() {
            var pp = $('.voteIndex .winners');
            setInterval(function() {
                pp.css('left', parseInt(pp.css('left')) - 1);
                if (parseInt(pp.css('left')) <= -pp.width()) {
                    pp.css('left', 300);
                }
            }, 20)

        }).call(function() {
            TweenMax.delayedCall(0.6, function() {
                for (var i = 0; i < 3; i++) {
                    var num = ip.find('.flipNum').eq(i).attr('data-num');
                    initFlipNum(ip.find('.flipNum').eq(i), num);

                }
            })

        }).pause();



    }


    // console.log($('.voteIndex .winners').width())
    ani2.to($('.voteIndex .winners'), 0.1, { autoAlpha: 0.7, yoyo: true, repeat: -1 })
}


function initPerfectScrollBar() {
    $('.perfectScrollBar').perfectScrollbar();
}


function initDataForm(el) {
    var vf = el;
    vf.find('.userName').val('游志忠');
    vf.find('.invoice').val('AB12345678');
    vf.find('.mobileTaiwan').val('0926276430');
    vf.find('.chkPid').val('F126815614');
    vf.find('.addr').val('taipei');
    vf.find('.userEmail').val('until5000@gmail.com');

    // showForm();
}

var verify2 = false;
// var verify1 = false;

function initFormOne() {
    // $("#voteForm").find('.sendBtn').click(function(e) {
    //   e.preventDefault();
    // });
    $("#voteForm").validate({
        submitHandler: function(e) {

            var vf = $("#voteForm")

            if (vf.find('.agreeBtn').prop('checked')) {
                var dd = {
                    fb_id: userfbId,
                    username: vf.find('.userName').val(),
                    phone: vf.find('.mobileTaiwan').val(),
                    personal_id: vf.find('.chkPid').val(),
                    address: vf.find('.addr').val(),
                    email: vf.find('.userEmail').val(),
                    keychain: keychain,
                    vote_id: vote_id
                };

                // console.log(dd)

                $.ajax({
                    url: 'api/vote_info.php',
                    type: 'POST',
                    dataType: 'json',
                    data: dd,
                    success: function(e) {
                        // console.log(e)
                        if (e.status == 1) {
                            var p = $('#votePage');
                            p.find('.subPage').addClass('hide');
                            p.find('.successPage').removeClass('hide');

                        } else {
                            alert(e.msg);
                        }
                    },

                    error: function(e) {
                        // console.log(e);
                    }
                })
            } else {
                alert('請勾選我已同意活動辦法');
            }
            // form.submit();
        },
        errorPlacement: function(error, element) {
            element.closest('.form-group').append(error);
        }
    });
}

var verifyCallback2 = function(response) {
    // 如果 JavaScript 驗證成功
    // console.log('verifyCallback2');
    verify2 = true;
    if (response) {
        $.post('api/captcha.php', { 'g-recaptcha-response': response }, function(data, status) {
            // 如果 PHP 驗證成功
            // console.log('verifyCallback2:' + status);
            if (status == 'success') {
                verify2 = true;
            }
        });
    }
};
var expCallback = function() {
    grecaptcha.reset();
};
// var verifyCallback = function(response) {
//     // 如果 JavaScript 驗證成功
//     if (response) {
//         $.post('api/captcha.php', { 'g-recaptcha-response': response }, function(data, status) {
//             // 如果 PHP 驗證成功
//             if (status == 'success') {
//                 verify1 = true;
//             }
//         });
//     }
// };

var onloadCallback = function() {
    grecaptcha.render(
        'codeWidget2', { // widget 驗證碼視窗在 id="my-widget" 顯示
            'sitekey': '6LdVBRcUAAAAAGimeF9TSR86QZXygS-gFTP2BMqC', // API Key
            'callback': verifyCallback2,
            'expired-callback': expCallback // 要呼叫的回調函式
        }
    );

    // grecaptcha.render(
    //     'codeWidget1', { // widget 驗證碼視窗在 id="my-widget" 顯示
    //         'sitekey': '6LdVBRcUAAAAAGimeF9TSR86QZXygS-gFTP2BMqC', // API Key
    //         'callback': verifyCallback // 要呼叫的回調函式
    //     }
    // );
};



function initFormTwo() {
    var p = $('#billForm');

    p.find('.addMoreBtn').on('click', function(e) {
        // console.log(123)
        p.find('.form-group.hide').eq(0).removeClass('hide');
        if (p.find('.form-group.hide').length == 0) {
            p.find('.addMoreBtn').addClass('hide')
        }
    });


    $("#billForm").validate({
        submitHandler: function(e) {
            var vf = $("#billForm");

            if (!vf.find('.agreeBtn').prop('checked')) {
                alert('請勾選我已同意活動辦法');
                return;
            }

            if (!verify2) {
                alert('請完成驗證');
                return;
            }

            var invoices = p.find('.invoiceWrap .invoice');
            var invoicesArr = [];

            // console.log(invoices.length)
            for (var i = 0; i < invoices.length; i++) {
                if (invoices.eq(i).val() != '') {
                    invoicesArr.push(invoices.eq(i).val());
                }
            }
            // console.log(invoicesArr)

            var dd = {
                invoice: invoicesArr.join(','),
                username: vf.find('.userName').val(),
                phone: vf.find('.mobileTaiwan').val(),
                email: vf.find('.userEmail').val()
            };

            

            $.ajax({
                url: 'api/invoice.php',
                type: 'POST',
                dataType: 'json',
                data: dd,
                success: function(e) {
                    console.log(e)




                    if (e.status == 1) {
                        if (e.invoice_used.length != 0) {
                            var txt = e.invoice_used.join(',');
                            alert('發票號碼' + txt + '已經登錄過了，其他筆發票號碼已成功登錄!');
                        }
                        console.log(e.invoice_used.length,invoicesArr.length)
                        if (e.invoice_used.length < invoicesArr.length) {
                            $('.invoicePop').removeClass('hide');
                            vf.find('.invoice').val('');
                            fbq('track', 'PageView');
                            fbq('track', 'CompleteRegistration');
                            clickforce_rtid("2320001");
                            ElandTracker.Track({
                                'source': 'CAP2320',
                                'trackType': 'view',
                                'trackSubfolderDepth': 3,
                                'targetType': 'usual'
                            });
                            clickforce_rtid("2320002");
                            ElandTracker.Track({
                                'source': 'CAP2320',
                                'trackType': 'click',
                                'trackSubfolderDepth': 3,
                                'targetType': 'invoiceOrder'
                            });
                            $('.trackingIMG').append($(plistaIMG));
                        }


                    } else {
                        alert(e.msg);
                    }



                },

                error: function(e) {
                    // console.log(e);
                }
            })


        },
        errorPlacement: function(error, element) {
            element.closest('.form-group').append(error);
        }
    });
}



function initFlipNum(el, num) {

    var f = el.find('.numWrap');



    var number = num * 1,
        output = [],
        sNumber = number.toString();



    for (var i = 0, len = sNumber.length; i < len; i += 1) {
        output.push(+sNumber.charAt(i));
    }

    for (var i = 0; i < 6 - sNumber.length; i++) {
        output.unshift('0');
    }

    for (var i = 0; i < f.length; i++) {
        var reverse = f.length - i;
        ChungTool.removeClassWithFilter(f.eq(i), 'n_');
        f.eq(i).addClass('n_' + output[i]);

    }
}

function initIndex() {

    var p = $('#coverIndex');

    p.find('.sb1').click(function() {
        p.addClass('hide');
        getWinner();
        goVotePage();
        $('#votePage').removeClass('hide');
        // $('#votePage').find('.winnerPage ').removeClass('hide');

    });


    p.find('.sb2').click(function() {
        p.addClass('hide');
        $("#billPage").removeClass('hide');
    })


}


function initVoteBox() {
    // animation

    var p = $('#votePage');


    var index = p.find('.voteIndex');
    var vote = p.find('.votePage');
    var subPs = p.find('.subPage');
    var winP = p.find('.winPage');
    var loseP = p.find('.losePage');
    var limitP = p.find('.limitPage');
    var formP = p.find('.formPage');
    var hd = p.find('.header');
    var winnerP = p.find('.winnerPage');
    var ruleP = p.find('.rulePage');


    // TweenMax.to(p.find('.light1'), 3, { rotation: -30, repeat: -1, yoyo: true, transformOrigin: "right top", ease: Power2.easeInOut });
    // TweenMax.to(p.find('.light2'), 3, { rotation: -30, repeat: -1, yoyo: true, transformOrigin: "right top", ease: Power2.easeInOut });

    setInterval(function() {
        if (!$('.voteIndex').hasClass('hide')) {
            $('.lightBox').removeClass('hide');
            $('.lightBox').addClass('shake');
            // ChungTool.replayCssAni($('.lightBox'),'shake');
        } else if (!$('.votePage').hasClass('hide')) {
            $('.lightBox').removeClass('hide');
            $('.lightBox').removeClass('shake');
        } else {
            $('.lightBox').addClass('hide');

        }


        if (vote.hasClass('hide') && !isFBing) {
            vote.find('.videoBtn').empty();
        }

    }, 100)

    //----- index ----//

    index.find('.voteBtn').on('click', function(e) {
        var t = $(this);
        var mom = t.closest('.manBox');
        var id = ChungTool.returnClassNameWithFilter(mom, 'mc') * 1;
        isFBing = true;
        switchVoteMan(id);

        if (!ChungTool.isOnline()) {
            goManPage();
        } else {
            if (ChungTool.isPhone()) {
                $.jStorage.flush();
                $.jStorage.set('voteId', id);

                window.location = "m_fb_auth.php";

            } else {
                fbPopUpWindow = window.open('fb_auth.php', 'Yahoo', config = 'height=500,width=600');
            }
        }
    });

    index.find('.manBox .man').on('click', function(e) {
        var t = $(this);
        if (ChungTool.isPhone()) {
            t.siblings('.voteBtn').trigger('click');

        }
    });

    //----- vote ----//





    // leftBtn
    vote.find('.lBtn').on('click', function(e) {
        var t = $(this);
        var mom = t.siblings('.manPic');
        var id = ChungTool.returnClassNameWithFilter(mom, 'p') * 1;
        id = (id == 1) ? 3 : id - 1;
        switchVoteMan(id);
    });

    // rightBtn
    vote.find('.rBtn').on('click', function(e) {
        var t = $(this);
        var mom = t.siblings('.manPic');
        var id = ChungTool.returnClassNameWithFilter(mom, 'p') * 1;
        id = (id == 3) ? 1 : id + 1;
        switchVoteMan(id);
    });

    // leftBtn
    vote.find('.lTBtn').on('click', function(e) {
        var t = $(this);
        var mom = t.siblings('.manPic');
        var id = ChungTool.returnClassNameWithFilter(mom, 'p') * 1;
        id = (id == 1) ? 3 : id - 1;
        switchVoteMan(id);
    });

    // rightBtn
    vote.find('.rTBtn').on('click', function(e) {
        var t = $(this);
        var mom = t.siblings('.manPic');
        var id = ChungTool.returnClassNameWithFilter(mom, 'p') * 1;
        id = (id == 3) ? 1 : id + 1;
        switchVoteMan(id);
    });

    // voteBtn
    vote.find('.voteBtn').on('click', function(e) {

        var t = $(this);
        var mom = t.parent().siblings('.manPic');
        var id = ChungTool.returnClassNameWithFilter(mom, 'p') * 1;
        // showWin();
        var url = (ChungTool.isOnline()) ? 'api/vote.php' : 'test/vote.txt';
        vote_id = id;
        _gaCK('votePage_voteNum0' + id);


        var data = { fb_id: userfbId, vote_id: id };
        // console.log(data);

        var target = index.find('.mc' + id).find('.flipNum');
        var newNum = target.attr('data-num') * 1 + 1;
        target.attr('data-num', newNum);
        initFlipNum(target, newNum);

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(e) {
                // console.log('vote.php');
                // console.log(JSON.stringify(e));
                // console.log(e.status);


                if (e.status == 0) {
                    alert('系統忙線中，請稍候再試');
                } else if (e.status == 1) {
                    // console.log('中獎');
                    keychain = e.keychain;
                    showWin();
                    fbq('track', 'PageView');
                    fbq('track', 'Lead');

                } else if (e.status == 2) {
                    // console.log('超過10次');
                    showlimit();
                    fbq('track', 'PageView');
                    fbq('track', 'Lead');

                } else if (e.status == 3) {
                    // console.log('沒中');
                    showlose();
                    fbq('track', 'PageView');
                    fbq('track', 'Lead');

                }
            },

            error: function(e) {
                // console.log('error')
                // console.log(e);
            }
        });
    });



    //----- win ----//
    winP.find('.btn').on('click', function(e) {
        var t = $(this);
        showForm();
    });

    //----- lose ----//
    loseP.find('.btn').on('click', function(e) {
        var t = $(this);
        showIndex();
    });

    //----- limitPage ----//
    limitP.find('.btn').on('click', function(e) {
        var t = $(this);
        goInvoicePage();
    });

    //----- limitPage ----//
    p.find('.successPage').find('.btn').on('click', function(e) {
        var t = $(this);
        goInvoicePage();
    });

    //----- header ----//

    hd.find('.menuBtn').on('click', function(e) {
        if (winP.hasClass('hide')) {
            hd.find('.phoneCloz').removeClass('phoneCloz');
        } else {
            alert('請先完成資料登錄')
        }
    });

    hd.find('.menu .clozBtn').on('click', function(e) {
        hd.find('.menu').addClass('phoneCloz');
    });



    hd.find('.m_invoiceBtn').on('click', function(e) {
        if (winP.hasClass('hide')) {
            showIndex();
            hd.find('.menu').addClass('phoneCloz');

        } else {
            alert('請先完成資料登錄')
        }
    });

    hd.find('.m_winner').on('click', function(e) {
        if (winP.hasClass('hide')) {
            showWinner();
            hd.find('.menu').addClass('phoneCloz');

        } else {
            alert('請先完成資料登錄')
        }
    });

    hd.find('.m_rule').on('click', function(e) {
        if (winP.hasClass('hide')) {
            showRule();
            hd.find('.menu').addClass('phoneCloz');

        } else {
            alert('請先完成資料登錄')
        }
    });

    hd.find('.bBtn').on('click', function(e) {
        if (winP.hasClass('hide')) {
            goInvoicePage();
            hd.find('.menu').addClass('phoneCloz');

        } else {
            alert('請先完成資料登錄')
        }
    });



    function showWin() {
        subPs.addClass('hide');
        winP.removeClass('hide');
    }

    function showlose() {
        subPs.addClass('hide');
        loseP.removeClass('hide');
    }

    function showlimit() {
        subPs.addClass('hide');
        limitP.removeClass('hide');
    }

    function showIndex() {
        subPs.addClass('hide');
        index.removeClass('hide');

        ani1.play();



    }

    function showRule() {
        subPs.addClass('hide');
        ruleP.removeClass('hide');
    }

    function showWinner() {
        subPs.addClass('hide');
        winnerP.removeClass('hide');
    }




}

// switchVoteMan
function switchVoteMan(id) {
    var p = $('#votePage');


    var index = p.find('.voteIndex');
    var vote = p.find('.votePage');

    ChungTool.removeClassWithFilter(vote.find('.manPic'), 'p');
    ChungTool.removeClassWithFilter(vote.find('.docTxt'), 'p');
    vote.find('.manPic').addClass('p' + id);
    vote.find('.docTxt').addClass('p' + id);
    simpleShow(vote.find('.manPic'));
    initFlipNum(vote.find('.flipNum'), index.find('.mc' + id).find('.flipNum').attr('data-num'));
    vote.find('.videoBtn').empty();
    ChungTool.addYouTube(vote.find('.videoBtn'), videoIdArr[id - 1]);

}

function initInvoiceBox() {
    var p = $('#billPage');
    var hd = p.find('.header');


    hd.find('.menuBtn').on('click', function(e) {
        hd.find('.phoneCloz').removeClass('phoneCloz');
    });

    hd.find('.menu .clozBtn').on('click', function(e) {
        hd.find('.menu').addClass('phoneCloz');
    });


    hd.find('.m_invoiceBtn').on('click', function(e) {
        goForm();
    });

    hd.find('.m_winner').on('click', function(e) {
        goWinner();

    });

    hd.find('.m_rule').on('click', function(e) {
        goRule();

    });

    hd.find('.bBtn').on('click', function(e) {
        goVotePage();
    });

    p.find('.scroll').on('click', function(e) {
        goForm();

    });

    p.find('.pricePage .btn').on('click', function(e) {
        goForm();
    });

    p.find('.ruleBtn').on('click', function(e) {
        goRule();
    });

    p.find('.invoicePop .cloz').on('click', function(e) {
        var t = $(this);
        $('.invoicePop').addClass('hide');
    });

    p.find('.invoicePop .btn').on('click', function(e) {
        var t = $(this);
        goVotePage();
    });

    function goForm() {
        var el = ($('.waitPage').hasClass('hide')) ? p.find('.formPage') : $('.waitPage');
        console.log(el);
        $('#billPage .container').animate({
            scrollTop: el.offset().top - p.find('.pricePage').offset().top
        }, 500);
        hd.find('.menu').addClass('phoneCloz');
    }

    function goRule() {
        $('#billPage .container').animate({
            scrollTop: p.find('.rulePage').offset().top - p.find('.pricePage').offset().top
        }, 500);
        hd.find('.menu').addClass('phoneCloz');
    }

    function goWinner() {
        $('#billPage .container').animate({
            scrollTop: p.find('.winnerPage').offset().top - p.find('.pricePage').offset().top
        }, 500);
        hd.find('.menu').addClass('phoneCloz');
    }




}

function goVotePage() {
    $("#billPage").addClass('hide');
    $("#votePage .subPage").addClass('hide');
    $("#votePage").removeClass('hide');
    $("#votePage .voteIndex").removeClass('hide');

    ani1.play();



    $('#billPage').find('.menu').addClass('phoneCloz');
}

function goInvoicePage() {
    $("#votePage .subPage").addClass('hide');
    $("#votePage").addClass('hide');
    $("#votePage .voteIndex").removeClass('hide');
    $('#billPage').removeClass('hide');
    $('#billPage .container').animate({
        scrollTop: 0
    }, 300);
    $('#votePage').find('.menu').addClass('phoneCloz');
    $('#billPage').find('.invoicePop').addClass('hide');

}



function showForm() {
    var p = $('#votePage');
    var index = p.find('.voteIndex');
    var vote = p.find('.votePage');
    var subPs = p.find('.subPage');
    var winP = p.find('.winPage');
    var loseP = p.find('.losePage');
    var limitP = p.find('.limitPage');
    var formP = p.find('.formPage');

    subPs.addClass('hide');
    formP.removeClass('hide');
}


// ---------- api --------------//

function getVoteNum() {
    var url = (ChungTool.isOnline()) ? 'api/vote_count.php' : 'test/vote.txt';
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(e) {
            // console.log('vote_count.php');
            // console.log(JSON.stringify(e));
            if (e.status == 1) {
                for (var i = 0; i < e.result.length; i++) {
                    $('.voteIndex').find('.manBox .flipNum').eq(i).attr('data-num', e.result[i].vote_count)
                }
            }
            checkUrl();

        },
        error: function(e) {
            // console.log(e);
        }
    });
}

function getDate() {
    var url = (ChungTool.isOnline()) ? 'api/current_date.php' : 'test/vote.txt';
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(e) {
            // console.log('current_date.php');
            // console.log(JSON.stringify(e));
            if (e.status == 1) {
                // $('#billPage .waitPage').addClass('hide');
                // console.log('current_date.php,status' + e.status);
            } else {
                // $('#billPage .formPage').addClass('hide');
                // console.log('current_date.php,status' + e.status);
            }
        },
        error: function(e) {
            // console.log(e);
        }
    });
}

function getWinner() { 

    var url = (ChungTool.isOnline()) ? 'api/award_list.php' : 'test/winner.txt';
    // var url = 'test/winner.txt';

    $.ajax({
        url: url,
        type: 'POST',
        dataType: 'json',
        data: { limit: 1000 },
        success: function(e) {
            // console.log('award_list.php');
            var indexLimit = (e.result.length > indexLimit) ? 10 : e.result.length;


            if (e.status == 1) {
                var txt = '';
                for (var i = 0; i < indexLimit; i++) {
                    if (e.result[i].username == null || e.result[i].phone == null) {
                        continue;
                    }

                    var st = e.result[i].username;
                    var chkName = /^[A-Za-z]$/;

                    if (!chkName.test(st[0])) {
                        st = st[0] + "O" + st.substring(2);
                    }


                    // txt += '恭喜' + st + '在 ' + e.result[i].updated.split(' ')[0] + ' 的時候抽中整箱樂事!!  ';
                    txt += '恭喜' + st + '抽中整箱樂事!!  ';
                }
                $('.voteIndex .winners').html(txt);


                // for (i = 0; i < e.result.length; i++) {
                //     var li = $('<li></li>')
                //     if (e.result[i].username == null || e.result[i].phone == null) {
                //         continue;
                //     }

                //     var st = e.result[i].username;
                //     var chkName = /^[A-Za-z]$/;

                //     if (!chkName.test(st[0])) {
                //         st = st[0] + "O" + st.substring(2);
                //     }

                //     // var Uname = e.result[i].username;
                //     var UPhone = e.result[i].phone.substring(0, 4) + '-xxx-' + e.result[i].phone.substring(7, 10);
                //     $('.winnerPage ul').append($('<li>' + st + '　' + UPhone + '</li>'))
                //         // console.log(UPhone)
                // }


            } else {

            }
        },
        error: function(e) {
            // console.log(e);
        }
    });
}

function auth_callback(fbid) {
    // console.log(fbid);
    userfbId = fbid;

    fbPopUpWindow.close();
    if (ChungTool.isPhone()) {
        $('.votePage .voteBtn').trigger('click');
        // console.log("$('.votePage .voteBtn').trigger('click');")
    } else {
        goManPage();
    }

}

function goManPage() {
    var p = $('#votePage');
    var index = p.find('.voteIndex');
    var vote = p.find('.votePage');


    index.addClass('hide');
    vote.removeClass('hide');
    isFBing = false;
}
