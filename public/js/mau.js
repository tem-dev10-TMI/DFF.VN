const menuItems = document.querySelectorAll(".left-menu ul li.item");
menuItems.forEach(item => {
    item.addEventListener("click", function() {
        menuItems.forEach(i => i.classList.remove("active"));       
        this.classList.add("active");
    });
});