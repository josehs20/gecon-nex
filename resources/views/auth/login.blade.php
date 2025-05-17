<!DOCTYPE html>
<html lang="en">

<head>
    <title>GECON</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('styleLogin/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('styleLogin/fonts/iconic/css/material-design-iconic-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('styleLogin/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('siedBar/css/bootstrap.min.css') }}">
    <script src="{{ asset('siedBar/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('siedBar/js/bootstrap.min.js') }}"></script>

</head>

<body>
    @if (!Agent::isMobile())
        <style>
            html,
            body {
                height: 100%;
                /* Garantir que a altura ocupe 100% da tela */
                margin: 0;
                /* Remover qualquer margem da página */
                padding: 0;
                /* Remover qualquer padding da página */
                overflow: hidden;
                /* Desabilitar a rolagem */
            }

            /* Ajustar o estilo da div do globo */
            #globo-container {
                height: 100vh;
                /* Garantir que a div ocupe toda a altura da tela */
                padding: 0;

            }
        </style>
    @else
        <style>
            html,
            body {
                height: 100%;
                /* Garantir que a altura ocupe 100% da tela */
                margin: 0;
                /* Remover qualquer margem da página */
                padding: 0;
                /* Remover qualquer padding da página */
                overflow: auto;

                /* Desabilitar a rolagem */
            }

            /* Ajustar o estilo da div do globo */
            #globo-container {
                height: 30vh;
                /* Garantir que a div ocupe toda a altura da tela */
                padding: 0;

            }
        </style>
    @endif
    <div class="row d-flex" style="background: #0A0A1A;">
        <div id="globo-container" class="col-md-6">
            <h1
                style="position: absolute; bottom: 10%; left: 50%; transform: translateX(-50%); color: #ffffff; font-size: 2rem; font-weight: bold; text-align: center;">
                GECONNEX
                <span style="display: block; position: relative; top: 5px;">
                    <span style="position: absolute; left: 0; width: 100%; height: 2px; background: #00ffff;"></span>
                    <span
                        style="position: absolute;top:10px; left: 0; width: 70%; height: 1px; background: #00ffff; bottom: -5px;"></span>
                </span>
            </h1>
            <!-- O canvas será inserido aqui -->
        </div>
        <div class="col-md-6">
            <div class="limiter">
                <div class="container-login100" style="position: relative; background: #0A0A1A;">
                    <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
                        <form method="POST" action="{{ route('login') }}" class="login100-form validate-form">
                            @csrf

                            <span class="login100-form-title p-b-13">
                                Login
                            </span>

                            <div class="wrap-input100 validate-input m-b-23" data-validate = "Digite um usário válido">
                                <span class="label-input100">Nome de usuário</span>
                                <input id="email" type="email" class="input100" name="email"
                                    placeholder="Digite seu e-mail" value="{{ old('email') }}" required
                                    autocomplete="email" autofocus>
                                <span class="focus-input100" data-symbol="&#xf206;"></span>
                                @error('email')
                                    <strong style="color: red;">{{ $message }}</strong>
                                @enderror
                            </div>

                            <div class="wrap-input100 validate-input" data-validate="Password is required">
                                <span class="label-input100">Senha</span>
                                <input id="password" type="password" placeholder="Digite sua senha" class="input100"
                                    name="password" required autocomplete="current-password">
                                <span class="focus-input100" data-symbol="&#xf190;"></span>

                                @error('password')
                                    <strong style="color: red;">{{ $message }}</strong>
                                @enderror
                            </div>

                            <div class="text-right p-t-8 p-b-31">
                                {{-- <a href="{{ route('recuperar.senha') }}" id="recuperar-senha" class="btn btn-link"> --}}
                                <a href="#" id="recuperar-senha" class="btn btn-link">

                                    Esqueceu sua senha?
                                </a>
                            </div>

                            <div class="container-login100-form-btn">
                                <div class="wrap-login100-form-btn">
                                    <div class="login100-form-bgbtn"></div>
                                    <button class="login100-form-btn">
                                        ENTRAR
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="dropDownSelect1"></div>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script> --}}
    <script src="{{ asset('three/three.min.js') }}"></script>

    <script>
        const scene = new THREE.Scene();
        scene.background = new THREE.Color(0x0a0a1a);
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({
            antialias: true
        });
        renderer.setPixelRatio(window.devicePixelRatio);

        const globoContainer = document.getElementById('globo-container');
        globoContainer.appendChild(renderer.domElement);

        function resizeCanvas() {
            const width = globoContainer.clientWidth;
            const height = globoContainer.clientHeight;
            renderer.setSize(width, height);
            camera.aspect = width / height;
            camera.updateProjectionMatrix();
        }
        resizeCanvas();

        const geometry = new THREE.SphereGeometry(3.5, 64, 64);
        const texture = new THREE.TextureLoader().load('img/terra_globo.jpg');
        const material = new THREE.MeshPhongMaterial({
            map: texture,
            shininess: 20,
            specular: 0x00aaaa
        });
        const globe = new THREE.Mesh(geometry, material);
        scene.add(globe);

        const ambientLight = new THREE.AmbientLight(0x404040, 0.5);
        scene.add(ambientLight);
        const pointLight = new THREE.PointLight(0x00ffff, 1, 100);
        pointLight.position.set(15, 15, 15);
        scene.add(pointLight);

        camera.position.z = 10;

        let isDragging = false;
        let previousMousePosition = {
            x: 0,
            y: 0
        };
        let velocity = {
            x: 0,
            y: 0
        };

        // Evento de pressionar o mouse
        document.addEventListener('mousedown', (e) => {
            isDragging = true;
            previousMousePosition = {
                x: e.clientX,
                y: e.clientY
            };
            velocity = {
                x: 0,
                y: 0
            };
        });

        // Evento de movimento do mouse
        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                const deltaMove = {
                    x: e.clientX - previousMousePosition.x,
                    y: e.clientY - previousMousePosition.y
                };
                globe.rotation.y += deltaMove.x * 0.005;
                globe.rotation.x += deltaMove.y * 0.005;
                velocity = {
                    x: deltaMove.x * 0.005,
                    y: deltaMove.y * 0.005
                };
                previousMousePosition = {
                    x: e.clientX,
                    y: e.clientY
                };
            }
        });

        // Evento de soltar o mouse
        document.addEventListener('mouseup', () => {
            isDragging = false;
        });

        // Zoom com rolagem do mouse
        window.addEventListener('wheel', (event) => {
            camera.position.z += event.deltaY * 0.01;
            camera.position.z = Math.max(5, Math.min(15, camera.position.z));
        });

        function animate() {
            requestAnimationFrame(animate);

            // Rotação contínua quando não há interação
            if (!isDragging) {
                globe.rotation.y += 0.005; // Rotação constante no eixo Y
                globe.rotation.x += 0.002; // Adiciona leve rotação no eixo X também
                velocity.x *= 0.95; // Reduz a velocidade gradualmente
                velocity.y *= 0.95;
            }

            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', resizeCanvas);
    </script>

    <script src="{{ asset('styleLogin/vendor/jquery/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/animsition/js/animsition.min.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/bootstrap/js/popper.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/select2/select2.min.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('styleLogin/vendor/countdowntime/countdowntime.js') }}"></script>
    <script src="{{ asset('styleLogin/js/main.js') }}"></script>
</body>

</html>
