<?php
include('mastodon.php');

if (isset($_POST['accesstoken'])) {

    $token = $_POST['accesstoken']; // Token of your Mastodon bot account
    $baseURL = (substr($_POST['instance'], 0, 8) == 'https://') ? $_POST['instance'] : 'https://' . $_POST['instance']; //'https://piaille.fr'; // URL of your instance (Do not include '/' at the end.)
    $visibility = 'public'; // "Direct" means sending message as a private message. The four tiers of privacy for toots are public , unlisted, private, and direct
    $language = 'fr'; // en for English, zh for Chinese, de for German etc.
    $in_reply_to = '';

    foreach ($_POST['status'] as $item) {
        $statusText = $item;
        $statusData = [
            'status'      => $statusText,
            'visibility'     => $visibility,
            'language'    => $language,
            'in_reply_to_id' =>  $in_reply_to,
        ];

        $mastodon = new MastodonAPI($token, $baseURL);
        $result = $mastodon->postStatus($statusData);
        $in_reply_to = $result['id'];
        $display_name = $result['account']['display_name'];
        $avatar = $result['account']['avatar_static'];
        $posted .= '<li class="list-group-item">' . $result['content'] . "</li>";
        // var_dump($result);
    }
    // die;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mastothread</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.6.2.slim.min.js" integrity="sha256-E3P3OaTZH+HlEM7f1gdAT3lHAn4nWBZXuYe89DFg2d0=" crossorigin="anonymous"></script>
    <script>
        $( document ).ready(function() {
            console.log( "ready!" );
            $('#add').click(function() {
                $('#add').before('<div class="form-floatin"><textarea class="form-control" name="status[]" placeholder="Next..."></textarea></div>');
                return false;
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>MastoThread</h1>
        <hr />
        <?php if (isset($_POST['accesstoken'])) { ?>
        <div class="alert alert-success" role="alert">
            Votre thread a été posté !
        </div>
        <div><img width="200" class="rounded float-start" src="<?php echo $avatar; ?>">
            <h4><?php echo $display_name; ?></h4>
        </div>
        <ul class="list-group">
            <?php echo $posted; ?>
        </ul>
        <?php } ?>
        <hr />
        <form action="./" method="POST" class="needs-validation">
            <div class="form-floating">
                <input class="form-control" id="at" name="accesstoken" type="text" placeholder="Access Token" required/>
                <label for="at">Access Token</label>
                <div class="invalid-feedback">
                    Un Access Token est requis
                </div>
            </div>
            <div class="form-floating">
                <input class="form-control" id="at" name="instance" type="text" placeholder="https://piaille.fr" required/>
                <label for="at">Mastodon instance (eg. https://mastodon.social)</label>
            </div>
            <div class="form-floatin">
                <textarea class="form-control" id="firststatus" name="status[]" placeholder="Start thread" required></textarea>
                <!-- <label>Début du thread</label> -->
            </div>
            <div class="form-floatin">
                <textarea class="form-control" name="status[]" placeholder="Next..." required></textarea>
                <!-- <label for="at">Suite...</label> -->
            </div>
            <a class="btn btn-primary" id="add" href=""><i class="bi bi-plus-circle"></i></a>
            <input class="btn btn-primary" type="submit" value="Toot!"/>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>