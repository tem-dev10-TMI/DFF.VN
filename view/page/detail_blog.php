<?php
// Giả sử $article được truyền từ Controller sang View
// $article = [
//   'id' => 1,
//   'title' => 'CEO WiGroup...',
//   'summary' => 'Khoảng cách lãi suất...',
//   'content' => 'Hiện tỷ giá đã tăng...',
//   'main_image_url' => 'https://img.freepik.com/free-photo/...jpg',
//   'author_name' => 'Thành Công',
//   'created_at' => '2025-09-12 09:01:00',
//   'tags' => ['Trần Ngọc Bầu', 'Tỷ giá', 'USD']
// ];
?>

<script src="https://cdn.tailwindcss.com"></script>
<div class="max-w-lg mx-auto bg-white rounded-2xl shadow-md p-4">

  <!-- Header -->
  <div class="flex items-center mb-3">
    <img src="https://i.pravatar.cc/100?u=<?= urlencode($article['author_name']) ?>" 
         class="w-12 h-12 rounded-full mr-3" alt="Avatar">
    <div>
      <p class="font-semibold"><?= htmlspecialchars($article['author_name']) ?></p>
      <p class="text-sm text-gray-500">
        <?= date("l, d/m/Y, H:i (T)", strtotime($article['created_at'])) ?>
      </p>
    </div>
  </div>

  <!-- Title -->
  <h2 class="text-lg font-bold mb-2">
    <?= htmlspecialchars($article['title']) ?>
  </h2>

  <!-- Content -->
  <p class="text-gray-700 mb-3">
    <?= nl2br(htmlspecialchars($article['summary'])) ?>
  </p>

  <!-- Post Image -->
  <img src="<?= $article['main_image_url'] ?>" 
       alt="<?= htmlspecialchars($article['title']) ?>" 
       class="rounded-xl w-full mb-3">

  <!-- Extra Content -->
  <p class="text-gray-700 mb-3">
    <?= nl2br(htmlspecialchars($article['content'])) ?>
  </p>

  <!-- Related (ví dụ tạm tĩnh, sau này query từ DB) -->
  <div class="bg-gray-100 rounded-lg p-3 mb-3">
    <h4 class="font-semibold mb-2">📌 Nội dung liên quan</h4>
    <ul class="list-disc list-inside text-gray-700 space-y-1 text-sm">
      <li>Đồng USD trên thế giới giảm, song tỷ giá trong nước chưa hạ nhiệt</li>
      <li>Tỷ giá thêm nóng</li>
      <li>Chuyên gia: Áp lực tỷ giá có thể kéo dài đến năm 2026</li>
    </ul>
  </div>
<?php /*
<!-- Tags -->
  <div class="flex flex-wrap gap-2 mb-3">
    <?php foreach ($article['tags'] as $tag): ?>
      <span class="bg-gray-200 px-3 py-1 rounded-full text-sm">
        <?= htmlspecialchars($tag) ?>
      </span>
    <?php endforeach; ?>
  </div>
*/ ?>
  <!-- Actions -->
  <div class="flex justify-around border-t border-b py-2 text-gray-600 text-sm mb-3">
    <!-- Like -->
    <button class="flex items-center gap-2 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 
                 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 
                 2.09C13.09 3.81 14.76 3 16.5 
                 3 19.58 3 22 5.42 22 
                 8.5c0 3.78-3.4 6.86-8.55 
                 11.54L12 21.35z"/>
      </svg>
      Thích
    </button>
    <!-- Comment -->
    <button class="flex items-center gap-2 hover:text-blue-600">
<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M20 2H4C2.9 2 2 2.9 2 
                 4v14c0 1.1 0.9 2 2 
                 2h14l4 4V4c0-1.1-0.9-2-2-2z"/>
      </svg>
      Bình luận
    </button>
    <!-- Share -->
    <button class="flex items-center gap-2 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 
                 12.7a2.5 2.5 0 0 0 0-1.39l7.02-4.11A2.5 
                 2.5 0 1 0 14.5 5a2.5 2.5 0 0 0 
                 0 5c.76 0 1.44-.3 1.96-.77l7.02 
                 4.11a2.5 2.5 0 1 0 0 1.39l-7.02 
                 4.11A2.5 2.5 0 1 0 18 16.08z"/>
      </svg>
      Chia sẻ
    </button>
    <!-- Report -->
    <button class="flex items-center gap-2 hover:text-red-500">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 2C6.48 2 2 6.48 2 
                 12s4.48 10 10 10 10-4.48 
                 10-10S17.52 2 12 2zm1 
                 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
      </svg>
      Báo cáo
    </button>
  </div>

  <!-- Comment input -->
  <div class="flex items-center gap-2">
    <img src="https://i.pravatar.cc/100?img=12" class="w-9 h-9 rounded-full" alt="User">
    <input type="text" placeholder="Bạn nghĩ gì về nội dung này?" 
           class="flex-1 border rounded-full px-4 py-2 text-sm focus:outline-blue-400">
  </div>

</div>