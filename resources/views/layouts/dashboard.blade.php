<!DOCTYPE html>
<html lang="en">

<x-head />

<body id="page-top">
    <div class="fixed-top min-vw-100 min-vh-100 bg-light justify-content-center align-items-center d-flex" id="pageLoader" style="z-index: 1051">
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    {{-- Page Wrapper  --}}
    <div id="wrapper">

        {{-- Sidebar  --}}
        <x-sidebar application-name="PT. ASM" />
        {{-- End of Sidebar  --}}

        {{-- Content Wrapper  --}}
        <div id="content-wrapper" class="d-flex flex-column">

            {{-- Main Content  --}}
            <div id="content" style="background-image: url('<?= url('/template/bg.jpg') ?>');">

                {{-- Topbar  --}}
                <x-topbar />
                {{-- End of Topbar  --}}

                {{-- Begin Page Content  --}}
                <div class="container-fluid">

                    {{-- Page Heading  --}}
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0" style="color:#FFFFFF; "><b>@yield('route_name')</b></h1>
                    </div>

                    {{-- Content Row  --}}
                    @yield('content')

                </div>
                {{-- /.container-fluid  --}}

            </div>
            {{-- End of Main Content  --}}

            {{-- Footer  --}}
            <x-footer application-copyright="PT. Auto Sunrise Mandiri" />
            {{-- End of Footer  --}}

        </div>
        {{-- End of Content Wrapper  --}}

    </div>
    {{-- End of Page Wrapper  --}}

    {{-- Scroll to Top Button --}}
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Logout Modal --}}
    <x-logout-modal />

    <x-script />
    @yield('scripts')

</body>

</html>