<aside class="w-64 bg-white shadow-lg border-r border-gray-200 flex flex-col">
    @php 
        $caseActive = request()->routeIs('case.index') || request()->routeIs('client.index') || request()->routeIs('partner.index');
        $accountingActive = request()->routeIs('quotation.index') || request()->routeIs('tax-invoice.index') || request()->routeIs('resit.index') || request()->routeIs('voucher.index') || request()->routeIs('bill.index');
        $settingsActive = request()->routeIs('settings.global') || request()->routeIs('settings.role') || request()->routeIs('settings.user') || request()->routeIs('settings.category') || request()->routeIs('settings.log');
    @endphp
    
    <!-- Logo Section -->
    <div class="flex items-center justify-center h-20">
        <div class="flex items-center">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                <span class="material-icons text-white text-sm">gavel</span>
            </div>
            <span class="text-lg font-bold text-gray-800">Naeelah Firm</span>
        </div>
    </div>
    <div class="border-b border-gray-200 mb-2 mt-2"></div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        <!-- Dashboard -->
        <div class="category-header relative">
            <a href="{{ route('overview') }}" class="block relative {{ request()->routeIs('overview') ? 'active' : '' }}">
                <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
                    <div class="flex items-center">
                        <span class="material-icons text-base text-blue-500 mr-3">dashboard</span>
                        <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">Overview</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Calendar -->
        <div class="mt-2"></div>
        <div class="category-header relative">
            <a href="{{ route('calendar') }}" class="block relative {{ request()->routeIs('calendar') ? 'active' : '' }}">
                <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
                    <div class="flex items-center">
                        <span class="material-icons text-base text-green-500 mr-3">calendar_today</span>
                        <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">Calendar</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Case Section -->
        <div class="mt-2"></div>
        <div class="category-header relative {{ $caseActive ? 'active' : '' }}" onclick="toggleSection('case-section')">
            <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
                <div class="flex items-center">
                    <span class="material-icons text-base text-purple-500 mr-3">folder</span>
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">Case</p>
                </div>
                <span class="material-icons text-xs text-gray-500 transform transition-transform duration-200" id="case-section-icon" style="transform: {{ $caseActive ? 'rotate(180deg)' : 'rotate(0deg)' }};">expand_more</span>
            </div>
        </div>
        <div id="case-section" class="hierarchical-menu" style="display: {{ $caseActive ? 'block' : 'none' }};">
            <a href="{{ route('case.index') }}" class="sidebar-submenu-item {{ request()->routeIs('case.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">description</span>
                <span class="text-xs">Case</span>
            </a>
            <a href="{{ route('client.index') }}" class="sidebar-submenu-item {{ request()->routeIs('client.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">people</span>
                <span class="text-xs">Client List</span>
            </a>
            <a href="{{ route('partner.index') }}" class="sidebar-submenu-item {{ request()->routeIs('partner.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">business</span>
                <span class="text-xs">Partner</span>
            </a>
        </div>

        <!-- ACCOUNTING Section -->
        <div class="mt-2"></div>
        <div class="category-header relative {{ $accountingActive ? 'active' : '' }}" onclick="toggleSection('accounting-section')">
            <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
                <div class="flex items-center">
                    <span class="material-icons text-base text-amber-700 mr-3">account_balance</span>
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">Accounting</p>
                </div>
                <span class="material-icons text-xs text-gray-500 transform transition-transform duration-200" id="accounting-section-icon" style="transform: {{ $accountingActive ? 'rotate(180deg)' : 'rotate(0deg)' }};">expand_more</span>
            </div>
        </div>
        <div id="accounting-section" class="hierarchical-menu" style="display: {{ $accountingActive ? 'block' : 'none' }};">
            <a href="{{ route('quotation.index') }}" class="sidebar-submenu-item {{ request()->routeIs('quotation.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">request_quote</span>
                <span class="text-xs">Quotation</span>
            </a>
            <a href="{{ route('tax-invoice.index') }}" class="sidebar-submenu-item {{ request()->routeIs('tax-invoice.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">receipt_long</span>
                <span class="text-xs">Tax Invoice</span>
            </a>
            <a href="{{ route('resit.index') }}" class="sidebar-submenu-item {{ request()->routeIs('resit.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">receipt</span>
                <span class="text-xs">Resit</span>
            </a>
            <a href="{{ route('voucher.index') }}" class="sidebar-submenu-item {{ request()->routeIs('voucher.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">confirmation_number</span>
                <span class="text-xs">Voucher</span>
            </a>
            <a href="{{ route('bill.index') }}" class="sidebar-submenu-item {{ request()->routeIs('bill.index') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">description</span>
                <span class="text-xs">Bill</span>
            </a>
        </div>

        <!-- File Management -->
        <div class="mt-2"></div>
        <div class="category-header relative">
            <a href="{{ route('file-management.index') }}" class="block relative {{ request()->routeIs('file-management.index') ? 'active' : '' }}">
                <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
                    <div class="flex items-center">
                        <span class="material-icons text-base text-orange-500 mr-3">folder_open</span>
                        <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">File Management</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Separator Line -->
        <div class="border-t border-gray-200 my-4"></div>

        <!-- Settings Section -->
        <div class="category-header relative {{ $settingsActive ? 'active' : '' }}" onclick="toggleSection('settings-section')">
            <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
                <div class="flex items-center">
                    <span class="material-icons text-base text-yellow-500 mr-3">settings</span>
                    <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">Settings</p>
                </div>
                <span class="material-icons text-xs text-gray-500 transform transition-transform duration-200" id="settings-section-icon" style="transform: {{ $settingsActive ? 'rotate(180deg)' : 'rotate(0deg)' }};">expand_more</span>
            </div>
        </div>
        <div id="settings-section" class="hierarchical-menu" style="display: {{ $settingsActive ? 'block' : 'none' }};">
            <a href="{{ route('settings.global') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.global') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">tune</span>
                <span class="text-xs">Global Config</span>
            </a>
            <a href="{{ route('settings.role') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.role') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">admin_panel_settings</span>
                <span class="text-xs">Role Management</span>
            </a>
            <a href="{{ route('settings.user') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.user') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">manage_accounts</span>
                <span class="text-xs">User Management</span>
            </a>
            <a href="{{ route('settings.category') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.category') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">category</span>
                <span class="text-xs">Category</span>
            </a>
            <a href="{{ route('settings.log') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.log') ? 'active' : '' }}">
                <span class="material-icons text-xs mr-3">history</span>
                <span class="text-xs">Log Activity</span>
            </a>
        </div>
    </nav>
</aside>

<script>
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById(sectionId + '-icon');
    
    if (section.style.display === 'none') {
        section.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    } else {
        section.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    }
}
</script> 