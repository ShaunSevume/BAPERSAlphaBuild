<?php


if (isset($_POST['backupSubmit'])) {

    import('controllers/server.cont');
    $sc = new ServerController();
    $sc->dbbackup();

}

?>

<form style="margin-bottom:20px;" method="POST" class="bapers-form">
    <label>Choose Frequency</label>
    <select class="form-control">
        <?php
        if (APP_DATA['debug_mode']) {
            echo '<option>Every Minute</option>';
        }
        ?>
        <option>Hourly</option>
        <option>Daily</option>
        <option>Monthly</option>
    </select>

    <label>Start Time</label>
    <div style="margin-bottom:15px;" class="input-group">
        <input type="number" class="form-control" placeholder="Hours">
        <input type="number" class="form-control" placeholder="Minutes">
    </div>

    <button type="submit" class="btn btn-primary" name="autoBackupSubmit">Save</button>
</form>

<form method="POST" style="display:inline;">
    <button type="submit" class="btn btn-primary" name="backupSubmit"><i style="margin-right:10px;" class="feather icon-download-cloud"></i>Backup Database</button>
</form>

<a href="<?php echo WEB_CONFIG['home'].'index.php?v=admin&s=dbrestore'; ?>" class="btn btn-primary" name="restoreSubmit"><i style="margin-right:10px;" class="feather icon-upload-cloud"></i>Restore Database</a>