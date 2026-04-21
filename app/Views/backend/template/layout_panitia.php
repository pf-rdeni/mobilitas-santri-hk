<!DOCTYPE html>
<html lang="id">
<?= $this->include('backend/template/meta'); ?>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-dark navbar-primary">
            <div class="container">
                <a href="<?= base_url('panitia') ?>" class="navbar-brand">
                    <i class="fas fa-bus-alt mr-2"></i>
                    <span class="brand-text font-weight-bold">Mobilitas Panitia</span>
                </a>

                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('logout') ?>">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"> <?= $title ?></h1>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container">
                    <?= $this->renderSection('content'); ?>
                </div>
            </div>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <div class="container text-center">
                <strong>Copyright &copy; <?= date('Y') ?> Mobilitas Santri.</strong>
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <?= $this->include('backend/template/js'); ?>
    <?= $this->renderSection('scripts'); ?>
</body>
</html>
