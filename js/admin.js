jQuery(function($) { 

    tinymce.init({ 
        selector:'.subtext', 
        height: 300,
        plugins: "paste",
    paste_as_text: true,
        toolbar: 'bold italic | time',   
        menubar: false,
        forced_root_block : "",
        setup: function (editor) {
         editor.addButton('time', {
              text: false,
              icon: 'insertdatetime',
              tooltip: "Insert Time",
              onclick: function() {
                editor.windowManager.open({
                    title: 'Subtitle',
                    body: [{
                        type: 'textbox',
                        name: 'subegins',
                        label: 'Begins:',
                        value: ''
                    }, {
                        type: 'textbox',
                        name: 'subends',
                        label: 'Ends',
                        value: ''
                        
                    }, ],
                    onsubmit: function(e) {
                        editor.insertContent(
                            '[sub data-begin="' +
                            e.data.subegins +
                            '" data-end="' +
                            e.data.subends +
                            '"]' +
                            editor.selection
                            .getContent() +
                            '[/sub]'
                        );
                    }
                });
            }
    });
  }});


        $(".dropdown dt a").click(function() {
            $(".dropdown dd ul").toggle();
        });

        $(".dropdown dd ul li a").click(function() {
            //console.log($(this).html())
            var text = $(this).html();
            $(".dropdown dt a span").html(text);

           var inner =  $(this).find("span:eq(1)").text();
           var innerID =  $(this).find("span:eq(2)").text();



            $(".dropdown input.title").val(inner);
            $(".dropdown input.id").val(innerID);

            $(".dropdown dd ul").hide();
            $("#result").html("Selected value is: " + getSelectedValue("country-select"));
        });

        function getSelectedValue(id) {
            //console.log(id,$("#" + id).find("dt a span.value").html())
            return $("#" + id).find("dt a span.value").html();
        }

        $(document).bind('click', function(e) {
            var $clicked = $(e.target);
            if (! $clicked.parents().hasClass("dropdown"))
                $(".dropdown dd ul").hide();
        });


        $("#flagSwitcher").click(function() {
            $(".dropdown img.flag").toggleClass("flagvisibility");
        });



        $(".addsubs").click(function() {



                        var getseason = $(this).attr('data-season');

               var count =  $('#here'+'_'+getseason+' .newsub').length;
            count = count + 1;


            $('#here'+'_'+getseason).append('<div class="row subadded newsub" style="padding: 15px 0;"><div class="col-md-9 col-xs-12"><input type="text" name="subtitles'+getseason+'['+count+'][episode]" placeholder="Select An Episode" value=""></div><div class="col-md-3 col-xs-12"><input type="text" name="subtitles'+getseason+'['+count+'][lang]" placeholder="Subtitle language?" value=""></div><div class="col-md-11 col-xs-12" style="padding-right: 0;"><input type="url" name="subtitles'+getseason+'['+count+'][watch]" placeholder="Where can this episode be watched?" value=""></div><div class="col-md-1"><div class="remove"><span class="dashicons dashicons-trash"></span></div></div><div class="col-md-12"><textarea class="subtext" name="subtitles'+getseason+'['+count+'][text]"></textarea></div></div>');
                                
                                    tinymce.init({ 
        selector:'.subtext', 
        height: 300,
        plugins: "paste",
    paste_as_text: true,
        toolbar: 'bold italic | time',   
        menubar: false,
        forced_root_block : "",
        setup: function (editor) {
         editor.addButton('time', {
              text: false,
              icon: 'insertdatetime',
              tooltip: "Insert Time",
              onclick: function() {
                editor.windowManager.open({
                    title: 'Subtitle',
                    body: [{
                        type: 'textbox',
                        name: 'subegins',
                        label: 'Begins:',
                        value: ''
                    }, {
                        type: 'textbox',
                        name: 'subends',
                        label: 'Ends',
                        value: ''
                        
                    }, ],
                    onsubmit: function(e) {
                        editor.insertContent(
                            '[sub data-begin="' +
                            e.data.subegins +
                            '" data-end="' +
                            e.data.subends +
                            '"]' +
                            editor.selection
                            .getContent() +
                            '[/sub]'
                        );
                    }
                });
            }
    });
  }});


            return false;
    


            console.log('Add Subs');
        });

          $(".remove").live('click', function() {
            $(this).parent().parent().remove();
        });

                    $(".editit").live('click', function() {
                            $(this).parent().parent().toggleClass('allsubs');


        });






    });