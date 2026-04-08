<x-layout bodyClass="bg-gray-200">
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
                    <img src="{{ asset('images/logo.png') }}" alt="OOPedia Logo" class="mb-4 img-fluid drop-shadow-lg" style="max-width: 150px;">
                    <h2 class="font-weight-bold text-white mb-3" style="letter-spacing: -0.5px;">Bergabunglah!</h2>
                    <p class="text-white opacity-8 fs-6 px-lg-4" style="line-height: 1.6;">
                        Akses materi pemrograman berorientasi objek terlengkap, latihan coding interaktif, dan jadilah developer handal.
                    </p>
                </div>
            </div>

            <!-- Right Side: Clean Form -->
            <div class="col-lg-7 col-12 d-flex align-items-center justify-content-center position-relative bg-white pt-5 pt-lg-0 overflow-auto" style="height: 100vh;">
                <div class="w-100 px-4 px-md-5 px-lg-6 py-5" style="max-width: 600px;">
                    <div class="text-center mb-5">
                        <h3 class="font-weight-bolder text-dark mb-2" style="font-size: 28px;">Buat Akun Baru</h3>
                        <p class="text-secondary">Lengkapi data diri Anda untuk memulai perjalanan</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="modern-form">
                        @csrf
                        
                        <!-- Input Nama Lengkap -->
                        <div class="mb-4">
                            <label class="form-label ms-1 fw-bold text-dark text-sm">Nama Lengkap</label>
                            <div class="custom-input-group mt-1">
                                <span class="input-icon"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                            </div>
                            @error('name')
                            <p class='text-danger text-xs mt-2 fw-medium mb-0'><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Input Email -->
                        <div class="mb-4">
                            <label class="form-label ms-1 fw-bold text-dark text-sm">Email Institusi/Pribadi</label>
                            <div class="custom-input-group mt-1">
                                <span class="input-icon"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="contoh@kampus.ac.id" required>
                            </div>
                            @error('email')
                            <p class='text-danger text-xs mt-2 fw-medium mb-0'><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Input Passwords -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label ms-1 fw-bold text-dark text-sm">Kata Sandi</label>
                                <div class="custom-input-group mt-1">
                                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" name="password" placeholder="Minimal 8 karakter" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label ms-1 fw-bold text-dark text-sm">Konfirmasi Sandi</label>
                                <div class="custom-input-group mt-1">
                                    <span class="input-icon"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi kata sandi" required>
                                </div>
                            </div>
                        </div>
                        @error('password')
                        <p class='text-danger text-xs mt-n2 fw-medium mb-3'><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                        @enderror
                        
                        <!-- Modern Toggle Card for Lecturer Registration -->
                        <div class="mb-0 mt-2">
                            <div class="form-check p-0">
                                <input class="d-none" type="checkbox" name="register_as_admin" id="register_as_admin" value="1" {{ old('register_as_admin') ? 'checked' : '' }}>
                                <label class="lecturer-card d-flex align-items-center justify-content-between p-3 border rounded-3 cursor-pointer transition-all" for="register_as_admin" style="border-radius: 14px !important;">
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box text-white text-center rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; background: linear-gradient(135deg, #1e3a8a, #3b82f6); box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);">
                                            <i class="fas fa-chalkboard-teacher text-md"></i>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="text-sm font-weight-bold text-dark">Daftar sebagai Dosen</span>
                                            <span class="text-xs text-secondary">Memerlukan persetujuan Admin</span>
                                        </div>
                                    </div>
                                    <!-- Toggle Switch -->
                                    <div class="toggle-switch relative">
                                        <div class="toggle-track bg-gray-200 rounded-pill transition-colors" style="width: 46px; height: 26px;"></div>
                                        <div class="toggle-thumb bg-white rounded-circle shadow-sm position-absolute transition-transform" style="width: 20px; height: 20px; top: 3px; left: 3px;"></div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-login w-100 mb-3">DAFTAR SEKARANG</button>
                        </div>
                        
                        <div class="row align-items-center justify-content-center mt-3 pt-2 border-top border-light">
                            <div class="col-12 text-center mt-3">
                                <p class="text-sm text-secondary mb-0">
                                    Sudah punya akun? 
                                    <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a>
                                </p>
                            </div>
                        </div>
                    </form>
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

        /* Lecturer Toggle Card Styling */
        .lecturer-card {
            border: 2px solid #e2e8f0 !important;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .lecturer-card:hover {
            border-color: #cbd5e1 !important;
            background: #f1f5f9;
        }

        /* Active State when Checkbox is Checked */
        input#register_as_admin:checked + label.lecturer-card {
            border-color: #3b82f6 !important;
            background: #eff6ff !important;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.15) !important;
        }

        /* Toggle Active State */
        input#register_as_admin:checked + label .toggle-track {
            background-color: #3b82f6 !important;
        }

        input#register_as_admin:checked + label .toggle-thumb {
            transform: translateX(20px) !important;
        }

        input#register_as_admin:checked + label .icon-box {
            transform: scale(1.05);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/blue-theme.css') }}">
</x-layout>