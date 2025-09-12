 function openModal(mode){
    const modal = document.getElementById('modal');
    const title = document.getElementById('modal-title');
    const nameField = document.getElementById('modal-name');
    const btn = document.getElementById('modal-btn');
    modal.style.display = 'flex';

    if(mode === 'login'){
      title.innerText = 'Đăng nhập';
      nameField.style.display = 'none';
      btn.innerText = 'Đăng nhập';
    } else {
      title.innerText = 'Đăng ký';
      nameField.style.display = 'block';
      btn.innerText = 'Đăng ký';
    }
  }
  function closeModal(){
    document.getElementById('modal').style.display = 'none';
  }

   async function loadComponent(id, file) {
      const response = await fetch(file);
      const html = await response.text();
      document.getElementById(id).innerHTML = html;
    }

    loadComponent("header", "header.html");
    loadComponent("content", "content.html");
    loadComponent("footer", "footer.html");