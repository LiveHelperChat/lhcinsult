ee.addListener('quoteActionRight', function (params, chat_id, msg_id) {
    var contentOriginal = params['content']();
    params['content'] = function(){ return contentOriginal + ' <br/> <a href="#" id="lhcinsult-msg-'+msg_id+'"><i class="material-icons mr-0">block</i> Mark as insult</a>' }
    // Add event listener
    setTimeout(function(){
        $('#lhcinsult-msg-'+msg_id).click(function (event) {
            $.getJSON(WWW_DIR_JAVASCRIPT + 'lhcinsult/markasinsult/'+msg_id, function(data) {
                
                if (data.error) {
                    alert(data.msg);
                    return ;
                }

                lhinst.syncadmincall();
            });
        });
    },400);
});