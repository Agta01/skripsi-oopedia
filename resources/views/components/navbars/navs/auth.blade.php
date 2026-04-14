@props(['titlePage'])

<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
    navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $titlePage }}</li>
            </ol>
            <h6 class="font-weight-bolder mb-0">{{ $titlePage }}</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item d-flex align-items-center">
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="nav-logout-btn">
                            <i class="material-icons" style="font-size: 16px;">logout</i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-reset-btn" onclick="resetAllTutorials()">
                        <i class="fa fa-redo" style="font-size: 13px;"></i>
                        <span class="d-sm-inline d-none">Reset Tutorial</span>
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar-main {
    background: rgba(255, 255, 255, 0.85) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border-radius: 16px !important;
    margin: 16px !important;
    padding: 8px 16px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08) !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
}

.navbar-main:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
    transform: translateY(-1px);
}

.navbar-main .breadcrumb-item a {
    transition: all 0.3s ease;
    position: relative;
}

.navbar-main .breadcrumb-item a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    transition: width 0.3s ease;
}

.navbar-main .breadcrumb-item a:hover::after {
    width: 100%;
}

.navbar-main .breadcrumb-item.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 600;
}

.navbar-main .font-weight-bolder {
    background: linear-gradient(135deg, #1a1d29, #2d3748);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    transition: all 0.3s ease;
}

.navbar-main:hover .font-weight-bolder {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.nav-logout-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    background: rgba(239, 68, 68, 0.08);
    border: 1px solid rgba(239, 68, 68, 0.15);
    color: #dc2626 !important;
    font-weight: 600;
    font-size: 13px;
    line-height: 1;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    cursor: pointer;
}

.nav-logout-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: 0.5s;
}

.nav-logout-btn:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);
}

.nav-logout-btn:hover::before {
    left: 100%;
}

.nav-logout-btn .material-icons {
    font-size: 18px;
    transition: transform 0.3s ease;
}

.nav-logout-btn:hover .material-icons {
    transform: translateX(3px);
}

.nav-reset-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 10px;
    background: rgba(79, 172, 254, 0.08);
    border: 1px solid rgba(79, 172, 254, 0.15);
    color: #0284c7 !important;
    font-weight: 600;
    font-size: 13px;
    line-height: 1;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.nav-reset-btn:hover {
    background: rgba(79, 172, 254, 0.15);
    color: #0ea5e9;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(79, 172, 254, 0.25);
}

.nav-reset-btn i {
    transition: transform 0.3s ease;
}

.nav-reset-btn:hover i {
    transform: rotate(180deg);
}

.sidenav-toggler-inner {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    padding: 0;
    transition: all 0.3s ease;
}

.sidenav-toggler-line {
    display: block;
    width: 22px;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
    transition: all 0.3s ease;
}

#iconNavbarSidenav:hover .sidenav-toggler-line:first-child {
    transform: translateY(-5px);
}

#iconNavbarSidenav:hover .sidenav-toggler-line:last-child {
    transform: translateY(5px);
}

#iconNavbarSidenav:hover .sidenav-toggler-line:nth-child(2) {
    opacity: 0;
    transform: scale(0);
}

@media (max-width: 768px) {
    .navbar-main {
        margin: 8px !important;
        padding: 8px 12px !important;
    }
    
    .nav-logout-btn,
    .nav-reset-btn {
        padding: 8px 12px;
        font-size: 12px;
    }
}

@media (prefers-reduced-motion: reduce) {
    .navbar-main,
    .nav-logout-btn,
    .nav-reset-btn,
    .sidenav-toggler-line {
        transition: none !important;
    }
}
</style>

<script>
function resetAllTutorials() {
    for (let key in localStorage) {
        if (key.includes('tutorial_complete') || key === 'skip_admin_tour') {
            localStorage.removeItem(key);
        }
    }
    
    Swal.fire({
        title: 'Tutorial Direset',
        text: 'Tutorial akan dimulai ulang',
        icon: 'success',
        confirmButtonText: 'OK'
    }).then(() => {
        const currentPage = '{{ request()->route() ? request()->route()->getName() : "" }}';
        startAdminTutorial();
    });
}
</script>
