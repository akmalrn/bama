<?php

use App\Http\Controllers\AiapplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\ComponentpageController;
use App\Http\Controllers\CryptocurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Database\AboutController;
use App\Http\Controllers\Database\BusinessFieldController;
use App\Http\Controllers\Database\CareerController;
use App\Http\Controllers\Database\CareerPositionController;
use App\Http\Controllers\Database\CategoryBlogController;
use App\Http\Controllers\Database\CategoryGallerysController;
use App\Http\Controllers\Database\CategoryPartnerController;
use App\Http\Controllers\Database\CategoryPortofolioController;
use App\Http\Controllers\Database\CategoryProductController;
use App\Http\Controllers\Database\CategoryProjectController;
use App\Http\Controllers\Database\CategoryServiceController;
use App\Http\Controllers\Database\CollaborationController;
use App\Http\Controllers\Database\ConfigurationController;
use App\Http\Controllers\Database\ContactController;
use App\Http\Controllers\Database\CounterSectionController;
use App\Http\Controllers\Database\FaqController;
use App\Http\Controllers\Database\FrontendController;
use App\Http\Controllers\Database\GalleryController;
use App\Http\Controllers\Database\GallerysController;
use App\Http\Controllers\Database\ImpersonateController;
use App\Http\Controllers\Database\ListOfBenefitAboutUsController;
use App\Http\Controllers\Database\ListOfBenefitOverviewController;
use App\Http\Controllers\Database\NavigationMenuController;
use App\Http\Controllers\Database\OrderStepController;
use App\Http\Controllers\Database\OverviewController;
use App\Http\Controllers\Database\PartnerController;
use App\Http\Controllers\Database\PasswordController;
use App\Http\Controllers\Database\PermissionController;
use App\Http\Controllers\Database\PortofolioController;
use App\Http\Controllers\Database\ProductController;
use App\Http\Controllers\Database\ProjectController;
use App\Http\Controllers\Database\RoleController;
use App\Http\Controllers\Database\ServiceController;
use App\Http\Controllers\Database\SliderController;
use App\Http\Controllers\Database\SuperiorityController;
use App\Http\Controllers\Database\SuperiorityIconController;
use App\Http\Controllers\Database\TeamController;
use App\Http\Controllers\Database\TestimonialController;
use App\Http\Controllers\Database\UserController;
use App\Http\Controllers\Database\VisitorManagementController;
use App\Http\Controllers\Database\WorkshopController;
use App\Http\Controllers\FormsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageConverterController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\RoleandaccessController;
use App\Http\Controllers\SendContactController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::controller(HomeController::class)->group(function () {
    Route::get('calendar', 'calendar')->name('calendar');
    Route::get('chatmessage', 'chatMessage')->name('chatMessage');
    Route::get('chatempty', 'chatempty')->name('chatempty');
    Route::get('email', 'email')->name('email');
    Route::get('error', 'error1')->name('error');
    Route::get('faq', 'faq')->name('faq');
    Route::get('gallery', 'gallery')->name('gallery');
    Route::get('kanban', 'kanban')->name('kanban');
    Route::get('pricing', 'pricing')->name('pricing');
    Route::get('termscondition', 'termsCondition')->name('termsCondition');
    Route::get('widgets', 'widgets')->name('widgets');
    Route::get('chatprofile', 'chatProfile')->name('chatProfile');
    Route::get('veiwdetails', 'veiwDetails')->name('veiwDetails');
    Route::get('blankPage', 'blankPage')->name('blankPage');
    Route::get('comingSoon', 'comingSoon')->name('comingSoon');
    Route::get('coming-soon', 'comingSoon')->name('coming-soon');
    Route::get('maintenance', 'maintenance')->name('maintenance');
    Route::get('starred', 'starred')->name('starred');
    Route::get('testimonials', 'testimonials')->name('testimonials');
});

// aiApplication
Route::prefix('aiapplication')->group(function () {
    Route::controller(AiapplicationController::class)->group(function () {
        Route::get('/codegenerator', 'codeGenerator')->name('codeGenerator');
        Route::get('/codegeneratornew', 'codeGeneratorNew')->name('codeGeneratorNew');
        Route::get('/imagegenerator', 'imageGenerator')->name('imageGenerator');
        Route::get('/textgeneratornew', 'textGeneratorNew')->name('textGeneratorNew');
        Route::get('/textgenerator', 'textGenerator')->name('textGenerator');
        Route::get('/videogenerator', 'videoGenerator')->name('videoGenerator');
        Route::get('/voicegenerator', 'voiceGenerator')->name('voiceGenerator');
    });
});

// Authentication
Route::prefix('authentication')->group(function () {
    Route::controller(AuthenticationController::class)->group(function () {
        Route::get('/forgotpassword', 'forgotPassword')->name('forgotPassword');
        Route::get('/signin', 'signin')->name('signin');
        Route::get('/signup', 'signup')->name('signup');
    });
});

// chart
Route::prefix('chart')->group(function () {
    Route::controller(ChartController::class)->group(function () {
        Route::get('/columnchart', 'columnChart')->name('columnChart');
        Route::get('/linechart', 'lineChart')->name('lineChart');
        Route::get('/piechart', 'pieChart')->name('pieChart');
    });
});

// Componentpage
Route::prefix('componentspage')->group(function () {
    Route::controller(ComponentpageController::class)->group(function () {
        Route::get('/alert', 'alert')->name('alert');
        Route::get('/avatar', 'avatar')->name('avatar');
        Route::get('/badges', 'badges')->name('badges');
        Route::get('/button', 'button')->name('button');
        Route::get('/calendar', 'calendar')->name('calendar');
        Route::get('/card', 'card')->name('card');
        Route::get('/carousel', 'carousel')->name('carousel');
        Route::get('/colors', 'colors')->name('colors');
        Route::get('/dropdown', 'dropdown')->name('dropdown');
        Route::get('/imageupload', 'imageUpload')->name('imageUpload');
        Route::get('/list', 'list')->name('list');
        Route::get('/pagination', 'pagination')->name('pagination');
        Route::get('/progress', 'progress')->name('progress');
        Route::get('/radio', 'radio')->name('radio');
        Route::get('/starrating', 'starRating')->name('starRating');
        Route::get('/switch', 'switch')->name('switch');
        Route::get('/tabs', 'tabs')->name('tabs');
        Route::get('/tags', 'tags')->name('tags');
        Route::get('/tooltip', 'tooltip')->name('tooltip');
        Route::get('/typography', 'typography')->name('typography');
        Route::get('/videos', 'videos')->name('videos');
    });
});

// Forms
Route::prefix('forms')->group(function () {
    Route::controller(FormsController::class)->group(function () {
        Route::get('/form-layout', 'formLayout')->name('formLayout');
        Route::get('/form-validation', 'formValidation')->name('formValidation');
        Route::get('/form', 'form')->name('form');
        Route::get('/wizard', 'wizard')->name('wizard');
    });
});

// invoice/invoiceList
Route::prefix('invoice')->group(function () {
    Route::controller(InvoiceController::class)->group(function () {
        Route::get('/invoice-add', 'invoiceAdd')->name('invoiceAdd');
        Route::get('/invoice-edit', 'invoiceEdit')->name('invoiceEdit');
        Route::get('/invoice-list', 'invoiceList')->name('invoiceList');
        Route::get('/invoice-preview', 'invoicePreview')->name('invoicePreview');
    });
});

// Settings
Route::prefix('settings')->group(function () {
    Route::controller(SettingsController::class)->group(function () {
        Route::get('/company', 'company')->name('company');
        Route::get('/currencies', 'currencies')->name('currencies');
        Route::get('/language', 'language')->name('language');
        Route::get('/notification', 'notification')->name('notification');
        Route::get('/notification-alert', 'notificationAlert')->name('notificationAlert');
        Route::get('/payment-gateway', 'paymentGateway')->name('paymentGateway');
        Route::get('/theme', 'theme')->name('theme');
    });
});

// Table
Route::prefix('table')->group(function () {
    Route::controller(TableController::class)->group(function () {
        Route::get('/tablebasic', 'tableBasic')->name('tableBasic');
        Route::get('/tabledata', 'tableData')->name('tableData');
    });
});

// Users
Route::prefix('users')->group(function () {
    Route::controller(UsersController::class)->group(function () {
        Route::get('/add-user', 'addUser')->name('addUser');
        Route::get('/users-grid', 'usersGrid')->name('usersGrid');
        Route::get('/users-list', 'usersList')->name('usersList');
        Route::get('/view-profile', 'viewProfile')->name('viewProfile');
    });
});

// Users
// Route::prefix('blog')->group(function () {
//     Route::controller(BlogController::class)->group(function () {
//         Route::get('/addBlog', 'addBlog')->name('addBlog');
//         Route::get('/blog', 'blog')->name('blog');
//         Route::get('/blogDetails', 'blogDetails')->name('blogDetails');
//     });
// });

// Users
Route::prefix('roleandaccess')->group(function () {
    Route::controller(RoleandaccessController::class)->group(function () {
        Route::get('/assignRole', 'assignRole')->name('assignRole');
        Route::get('/roleAaccess', 'roleAaccess')->name('roleAaccess');
    });
});

// Users
Route::prefix('cryptocurrency')->group(function () {
    Route::controller(CryptocurrencyController::class)->group(function () {
        Route::get('/marketplace', 'marketplace')->name('marketplace');
        Route::get('/marketplacedetails', 'marketplaceDetails')->name('marketplaceDetails');
        Route::get('/portfolio', 'portfolio')->name('portfolio');
        Route::get('/wallet', 'wallet')->name('wallet');
    });
});

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/post', [LoginController::class, 'login'])->name('postLogin');
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('dashboard-admin')->name('dashboard-admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');

    // My Workspace (Personal Workbench)
    Route::prefix('workspace')->name('workspace.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MyWorkspaceController::class, 'index'])->name('index');
        Route::post('/timesheet', [\App\Http\Controllers\MyWorkspaceController::class, 'storeTimesheet'])->name('timesheet.store');
        Route::patch('/tasks/{task}/status', [\App\Http\Controllers\MyWorkspaceController::class, 'updateTaskStatus'])->name('task.status');
    });

    Route::get('/sales/dashboard', [\App\Http\Controllers\SalesDashboardController::class, 'index'])
        ->name('sales.dashboard')
        ->middleware('permission:sales.dashboard.view');
    Route::get('/sales/dashboard/export', [\App\Http\Controllers\SalesDashboardController::class, 'export'])
        ->name('sales.dashboard.export')
        ->middleware('permission:sales.dashboard.view');

    // Sales CRM - Customers
    Route::prefix('sales/customers')->name('sales.customers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CustomerController::class, 'index'])
            ->name('index')
            ->middleware('permission:sales.customer.view');
        Route::get('/create', [\App\Http\Controllers\CustomerController::class, 'create'])
            ->name('create')
            ->middleware('permission:sales.customer.create');
        Route::post('/', [\App\Http\Controllers\CustomerController::class, 'store'])
            ->name('store')
            ->middleware('permission:sales.customer.create');
        Route::get('/{customer}', [\App\Http\Controllers\CustomerController::class, 'show'])
            ->name('show')
            ->middleware('permission:sales.customer.view');
        Route::get('/{customer}/edit', [\App\Http\Controllers\CustomerController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:sales.customer.edit');
        Route::put('/{customer}', [\App\Http\Controllers\CustomerController::class, 'update'])
            ->name('update')
            ->middleware('permission:sales.customer.edit');
        Route::delete('/{customer}', [\App\Http\Controllers\CustomerController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:sales.customer.delete');
    });

    // Sales CRM - Opportunities & Pipeline
    Route::prefix('sales/opportunities')->name('sales.opportunities.')->group(function () {
        Route::get('/', [\App\Http\Controllers\OpportunityController::class, 'index'])
            ->name('index')
            ->middleware('permission:sales.opportunity.view_all|sales.opportunity.view_own');
        Route::get('/export', [\App\Http\Controllers\OpportunityController::class, 'export'])
            ->name('export')
            ->middleware('permission:sales.opportunity.view_all|sales.opportunity.view_own');
        Route::get('/create', [\App\Http\Controllers\OpportunityController::class, 'create'])
            ->name('create')
            ->middleware('permission:sales.opportunity.create');
        Route::post('/', [\App\Http\Controllers\OpportunityController::class, 'store'])
            ->name('store')
            ->middleware('permission:sales.opportunity.create');
        Route::get('/{opportunity}', [\App\Http\Controllers\OpportunityController::class, 'show'])
            ->name('show')
            ->middleware('permission:sales.opportunity.view_all|sales.opportunity.view_own');
        Route::get('/{opportunity}/edit', [\App\Http\Controllers\OpportunityController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:sales.opportunity.edit');
        Route::put('/{opportunity}', [\App\Http\Controllers\OpportunityController::class, 'update'])
            ->name('update')
            ->middleware('permission:sales.opportunity.edit');
        Route::patch('/{opportunity}/stage', [\App\Http\Controllers\OpportunityController::class, 'updateStage'])
            ->name('update-stage')
            ->middleware('permission:sales.opportunity.edit');
        Route::delete('/{opportunity}', [\App\Http\Controllers\OpportunityController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:sales.opportunity.delete');
    });

    // Sales CRM - Proposals & Quotations
    Route::prefix('sales/proposals')->name('sales.proposals.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProposalController::class, 'index'])
            ->name('index')
            ->middleware('permission:sales.proposal.view');
        Route::get('/create', [\App\Http\Controllers\ProposalController::class, 'create'])
            ->name('create')
            ->middleware('permission:sales.proposal.create');
        Route::post('/', [\App\Http\Controllers\ProposalController::class, 'store'])
            ->name('store')
            ->middleware('permission:sales.proposal.create');
        Route::get('/{proposal}', [\App\Http\Controllers\ProposalController::class, 'show'])
            ->name('show')
            ->middleware('permission:sales.proposal.view');
        Route::get('/{proposal}/edit', [\App\Http\Controllers\ProposalController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:sales.proposal.edit');
        Route::put('/{proposal}', [\App\Http\Controllers\ProposalController::class, 'update'])
            ->name('update')
            ->middleware('permission:sales.proposal.edit');
        Route::delete('/{proposal}', [\App\Http\Controllers\ProposalController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:sales.proposal.delete');
    });

    // Sales CRM - Activities Register
    Route::prefix('sales/activities')->name('sales.activities.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SalesActivityController::class, 'index'])
            ->name('index')
            ->middleware('permission:sales.activity.view');
        Route::post('/', [\App\Http\Controllers\SalesActivityController::class, 'store'])
            ->name('store')
            ->middleware('permission:sales.activity.create');
        Route::put('/{activity}', [\App\Http\Controllers\SalesActivityController::class, 'update'])
            ->name('update')
            ->middleware('permission:sales.activity.edit');
        Route::delete('/{activity}', [\App\Http\Controllers\SalesActivityController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:sales.activity.delete');
    });

    // Sales CRM - Customer Contacts
    Route::prefix('sales/contacts')->name('sales.contacts.')->group(function () {
        Route::get('/', [\App\Http\Controllers\CustomerContactController::class, 'index'])
            ->name('index')
            ->middleware('permission:sales.customer.view');
        Route::post('/', [\App\Http\Controllers\CustomerContactController::class, 'store'])
            ->name('store')
            ->middleware('permission:sales.customer.create');
        Route::put('/{customer}', [\App\Http\Controllers\CustomerContactController::class, 'update'])
            ->name('update')
            ->middleware('permission:sales.customer.edit');
        Route::delete('/{customer}', [\App\Http\Controllers\CustomerContactController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:sales.customer.delete');
    });

    // Sales CRM - Performance
    Route::get('/sales/performance', [\App\Http\Controllers\SalesPerformanceController::class, 'index'])
        ->name('sales.performance.index')
        ->middleware('permission:sales.dashboard.view');

    // Sales CRM - AI Opportunities
    Route::prefix('sales/ai-opportunities')->name('sales.ai-opportunities.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SalesAIOpportunitiesController::class, 'index'])
            ->name('index')
            ->middleware('permission:sales.opportunity.view_all|sales.opportunity.view_own');
        Route::post('/convert', [\App\Http\Controllers\SalesAIOpportunitiesController::class, 'convert'])
            ->name('convert')
            ->middleware('permission:sales.opportunity.create');
    });

    // Project Management (PMO)
    Route::prefix('pmo')->name('pmo.')->group(function () {
        // 1. Dashboard PMO
        Route::get('/dashboard', [\App\Http\Controllers\PmoDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:pmo.dashboard.view');

        // 2. Project Register
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PmoProjectController::class, 'index'])
                ->name('index')
                ->middleware('permission:pmo.project.view_all|pmo.project.view_own');
            Route::post('/', [\App\Http\Controllers\PmoProjectController::class, 'store'])
                ->name('store')
                ->middleware('permission:pmo.project.create');
            Route::get('/{project}', [\App\Http\Controllers\PmoProjectController::class, 'show'])
                ->name('show')
                ->middleware('permission:pmo.project.view_all|pmo.project.view_own');
            Route::put('/{project}', [\App\Http\Controllers\PmoProjectController::class, 'update'])
                ->name('update')
                ->middleware('permission:pmo.project.edit');
            Route::delete('/{project}', [\App\Http\Controllers\PmoProjectController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:pmo.project.delete');
        });

        // 3. Cost Management
        Route::prefix('cost-management')->name('cost.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PmoCostController::class, 'index'])
                ->name('index')
                ->middleware('permission:pmo.dashboard.view|pmo.project.view_all');
            Route::post('/transactions', [\App\Http\Controllers\PmoCostController::class, 'storeTransaction'])
                ->name('transactions.store')
                ->middleware('permission:pmo.project.edit|pmo.project.create');
            Route::delete('/transactions/{transaction}', [\App\Http\Controllers\PmoCostController::class, 'destroyTransaction'])
                ->name('transactions.destroy')
                ->middleware('permission:pmo.project.delete|pmo.project.edit');
        });

        // 4. Planning & Scheduling (WBS & Milestones)
        Route::get('/planning-scheduling', [\App\Http\Controllers\PmoMilestoneController::class, 'index'])
            ->name('planning.index')
            ->middleware('permission:pmo.milestone.manage');

        Route::prefix('milestones')->name('milestones.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PmoMilestoneController::class, 'index'])
                ->name('index')
                ->middleware('permission:pmo.milestone.manage');
            Route::post('/', [\App\Http\Controllers\PmoMilestoneController::class, 'store'])
                ->name('store')
                ->middleware('permission:pmo.milestone.manage');
            Route::put('/{milestone}', [\App\Http\Controllers\PmoMilestoneController::class, 'update'])
                ->name('update')
                ->middleware('permission:pmo.milestone.manage');
            Route::delete('/{milestone}', [\App\Http\Controllers\PmoMilestoneController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:pmo.milestone.manage');
        });

        // 5. Project Documents & Handover (Central Portfolio Dossier)
        Route::prefix('documents-handover')->name('documents-handover.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PmoDocumentsHandoverController::class, 'index'])
                ->name('index')
                ->middleware('permission:pmo.project.view_all|pmo.project.view_own');
            Route::put('/{project}/status', [\App\Http\Controllers\PmoDocumentsHandoverController::class, 'updateStatus'])
                ->name('status')
                ->middleware('permission:pmo.project.edit');
            Route::post('/', [\App\Http\Controllers\PmoDocumentsHandoverController::class, 'storeDocument'])
                ->name('store')
                ->middleware('permission:pmo.project.edit|pmo.project.create');
            Route::delete('/{document}', [\App\Http\Controllers\PmoDocumentsHandoverController::class, 'destroyDocument'])
                ->name('destroy')
                ->middleware('permission:pmo.project.edit|pmo.project.delete');
        });

        // Penugasan Tim (Internal Team Assignment)
        Route::prefix('teams')->name('teams.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PmoTeamController::class, 'index'])
                ->name('index')
                ->middleware('permission:pmo.team.assign');
            Route::post('/', [\App\Http\Controllers\PmoTeamController::class, 'store'])
                ->name('store')
                ->middleware('permission:pmo.team.assign');
            Route::put('/{member}', [\App\Http\Controllers\PmoTeamController::class, 'update'])
                ->name('update')
                ->middleware('permission:pmo.team.assign');
            Route::delete('/{member}', [\App\Http\Controllers\PmoTeamController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:pmo.team.assign');
        });

        // Per-project direct document upload
        Route::prefix('projects/{project}/documents')->name('documents.')->group(function () {
            Route::post('/', [\App\Http\Controllers\PmoDocumentController::class, 'store'])
                ->name('store')
                ->middleware('permission:pmo.project.edit');
            Route::delete('/{document}', [\App\Http\Controllers\PmoDocumentController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:pmo.project.edit');
        });
    });

    // Departemen Finance & Tax
    Route::prefix('finance')->name('finance.')->group(function () {
        // Finance & Taxes Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\FinanceDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:finance.dashboard.view');

        // Invoice Monitoring (AR)
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [\App\Http\Controllers\FinanceInvoiceController::class, 'index'])
                ->name('index')
                ->middleware('permission:finance.invoice.view');
            Route::post('/', [\App\Http\Controllers\FinanceInvoiceController::class, 'store'])
                ->name('store')
                ->middleware('permission:finance.invoice.issue');
            Route::get('/{invoice}', [\App\Http\Controllers\FinanceInvoiceController::class, 'show'])
                ->name('show')
                ->middleware('permission:finance.invoice.view');
            Route::put('/{invoice}', [\App\Http\Controllers\FinanceInvoiceController::class, 'update'])
                ->name('update')
                ->middleware('permission:finance.invoice.issue');
            Route::delete('/{invoice}', [\App\Http\Controllers\FinanceInvoiceController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:finance.invoice.issue');
            Route::post('/{invoice}/payments', [\App\Http\Controllers\FinanceInvoiceController::class, 'addPayment'])
                ->name('payments.store')
                ->middleware('permission:finance.invoice.issue');
        });

        // Project Cost Management
        Route::prefix('project-costs')->name('cost.')->group(function () {
            Route::get('/', [\App\Http\Controllers\FinanceProjectCostController::class, 'index'])
                ->name('index')
                ->middleware('permission:finance.budget_rab.view');
            Route::post('/', [\App\Http\Controllers\FinanceProjectCostController::class, 'store'])
                ->name('store')
                ->middleware('permission:finance.budget_rab.manage');
            Route::put('/{cost}', [\App\Http\Controllers\FinanceProjectCostController::class, 'update'])
                ->name('update')
                ->middleware('permission:finance.budget_rab.manage');
            Route::patch('/{cost}/paid', [\App\Http\Controllers\FinanceProjectCostController::class, 'markAsPaid'])
                ->name('paid')
                ->middleware('permission:finance.budget_rab.manage');
            Route::delete('/{cost}', [\App\Http\Controllers\FinanceProjectCostController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:finance.budget_rab.manage');
        });

        // Corporate Finance Tax Management
        Route::prefix('taxes')->name('tax.')->group(function () {
            Route::get('/', [\App\Http\Controllers\FinanceTaxController::class, 'index'])
                ->name('index')
                ->middleware('permission:finance.tax.view');
            Route::post('/', [\App\Http\Controllers\FinanceTaxController::class, 'store'])
                ->name('store')
                ->middleware('permission:finance.tax.manage');
            Route::delete('/{tax}', [\App\Http\Controllers\FinanceTaxController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:finance.tax.manage');
        });
    });

    // Departemen HCD (Human Capital Development)
    Route::prefix('hcd')->name('hcd.')->group(function () {
        // 1. Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\HcdDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:hcd.dashboard.view');

        // 2. Task Monitoring
        Route::prefix('tasks')->name('tasks.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HcdTaskController::class, 'index'])
                ->name('index')
                ->middleware('permission:hcd.task.view|hcd.employee.view');
            Route::post('/', [\App\Http\Controllers\HcdTaskController::class, 'store'])
                ->name('store')
                ->middleware('permission:hcd.task.manage|hcd.employee.manage');
            Route::patch('/{task}/status', [\App\Http\Controllers\HcdTaskController::class, 'updateStatus'])
                ->name('status')
                ->middleware('permission:hcd.task.manage|hcd.employee.manage');
            Route::delete('/{task}', [\App\Http\Controllers\HcdTaskController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:hcd.task.manage|hcd.employee.manage');
        });

        // 3. Employee Register
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HcdEmployeeController::class, 'index'])
                ->name('index')
                ->middleware('permission:hcd.employee.view');
            Route::post('/', [\App\Http\Controllers\HcdEmployeeController::class, 'store'])
                ->name('store')
                ->middleware('permission:hcd.employee.manage');
            Route::get('/{employee}', [\App\Http\Controllers\HcdEmployeeController::class, 'show'])
                ->name('show')
                ->middleware('permission:hcd.employee.view');
            Route::put('/{employee}', [\App\Http\Controllers\HcdEmployeeController::class, 'update'])
                ->name('update')
                ->middleware('permission:hcd.employee.manage');
            Route::delete('/{employee}', [\App\Http\Controllers\HcdEmployeeController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:hcd.employee.manage');
        });

        // 4. Training & Development
        Route::prefix('training')->name('training.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HcdTrainingController::class, 'index'])
                ->name('index')
                ->middleware('permission:hcd.certification.manage|hcd.employee.view');
            Route::post('/catalog', [\App\Http\Controllers\HcdTrainingController::class, 'storeCatalog'])
                ->name('catalog.store')
                ->middleware('permission:hcd.certification.manage');
            Route::post('/certificates', [\App\Http\Controllers\HcdTrainingController::class, 'storeCertificate'])
                ->name('certificates.store')
                ->middleware('permission:hcd.certification.manage');
            Route::delete('/certificates/{certificate}', [\App\Http\Controllers\HcdTrainingController::class, 'destroyCertificate'])
                ->name('certificates.destroy')
                ->middleware('permission:hcd.certification.manage');
        });

        // 5. Professional Database
        Route::prefix('professionals')->name('professionals.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HcdProfessionalController::class, 'index'])
                ->name('index')
                ->middleware('permission:hcd.professional.view|hcd.employee.view');
            Route::post('/', [\App\Http\Controllers\HcdProfessionalController::class, 'store'])
                ->name('store')
                ->middleware('permission:hcd.professional.manage|hcd.employee.manage');
            Route::put('/{professional}', [\App\Http\Controllers\HcdProfessionalController::class, 'update'])
                ->name('update')
                ->middleware('permission:hcd.professional.manage|hcd.employee.manage');
            Route::delete('/{professional}', [\App\Http\Controllers\HcdProfessionalController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:hcd.professional.manage|hcd.employee.manage');
        });
    });

    // Departemen HSSE (Health, Safety, Security & Environment)
    Route::prefix('hsse')->name('hsse.')->group(function () {
        // 1. Dashboard HSSE
        Route::get('/dashboard', [\App\Http\Controllers\HsseDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:hsse.dashboard.view');

        // 2. Safety Permit & JSA
        Route::prefix('permits')->name('permits.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HssePermitController::class, 'index'])
                ->name('index')
                ->middleware('permission:hsse.permit.view');
            Route::post('/', [\App\Http\Controllers\HssePermitController::class, 'store'])
                ->name('store')
                ->middleware('permission:hsse.permit.issue');
            Route::patch('/{permit}/status', [\App\Http\Controllers\HssePermitController::class, 'updateStatus'])
                ->name('status')
                ->middleware('permission:hsse.permit.issue');
            Route::delete('/{permit}', [\App\Http\Controllers\HssePermitController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:hsse.permit.issue');
        });

        // 3. Inspeksi Alat & APD
        Route::prefix('inspections')->name('inspections.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HsseInspectionController::class, 'index'])
                ->name('index')
                ->middleware('permission:hsse.inspection.manage');
            Route::post('/', [\App\Http\Controllers\HsseInspectionController::class, 'store'])
                ->name('store')
                ->middleware('permission:hsse.inspection.manage');
            Route::delete('/{inspection}', [\App\Http\Controllers\HsseInspectionController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:hsse.inspection.manage');
        });

        // 4. Laporan Insiden
        Route::prefix('incidents')->name('incidents.')->group(function () {
            Route::get('/', [\App\Http\Controllers\HsseIncidentController::class, 'index'])
                ->name('index')
                ->middleware('permission:hsse.incident.report');
            Route::post('/', [\App\Http\Controllers\HsseIncidentController::class, 'store'])
                ->name('store')
                ->middleware('permission:hsse.incident.report');
            Route::patch('/{incident}/capa', [\App\Http\Controllers\HsseIncidentController::class, 'updateCapa'])
                ->name('capa')
                ->middleware('permission:hsse.incident.report');
            Route::delete('/{incident}', [\App\Http\Controllers\HsseIncidentController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:hsse.incident.report');
        });
    });

    // Departemen Procurement & Logistics
    Route::prefix('procurement')->name('procurement.')->group(function () {
        // 1. Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\ProcurementDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:procurement.dashboard.view');

        // 2. Vendor Management
        Route::prefix('vendors')->name('vendors.')->group(function () {
            Route::get('/', [\App\Http\Controllers\ProcurementVendorController::class, 'index'])
                ->name('index')
                ->middleware('permission:procurement.vendor.manage');
            Route::post('/', [\App\Http\Controllers\ProcurementVendorController::class, 'store'])
                ->name('store')
                ->middleware('permission:procurement.vendor.manage');
            Route::put('/{vendor}', [\App\Http\Controllers\ProcurementVendorController::class, 'update'])
                ->name('update')
                ->middleware('permission:procurement.vendor.manage');
            Route::delete('/{vendor}', [\App\Http\Controllers\ProcurementVendorController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:procurement.vendor.manage');
        });

        // 3. Purchase Request (PR)
        Route::prefix('purchase-requests')->name('purchase-requests.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PurchaseRequestController::class, 'index'])
                ->name('index')
                ->middleware('permission:procurement.pr.create|procurement.pr.approve');
            Route::post('/', [\App\Http\Controllers\PurchaseRequestController::class, 'store'])
                ->name('store')
                ->middleware('permission:procurement.pr.create');
            Route::patch('/{purchaseRequest}/status', [\App\Http\Controllers\PurchaseRequestController::class, 'updateStatus'])
                ->name('status')
                ->middleware('permission:procurement.pr.approve');
            Route::delete('/{purchaseRequest}', [\App\Http\Controllers\PurchaseRequestController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:procurement.pr.create');
        });

        // 4. Purchase Order (PO)
        Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
            Route::get('/', [\App\Http\Controllers\PurchaseOrderController::class, 'index'])
                ->name('index')
                ->middleware('permission:procurement.po.view');
            Route::post('/', [\App\Http\Controllers\PurchaseOrderController::class, 'store'])
                ->name('store')
                ->middleware('permission:procurement.po.create');
            Route::get('/{purchaseOrder}', [\App\Http\Controllers\PurchaseOrderController::class, 'show'])
                ->name('show')
                ->middleware('permission:procurement.po.view');
            Route::patch('/{purchaseOrder}/status', [\App\Http\Controllers\PurchaseOrderController::class, 'updateStatus'])
                ->name('status')
                ->middleware('permission:procurement.po.create');
            Route::delete('/{purchaseOrder}', [\App\Http\Controllers\PurchaseOrderController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:procurement.po.create');
        });

        // 5. Ekspedisi & Logistik
        Route::prefix('logistics')->name('logistics.')->group(function () {
            Route::get('/', [\App\Http\Controllers\LogisticsController::class, 'index'])
                ->name('index')
                ->middleware('permission:procurement.logistic.track');
            Route::post('/', [\App\Http\Controllers\LogisticsController::class, 'store'])
                ->name('store')
                ->middleware('permission:procurement.logistic.track');
            Route::patch('/{delivery}/status', [\App\Http\Controllers\LogisticsController::class, 'updateStatus'])
                ->name('status')
                ->middleware('permission:procurement.logistic.track');
            Route::delete('/{delivery}', [\App\Http\Controllers\LogisticsController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:procurement.logistic.track');
        });
    });

    // Departemen Asset Management (Mesin, Peralatan & Kalibrasi)
    Route::prefix('assets')->name('assets.')->group(function () {
        // 1. Dashboard Asset
        Route::get('/dashboard', [\App\Http\Controllers\AssetDashboardController::class, 'index'])
            ->name('dashboard')
            ->middleware('permission:asset.dashboard.view');

        // 2. Inventaris Mesin & Alat
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AssetInventoryController::class, 'index'])
                ->name('index')
                ->middleware('permission:asset.inventory.view');
            Route::post('/', [\App\Http\Controllers\AssetInventoryController::class, 'store'])
                ->name('store')
                ->middleware('permission:asset.inventory.manage');
            Route::put('/{asset}', [\App\Http\Controllers\AssetInventoryController::class, 'update'])
                ->name('update')
                ->middleware('permission:asset.inventory.manage');
            Route::delete('/{asset}', [\App\Http\Controllers\AssetInventoryController::class, 'destroy'])
                ->name('destroy')
                ->middleware('permission:asset.inventory.manage');
        });

        // 3. Alokasi Project Lapangan
        Route::prefix('allocations')->name('allocations.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AssetAllocationController::class, 'index'])
                ->name('index')
                ->middleware('permission:asset.allocation.request|asset.allocation.approve');
            Route::post('/', [\App\Http\Controllers\AssetAllocationController::class, 'store'])
                ->name('store')
                ->middleware('permission:asset.allocation.request');
            Route::post('/{allocation}/return', [\App\Http\Controllers\AssetAllocationController::class, 'returnAsset'])
                ->name('return')
                ->middleware('permission:asset.allocation.approve');
        });

        // 4. Jadwal Maintenance & Kalibrasi
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [\App\Http\Controllers\AssetMaintenanceController::class, 'index'])
                ->name('index')
                ->middleware('permission:asset.maintenance.schedule');
            Route::post('/', [\App\Http\Controllers\AssetMaintenanceController::class, 'store'])
                ->name('store')
                ->middleware('permission:asset.maintenance.schedule');
            Route::post('/{maintenance}/complete', [\App\Http\Controllers\AssetMaintenanceController::class, 'complete'])
                ->name('complete')
                ->middleware('permission:asset.maintenance.schedule');
        });
    });

    Route::get('/chart/blog', [DashboardController::class, 'blogChart'])->name('blogChart');

    Route::prefix('image-converter')->name('image-converter.')->group(function () {
        Route::get('/', [ImageConverterController::class, 'index'])->name('index');
        Route::post('/convert', [ImageConverterController::class, 'convert'])->name('convert');
        Route::get('/preview/{batch}/{filename}', [ImageConverterController::class, 'preview'])
            ->middleware('signed')->name('preview');
        Route::get('/download/{batch}/{filename}', [ImageConverterController::class, 'download'])
            ->middleware('signed')->name('download');
        Route::get('/download-all/{batch}', [ImageConverterController::class, 'downloadAll'])
            ->middleware('signed')->name('download-all');
    });

    Route::get('/visitors/archives', [VisitorManagementController::class, 'history'])
        ->name('visitors.history')
        ->middleware('permission:view visitor statistics');
    Route::get('/visitors/settings', [VisitorManagementController::class, 'settings'])
        ->name('visitors.settings')
        ->middleware('permission:manage visitor settings');
    Route::put('/visitors/settings', [VisitorManagementController::class, 'update'])
        ->name('visitors.settings.update')
        ->middleware('permission:manage visitor settings');
    Route::post('/visitors/archive', [VisitorManagementController::class, 'archiveNow'])
        ->name('visitors.archive')
        ->middleware('permission:run visitor archive');
    Route::post('/visitors/cleanup/preview', [VisitorManagementController::class, 'previewCleanup'])
        ->name('visitors.cleanup.preview')
        ->middleware('permission:delete visitor data');
    Route::delete('/visitors/cleanup', [VisitorManagementController::class, 'cleanupNow'])
        ->name('visitors.cleanup')
        ->middleware('permission:delete visitor data');

    Route::get('/configurations', [ConfigurationController::class, 'create'])->name('configurations.create');
    Route::post('/configurations/storeOrUpdate', [ConfigurationController::class, 'storeOrUpdate'])->name('configurations.storeOrUpdate');

    Route::get('/contacts', [ContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts/storeOrUpdate', [ContactController::class, 'storeOrUpdate'])->name('contacts.storeOrUpdate');

    Route::get('/abouts', [AboutController::class, 'create'])->name('abouts.create');
    Route::post('/abouts/storeOrUpdate', [AboutController::class, 'storeOrUpdate'])->name('abouts.storeOrUpdate');

    Route::get('/overviews', [OverviewController::class, 'create'])->name('overviews.create');
    Route::post('/overviews/storeOrUpdate', [OverviewController::class, 'storeOrUpdate'])->name('overviews.storeOrUpdate');

    Route::get('/careers', [CareerController::class, 'create'])->name('careers.create');
    Route::post('/careers/storeOrUpdate', [CareerController::class, 'storeOrUpdate'])->name('careers.storeOrUpdate');
    Route::resource('career-positions', CareerPositionController::class)->except(['index'])->names('career-positions');
    Route::post('/career-positions/{careerPosition}/toggle-status', [CareerPositionController::class, 'toggleStatus'])->name('career-positions.toggle-status');

    Route::get('/collaborations', [CollaborationController::class, 'create'])->name('collaborations.create');
    Route::post('/collaborations/storeOrUpdate', [CollaborationController::class, 'storeOrUpdate'])->name('collaborations.storeOrUpdate');

    Route::post('/services/storeOrUpdate', [ServiceController::class, 'storeOrUpdate'])->name('services.storeOrUpdate');
    Route::post('/business-fields/storeOrUpdate', [BusinessFieldController::class, 'storeOrUpdate'])->name('business-fields.storeOrUpdate');
    Route::post('/order-steps/storeOrUpdate', [OrderStepController::class, 'storeOrUpdate'])->name('order-steps.storeOrUpdate');
    Route::post('/testimonials/storeOrUpdate', [TestimonialController::class, 'storeOrUpdate'])->name('testimonials.storeOrUpdate');
    Route::post('/blogs/storeOrUpdate', [App\Http\Controllers\Database\BlogController::class, 'storeOrUpdate'])->name('blogs.storeOrUpdate');
    Route::post('/teams/storeOrUpdate', [TeamController::class, 'storeOrUpdate'])->name('teams.storeOrUpdate');
    Route::post('/partners/storeOrUpdate', [PartnerController::class, 'storeOrUpdate'])->name('partners.storeOrUpdate');
    Route::post('/superioritys_image/storeOrUpdate', [SuperiorityController::class, 'storeOrUpdate'])->name('superioritys_image.storeOrUpdate');
    Route::post('/superioritys_icon/storeOrUpdate', [SuperiorityIconController::class, 'storeOrUpdate'])->name('superioritys_icon.storeOrUpdate');
    Route::post('/projects/storeOrUpdate', [ProjectController::class, 'storeOrUpdate'])->name('projects.storeOrUpdate');
    Route::post('/galleries/storeOrUpdate', [GalleryController::class, 'storeOrUpdate'])->name('galleries.storeOrUpdate');
    Route::post('/portofolios/storeOrUpdate', [PortofolioController::class, 'storeOrUpdate'])->name('portofolios.storeOrUpdate');
    Route::post('/faqs/storeOrUpdate', [FaqController::class, 'storeOrUpdate'])->name('faqs.storeOrUpdate');
    Route::post('/gallerys/storeOrUpdate', [GallerysController::class, 'storeOrUpdate'])->name('gallerys.storeOrUpdate');
    Route::post('/wrokshops/storeOrUpdate', [WorkshopController::class, 'storeOrUpdate'])->name('wrokshops.storeOrUpdate');
    Route::post('/products/storeOrUpdate', [ProductController::class, 'storeOrUpdate'])->name('products.storeOrUpdate');
    Route::post('/sliders/storeOrUpdate', [SliderController::class, 'storeOrUpdate'])->name('sliders.storeOrUpdate');

    // CRUD
    Route::resource('navigation-menus', NavigationMenuController::class)->except('show');
    Route::resource('sliders', SliderController::class)->except('show');
    Route::resource('blogs', App\Http\Controllers\Database\BlogController::class)->except('show');
    Route::resource('categorys-blog', CategoryBlogController::class)->except('show');
    Route::resource('list-of-benerfit-about-us', ListOfBenefitAboutUsController::class)->except('show');
    Route::resource('list-of-benerfit-overview', ListOfBenefitOverviewController::class)->except('show');
    Route::post('counter-section/label', [CounterSectionController::class, 'storeOrUpdateLabel'])->name('counter-section.storeOrUpdateLabel');
    Route::resource('counter-section', CounterSectionController::class)->except('show');
    Route::resource('superioritys_image', SuperiorityController::class)->except('show');
    Route::resource('superioritys_icon', SuperiorityIconController::class)->except('show');
    Route::resource('categorys-service', CategoryServiceController::class)->except('show');
    Route::resource('services', ServiceController::class)->except('show');
    Route::resource('business-fields', BusinessFieldController::class)->except('show');
    Route::resource('categorys-product', CategoryProductController::class)->except('show');
    Route::resource('products', ProductController::class)->except('show');
    Route::resource('order-steps', OrderStepController::class)->except('show');
    Route::resource('galleries', GalleryController::class)->except('show');
    Route::resource('categorys-gallerys', CategoryGallerysController::class)->except('show');
    Route::resource('gallerys', GallerysController::class)->except('show');
    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::resource('teams', TeamController::class)->except('show');
    Route::resource('categorys-partner', CategoryPartnerController::class)->except('show');
    Route::resource('partners', PartnerController::class)->except('show');
    Route::resource('categorys-project', CategoryProjectController::class)->except('show');
    Route::resource('projects', ProjectController::class)->except('show');
    Route::resource('categorys-portofolio', CategoryPortofolioController::class)->except('show');
    Route::resource('portofolios', PortofolioController::class)->except('show');
    Route::resource('faqs', FaqController::class)->except('show');
    Route::resource('workshops', WorkshopController::class)->except('show');

    Route::post('/ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.upload');

    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:lihat user');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:buat user');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:buat user');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit user');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit user');

    Route::get('/users/trash', [UserController::class, 'trashList'])->name('users.trash.list');
    Route::delete('/users/{id}/trash', [UserController::class, 'trash'])->name('users.trash');
    Route::get('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{id}/delete', [UserController::class, 'forceDelete'])->name('users.forceDelete')->middleware('permission:hapus user');

    // PERMISSIONS
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:lihat permission');
    Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:buat permission');
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:buat permission');
    Route::get('/permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit permission');
    Route::put('/permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit permission');

    Route::get('/permissions-trash', [PermissionController::class, 'trashList'])->name('permissions.trash.list');
    Route::delete('/permissions/{id}/trash', [PermissionController::class, 'trash'])->name('permissions.trash');
    Route::get('/permissions/{id}/restore', [PermissionController::class, 'restore'])->name('permissions.restore');
    Route::delete('/permissions/{id}/force-delete', [PermissionController::class, 'forceDelete'])->name('permissions.forceDelete')->middleware('permission:hapus permission');

    // ROLES
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:lihat role');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:buat role');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:buat role');
    Route::get('/roles/{id}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit role');
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit role');

    Route::get('/roles-trash', [RoleController::class, 'trashList'])->name('roles.trash.list');
    Route::delete('/roles/{id}/trash', [RoleController::class, 'trash'])->name('roles.trash');
    Route::get('/roles/{id}/restore', [RoleController::class, 'restore'])->name('roles.restore');
    Route::delete('/roles/{id}/force-delete', [RoleController::class, 'forceDelete'])->name('roles.forceDelete')->middleware('permission:hapus role');

    // IMPERSONATE
    Route::get('/impersonate/{id}', [ImpersonateController::class, 'impersonate'])
        ->name('impersonate');

    Route::post('/leave-impersonate', [ImpersonateController::class, 'leave'])
        ->name('impersonate.leave');

    Route::get('/change-password', [PasswordController::class, 'changePassword'])
        ->name('users.changePassword');

    Route::put('/change-password', [PasswordController::class, 'updatePassword'])
        ->name('users.updatePassword');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    Route::put('/profile/update', [UserController::class, 'updateProfile'])
        ->name('profile.update');

    Route::put('/profile/password', [UserController::class, 'updatePassword'])
        ->name('profile.password');
});
