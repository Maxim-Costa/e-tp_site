<?php

use App\Auth;

Auth::AdminVerif();


$pageTitle = 'new tp';

?>
 <div class="mt-5 card">
     <div class="card-body">
        <from action=""  method="post">
            <input type="text" class="form-control" placeholder="Titre" required id="title" />
            <div class="d-flex">
                <div class="w-50 mt-4 mr-1">
                    <label for="markdown" class="">Markdown</label>
                    <textarea id="markdown" class="w-100" name="markdown" required placeholder=" Contenu ..."></textarea>
                </div>
                <div class="mt-4 w-50">
                    <label for="markdown" class="">Previw</label>
                    <div id="preview" class="mode border rounded border-dark ml-1">
                        <div id="output" style="min-height: 54px" class="content markdown-body">
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group form-inline mt-4">
                <input type="number" class="form-control" name="nbJ" id="nbJ" placeholder="nombres de jours">
                <button type="submit" class="btn btn-primary ml-4 mr-4" id="subPost">Submit</button>
            </div>
            <div id="output2"></div>
        </from>
    </div>
</div>





<?php ob_start(); ?>
<script src="/assets/js/marked.min.js"></script>
<script type="text/javascript">
    $('#subPost').click(function() {
        $.ajax({
            type: "POST",
            url: "new_tp_update",
            data: {
                title: $('#title').val(),
                time: $('#nbJ').val(),
                content: $('#markdown').val()
            }
        }).done(function( msg ) {
            if (msg !== "true") {
                $('#output2').html('<div class="alert alert-danger" role="alert"> je champs '+msg+' est mal remplie </div>');
            } else {
                $('#output2').html('<div class="alert alert-success" role="alert"> Tout est bon :) </div>');
                $('#title').val("");
                $('#nbJ').val("");
                $('#markdown').val("");
                $('#output').html("");
            }
        });
    });
</script>
<script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-73660-14']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
      $(function() {
        var currentMode = 'edit';
        var scrollTops = {
            'edit' : 0,
            'preview' : 0
        };

        var isEdited = false;

        let convert = () => {
            let html = marked($('#markdown').val());
            $('#output').html(html);
        }

        $('#markdown').bind('keyup', function() {
            isEdited = true;
            convert();
            $('#output a').each(function(index, element) {
                var href = element.getAttribute('href');
                if (RegExp('^javascript', 'i').test(href)) {
                    element.setAttribute('href', '#');
                }
            });
        });

        //autoresize
        $('textarea').autosize();

        //leave
        $(window).bind('beforeunload', function() {
            if (isEdited) {
            return 'Are you sure you want to leave? Your changes will be lost.';
            }
        });

        convert();
        });
    </script>
<?php $pageJavascripts = ob_get_clean(); ?> 