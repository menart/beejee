const APP_URL = 'app/';
const DATA_URL = APP_URL + 'gettasks.php';
const CHANGE_URL = APP_URL + 'updatetask.php';
const AUTH_URL = APP_URL + 'auth.php';
let admin = 0;

function getData(page, counttask, order, direct) {
    $.ajax({
        type: 'POST',
        url: DATA_URL,
        data: 'page=' + (page - 1) +
            '&counttask=' + counttask +
            '&order=' + order +
            '&direction=' + direct,
        async: true,
        success: createTable
    })
}

function changeTask() {
    let action = $('#change_popup').data('action');
    let task_id = $('#change_popup').data('id');
    let user = $('#task_name').val();
    let email = $('#task_email').val();
    let context = $('#task_context').val();
    let status = $('#change_popup').data('status');

    if (user == '') {
        alert('Не заполнено поле "Имя"!');
        $('#task_name').focus();
        return;
    }

    let pattern = /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,20}\.)?[a-z]{2,20}$/i;
    if (email.search(pattern) != 0) {
        alert('e-mail не корректен!');
        $('#email').focus();
        return;
    }

    if (context == '') {
        alert('Не заполнено задание!');
        $('#context').focus();
        return;
    }

    sendChange(action, task_id, user, email, context, status);

    $('#change_popup').hide();
}

function init() {
    getData(0, 3, 'id', 'asc');
    $('#add_task').bind('click', function (event) {
        event.preventDefault();
        $('#change_popup').data('action', 'insert');
        $('#change_popup').data('id', '0');
        $('#change_popup').data('status', 0);
        $('#change_popup').show();
    });
    $('#change_popup_cancel').bind('click', function (event) {
        event.preventDefault();
        $('#change_popup').hide();
    });
    $('#change_popup_ok').bind('click', function (event) {
        event.preventDefault();
        changeTask();
    });
    $('#update').click(function (event) {
        event.preventDefault();
        getData($('.page-item.active').text(),
            $('select#head_count-page_select').val(),
            $('select#head_order-box_select').val(),
            $('select#head_order-box_direct_select').val());
    });

    $('#auth').click(function (event) {
        event.preventDefault();
        if (admin == 0) {
            authform();
        } else {
            auth('logout');
        }
    })
    $('#auth_popup_ok').bind('click', function (event) {
        event.preventDefault();
        auth('logon');
    });
    $('#auth_popup_cancel').bind('click', function (event) {
        event.preventDefault();
        $('#auth_popup').hide();
    });

}

function createTable(data) {
    let pagination = $('.pagination');
    admin = data.admin;

    $("#auth").text(admin == 0 ? 'Войти' : 'Выйти');

    pagination.html('');
    for (let i = 0; i < data.countPage; i++) {
        pagination.append($('<li class="page-item ' + ((i == data.page) ? 'active' : '') + '">' +
            '<a href="#" class="page-link">' + (i + 1) + '</a></li>'));
    }

    $('.page-link').unbind('click');
    $('.page-link').bind('click', function (event) {
        event.preventDefault();
        getData($(this).text(),
            $('select#head_count-page_select').val(),
            $('select#head_order-box_select').val(),
            $('select#head_order-box_direct_select').val());
    });

    let tableData = $('.table_data');
    tableData.html('');
    tableData.html(data.list.reduce(function f(acc, item) {
        console.log(item.status);
        return acc + '<div class="task col-lg-12" id="task' + item.id + '">' +
            '<div class="task_title row">' +
            '<div class="col-lg-' + (admin == 1 ? '3' : '4') + ' task_user">Пользователь: <span class="data">' +
            item.user + '</span></div>' +
            '<div class="col-lg-' + (admin == 1 ? '3' : '4') + ' task_email"> e-mail: <span class="data">' +
            item.email + '</span></div>' +
            '<div class="col-lg-3 task_status">' +
            (admin == 1 ? '<a href="#" class="task_finish task_action" data-id="' + item.id +
                '" title="' + (item.status & 1 == 0 ? 'Выполнить' : 'Отменить выполнение') + '">' : '') +
            (item.status & 1 ? 'В' : 'Нев' ) + 'ыполнено' +
            (item.status & 2 ? ' / Отредактировано администратором' : '') +
            (admin == 1 ? '</a>' : '') +
            '<span class="data hide">' + (item.status & 1) + '</span> </div>' +
            (admin == 1 ? '<div class="col-lg-3">' +
                '<a href="#" class="task_change task_action" data-id="' + item.id + '" title="Изменить">Изменить</a> ' +
                '<a href="#" class="task_delete task_action" data-id="' + item.id + '"title="Удалить">Удалить</a>' +
                '</div>' : '') +
            '</div><div class="jumbotron task_context">' + item.context + '</div></div>';
    }, ''));
    if (admin == 1) init_change();
}

function init_change() {
    $('.task_finish').unbind('click');
    $('.task_finish').click(function (event) {
        event.preventDefault();
        const task_id = $(this).data('id');
        const user = $('#task' + task_id + ' .task_user span.data').text();
        const email = $('#task' + task_id + ' .task_email span.data').text();
        const context = $('#task' + task_id + ' .task_context').text();
        let status = $('#task' + task_id + ' .task_status span.data').text() == '0' ? '1' : '0';
        sendChange('update', task_id, user, email, context, status)
    });
    $('.task_change').unbind('click');
    $('.task_change').click(function (event) {
        event.preventDefault();

        const task_id = $(this).data('id');
        $('#change_popup').data('id', task_id);

        let user = $('#task' + task_id + ' .task_user span.data').text();
        $('#task_name').val(user);

        let email = $('#task' + task_id + ' .task_email span.data').text();
        $('#task_email').val(email);

        let context = $('#task' + task_id + ' .task_context').text();
        $('#task_context').val(context);

        let status = $('#task' + task_id + ' .task_status').text();
        $('#change_popup').data('status', status);

        $('#change_popup').data('action', 'update');
        $('#change_popup').show();
    });
    $('.task_delete').unbind('click');
    $('.task_delete').click(function (event) {
        event.preventDefault();
        const task_id = $(this).data('id');
        sendChange('delete', task_id, '', '', '', '');
    });
}

function sendChange(action, task_id, user, email, context, status) {
    $.ajax({
        type: 'POST',
        url: CHANGE_URL + '?action=' + action,
        data: 'id=' + task_id +
            '&user=' + user +
            '&email=' + email +
            '&context=' + context +
            '&status=' + status,
        async: true,
        success: function (data) {
            if (data.status == "-1") {
                getData($('.page-item.active').text(),
                    $('select#head_count-page_select').val(),
                    $('select#head_order-box_select').val(),
                    $('select#head_order-box_direct_select').val());
                authform();
            }
            if (data.status == "0")
                getData($('.page-item.active').text(),
                    $('select#head_count-page_select').val(),
                    $('select#head_order-box_select').val(),
                    $('select#head_order-box_direct_select').val());
            alert(data.retmsg);
        }
    });
}

function authform() {
    $('#auth_popup').show();
}

function auth(action) {
    $.ajax({
        type: 'POST',
        data: 'user=' + $('#user_name').val() +
            '&pwd=' + $('#user_pwd').val(),
        url: AUTH_URL + '?action=' + action,
        success: function (data) {
            console.log(data);
            if (data.admin == -1) {
                alert('Не верное имя пользователя или пароль!')
            } else {
                $('#auth_popup').hide();
                admin = data.admin;
            }
        }
    })
    getData($('.page-item.active').text(),
        $('select#head_count-page_select').val(),
        $('select#head_order-box_select').val(),
        $('select#head_order-box_direct_select').val());
}