$(function(){
    var $body = $('body');

    $body.on('submit', '.message-form', function(event){
        event.preventDefault();
        var request = $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: "json"
        });
        request.done(function( response ) {
            Chat.updateMessageList();
            Chat.refreshForm();
        });
        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });
        return false;
    });

    $body.on('click', '.message-edit', function(event){
        return false;
    });

    $body.on('mousedown', '.message-edit', function(event){
        switch (event.which) { // при открытии в новой вкладке - открываем форму
            case 2:
            case 3:
                return true;
        }
        event.preventDefault();
        var request = $.ajax({
            url: $(this).attr('href') + '.json',
            dataType: "json"
        });
        request.done(function( response ) {
            $('.message-form-container').html(response.html);
        });
        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });
        return false;
    });

    $body.on('click', '.write-comment', function(){
        $('.message-form textarea').focus();
    })
});

var Chat = {
    _id : null,
    _user_id: null,
    init: function(options){
        this._id      = options.id;
        this._user_id = options.user_id;
    },
    /**
     * Обновляем список сообщений
     */
    updateMessageList: function(){
        var url = Routing.generate('v1_get_chat_messages', {chatId : this._id, _format : 'json'});
        var user_id = this._getUserId();
        var $messageListContainer = this._getMessageListContainer();

        var request = $.ajax({
            url: url,
            method: 'GET',
            dataType: "json"
        });
        request.done(function( response ) {

            $messageListContainer.html('');
            if (response.messages.length == 0){
                $messageListContainer.html(Mustache.render($('#tmpl-no-message-alert').html()));
                return false;
            }

            var template = $('#tmpl-message-one').html();
            Mustache.parse(template);   // optional, speeds up future uses

            $.each(response.messages, function(idx, message){
                var edit_url = Routing.generate('v1_get_message_edit', {'id' : message.id});

                var createdAtFormat = '';
                if (Date.parse(message.createdAt)){
                    var createdAt = new Date(message.createdAt);
                    createdAtFormat = createdAt.toLocaleString();
                }
                message.createdAt = createdAtFormat;

                var rendered = Mustache.render(template, {'message': message, 'edit_url' : edit_url, 'show_btn_edit' : user_id == message.user.id});
                $messageListContainer.append(rendered);
            });

            //$messageList.html(rendered);
            $messageListContainer.animate({ scrollTop: $messageListContainer[0].scrollHeight }, "slow");
        });

        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });
    },
    /**
     * Обновляем список юзеров
     */
    updateUserList:function(){
        var url = Routing.generate('v1_get_users', {chatId : this._id, _format : 'json'});
        var $userListContainer = this._getUserListContainer();

        var request = $.ajax({
            url: url,
            method: 'GET',
            dataType: "json"
        });
        request.done(function( response ) {
            $userListContainer.html('');
            if (response.users.length == 0){
                $userListContainer.html(Mustache.render($('#tmpl-no-user-alert').html()));
                return false;
            }

            var template = $('#tmpl-user-one').html();
            Mustache.parse(template);   // optional, speeds up future uses
            $.each(response.users, function(idx, user){
                var rendered = Mustache.render(template, {'user': user});
                $userListContainer.append(rendered);
            });
        });
        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });
    },
    /**
     * Обновляем форму добавления комментария
     *
     * @returns {boolean}
     */
    refreshForm: function(){
        if (!this._getUserId()){
            return false;
        }
        var url = Routing.generate( 'v1_get_chat_message_new', {chatId : this._id, _format : 'json'});
        var $formContainer =  this._getMessageFromContainer();
        var request = $.ajax({
            url: url,
            method: 'GET',
            dataType: "json"
        });
        request.done(function( response ) {
            $formContainer.html(response.html);
        });

        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });
    },
    _getUserListContainer:function(){
        return $('.user-list');
    },
    _getMessageListContainer: function(){
        return  $(".message-list");
    },
    _getMessageFromContainer: function(){
        return $('.message-form-container');
    },
    _getUserId : function(){
        return parseInt(this._user_id);
    }

};