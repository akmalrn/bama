<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('dashboard-admin.index') }}" class="sidebar-logo">
            <img src="{{ asset($configuration->logo_dashboard ?? '') }}" alt="site logo" class="light-logo">
            <img src="{{ asset($configuration->logo_dashboard_2 ?? '') }}" alt="site logo" class="dark-logo">
            <img src="{{ asset($configuration->favicon_dashboard ?? '') }}" alt="site logo" class="logo-icon">
        </a>
    </div>
    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard-admin.index') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-menu-group-title">Aplikasi</li>
            @canany(['sales.dashboard.view', 'pmo.dashboard.view', 'finance.dashboard.view', 'hcd.dashboard.view',
                'hsse.dashboard.view', 'procurement.dashboard.view', 'asset.dashboard.view'])
            @endcanany

            
            @canany(['sales.dashboard.view', 'sales.opportunity.view_all', 'sales.opportunity.view_own',
                'sales.customer.view', 'sales.proposal.view', 'sales.activity.view'])
                @php
                    $isSalesActive = request()->routeIs('dashboard-admin.sales.*');
                    $sidebarProposalCount = \App\Models\Proposal::count() ?: 8;
                @endphp
                <li class="dropdown {{ $isSalesActive ? 'dropdown-open open' : '' }}">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:chart-square-outline" class="menu-icon"></iconify-icon>
                        <span>Sales (CRM)</span>
                    </a>
                    <ul class="sidebar-submenu {{ $isSalesActive ? 'show' : '' }}">
                        @can('sales.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.sales.dashboard') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.dashboard') }}">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Sales Dashboard
                                </a>
                            </li>
                        @endcan
                        @canany(['sales.opportunity.view_all', 'sales.opportunity.view_own'])
                            <li class="{{ request()->routeIs('dashboard-admin.sales.opportunities.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.opportunities.index') }}">
                                    <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                                    Sales Pipeline
                                </a>
                            </li>
                        @endcanany
                        @can('sales.proposal.view')
                            <li class="{{ request()->routeIs('dashboard-admin.sales.proposals.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.proposals.index') }}"
                                    class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                        Proposal Register
                                    </span>
                                    <span
                                        class="badge rounded-pill bg-warning-600 text-white px-2 py-0 text-xs">{{ $sidebarProposalCount }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('sales.activity.view')
                            <li class="{{ request()->routeIs('dashboard-admin.sales.activities.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.activities.index') }}">
                                    <i class="ri-circle-fill circle-icon text-purple-main w-auto"></i>
                                    Sales Activity Register
                                </a>
                            </li>
                        @endcan
                        @can('sales.customer.view')
                            <li class="{{ request()->routeIs('dashboard-admin.sales.customers.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.customers.index') }}">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Customer Register
                                </a>
                            </li>
                        @endcan
                        @can('sales.customer.view')
                            <li class="{{ request()->routeIs('dashboard-admin.sales.contacts.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.contacts.index') }}">
                                    <i class="ri-circle-fill circle-icon text-indigo-main w-auto"></i>
                                    Customer Contact
                                </a>
                            </li>
                        @endcan
                        @can('sales.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.sales.performance.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.performance.index') }}">
                                    <i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                    Sales Performance
                                </a>
                            </li>
                        @endcan
                        @canany(['sales.opportunity.view_all', 'sales.opportunity.view_own'])
                            <li
                                class="{{ request()->routeIs('dashboard-admin.sales.ai-opportunities.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.sales.ai-opportunities.index') }}"
                                    class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="ri-circle-fill circle-icon text-cyan-main w-auto"></i>
                                        Sales AI Opportunities
                                    </span>
                                    <span class="badge rounded-pill bg-primary-600 text-white px-2 py-0 text-xs">12</span>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            
            @canany(['pmo.dashboard.view', 'pmo.project.view_all', 'pmo.project.view_own', 'pmo.milestone.manage',
                'pmo.wbs.manage', 'pmo.team.assign'])
                @php
                    $isPmoActive = request()->routeIs('dashboard-admin.pmo.*');
                    $sidebarActiveProjectCount = \App\Models\Project::where(
                        'status',
                        \App\Models\Project::STATUS_ON_PROGRESS,
                    )->count();
                @endphp
                <li class="dropdown {{ $isPmoActive ? 'dropdown-open open' : '' }}">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:folder-with-files-outline" class="menu-icon"></iconify-icon>
                        <span style="font-size: 13.5px;">Project Management (PMO)</span>
                    </a>
                    <ul class="sidebar-submenu {{ $isPmoActive ? 'show' : '' }}">
                        @can('pmo.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.pmo.dashboard') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.pmo.dashboard') }}">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    PMO Dashboard
                                </a>
                            </li>
                        @endcan
                        @canany(['pmo.project.view_all', 'pmo.project.view_own'])
                            <li class="{{ request()->routeIs('dashboard-admin.pmo.projects.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.pmo.projects.index') }}"
                                    class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                                        Project Register
                                    </span>
                                    <span
                                        class="badge rounded-pill bg-info-600 text-white px-2 py-0 text-xs">{{ $sidebarActiveProjectCount }}</span>
                                </a>
                            </li>
                        @endcanany
                        @can('pmo.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.pmo.cost.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.pmo.cost.index') }}">
                                    <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                    Cost Management
                                </a>
                            </li>
                        @endcan
                        @can('pmo.milestone.manage')
                            <li
                                class="{{ request()->routeIs('dashboard-admin.pmo.planning.*') || request()->routeIs('dashboard-admin.pmo.milestones.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.pmo.planning.index') }}">
                                    <i class="ri-circle-fill circle-icon text-purple-main w-auto"></i>
                                    Planning &amp; Scheduling
                                </a>
                            </li>
                        @endcan
                        @canany(['pmo.project.view_all', 'pmo.project.view_own'])
                            <li
                                class="{{ request()->routeIs('dashboard-admin.pmo.documents-handover.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.pmo.documents-handover.index') }}">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Project Documents &amp; Handover
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            
            @canany(['finance.dashboard.view', 'finance.budget_rab.view', 'finance.invoice.view',
                'finance.vendor_bill.view', 'finance.tax.view'])
                @php
                    $isFinanceActive = request()->routeIs('dashboard-admin.finance.*');
                @endphp
                <li class="dropdown {{ $isFinanceActive ? 'dropdown-open open' : '' }}">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:wallet-money-outline" class="menu-icon"></iconify-icon>
                        <span>Finance & Tax</span>
                    </a>
                    <ul class="sidebar-submenu {{ $isFinanceActive ? 'show' : '' }}">
                        @can('finance.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.finance.dashboard') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.finance.dashboard') }}" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Finance & Taxes Dashboard
                                </a>
                            </li>
                        @endcan
                        @can('finance.tax.view')
                            <li class="{{ request()->routeIs('dashboard-admin.finance.tax.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.finance.tax.index') }}" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                                    Corporate Finance Tax Management
                                </a>
                            </li>
                        @endcan
                        @can('finance.budget_rab.view')
                            <li class="{{ request()->routeIs('dashboard-admin.finance.cost.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.finance.cost.index') }}" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Project Cost Management
                                </a>
                            </li>
                        @endcan
                        @can('finance.invoice.view')
                            <li class="{{ request()->routeIs('dashboard-admin.finance.invoices.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.finance.invoices.index') }}"
                                    class="d-flex align-items-center justify-content-between">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                        Invoice Monitoring
                                    </span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            
            @canany(['hcd.dashboard.view', 'hcd.employee.view', 'hcd.task.view', 'hcd.certification.manage', 'hcd.professional.view'])
                @php
                    $isHcdActive = request()->routeIs('dashboard-admin.hcd.*');
                @endphp
                <li class="dropdown {{ $isHcdActive ? 'dropdown-open open' : '' }}">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:users-group-two-rounded-outline" class="menu-icon"></iconify-icon>
                        <span>HCD</span>
                    </a>
                    <ul class="sidebar-submenu {{ $isHcdActive ? 'show' : '' }}">
                        @can('hcd.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.hcd.dashboard') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hcd.dashboard') }}">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Dashboard
                                </a>
                            </li>
                        @endcan
                        @canany(['hcd.task.view', 'hcd.employee.view'])
                            <li class="{{ request()->routeIs('dashboard-admin.hcd.tasks.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hcd.tasks.index') }}">
                                    <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                    Task Monitoring
                                </a>
                            </li>
                        @endcanany
                        @can('hcd.employee.view')
                            <li class="{{ request()->routeIs('dashboard-admin.hcd.employees.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hcd.employees.index') }}">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Employee Register
                                </a>
                            </li>
                        @endcan
                        @canany(['hcd.certification.manage', 'hcd.employee.view'])
                            <li class="{{ request()->routeIs('dashboard-admin.hcd.training.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hcd.training.index') }}">
                                    <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                                    Training & Development
                                </a>
                            </li>
                        @endcanany
                        @canany(['hcd.professional.view', 'hcd.employee.view'])
                            <li class="{{ request()->routeIs('dashboard-admin.hcd.professionals.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hcd.professionals.index') }}">
                                    <i class="ri-circle-fill circle-icon text-purple-main w-auto"></i>
                                    Professional Database
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany

            
            @canany(['hsse.dashboard.view', 'hsse.permit.view', 'hsse.inspection.manage', 'hsse.incident.report'])
                @php
                    $isHsseActive = request()->routeIs('dashboard-admin.hsse.*');
                @endphp
                <li class="dropdown {{ $isHsseActive ? 'dropdown-open open' : '' }}">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:shield-check-outline" class="menu-icon"></iconify-icon>
                        <span>HSSE (Safety)</span>
                    </a>
                    <ul class="sidebar-submenu {{ $isHsseActive ? 'show' : '' }}">
                        @can('hsse.dashboard.view')
                            <li class="{{ request()->routeIs('dashboard-admin.hsse.dashboard') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hsse.dashboard') }}">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Dashboard HSSE
                                </a>
                            </li>
                        @endcan
                        @can('hsse.permit.view')
                            <li class="{{ request()->routeIs('dashboard-admin.hsse.permits.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hsse.permits.index') }}">
                                    <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                    Safety Permit & JSA
                                </a>
                            </li>
                        @endcan
                        @can('hsse.inspection.manage')
                            <li class="{{ request()->routeIs('dashboard-admin.hsse.inspections.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hsse.inspections.index') }}">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Inspeksi Alat & APD
                                </a>
                            </li>
                        @endcan
                        @can('hsse.incident.report')
                            <li class="{{ request()->routeIs('dashboard-admin.hsse.incidents.*') ? 'active-page' : '' }}">
                                <a href="{{ route('dashboard-admin.hsse.incidents.index') }}">
                                    <i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                    Laporan Insiden
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            
            @canany(['procurement.dashboard.view', 'procurement.vendor.manage', 'procurement.pr.create',
                'procurement.po.view', 'procurement.logistic.track'])
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:box-minimalistic-outline" class="menu-icon"></iconify-icon>
                        <span>Procurement & Logistic</span>
                    </a>
                    <ul class="sidebar-submenu">
                        @can('procurement.dashboard.view')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Dashboard Procurement
                                </a>
                            </li>
                        @endcan
                        @can('procurement.vendor.manage')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-info-main w-auto"></i>
                                    Vendor Management
                                </a>
                            </li>
                        @endcan
                        @can('procurement.pr.create')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                    Purchase Request (PR)
                                </a>
                            </li>
                        @endcan
                        @can('procurement.po.view')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Purchase Order (PO)
                                </a>
                            </li>
                        @endcan
                        @can('procurement.logistic.track')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Ekspedisi & Logistik
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            {{-- 7. DEPARTEMEN ASSET MANAGEMENT --}}
            @canany(['asset.dashboard.view', 'asset.inventory.view', 'asset.allocation.request',
                'asset.allocation.approve', 'asset.maintenance.schedule'])
                <li class="dropdown">
                    <a href="javascript:void(0);" class="dropdown-toggle">
                        <iconify-icon icon="solar:tuning-2-outline" class="menu-icon"></iconify-icon>
                        <span>Asset Management</span>
                    </a>
                    <ul class="sidebar-submenu">
                        @can('asset.dashboard.view')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i>
                                    Dashboard Asset
                                </a>
                            </li>
                        @endcan
                        @can('asset.inventory.view')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-danger-main w-auto"></i>
                                    Inventaris Mesin & Alat
                                </a>
                            </li>
                        @endcan
                        @can('asset.allocation.request')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-warning-main w-auto"></i>
                                    Alokasi Project
                                </a>
                            </li>
                        @endcan
                        @can('asset.maintenance.schedule')
                            <li>
                                <a href="javascript:void(0);" class="text-secondary-light">
                                    <i class="ri-circle-fill circle-icon text-success-main w-auto"></i>
                                    Jadwal Maintenance
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany
            {{-- @can('God Anjay')
                <li>
                    <a href="{{ route('dashboard-admin.image-converter.index') }}"
                        class="{{ request()->routeIs('dashboard-admin.image-converter.*') ? 'active-page' : '' }}">
                        <iconify-icon icon="solar:gallery-wide-outline" class="menu-icon"></iconify-icon>
                        <span>Image Converter</span>
                    </a>
                </li>
            @endcan --}}
            <li class="sidebar-menu-group-title">User Management</li>

            @can('GOD-To_aDmIn')
                <li>
                    <a href="{{ route('dashboard-admin.permissions.index') }}"
                        class="{{ request()->routeIs('dashboard-admin.permissions.*') ? 'active-page' : '' }}">
                        <iconify-icon icon="solar:shield-keyhole-outline" class="menu-icon"></iconify-icon>
                        <span>Permission</span>
                    </a>
                </li>
            @endcan

            @can('lihat role')
                <li>
                    <a href="{{ route('dashboard-admin.roles.index') }}"
                        class="{{ request()->routeIs('dashboard-admin.roles.*') ? 'active-page' : '' }}">
                        <iconify-icon icon="solar:user-id-outline" class="menu-icon"></iconify-icon>
                        <span>Role</span>
                    </a>
                </li>
            @endcan
            @can('lihat user')
                <li id="menu-users">
                    <a href="{{ route('dashboard-admin.users.index') }}"
                        class="{{ request()->routeIs('dashboard-admin.users.*') ? 'active-page' : '' }}">
                        <iconify-icon icon="solar:users-group-rounded-outline" class="menu-icon"></iconify-icon>
                        <span>User</span>
                    </a>
                </li>
            @endcan

            <li class="sidebar-menu-group-title">Pengaturan Website</li>


            @can('lihat pengaturan-website')
                <li>
                    <a href="{{ route('dashboard-admin.configurations.create') }}">
                        <iconify-icon icon="solar:settings-bold" class="menu-icon"></iconify-icon>
                        <span>Pengaturan</span>
                    </a>
                </li>
            @endcan


            {{-- @can('GOD-To_aDmIn')
                <li class="sidebar-menu-group-title">Fitur Lainnya</li>
                <li>
                    <a href="{{ route('email') }}">
                        <iconify-icon icon="mage:email" class="menu-icon"></iconify-icon>
                        <span>Email</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('chatMessage') }}">
                        <iconify-icon icon="bi:chat-dots" class="menu-icon"></iconify-icon>
                        <span>Chat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('calendar') }}">
                        <iconify-icon icon="solar:calendar-outline" class="menu-icon"></iconify-icon>
                        <span>Calendar</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('kanban') }}">
                        <iconify-icon icon="material-symbols:map-outline" class="menu-icon"></iconify-icon>
                        <span>Kanban</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:invoice-03" class="menu-icon"></iconify-icon>
                        <span>Invoice</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('invoiceList') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> List</a>
                        </li>
                        <li>
                            <a href="{{ route('invoicePreview') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Preview</a>
                        </li>
                        <li>
                            <a href="{{ route('invoiceAdd') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add new</a>
                        </li>
                        <li>
                            <a href="{{ route('invoiceEdit') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Edit</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="ri-robot-2-line text-xl me-6 d-flex w-auto"></i>
                        <span>Ai Application</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('textGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Text Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('codeGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Code Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('imageGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Image Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('voiceGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Voice Generator</a>
                        </li>
                        <li>
                            <a href="{{ route('videoGenerator') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Video Generator</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="ri-btc-line text-xl me-6 d-flex w-auto"></i>
                        <span>Crypto Currency</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('wallet') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Wallet</a>
                        </li>
                        <li>
                            <a href="{{ route('marketplace') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Marketplace</a>
                        </li>
                        <li>
                            <a href="{{ route('marketplaceDetails') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Marketplace
                                Details</a>
                        </li>
                        <li>
                            <a href="{{ route('portfolio') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Portfolios</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">UI Elements</li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:document-text-outline" class="menu-icon"></iconify-icon>
                        <span>Components</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('typography') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Typography</a>
                        </li>
                        <li>
                            <a href="{{ route('colors') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Colors</a>
                        </li>
                        <li>
                            <a href="{{ route('button') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Button</a>
                        </li>
                        <li>
                            <a href="{{ route('dropdown') }}"><i
                                    class="ri-circle-fill circle-icon text-lilac-600 w-auto"></i> Dropdown</a>
                        </li>
                        <li>
                            <a href="{{ route('alert') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Alerts</a>
                        </li>
                        <li>
                            <a href="{{ route('card') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Card</a>
                        </li>
                        <li>
                            <a href="{{ route('carousel') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Carousel</a>
                        </li>
                        <li>
                            <a href="{{ route('avatar') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Avatars</a>
                        </li>
                        <li>
                            <a href="{{ route('progress') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Progress bar</a>
                        </li>
                        <li>
                            <a href="{{ route('tabs') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Tab & Accordion</a>
                        </li>
                        <li>
                            <a href="{{ route('pagination') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Pagination</a>
                        </li>
                        <li>
                            <a href="{{ route('badges') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Badges</a>
                        </li>
                        <li>
                            <a href="{{ route('tooltip') }}"><i
                                    class="ri-circle-fill circle-icon text-lilac-600 w-auto"></i> Tooltip & Popover</a>
                        </li>
                        <li>
                            <a href="{{ route('videos') }}"><i class="ri-circle-fill circle-icon text-cyan w-auto"></i>
                                Videos</a>
                        </li>
                        <li>
                            <a href="{{ route('starRating') }}"><i
                                    class="ri-circle-fill circle-icon text-indigo w-auto"></i> Star Ratings</a>
                        </li>
                        <li>
                            <a href="{{ route('tags') }}"><i class="ri-circle-fill circle-icon text-purple w-auto"></i>
                                Tags</a>
                        </li>
                        <li>
                            <a href="{{ route('list') }}"><i class="ri-circle-fill circle-icon text-red w-auto"></i>
                                List</a>
                        </li>
                        <li>
                            <a href="{{ route('calendar') }}"><i
                                    class="ri-circle-fill circle-icon text-yellow w-auto"></i> Calendar</a>
                        </li>
                        <li>
                            <a href="{{ route('radio') }}"><i class="ri-circle-fill circle-icon text-orange w-auto"></i>
                                Radio</a>
                        </li>
                        <li>
                            <a href="{{ route('switch') }}"><i class="ri-circle-fill circle-icon text-pink w-auto"></i>
                                Switch</a>
                        </li>
                        <li>
                            <a href="{{ route('imageUpload') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Upload</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="heroicons:document" class="menu-icon"></iconify-icon>
                        <span>Forms</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('form') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Input Forms</a>
                        </li>
                        <li>
                            <a href="{{ route('formLayout') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Input Layout</a>
                        </li>
                        <li>
                            <a href="{{ route('formValidation') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Form Validation</a>
                        </li>
                        <li>
                            <a href="{{ route('wizard') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Form Wizard</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="mingcute:storage-line" class="menu-icon"></iconify-icon>
                        <span>Table</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('tableBasic') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Basic Table</a>
                        </li>
                        <li>
                            <a href="{{ route('tableData') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Data Table</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="solar:pie-chart-outline" class="menu-icon"></iconify-icon>
                        <span>Chart</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('lineChart') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Line Chart</a>
                        </li>
                        <li>
                            <a href="{{ route('columnChart') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Column Chart</a>
                        </li>
                        <li>
                            <a href="{{ route('pieChart') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Pie Chart</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('widgets') }}">
                        <iconify-icon icon="fe:vector" class="menu-icon"></iconify-icon>
                        <span>Widgets</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('usersList') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Users List</a>
                        </li>
                        <li>
                            <a href="{{ route('usersGrid') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Users Grid</a>
                        </li>
                        <li>
                            <a href="{{ route('addUser') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Add User</a>
                        </li>
                        <li>
                            <a href="{{ route('viewProfile') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> View Profile</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <i class="ri-user-settings-line text-xl me-6 d-flex w-auto"></i>
                        <span>Role & Access</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('roleAaccess') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Role & Access</a>
                        </li>
                        <li>
                            <a href="{{ route('assignRole') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Assign Role</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">Application</li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="simple-line-icons:vector" class="menu-icon"></iconify-icon>
                        <span>Authentication</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('signin') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Sign In</a>
                        </li>
                        <li>
                            <a href="{{ route('signup') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Sign Up</a>
                        </li>
                        <li>
                            <a href="{{ route('forgotPassword') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Forgot Password</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('gallery') }}">
                        <iconify-icon icon="solar:gallery-wide-linear" class="menu-icon"></iconify-icon>
                        <span>Gallery</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pricing') }}">
                        <iconify-icon icon="hugeicons:money-send-square" class="menu-icon"></iconify-icon>
                        <span>Pricing</span>
                    </a>
                </li>             
                <li>
                    <a href="{{ route('testimonials') }}">
                        <i class="ri-star-line text-xl me-6 d-flex w-auto"></i>
                        <span>Testimonial</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('faq') }}">
                        <iconify-icon icon="mage:message-question-mark-round" class="menu-icon"></iconify-icon>
                        <span>FAQs</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('error') }}">
                        <iconify-icon icon="streamline:straight-face" class="menu-icon"></iconify-icon>
                        <span>404</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('termsCondition') }}">
                        <iconify-icon icon="octicon:info-24" class="menu-icon"></iconify-icon>
                        <span>Terms & Conditions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('comingSoon') }}">
                        <i class="ri-rocket-line text-xl me-6 d-flex w-auto"></i>
                        <span>Coming Soon</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('maintenance') }}">
                        <i class="ri-hammer-line text-xl me-6 d-flex w-auto"></i>
                        <span>Maintenance</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('blankPage') }}">
                        <i class="ri-checkbox-multiple-blank-line text-xl me-6 d-flex w-auto"></i>
                        <span>Blank Page</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="icon-park-outline:setting-two" class="menu-icon"></iconify-icon>
                        <span>Settings</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('company') }}"><i
                                    class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Company</a>
                        </li>
                        <li>
                            <a href="{{ route('notification') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Notification</a>
                        </li>
                        <li>
                            <a href="{{ route('notificationAlert') }}"><i
                                    class="ri-circle-fill circle-icon text-info-main w-auto"></i> Notification Alert</a>
                        </li>
                        <li>
                            <a href="{{ route('theme') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Theme</a>
                        </li>
                        <li>
                            <a href="{{ route('currencies') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Currencies</a>
                        </li>
                        <li>
                            <a href="{{ route('language') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Languages</a>
                        </li>
                        <li>
                            <a href="{{ route('paymentGateway') }}"><i
                                    class="ri-circle-fill circle-icon text-danger-main w-auto"></i> Payment Gateway</a>
                        </li>
                    </ul>
                </li>
            @endcan --}}
        </ul>
    </div>
</aside>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const active = document.querySelector(".active-page");
        if (active) {
            active.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    });
</script>
