<?php
require 'db.php';
auth_required();
 
$uid = $_SESSION['user_id'];
 
// Fetch current user
$me = $pdo->prepare('SELECT * FROM users WHERE id=?');
$me->execute([$uid]);
$me = $me->fetch();
 
// Build feed: self + following; if none, show all
$feed = $pdo->prepare('SELECT t.*, u.username,u.full_name,u.avatar,
  (SELECT COUNT(*) FROM likes l WHERE l.tweet_id=t.id) as like_count,
  (SELECT COUNT(*) FROM comments c WHERE c.tweet_id=t.id) as comment_count,
  (SELECT COUNT(*) FROM likes l2 WHERE l2.tweet_id=t.id AND l2.user_id=?) as i_liked
  FROM tweets t 
  JOIN users u ON u.id=t.user_id
  WHERE t.user_id IN (SELECT following_id FROM follows WHERE follower_id=?) OR t.user_id=? 
  ORDER BY t.created_at DESC LIMIT 200');
$feed->execute([$uid,$uid,$uid]);
$tweets = $feed->fetchAll();
 
// If empty, fallback to all tweets
if(!$tweets){
  $all = $pdo->query('SELECT t.*, u.username,u.full_name,u.avatar,
    (SELECT COUNT(*) FROM likes l WHERE l.tweet_id=t.id) as like_count,
    (SELECT COUNT(*) FROM comments c WHERE c.tweet_id=t.id) as comment_count,
    0 as i_liked
    FROM tweets t 
    JOIN users u ON u.id=t.user_id 
    ORDER BY t.created_at DESC LIMIT 100');
  $tweets = $all->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Home — Chatter</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{margin:0;font-family:sans-serif;background:#0f172a;color:#e5e7eb}
header{background:#111827;padding:10px 20px;display:flex;justify-content:space-between;align-items:center;position:sticky;top:0}
header h1{margin:0;font-size:22px;color:#22d3ee}
.container{max-width:600px;margin:20px auto;padding:0 15px}
.tweet-box{background:#111827;padding:15px;border-radius:12px;margin-bottom:20px}
.tweet-box textarea{width:100%;border:none;resize:none;padding:10px;border-radius:8px;background:#0b1220;color:#fff;font-size:15px;outline:none}
.tweet-box button{margin-top:8px;padding:10px 16px;background:#22d3ee;color:#0f172a;font-weight:bold;border:none;border-radius:20px;cursor:pointer;float:right}
.feed .tweet{background:#111827;padding:15px;border-radius:12px;margin-bottom:15px}
.tweet .author{display:flex;align-items:center;gap:10px}
.tweet img{width:40px;height:40px;border-radius:50%}
.tweet .meta{font-size:14px;color:#94a3b8}
.actions{margin-top:8px;display:flex;gap:20px;font-size:14px;color:#94a3b8}
.actions span{cursor:pointer}
.actions span:hover{color:#22d3ee}
</style>
</head>
<body>
<header>
  <h1>🐦 Chatter</h1>
  <div><?=htmlspecialchars($me['username'])?></div>
</header>
 
<div class="container">
  <!-- Tweet Box -->
  <div class="tweet-box">
    <form action="post_tweet.php" method="POST">
      <textarea name="content" rows="3" maxlength="280" placeholder="What's happening?"></textarea>
      <button type="submit">Tweet</button>
    </form>
  </div>
 
  <!-- Feed -->
  <div class="feed">
    <?php foreach($tweets as $t): ?>
      <div class="tweet">
        <div class="author">
          <img src="<?=htmlspecialchars($t['avatar'])?>" alt="avatar">
          <div>
            <b><?=htmlspecialchars($t['full_name'])?></b> 
            <span class="meta">@<?=htmlspecialchars($t['username'])?> • <?=date('M d, H:i', strtotime($t['created_at']))?></span>
          </div>
        </div>
        <p><?=nl2br(htmlspecialchars($t['content']))?></p>
        <div class="actions">
          <span>❤️ <?=$t['like_count']?></span>
          <span>💬 <?=$t['comment_count']?></span>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
 
</body>
</html>
