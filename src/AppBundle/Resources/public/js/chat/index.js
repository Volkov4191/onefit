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
            console.log(response);
        });

        request.fail(function( jqXHR, textStatus ) {
            console.error(jqXHR, textStatus);
        });

        return false;
    });
});