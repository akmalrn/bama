<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="id">
<x-head />

<body>

    <!-- ..::  header area start ::.. -->
    <x-sidebar />
    <!-- ..::  header area end ::.. -->

    <main class="dashboard-main">

        <!-- ..::  navbar start ::.. -->
        <x-navbar />
        <!-- ..::  navbar end ::.. -->
        <div class="dashboard-main-body">

            <!-- ..::  breadcrumb  start ::.. -->
            <x-breadcrumb :title="$title" :subTitle="$subTitle" />
            <!-- ..::  header area end ::.. -->

            @yield('content')

        </div>
        <!-- ..::  footer  start ::.. -->
        <x-footer />
        <!-- ..::  footer area end ::.. -->

    </main>

    <!-- ..::  scripts  start ::.. -->
    <x-script :script="$script ?? ''" />
    <!-- ..::  scripts  end ::.. -->
    <div id="pageLoader" class="page-loader d-none">
        <div class="loader-spinner"></div>
    </div>

</body>

</html>
