<?php require 'db.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign up — Chatter</title>
<style>
:root{--bg:#0b1220;--card:#0f172a;--line:#1f2937;--text:#e5e7eb;--muted:#94a3b8;--brand:#22d3ee;--accent:#60a5fa;--danger:#ef4444}
*{box-sizing:border-box}body{margin:0;background:linear-gradient(180deg,#0b1220,#0f172a 45%);font-family:Inter,system-ui,Segoe UI,Roboto;color:var(--text)}
.wrap{max-width:480px;margin:50px auto;padding:22px}
.card{background:linear-gradient(180deg,#0f172a,#0b1220);border:1px solid var(--line);border-radius:18px;padding:24px 20px;box-shadow:0 18px 60px rgba(0,0,0,.35)}
h1{margin:0 0 8px;font-size:28px}
.p{color:var(--muted);margin:0 0 16px}
.input{width:100%;padding:14px;border:1px solid var(--line);background:#0b1220;color:#fff;border-radius:12px;margin:8px 0;font-size:15px}
.input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 4px rgba(96,165,250,.15)}
.btn{width:100%;padding:14px 16px;border:none;border-radius:12px;background:linear-gradient(90deg,var(--accent),var(--brand));color:#001c2b;font-weight:800;cursor:pointer}
.note{font-size:13px;color:var(--muted);margin-top:10px;text-align:center}
.link{color:var(--brand);text-decoration:none}
.alert{padding:10px 12px;border-radius:10px;margin:10px 0;background:#1f2937}
</style></head><body>
<div class="wrap"><div class="card">
<h1>Create account</h1><p class="p">Join Chatter and start posting thoughts.</p>
<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
$username=trim($_POST['username']??'');
$email=trim($_POST['email']??'');
$password=$_POST['password']??'';
if(!$username||!$email||!$password){ echo '<div class="alert">All fields are required.</div>'; }
else{
$q=$pdo->prepare('SELECT id FROM users WHERE username=? OR email=?');
$q->execute([$username,$email]);
if($q->fetch()){ echo '<div class="alert" style="color:#fca5a5">Username or email already in use.</div>'; }
else{
$hash=password_hash($password,PASSWORD_BCRYPT);
$ins=$pdo->prepare('INSERT INTO users (username,email,password,full_name) VALUES (?,?,?,?)');
$ins->execute([$username,$email,$hash,$username]);
echo '<div class="alert" style="color:#86efac">Account created! Redirecting...</div><script>setTimeout(()=>location.href="login.php",800)</script>';
}
}
}
?>
<form method="post">
<input class="input" name="username" placeholder="Username" required>
<input class="input" name="email" type="email" placeholder="Email" required>
<input class="input" name="password" type="password" placeholder="Password" required>
<button class="btn">Sign up</button>
</form>
<p class="note">Already have an account? <a class="link" href="login.php">Log in</a></p>
</div></div></body></htm
