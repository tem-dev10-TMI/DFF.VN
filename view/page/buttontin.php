<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Khung chọn viết bài — HTML + CSS + JS</title>
  <style>
    /* Reset cơ bản */
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;font-family:Inter, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;background:#f3f4f6;color:#111}

    /* Khung chính */
    .write-card{
      width:360px;            /* dễ chỉnh */
      margin:28px auto;
      background:#fff;
      border-radius:12px;
      box-shadow:0 6px 18px rgba(16,24,40,0.08);
      padding:14px;
      border:1px solid rgba(16,24,40,0.04);
    }

    .write-title{
      font-size:14px;color:#374151;margin-bottom:8px;font-weight:600;
    }

    .write-list{display:flex;flex-direction:column;gap:8px}

    /* Mỗi hàng (item) */
    .write-item{
      display:flex;align-items:center;gap:12px;
      padding:10px 12px;border-radius:10px;cursor:pointer;
      transition:all .18s ease;
      user-select:none;
    }
    .write-item:focus{outline:none}
    .write-item:hover{background:#f8fafc;transform:translateY(-1px)}

    /* Khi chọn */
    .write-item.active{background:linear-gradient(180deg,#eef2ff,#eef6ff);box-shadow:0 6px 18px rgba(99,102,241,0.08)}

    /* Icon vòng tròn + */
    .icon-circle{
      width:36px;height:36px;border-radius:50%;display:inline-grid;place-items:center;
      background:linear-gradient(180deg,#ffffff,#fbfbfe);
      border:1px solid rgba(16,24,40,0.08);
      box-shadow:0 2px 6px rgba(16,24,40,0.06);
      flex-shrink:0;
      transition:transform .12s ease, box-shadow .12s ease;
    }
    .write-item:hover .icon-circle{transform:scale(1.03)}

    /* Ký hiệu dấu cộng bên trong (SVG) */
    .icon-circle svg{width:18px;height:18px;display:block}

    /* Văn bản */
    .item-text{font-size:15px;color:#111827}
    .item-sub{font-size:12px;color:#6b7280}

    /* Ký hiệu nhỏ (mimic ảnh trong ảnh gốc) */
    .dot{
      width:8px;height:8px;border-radius:50%;background:#111827;margin-left:6px;opacity:0.85;box-shadow:0 1px 2px rgba(0,0,0,0.08);
    }

    /* Responsive */
    @media (max-width:420px){.write-card{width:94%;padding:12px}}

  </style>
</head>
<body>

  <div class="write-card" role="region" aria-label="Khung chọn loại viết bài">
    <div class="write-title">Tạo bài</div>

    <div class="write-list">

      <!-- Item 1 -->
      <button class="write-item" data-type="fast" aria-pressed="false">
        <span class="icon-circle" aria-hidden="true">
          <!-- SVG dấu cộng + -->
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14" stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5 12h14" stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </span>

        <span class="item-text">Viết bài nhanh</span>
        <!-- dot nhỏ giống ảnh tham chiếu -->
        <span class="dot" aria-hidden="true"></span>
      </button>

      <!-- Item 2 -->
      <button class="write-item" data-type="regular" aria-pressed="false">
        <span class="icon-circle" aria-hidden="true">
          <!-- Dùng cùng SVG nhưng mờ hơn để phân biệt -->
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M12 5v14" stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" opacity="0.9"/>
            <path d="M5 12h14" stroke="#111827" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" opacity="0.9"/>
          </svg>
        </span>

        <span class="item-text">Viết bài thường</span>
        <span class="dot" style="opacity:0.5" aria-hidden="true"></span>
      </button>

    </div>
  </div>

  <script>
    // Tương tác nhỏ: chọn 1 trong 2, cập nhật aria và class
    const items = document.querySelectorAll('.write-item');
    items.forEach(item =>{
      item.addEventListener('click', ()=>{
        // bỏ active các item khác
        items.forEach(i=>{
          i.classList.remove('active');
          i.setAttribute('aria-pressed','false');
        })
        // active item bấm
        item.classList.add('active');
        item.setAttribute('aria-pressed','true');

        // Hành động tùy biến: ở đây chỉ log
        const type = item.getAttribute('data-type');
        console.log('Người dùng chọn:', type);
      });

      // Hỗ trợ bàn phím (Enter/Space)
      item.addEventListener('keydown', e=>{
        if(e.key === 'Enter' || e.key === ' '){e.preventDefault(); item.click();}
      })
    })

    // Gợi ý: chọn item đầu tiên làm mặc định
    items[0].classList.add('active');
    items[0].setAttribute('aria-pressed','true');
  </script>

</body>
</html>
