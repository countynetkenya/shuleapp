
<div class="form-box" id="login-box">
    <div class="header"><?=$this->lang->line('select_school')?></div>

      <!-- style="margin-top:40px;" -->

      <div class="body white-bg">
        <?php foreach($schools as $school) {?>
          <form method="post">
            <input type="hidden" name="schoolID" value="<?=$school->schoolID?>">
            <input type="submit" class="btn btn-lg btn-success btn-block" value="<?=$school->name?>" />
          </form>
          <br/>
        <?php }?>
      </div>
</div>
