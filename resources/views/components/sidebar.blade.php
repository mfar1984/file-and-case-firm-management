@php
    use App\Helpers\PermissionHelper;
    $caseActive = request()->routeIs('case.index') || request()->routeIs('client.index') || request()->routeIs('partner.index');
    $accountingActive = request()->routeIs('pre-quotation.index') || request()->routeIs('quotation.index') || request()->routeIs('tax-invoice.index') || request()->routeIs('resit.index') || request()->routeIs('voucher.index') || request()->routeIs('bill.index');
    $gLedgerActive = request()->routeIs('general-ledger.index') || request()->routeIs('detail-transaction.index') || request()->routeIs('journal-report.index') || request()->routeIs('balance-sheet.index') || request()->routeIs('profit-loss.index') || request()->routeIs('trial-balance.index');
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
    @if(PermissionHelper::hasPermission('view-overview'))
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
    @endif

    <!-- Calendar -->
    @if(PermissionHelper::hasPermission('view-calendar'))
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
    @endif

    <!-- Case Section -->
    @if(PermissionHelper::hasAnyPermission(['view-cases', 'view-clients', 'view-partners']))
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
        @if(PermissionHelper::hasPermission('view-cases'))
        <a href="{{ route('case.index') }}" class="sidebar-submenu-item {{ request()->routeIs('case.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">description</span>
            <span class="text-xs">Case</span>
        </a>
        @endif
        @if(PermissionHelper::hasPermission('view-clients'))
        <a href="{{ route('client.index') }}" class="sidebar-submenu-item {{ request()->routeIs('client.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">people</span>
            <span class="text-xs">Client List</span>
        </a>
        @endif
        @if(PermissionHelper::hasPermission('view-partners'))
        <a href="{{ route('partner.index') }}" class="sidebar-submenu-item {{ request()->routeIs('partner.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">business</span>
            <span class="text-xs">Partner</span>
        </a>
        @endif
    </div>
    @endif

    <!-- ACCOUNTING Section -->
    @if(PermissionHelper::hasPermission('view-accounting'))
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
        <a href="{{ route('pre-quotation.index') }}" class="sidebar-submenu-item {{ request()->routeIs('pre-quotation.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">description</span>
            <span class="text-xs">Pre-Quotation</span>
        </a>
        <a href="{{ route('quotation.index') }}" class="sidebar-submenu-item {{ request()->routeIs('quotation.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">request_quote</span>
            <span class="text-xs">Quotation</span>
        </a>
        <a href="{{ route('tax-invoice.index') }}" class="sidebar-submenu-item {{ request()->routeIs('tax-invoice.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">receipt_long</span>
            <span class="text-xs">Tax Invoice</span>
        </a>
        <a href="{{ route('receipt.index') }}" class="sidebar-submenu-item {{ request()->routeIs('receipt.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">receipt</span>
            <span class="text-xs">Receipt</span>
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
    @endif

    <!-- G. LEDGER Section -->
    @if(PermissionHelper::hasPermission('view-accounting'))
    <div class="mt-2"></div>
    <div class="category-header relative {{ $gLedgerActive ? 'active' : '' }}" onclick="toggleSection('gledger-section')">
        <div class="px-4 py-2 flex justify-between items-center cursor-pointer relative">
            <div class="flex items-center">
                <span class="material-icons text-base text-green-600 mr-3">account_balance_wallet</span>
                <p class="text-xs uppercase tracking-wider text-gray-500 font-normal">G. Ledger</p>
            </div>
            <span class="material-icons text-xs text-gray-500 transform transition-transform duration-200" id="gledger-section-icon" style="transform: {{ $gLedgerActive ? 'rotate(180deg)' : 'rotate(0deg)' }};">expand_more</span>
        </div>
    </div>
    <div id="gledger-section" class="hierarchical-menu" style="display: {{ $gLedgerActive ? 'block' : 'none' }};">
        <a href="{{ route('general-ledger.index') }}" class="sidebar-submenu-item {{ request()->routeIs('general-ledger.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">list_alt</span>
            <span class="text-xs">General Ledger Listing</span>
        </a>
        <a href="{{ route('detail-transaction.index') }}" class="sidebar-submenu-item {{ request()->routeIs('detail-transaction.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">receipt_long</span>
            <span class="text-xs">Detail Transaction Reports</span>
        </a>
        <a href="{{ route('journal-report.index') }}" class="sidebar-submenu-item {{ request()->routeIs('journal-report.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">book</span>
            <span class="text-xs">Journal Report</span>
        </a>
        <a href="{{ route('balance-sheet.index') }}" class="sidebar-submenu-item {{ request()->routeIs('balance-sheet.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">balance</span>
            <span class="text-xs">Balance Sheet</span>
        </a>
        <a href="{{ route('profit-loss.index') }}" class="sidebar-submenu-item {{ request()->routeIs('profit-loss.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">trending_up</span>
            <span class="text-xs">Profit and Loss Account</span>
        </a>
        <a href="{{ route('trial-balance.index') }}" class="sidebar-submenu-item {{ request()->routeIs('trial-balance.index') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">account_balance</span>
            <span class="text-xs">Trial Balance</span>
        </a>
    </div>
    @endif

    <!-- File Management -->
    @if(PermissionHelper::hasPermission('view-files'))
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
    @endif

    <!-- Separator Line -->
    <div class="border-t border-gray-200 my-4"></div>

    <!-- Settings Section -->
    @if(PermissionHelper::hasPermission('view-settings'))
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

        @if(PermissionHelper::hasPermission('manage-firm-settings'))
        <a href="{{ route('settings.global') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.global') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">tune</span>
            <span class="text-xs">Global Config</span>
        </a>
        @endif
        @if(PermissionHelper::hasPermission('manage-roles'))
        <a href="{{ route('settings.role') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.role') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">admin_panel_settings</span>
            <span class="text-xs">Role Management</span>
        </a>
        @endif
        @if(PermissionHelper::hasPermission('manage-users'))
        <a href="{{ route('settings.user') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.user') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">manage_accounts</span>
            <span class="text-xs">User Management</span>
        </a>
        @endif

        @if(PermissionHelper::hasPermission('manage-system-logs'))
        <a href="{{ route('settings.category') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.category') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">category</span>
            <span class="text-xs">Category</span>
        </a>
        @endif
        @if(PermissionHelper::hasPermission('manage-system-logs'))
        <a href="{{ route('settings.log') }}" class="sidebar-submenu-item {{ request()->routeIs('settings.log') ? 'active' : '' }}">
            <span class="material-icons text-xs mr-3">history</span>
            <span class="text-xs">Log Activity</span>
        </a>
        @endif
    </div>
    @endif
</nav>

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