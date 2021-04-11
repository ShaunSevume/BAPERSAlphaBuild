<?php

// imports
import('controllers/server.cont');

$sc = new ServerController();
$dl = $sc->getFolderContentList('/dbbackups/');

if (isset($_POST['dbrestoreSubmit'])) {
    $sc->dbrestore($_POST['bname']);
}

if ($sc->success) {
?>

<h5>Select a Backup to restore, or upload your own .sql file</h5><br>

<form style="margin-bottom:20px;" method="post" enctype="multipart/form-data">
    <div class="input-group">
        <input class="form-control" type="file">
        <button class="btn btn-primary">Restore</button>
    </div>
</form>
<?php
foreach ($dl as $item) {
?>

<div class="card bp-lv-card">
    <div class="card-header">
        <?php echo $item['name']; ?>

        <div class="lv-close-cue lv-drop">
            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="feather icon-chevron-down"></i>
            </button>

            <ul class="dropdown-menu">
                <li>
                    <form method="post">
                        <input style="display:none;" type="text" name="bname" value="<?php echo $item['name']; ?>">
                        <button type="submit" class="dropdown-item" name="dbrestoreSubmit">Restore Backup</button>
                    </form>
                    <a href="#" type="button" class="dropdown-item">Delete Backup</a>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php
}
} else {
    echo $sc->msg;
}
?>