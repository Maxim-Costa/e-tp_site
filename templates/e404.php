<?php

http_response_code(404);

?>

<div style="text-align: -webkit-center;">
    <div class="card border-warning mt-5" style="max-width: 25rem">
        <img src="https://i.pinimg.com/originals/7d/21/02/7d21021dac6bc10c72ab13801588415b.jpg" alt="" class="card-img-top">
        <div class="card-body text-warning">
            <h5 class="card-title">Tu t'es tromper de chemain</h5>
            <a href="<?=$router->generate('home')?>" class="btn btn-outline-warning">Go Home</a>
        </div>
    </div>
</div>