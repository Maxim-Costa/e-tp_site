<?php

use App\Auth;

Auth::AdminVerif();

$pageCss = '<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">';
$pageTitle = 'new tp';

?>
<div class="mt-5 card">
    <div class="card-body">
        <from action="" method="post">
            <input type="text" class="form-control" placeholder="Titre" required id="title"/>
            <div class="d-flex">
                <div class="w-100 mt-4 mr-1">
                    <label for="markdown" class="">Contenus</label>
                    <textarea id="markdown" class="w-100" name="markdown" required
                              placeholder=" Contenu ..."></textarea>
                </div>
            </div>
            <div class="form-group form-inline mt-4">
                <input type="number" class="form-control" name="nbJ" id="nbJ" placeholder="nombres de jours">
                <select class="custom-select ml-4 form-control" id="typeProject" required>
                    <option selected>Choose...</option>
                    <option value="1">Web</option>
                    <option value="2">Algo</option>
                    <option value="100">Others</option>
                </select>
                <button type="submit" class="btn btn-primary ml-4 mr-4" id="subPost">Submit</button>
            </div>
            <div id="output2"></div>
        </from>
    </div>
</div>


<?php ob_start(); ?>

<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
    var simplemde = new SimpleMDE({element: $("#markdown")[0]});

    $('#subPost').click(function () {
        $.ajax({
            type: "POST",
            url: "new_tp_update",
            data: {
                title: $('#title').val(),
                time: $('#nbJ').val(),
                content: simplemde.options.previewRender(simplemde.value()),
                typeProject: $('#typeProject').val()
            }
        }).done(function (msg) {
            if (msg !== "true") {
                $('#output2').html('<div class="alert alert-danger" role="alert">' + msg + '</div>');
            } else {
                $('#output2').html('<div class="alert alert-success" role="alert"> Tout est bon :) </div>');
                $('#title').val("");
                $('#nbJ').val("");
                simplemde.value("");
                $('#output').html("");
            }
        });
    });


</script>


<?php $pageJavascripts = ob_get_clean(); ?> 