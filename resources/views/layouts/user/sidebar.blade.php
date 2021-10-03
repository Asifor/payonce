<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
        <div class="mdk-drawer__content">
            <div class="sidebar sidebar-light sidebar-left sidebar-p-t" data-perfect-scrollbar>
                <div class="sidebar-heading">Menu</div>
                <ul class="sidebar-menu">
                    <li class="sidebar-menu-item active open">
                        <a class="sidebar-menu-button"  href="/dashboard">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dashboard</i>
                            <span class="sidebar-menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item active open">
                        <a class="sidebar-menu-button" href="{{ route('payonce.method.bank') }}">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance</i>
                            <span class="sidebar-menu-text">Bank Transfer</span>
                        </a>
                    </li>

                    <li class="sidebar-menu-item active open">
                        <a class="sidebar-menu-button" href="javascript:void(0);" data-toggle="modal" data-target="#walletPayment">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                            <span class="sidebar-menu-text">Wallet Transfer</span>
                        </a>
                    </li>


                    @if(!empty($app_usage) && $app_usage == 'Pay Salaries')

                    <li class="sidebar-menu-item active open">
                        <a class="sidebar-menu-button"  href="{{ route('user.employees') }}">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">group</i>
                            <span class="sidebar-menu-text">Employee(s)</span>
                        </a>
                    </li>

                    @elseif(!empty($app_usage) && $app_usage == 'Pay Team Members')

                    <li class="sidebar-menu-item active open">
                        <a class="sidebar-menu-button"  href="{{ route('user.employees') }}">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">group</i>
                            <span class="sidebar-menu-text">My Team Mates</span>
                        </a>
                    </li>

                    @endif

                    <li class="sidebar-menu-item active">

                        <a class="sidebar-menu-button" data-toggle="collapse" href="#apps_menu">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">send</i>
                            <span class="sidebar-menu-text">Transfer Histories</span>
                            <span class="ml-auto sidebar-menu-toggle-icon"></span>
                        </a>
                        <ul class="sidebar-submenu collapse" id="apps_menu">

                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('user.wallet.transfer.transactions') }}">
                                    <span class="sidebar-menu-text">Wallet Transfer</span>
                                </a>
                            </li>

                            <li class="sidebar-menu-item">
                                <a class="sidebar-menu-button" href="{{ route('user.transactions') }}">
                                    <span class="sidebar-menu-text">Bank Transfer</span>
                                </a>
                            </li>
                            
                        </ul>

                    </li>

                    <li class="sidebar-menu-item active">
                        <a class="sidebar-menu-button" href="{{ route('user.wallet') }}">
                            <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">account_balance_wallet</i>
                            <span class="sidebar-menu-text">My Wallet</span>
                        </a>
                    </li>

                </ul>


                <div class="sidebar-heading">Account Management</div>

                

                <div class="d-flex align-items-center sidebar-p-a border-bottom sidebar-account">
                    <a href="{{ route('user.profile') }}" class="flex d-flex align-items-center text-underline-0 text-body">
                        <span class="avatar avatar-sm mr-2">

                        @if(empty(auth()->user()->profile_pic))
                            <img src="{{asset('assets/images/avatar/placeholder.jpg')}}" alt="avatar" class="avatar-img rounded-circle">
                        @else
                            <img src="{{ Auth::user()->profile_pic }}" class="w-100 img-thumbnail" id="img-placeholder">
                        @endif

                        </span>
                        <span class="flex d-flex flex-column">
                            <strong>{{ ucwords(auth()->user()->full_name) }}</strong>
                        </span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- // END drawer-layout -->

</div>
<!-- // END header-layout__content -->

</div>
<!-- // END header-layout -->