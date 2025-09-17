 document.addEventListener("DOMContentLoaded", function() {
        const allLis = document.querySelectorAll("ul li");
        allLis.forEach(li => {
            const a = li.querySelector("a");
            if (a) {
                a.addEventListener("click", function(e) {
                    allLis.forEach(item => item.classList.remove("active"));
                    li.classList.add("active");
                });
            }
        });
    });
</scr