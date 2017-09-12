<div class="offcanvasMenu">
    <ul class="menu">
        <li>
            <a href="{{ url('/') }}">
                <i class="fa fa-dashboard"></i>
                <span class="menuText">Command Center</span>
            </a>
        </li>
        <li class="active">
            <a href="">
                <i class="fa fa-file-text-o"></i>
                <span class="menuText">Reporting</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li class="active">
                    <a href="#">Today</a>
                </li>
                <li>
                    <a href="">Create Report</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-pie-chart"></i>
                <span class="menuText">Campaigns</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Manage Campaigns</a>
                </li>
                <li>
                    <a href="">Create Campaigns</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-users"></i>
                <span class="menuText">Publishers</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Manage Publishers</a>
                </li>
                <li>
                    <a href="">Create Publishers</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-dot-circle-o"></i>
                <span class="menuText">Targets</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Manage Targets</a>
                </li>
                <li>
                    <a href="{{ url('/add-target') }}">Add Targets</a>
                </li>
                <li>
                    <a href="">Manage Groups</a>
                </li>
                <li>
                    <a href="">Add Group</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-ship"></i>
                <span class="menuText">Buyers</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Manage Buyers</a>
                </li>
                <li>
                    <a href="">Add Buyers</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-phone"></i>
                <span class="menuText">Numbers</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Manage Numbers</a>
                </li>
                <li>
                    <a href="">Create Numbers</a>
                </li>
                <li>
                    <a href="">Manage Pools</a>
                </li>
                <li>
                    <a href="">Manage Blocked Numbers</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-link"></i>
                <span class="menuText">Url Parameters</span>
            </a>
        </li>
        <li>
            <a href="">
                <i class="fa fa-handshake-o"></i>
                <span class="menuText">Integration</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Pixels</a>
                </li>
                <li>
                    <a href="">Webhooks</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="">
                <i class="fa fa-cog"></i>
                <span class="menuText">Settings</span>
                <i class="fa fa-caret-down"></i>
            </a>
            <ul class="submenu">
                <li>
                    <a href="">Account Settings</a>
                </li>
                <li>
                    <a href="">Manage Addresses</a>
                </li>
                <li>
                    <a href="">Add Addresses</a>
                </li>
                <li>
                    <a href="">Billing Settings</a>
                </li>
                
                <li>
                    <a href="">User</a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ url('/logout') }}">
                <i class="fa fa-sign-out"></i>
                <span class="menuText">Logout</span>
            </a>
        </li>
    </ul>
</div>