<x-layout bodyClass="bg-gray-200">

    <!-- Navbar removed as per user request -->
        <main class="main-content mt-0 p-0">
        <div class="row g-0 vh-100 bg-white">
            
            <!-- Left Side: Modern Branding -->
            <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-center align-items-center position-relative overflow-hidden" 
                 style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                <!-- Decorative Elements -->
                <div class="position-absolute w-100 h-100" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 24px 24px; opacity: 0.6;"></div>
                <div class="position-absolute rounded-circle" style="width: 300px; height: 300px; background: rgba(255,255,255,0.05); top: -50px; left: -100px; filter: blur(2px);"></div>
                <div class="position-absolute rounded-circle" style="width: 200px; height: 200px; background: rgba(255,255,255,0.08); bottom: -30px; right: -50px; filter: blur(1px);"></div>
                
                <!-- Content -->
                <div class="position-relative z-index-2 text-center px-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo OOPedia" class="mb-4 img-fluid drop-shadow-lg" style="max-height: 140px;">
                    <h2 class="text-white font-weight-bolder mb-3" style="letter-spacing: -0.5px;">Selamat Datang Kembali!</h2>
                    <p class="text-white opacity-8 fs-6 px-lg-4" style="line-height: 1.6;">
                        Lanjutkan progres pembelajaran Pemrograman Berorientasi Objek Anda dan raih pemahaman mendalam secara praktis dan interaktif.
                    </p>
                </div>
            </div>

            <!-- Right Side: Clean Form Form -->
            <div class="col-lg-7 col-12 d-flex align-items-center justify-content-center position-relative bg-white pt-5 pt-lg-0">
                <div class="w-100 px-4 px-md-5 px-lg-6" style="max-width: 550px;">
                    <div class="text-center mb-5">
                        <h3 class="font-weight-bolder text-dark mb-2" style="font-size: 28px;">Masuk ke Akun Anda</h3>
                        <p class="text-secondary">Silakan masukkan email dan password untuk melanjutkan</p>
                    </div>

                    <form role="form" method="POST" action="{{ route('login') }}" class="modern-form">
                        @csrf
                        
                        @if (Session::has('status'))
                        <div class="alert alert-success alert-dismissible text-white border-0 shadow-sm rounded-3 mb-4" role="alert" style="background: #10b981;">
                            <span class="text-sm fw-medium"><i class="fas fa-check-circle me-2"></i>{{ Session::get('status') }}</span>
                            <button type="button" class="btn-close text-white opacity-10" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        @if ($errors->any() || Session::has('error'))
                        <div class="alert alert-danger alert-dismissible text-white border-0 shadow-sm rounded-3 mb-4" role="alert" style="background: #ef4444;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                                <div>
                                    <span class="text-sm fw-bold d-block">Gagal Masuk!</span>
                                    <span class="text-xs">Email atau kata sandi yang Anda masukkan salah.</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close text-white opacity-50" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <!-- Input Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label ms-1 fw-bold text-dark text-sm">Alamat Email</label>
                            <div class="custom-input-group mt-1 @error('email') border-danger @enderror">
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" id="email" class="form-control" name="email" placeholder="contoh@kampus.ac.id" required autofocus>
                            </div>
                            @error('email')
                            <p class='text-danger text-xs mt-2 fw-medium mb-0'><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label ms-1 fw-bold text-dark text-sm">Kata Sandi</label>
                            <div class="custom-input-group mt-1 @error('password') border-danger @enderror">
                                <span class="input-icon"><i class="fas fa-lock"></i></span>
                                <input type="password" id="password" class="form-control" name="password" placeholder="Masukkan kata sandi Anda" required>
                            </div>
                            @error('password')
                            <p class='text-danger text-xs mt-2 fw-medium mb-0'><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-login w-100 mb-3">MASUK SEKARANG</button>
                        </div>
                        
                        <div class="row align-items-center justify-content-center mt-4 pt-2 border-top border-light">
                            <div class="col-12 text-center mt-3">
                                <p class="text-sm text-secondary mb-2">
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar di sini</a>
                                </p>
                                <p class="text-sm text-secondary mb-0">
                                    Ingin mencoba materi gratis? 
                                    <a href="{{ route('mahasiswa.materials.index') }}" class="text-info fw-bold text-decoration-none">Mode Tamu</a>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Footer for mobile view, hidden on desktop -->
                <div class="d-lg-none position-absolute bottom-0 w-100 text-center pb-3">
                    <x-footers.guest></x-footers.guest>
                </div>
            </div>
            
        </div>
    </main>

    @push('js')
    <script src="{{ asset('assets') }}/js/jquery.min.js"></script>
    <script>
        $(function() {
            // Animating icons on focus
            $(".custom-input-group input").on("focus", function() {
                $(this).parent().addClass("is-focused");
            }).on("blur", function() {
                $(this).parent().removeClass("is-focused");
            });
        });
    </script>
    @endpush

    <style>
        /* Modern Clean Form Setup */
        .custom-input-group {
            position: relative;
            background: #f8fafc;
            border-radius: 14px;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 4px 16px;
        }
        
        .custom-input-group.is-focused {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        
        .custom-input-group .input-icon {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-right: 12px;
            transition: color 0.3s ease;
        }
        
        .custom-input-group.is-focused .input-icon {
            color: #3b82f6;
        }

        .custom-input-group .form-control {
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
            padding: 12px 0 12px 0 !important;
            color: #1e293b !important;
            font-weight: 500;
            font-size: 15px;
            width: 100%;
        }
        
        .custom-input-group .form-control::placeholder {
            color: #cbd5e1;
            font-weight: 400;
        }

        .custom-input-group.border-danger {
            border-color: #ef4444 !important;
            background: #fef2f2 !important;
        }
        
        .custom-input-group.border-danger .input-icon {
            color: #ef4444 !important;
        }
        
        .custom-input-group.border-danger.is-focused {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15) !important;
        }
        
        /* Modern Bold Button */
        .btn-login {
            background: linear-gradient(to right, #2563eb, #3b82f6) !important;
            color: #ffffff !important;
            border: none;
            border-radius: 14px;
            padding: 14px 24px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.5px;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px -5px rgba(37, 99, 235, 0.5) !important;
            background: linear-gradient(to right, #1d4ed8, #2563eb) !important;
        }
        
        .btn-login:active {
            transform: translateY(1px);
        }
        
        .drop-shadow-lg {
            filter: drop-shadow(0 10px 15px rgba(0,0,0,0.25));
        }

        /* Prevent scroll if possible */
        body {
            overflow-x: hidden;
        }
    </style>
</x-layout>
