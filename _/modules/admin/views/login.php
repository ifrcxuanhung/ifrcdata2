<section id="login-block">
    <div class="block-border">
        <div class="block-content">
            <h1>Login</h1>
            <div class="block-header">IFRC Databases</div>
            <form class="form with-margin" name="login-form" id="login-form" method="post" action="">
                <?php if ($message) { ?>
                    <p class="message error no-margin"><?php echo $message; ?></p>
                <?php } ?>
                <input type="hidden" name="a" id="a" value="send">
                <p class="inline-small-label">
                    <label for="login"><span class="big">Email</span></label>
                    <?php echo form_input($identity); ?>
                </p>
                <p class="inline-small-label">
                    <label for="pass"><span class="big">Password</span></label>
                    <?php echo form_input($password); ?>
                </p>
                <p>
                    <label for="remember">Remember me :
                        <?php echo form_checkbox('remember', '1', FALSE, 'id="remember"'); ?>
                    </label>
                </p>
                <button type="submit" class="float-right">Login</button>
                <p class="input-height">

                </p>
            </form>
        </div>
    </div>
</section>