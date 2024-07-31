<style>
    /* Ajoutez ce CSS à votre fichier de styles */
.sidebar-hidden {
    display: none;
}

.sidebar-visible {
    display: block;
}

</style>


<aside 
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href=" {{ route('dashboard') }} ">
                <img src="{{ asset('assets') }}/img/ipf.jpg" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-2 font-weight-bold text-white">Institut Pédagogique<br> de Formation (IPF)</span>
            </a>
    
            
    </div>
    
    <hr class="horizontal light mt-0 mb-2">
    <!-- <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main"> -->
        <ul class="navbar-nav nav  nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            
            <li class="nav-item mt-3">
                <!-- <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Pages</h6> -->
            </li>

            
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'dashboard' ? ' active bg-gradient-info' : '' }} "
                    href="{{ route('dashboard') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">dashboard</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Tableau de bord</b></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'etudiant-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('etudiant-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_list_bulleted</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Etudiants</b></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'prof-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('prof-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_list_bulleted</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Professeurs</b> </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'formations-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('formations-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_list_bulleted</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Programmes</b> </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'sessions-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('sessions-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_list_bulleted</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Formations</b> </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'paiement-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('paiement-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">payment</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Paiements Etudiants</b> </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'paiementprof-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('paiementprof-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">payment</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Paiements Professeurs</b> </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'user-profile' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('user-profile') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">person</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Profile</b> </span>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link text-white" href="#">
                
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">logout</i>
                    </div>
                    <b><livewire:auth.logout/></b> 
                    
                </a>
            </li> -->
            <!-- <li class="nav-item">
                <a class="nav-link text-white {{ Route::currentRouteName() == 'contenusformation-management' ? ' active bg-gradient-info' : '' }}  "
                    href="{{ route('contenusformation-management') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">format_list_bulleted</i>
                    </div>
                    <span class="nav-link-text ms-1">Contenus de programme</span>
                </a>
            </li> -->

            
            <!-- <li class="nav-item">
                <a class="nav-link text-white " href="{{ route('static-sign-in') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">login</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>Se connecter </b></span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white " href="{{ route('static-sign-up') }}">
                    <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="material-icons opacity-10">assignment</i>
                    </div>
                    <span class="nav-link-text ms-1"><b>S'inscrire</b></span>
                </a>
            </li>  -->


            
        </ul>
    
</aside>

