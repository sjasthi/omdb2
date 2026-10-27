<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page=true; require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
$errors=[]; $success=false; $values=$_POST;
if (is_post_request()) {
    try {
        $first=catalog_text($_POST,'first_name',25,true); $last=catalog_text($_POST,'last_name',25,true);
        $email=catalog_text($_POST,'email',100,true);
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) { throw new InvalidArgumentException('Enter an email address such as name@example.com.'); }
        $password=$_POST['password'] ?? ''; $repeat=$_POST['repeat_password'] ?? '';
        if (!is_string($password) || !is_string($repeat) || mb_strlen($password,'UTF-8')<8) { throw new InvalidArgumentException('Enter a password with at least 8 characters.'); }
        if (strlen($password)>72) { throw new InvalidArgumentException('This password is too long. Choose a shorter password.'); }
        if ($password !== $repeat) { throw new InvalidArgumentException('The passwords do not match. Enter the same password in both fields.'); }
        if (db_query($db,'SELECT id FROM users WHERE email=?',[$email])->num_rows) { throw new InvalidArgumentException('An account already uses that email address. Enter a different email.'); }
        $hash=password_hash($password,PASSWORD_DEFAULT);
        db_query($db,'INSERT INTO users (password,email,first_name,last_name,role) VALUES (?,?,?,?,?)',[$hash,$email,$first,$last,'USER']);
        $success=true; $values=[];
    } catch (InvalidArgumentException $error) { $errors[]=$error->getMessage(); http_response_code(400); }
    catch (mysqli_sql_exception $error) {
        if ($error->getCode() !== 1062) { throw $error; }
        $errors[]='An account already uses that email address. Enter a different email.'; http_response_code(400);
    }
}
$nav_selected='SETUP'; $left_buttons='YES'; $left_selected='ACCOUNTS';
include __DIR__ . '/nav.php';
if ($success) { echo ui_notice('User account created. The person can now sign in.','success'); }
echo display_errors($errors);
?>
<form class="form-panel" method="post" action="sign_up.php">
<?php echo csrf_field(); ?>
<p class="form-help">All fields are required. The new account will have the Member role.</p>
<?php foreach (['first_name'=>['First name','text',25],'last_name'=>['Last name','text',25],'email'=>['Email address','email',100]] as $key=>[$label,$type,$limit]) { ?>
<div class="form-group"><label for="<?php echo h($key); ?>"><?php echo h($label); ?></label><input class="form-control" type="<?php echo h($type); ?>" id="<?php echo h($key); ?>" name="<?php echo h($key); ?>" maxlength="<?php echo h($limit); ?>" value="<?php echo h(ui_field_value($values,$key)); ?>" required></div>
<?php } ?>
<div class="form-group"><label for="password">Password</label><input class="form-control" id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" aria-describedby="password-help" required><small class="form-help" id="password-help">Use at least 8 characters. Choose a unique password for this account.</small></div>
<div class="form-group"><label for="repeat_password">Repeat password</label><input class="form-control" id="repeat_password" name="repeat_password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required></div>
<div class="form-actions"><button class="btn btn-primary" type="submit">Create account</button><a class="btn btn-default" href="setup.php">Cancel</a></div>
</form>
<?php include __DIR__ . '/footer.php'; ?>
