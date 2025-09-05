<?php require 'db.php'; ?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — Chatter</title>
<style>
:root{--bg:#0b1220;--card:#0f172a;--line:#1f2937;--text:#e5e7eb;--muted:#94a3b8;--brand:#22d3ee;--accent:#60a5fa}
*{box-sizing:border-box}body{margin:0;background:radial-gradient(1200px 600px at 20% 0%,rgba(96,165,250,.2),transparent),#0b1220;font-family:Inter,system-ui}
.wrap{max-width:480px;margin:60px auto;padding:22px}
.card{background:linear-gradient(180deg,#0f172a,#0b1220);border:1px solid var(--line);border-radius:18px;padding:24px 20px;box-shadow:0 18px 60px rgba(0,0,0,.35)}
.input{width:100%;padding:14px;border:1px solid var(--line);background:#0b1220;color:#fff;border-radius:12px;margin:8px 0;font-size:15px}
.input:focus{outline:none;border-color:var(--accent);box-shadow:0 0 0 4px rgba(96,165,250,.15)}
.btn{width:100%;padding:14px 16px;border:none;border-radius:12px;background:linear-gradient(90deg,var(--accent),var(--brand));color:#001c2b;font-weight:800;cursor:pointer}
.note{font-size:13px;color:var(--muted);margin-top:10px;text-align:center}
.alert{padding:10px 12px;border-radius:10px;margin:10px 0;background:#1f2937}
</style></head><body>
<div class="wrap"><div class="card">
<h1>Welcome back</h1>
<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
$username=trim($_POST['username']??'');
$password=$_POST['password']??'';
$q=$pdo->prepare('SELECT * FROM users WHERE username=?');
$q->execute([$username]);
$u=$q->fetch();
if($u && password_verify($password,$u['password'])){
$_SESSION['user_id']=$u['id'];
echo '<div class="alert" style="color:#86efac">Logged in! Redirecting...</div><script>setTimeout(()=>location.href="index.php",600)</script>';
} else {
echo '<div class="alert" style="color:#fca5a5">Invalid credentials.</div>';
}
}
?>
<form method="post">
<input class="input" name="username" placeholder="Username" required>
<input class="input" name="password" type="password" placeholder="Password" required>
<button class="btn">Log in</button>
</form>
<p class="note">No account? <a class="link" href="register.php">Sign up</a></p>
</div></div></body></html>
