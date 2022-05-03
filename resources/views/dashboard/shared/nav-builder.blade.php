
    
    <div class="c-sidebar-brand">
        <img class="c-sidebar-brand-full" src="{{ asset('storage/general_icons/Vttor.png') }}" width="118"  alt="Logo">
        <img class="c-sidebar-brand-minimized" src="{{ asset('storage/general_icons/Vttor.png')}}" width="118" alt="Logo">
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/home.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Inicio
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('allSubjects')}}">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/bubble.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Areas e Assuntos
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('allQuestions')}}">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/bubbles.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Questões
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('allImagesVideos')}}">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/newspaper.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Classificações
            </a>
        </li>

        <li class="c-sidebar-nav-item c-sidebar-nav-dropdown c-sidebar-nav-dropdown-">
            <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/users.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span> Usuarios
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{route('allUsers')}}">
                        <span class="c-sidebar-nav-icon"></span> Usuarios Painel
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href="{{route('allUsersMobile')}}">
                        <span class="c-sidebar-nav-icon"></span> Usuários Aplicativo
                    </a>
                </li>
            </ul>
        </li>

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('myProfile')}}">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/user.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Meu perfil
            </a>
        </li>
        <!--
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('allSupports')}}">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/notification.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Suporte
            </a>
        </li>
        -->

        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link" href="{{route('logoutPainel')}}">
                <i class="c-sidebar-nav-icon h-100">
                    <img width="16" src="{{asset('storage/general_icons/exit.png')}}">
                </i>
                <span class="c-sidebar-nav-icon"></span>Sair
            </a>
        </li>
    </ul>
</div>

