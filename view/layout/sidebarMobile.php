<div class="collapse navbar-collapse m-menu-i" id="navbarNav5">
    <style>
        @media (max-width: 991.98px) {
            .m-menu-i.collapse:not(.show) {
                display: none;
            }

            .m-menu-i.collapsing,
            .m-menu-i.collapse.show {
                position: fixed;
                top: 5%;

                left: 0;
                width: 100vw;

                z-index: 9997;
                /* Dưới modal nhưng trên mọi thứ khác */
                background: #fff;
                padding: 20px 20px 35px 20px;
                max-height: 100vh;
                overflow-y: auto;
                overscroll-behavior: contain;
                -webkit-overflow-scrolling: touch;
            }

            .m-menu-i .line {
                margin: 10px 0;
            }

            .m-menu-i .top-item li {
                margin-bottom: 8px;
            }
        }

        /* Điều chỉnh cho điện thoại có chiều cao thấp */
        @media (max-width: 991.98px) and (max-height: 720px) {

            .m-menu-i.collapsing,
            .m-menu-i.collapse.show {
                top: 10%;
                height: 90vh;
            }
        }
    </style>

    <?php render_sidebar_content('mobile', $topTopics, $moreTopics); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var nav = document.getElementById('navbarNav5');
            if (!nav) return;
            nav.addEventListener('shown.bs.collapse', function() {
                document.body.style.overflow = 'hidden';
            });
            nav.addEventListener('hidden.bs.collapse', function() {
                document.body.style.overflow = '';
            });
        });
    </script>
</div>