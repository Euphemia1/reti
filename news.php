<?php
require_once 'includes/functions.php';

$per_page = 6;
$current_page = max(1, (int)($_GET['page'] ?? 1));
$single_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$site_title = getSiteSetting('site_title') ?: 'Rising East Training Institute';

if ($single_id > 0) {
    $article = null;
    try {
        $stmt = $conn->prepare("SELECT * FROM news WHERE id = ? AND status = 'published'");
        $stmt->execute([$single_id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($article) $conn->prepare("UPDATE news SET views = views + 1 WHERE id = ?")->execute([$single_id]);
    } catch(PDOException $e) {}
    if (!$article) { header('Location: news.php'); exit; }
    $related = [];
    try {
        $stmt = $conn->prepare("SELECT * FROM news WHERE status = 'published' AND id != ? ORDER BY created_at DESC LIMIT 3");
        $stmt->execute([$single_id]);
        $related = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {}
} else {
    $total = 0; $news_list = [];
    try { $total = (int)$conn->query("SELECT COUNT(*) FROM news WHERE status = 'published'")->fetchColumn(); } catch(PDOException $e) {}
    $pagination = getPagination($total, $per_page, $current_page);
    try {
        $stmt = $conn->prepare("SELECT * FROM news WHERE status = 'published' ORDER BY created_at DESC LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $per_page, PDO::PARAM_INT);
        $stmt->bindValue(2, $pagination['offset'], PDO::PARAM_INT);
        $stmt->execute();
        $news_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if ($single_id > 0 && isset($article)): ?>
    <title><?php echo htmlspecialchars($article['title']); ?> - <?php echo htmlspecialchars($site_title); ?></title>
    <?php else: ?>
    <title>News & Events - <?php echo htmlspecialchars($site_title); ?></title>
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
<header id="mainHeader" class="scrolled">
    <nav class="container">
        <a href="index.php" class="logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></a>
        <ul class="nav-links" id="navLinks">
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About Us</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="news.php" class="active">News</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="enroll.php" class="nav-cta">Apply Now</a></li>
        </ul>
        <div class="menu-toggle" id="menuToggle"><span></span><span></span><span></span></div>
    </nav>
</header>

<?php if ($single_id > 0 && isset($article)): ?>
<div class="page-header">
    <div class="container">
        <h1 style="font-size:clamp(1.5rem,4vw,2.5rem);"><?php echo htmlspecialchars($article['title']); ?></h1>
        <div class="breadcrumb">
            <a href="index.php">Home</a><span><i class="fas fa-chevron-right"></i></span>
            <a href="news.php">News</a><span><i class="fas fa-chevron-right"></i></span>
            <span>Article</span>
        </div>
    </div>
</div>
<section>
    <div class="container">
        <div class="single-news-content">
            <div class="news-meta" style="margin-bottom:1.5rem;">
                <span><i class="far fa-calendar"></i> <?php echo date('d F Y', strtotime($article['created_at'])); ?></span>
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($article['author'] ?: 'RETI Admin'); ?></span>
                <span><i class="fas fa-eye"></i> <?php echo number_format((int)($article['views'] ?? 0)); ?> views</span>
            </div>
            <?php if (!empty($article['tags'])): ?>
            <div class="news-tags" style="margin-bottom:1.5rem;">
                <?php foreach (explode(',', $article['tags']) as $tag): ?>
                <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <?php if (!empty($article['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($article['image_url']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" loading="lazy" style="border-radius:var(--radius-lg);margin-bottom:2rem;width:100%;">
            <?php endif; ?>
            <div class="article-body"><?php echo $article['content']; ?></div>
            <div style="margin-top:2rem;padding-top:2rem;border-top:1px solid var(--border);display:flex;gap:1rem;flex-wrap:wrap;">
                <a href="news.php" class="btn btn-outline-primary"><i class="fas fa-arrow-left"></i> Back to News</a>
                <a href="contact.php" class="btn btn-primary">Apply Now <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
        <?php if (!empty($related)): ?>
        <div style="margin-top:4rem;">
            <h3 style="margin-bottom:1.5rem;">More News</h3>
            <div class="news-grid">
                <?php foreach ($related as $r): ?>
                <div class="news-card">
                    <div class="news-image"><img src="<?php echo htmlspecialchars($r['image_url'] ?: 'assets/images/news-placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($r['title']); ?>" loading="lazy"><span class="news-date"><i class="far fa-calendar"></i> <?php echo date('d M Y', strtotime($r['created_at'])); ?></span></div>
                    <div class="news-body"><h3><?php echo htmlspecialchars($r['title']); ?></h3><p><?php echo htmlspecialchars(truncateText($r['excerpt'] ?: strip_tags($r['content']), 100)); ?></p><a href="news.php?id=<?php echo $r['id']; ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php else: ?>
<div class="page-header">
    <div class="container">
        <h1>News & Events</h1>
        <p>Stay updated with the latest news, events, and announcements from RETI</p>
        <div class="breadcrumb"><a href="index.php">Home</a><span><i class="fas fa-chevron-right"></i></span><span>News</span></div>
    </div>
</div>
<section>
    <div class="container">
        <?php if (empty($news_list)): ?>
        <div style="text-align:center;padding:4rem;">
            <i class="fas fa-newspaper" style="font-size:4rem;color:var(--border);display:block;margin-bottom:1rem;"></i>
            <h3>No news articles yet</h3>
            <p style="color:var(--text-light);">Check back soon for updates from RETI.</p>
        </div>
        <?php else: ?>
        <div class="news-list">
            <?php foreach ($news_list as $i => $item): ?>
            <div class="news-article" data-aos="fade-up" data-aos-delay="<?php echo min($i * 60, 240); ?>">
                <div class="news-article-img">
                    <img src="<?php echo htmlspecialchars($item['image_url'] ?: 'assets/images/news-placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
                </div>
                <div class="news-article-body">
                    <div class="news-meta">
                        <span><i class="far fa-calendar"></i> <?php echo date('d F Y', strtotime($item['created_at'])); ?></span>
                        <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($item['author'] ?: 'RETI Admin'); ?></span>
                        <?php if (!empty($item['tags'])): ?><span><i class="fas fa-tag"></i> <?php echo htmlspecialchars(explode(',', $item['tags'])[0]); ?></span><?php endif; ?>
                    </div>
                    <h2><?php echo htmlspecialchars($item['title']); ?></h2>
                    <p><?php echo htmlspecialchars(truncateText($item['excerpt'] ?: strip_tags($item['content']), 200)); ?></p>
                    <a href="news.php?id=<?php echo $item['id']; ?>" class="btn btn-outline-primary btn-sm">Read Full Article <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ($pagination['total_pages'] > 1): ?>
        <div class="pagination">
            <?php if ($pagination['has_prev']): ?>
            <a href="?page=<?php echo $current_page-1; ?>" class="page-link"><i class="fas fa-chevron-left"></i> Prev</a>
            <?php else: ?>
            <span class="page-link disabled"><i class="fas fa-chevron-left"></i> Prev</span>
            <?php endif; ?>
            <?php for ($p=max(1,$current_page-2);$p<=min($pagination['total_pages'],$current_page+2);$p++): ?>
            <a href="?page=<?php echo $p; ?>" class="page-link <?php echo $p===$current_page?'active':''; ?>"><?php echo $p; ?></a>
            <?php endfor; ?>
            <?php if ($pagination['has_next']): ?>
            <a href="?page=<?php echo $current_page+1; ?>" class="page-link">Next <i class="fas fa-chevron-right"></i></a>
            <?php else: ?>
            <span class="page-link disabled">Next <i class="fas fa-chevron-right"></i></span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<footer class="main-footer">
    <div class="footer-top"><div class="container"><div class="footer-grid">
        <div class="footer-brand"><div class="footer-logo"><i class="fas fa-graduation-cap"></i><span>RETI</span></div><p><?php echo htmlspecialchars($site_title); ?><br>BUILDING SKILLS THAT OPEN DOORS.</p><div class="social-links"><a href="#"><i class="fab fa-facebook-f"></i></a><a href="#"><i class="fab fa-twitter"></i></a><a href="#"><i class="fab fa-instagram"></i></a></div></div>
        <div class="footer-col"><h4>Quick Links</h4><ul><li><a href="index.php"><i class="fas fa-chevron-right"></i> Home</a></li><li><a href="about.php"><i class="fas fa-chevron-right"></i> About</a></li><li><a href="courses.php"><i class="fas fa-chevron-right"></i> Courses</a></li><li><a href="news.php"><i class="fas fa-chevron-right"></i> News</a></li><li><a href="contact.php"><i class="fas fa-chevron-right"></i> Contact</a></li></ul></div>
        <div class="footer-col"><h4>Programmes</h4><ul><li><a href="courses.php#level3"><i class="fas fa-chevron-right"></i> Level III</a></li><li><a href="courses.php#skills"><i class="fas fa-chevron-right"></i> Skills Award</a></li><li><a href="courses.php#short"><i class="fas fa-chevron-right"></i> Short Courses</a></li></ul></div>
        <div class="footer-col"><h4>Contact</h4><div class="footer-contact"><div class="contact-line"><i class="fas fa-phone"></i><a href="tel:+260211296071"><?php echo htmlspecialchars(getSiteSetting('contact_phone') ?: '0211-296071'); ?></a></div><div class="contact-line"><i class="fas fa-envelope"></i><a href="mailto:academic@reti.edu.zm"><?php echo htmlspecialchars(getSiteSetting('contact_email') ?: 'academic@reti.edu.zm'); ?></a></div></div></div>
    </div></div></div>
    <div class="footer-bottom"><div class="container"><p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($site_title); ?>. All rights reserved.</p><p><a href="admin/index.php">Admin</a></p></div></div>
</footer>
<button id="backToTop"><i class="fas fa-chevron-up"></i></button>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
AOS.init({duration:800,once:true,offset:80});
const menuToggle=document.getElementById('menuToggle'),navLinks=document.getElementById('navLinks');
menuToggle.addEventListener('click',()=>{menuToggle.classList.toggle('active');navLinks.classList.toggle('open');});
const btt=document.getElementById('backToTop');
window.addEventListener('scroll',()=>btt.classList.toggle('visible',window.scrollY>400));
btt.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));
</script>
</body>
</html>
