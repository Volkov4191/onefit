$(function(){
    $('body').on('submit', '.message-form', function(event){
        event.preventDefault();
        console.log('Submit Form', $(this).serialize());

        var request = $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: $(this).serialize(),
            dataType: "json"
        });

        request.done(function( response ) {

            Chat.updateMessageList(response.message.chat.id);

        });

        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });

        return false;
    });

    var $messageList = $(".message-list");
    $messageList.animate({ scrollTop: $messageList[0].scrollHeight }, "slow");

    var Chat = {
        updateMessageList: function(chatId){
            var url = Routing.generate('v1_get_chat_messages', {chatId : chatId, _format : 'json'});

            var request = $.ajax({
                url: url,
                method: 'GET',
                dataType: "json"
            });
            request.done(function( response ) {
                console.log(response.items);

                var template = $('#tmpl-message-list').html();
                Mustache.parse(template);   // optional, speeds up future uses
                var rendered = Mustache.render(template, {items: response.items});
                console.log(rendered);

                $messageList.html(rendered);
                $messageList.animate({ scrollTop: $messageList[0].scrollHeight }, "slow");
            });

            request.fail(function( jqXHR, textStatus ) {
                console.error(jqXHR, textStatus);
            });
        }
    }
});