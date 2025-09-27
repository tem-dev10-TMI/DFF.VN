<style>
    body {
        font-family: 'Quicksand', sans-serif;
        overflow: hidden;
    }

    .scene {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
        background: #000;
        /* Fallback */
    }

    .parallax-layer {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    /* --- HIỆU ỨNG NGÀY & ĐÊM --- */
    #sky-gradient {
        animation: day-night-cycle 60s infinite linear;
    }

    @keyframes day-night-cycle {
        0% {
            background: linear-gradient(to bottom, #2c3e50, #4ca1af);
        }

        /* Đêm -> Sáng */
        25% {
            background: linear-gradient(to bottom, #87CEEB, #B0E0E6);
        }

        /* Sáng */
        50% {
            background: linear-gradient(to bottom, #ff7e5f, #feb47b);
        }

        /* Chiều */
        75% {
            background: linear-gradient(to bottom, #485563, #29323c);
        }

        /* Tối */
        100% {
            background: linear-gradient(to bottom, #2c3e50, #4ca1af);
        }

        /* Đêm */
    }

    #sun-moon-container {
        width: 100%;
        height: 200%;
        animation: sun-moon-rotation 60s infinite linear;
        transform-origin: center;
    }

    #sun {
        background: radial-gradient(circle, rgba(255, 253, 208, 1) 0%, rgba(255, 253, 208, 0) 70%);
    }

    #moon {
        background: radial-gradient(circle, rgba(240, 240, 255, 1) 0%, rgba(240, 240, 255, 0) 70%);
        transform: rotate(180deg);
    }

    @keyframes sun-moon-rotation {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    #stars {
        background-image: radial-gradient(1px 1px at 20px 30px, white, transparent),
            radial-gradient(1px 1px at 40% 60%, white, transparent),
            radial-gradient(1px 1px at 80% 10%, white, transparent),
            radial-gradient(2px 2px at 90% 70%, white, transparent),
            radial-gradient(2px 2px at 30% 90%, white, transparent),
            radial-gradient(1px 1px at 50% 50%, white, transparent);
        animation: stars-fade 60s infinite linear;
    }

    @keyframes stars-fade {
        0% {
            opacity: 1;
        }

        25% {
            opacity: 0;
        }

        50% {
            opacity: 0;
        }

        75% {
            opacity: 1;
        }

        100% {
            opacity: 1;
        }
    }

    /* --- [MỚI] HIỆU ỨNG BẦY ĐOM ĐÓM --- */
    #fireflies-container {
        position: absolute;
        inset: 0;
        z-index: 20;
    }

    .firefly {
        position: absolute;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 0 5px #fff, 0 0 10px #fff, 0 0 15px #f0ffc9;
    }

    @keyframes pulse-glow-firefly {

        0%,
        100% {
            transform: scale(1);
            opacity: 0.8;
        }

        50% {
            transform: scale(1.5);
            opacity: 1;
        }
    }

    /* Các đường bay ngẫu nhiên */
    @keyframes fly-path-1 {
        0% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(40vw, -30vh);
        }

        100% {
            transform: translate(-10vw, 10vh);
        }
    }

    @keyframes fly-path-2 {
        0% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(-30vw, 20vh);
        }

        100% {
            transform: translate(20vw, -40vh);
        }
    }

    @keyframes fly-path-3 {
        0% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(10vw, 50vh);
        }

        100% {
            transform: translate(30vw, -10vh);
        }
    }

    @keyframes fly-path-4 {
        0% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(-50vw, -10vh);
        }

        100% {
            transform: translate(10vw, 30vh);
        }
    }
</style>
<div class="scene" id="scene">
    <!-- Background Động -->
    <div class="parallax-layer" id="sky-gradient" data-depth="0"></div>
    <div class="parallax-layer" id="stars" data-depth="0.1"></div>
    <div class="parallax-layer" data-depth="0.2">
        <div id="sun-moon-container">
            <div id="sun" class="absolute top-[10%] left-[15%] w-48 h-48 rounded-full"></div>
            <div id="moon" class="absolute bottom-[10%] right-[15%] w-32 h-32 rounded-full"></div>
        </div>
    </div>
    <div class="parallax-layer" data-depth="0.3" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 800 400\'%3E%3Cpath d=\'M0 400 L0 250 Q 100 200, 200 260 T 400 250 T 600 270 T 800 240 L800 400 Z\' fill=\'%232c3e50\' opacity=\'0.4\'/%3E%3C/svg%3E'); background-size: cover; background-position: bottom center;"></div>
    <div class="parallax-layer" data-depth="0.5" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 800 400\'%3E%3Cpath d=\'M0 400 L0 300 Q 150 250, 300 310 T 600 290 T 800 320 L800 400 Z\' fill=\'%232c3e50\' opacity=\'0.6\'/%3E%3C/svg%3E'); background-size: cover; background-position: bottom center;"></div>
    <div class="parallax-layer" data-depth="0.8" style="background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 800 400\'%3E%3Cpath d=\'M-50 400 L-20 350 L-10 400 Z M30 400 L60 330 L90 400 Z M150 400 L180 340 L210 400 Z M250 400 L290 320 L330 400 Z M400 400 L420 350 L440 400 Z M500 400 L540 330 L580 400 Z M650 400 L670 340 L690 400 Z M750 400 L790 320 L830 400 Z\' fill=\'%231a252f\'/%3E%3C/svg%3E'); background-size: cover; background-position: bottom center;"></div>

    <div class="absolute inset-0 flex flex-col items-center justify-center text-center z-10 text-white p-4">
        <div class="text-container" style="text-shadow: 2px 2px 8px rgba(0,0,0,0.6);">
            <h1 class="text-7xl md:text-9xl font-bold">404</h1>
            <p class="text-2xl md:text-4xl mt-2">Ối, có vẻ bạn đã lạc lối!</p>
            <p class="text-lg md:text-xl mt-4 max-w-md">Trang bạn đang tìm không tồn tại, nhưng đừng lo, những chú đom đóm sẽ dẫn đường.</p>
            <a href="#" class="mt-8 inline-block bg-white text-blue-800 font-bold py-3 px-8 rounded-full text-lg shadow-xl transform hover:scale-105 transition-transform duration-300 hover:shadow-2xl">
                Trở Về Nhà
            </a>
        </div>
    </div>

    <!-- [MỚI] Bầy đom đóm -->
    <div id="fireflies-container"></div>
</div>

<script>
    // Parallax effect script
    document.addEventListener('mousemove', function(e) {
        const scene = document.getElementById('scene');
        const layers = scene.querySelectorAll('.parallax-layer');
        if (!scene) return;

        const {
            clientWidth: width,
            clientHeight: height
        } = scene;
        const x = e.clientX - width / 2;
        const y = e.clientY - height / 2;

        layers.forEach(layer => {
            const depth = parseFloat(layer.getAttribute('data-depth'));
            if (isNaN(depth)) return;
            const moveX = x * depth / 15;
            const moveY = y * depth / 15;
            layer.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });
    });

    // Fireflies generation script
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('fireflies-container');
        if (!container) return;

        const numFireflies = 30; // Số lượng đom đóm
        const pathCount = 4; // Số lượng đường bay có sẵn

        for (let i = 0; i < numFireflies; i++) {
            const firefly = document.createElement('div');
            firefly.className = 'firefly';

            const size = Math.random() * 4 + 1; // Kích thước từ 1px đến 5px
            firefly.style.width = `${size}px`;
            firefly.style.height = `${size}px`;

            firefly.style.left = `${Math.random() * 100}%`;
            firefly.style.top = `${Math.random() * 100}%`;

            const flyDuration = Math.random() * 10 + 8; // Thời gian bay từ 8s đến 18s
            const pulseDuration = Math.random() * 2 + 1; // Thời gian nháy từ 1s đến 3s

            const flyDelay = Math.random() * 10;
            const pulseDelay = Math.random() * 3;

            const pathName = `fly-path-${Math.ceil(Math.random() * pathCount)}`;

            firefly.style.animation = `
                    ${pathName} ${flyDuration}s alternate infinite ease-in-out,
                    pulse-glow-firefly ${pulseDuration}s infinite ease-in-out
                `;
            firefly.style.animationDelay = `${flyDelay}s, ${pulseDelay}s`;

            container.appendChild(firefly);
        }
    });
</script>