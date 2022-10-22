        
        <div class="flex-column">
        <!-- navigation -->
            <?php if($auth === 'klant') : ?>
<nav class="navbar navbar-light navbar-expand-md navigation-clean">
                <div class="container">
                <a class="navbar-brand pt-3 pb-3" href="#"><img class="img-fluid" src="<?php echo base_url('assets/images/logo_horizontal.png'); ?>" alt="Logo Fotografie Sandy"></a>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#nav-text"><span class="sr-only"><?php echo $this->lang->line('toggle'); ?></span><span class="navbar-toggler-icon"></span></button>
                    <div id="nav-feather" role="group" aria-label="Group main buttons for a Client">
                        <a href="<?php echo base_url('client'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('home'); ?>">
                            <span class="nav-feather" data-feather="home"></span>
                        </a>
                        <a href="<?php echo base_url('contact'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('contact'); ?>">
                            <span class="nav-feather" data-feather="mail"></span>
                        </a>
                        <a href="<?php echo base_url('password/editMyPassword'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('edit_password'); ?>">
                            <span class="nav-feather" data-feather="key"></span>
                        </a>
                        <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('logout'); ?>">
                            <span class="nav-feather" data-feather="log-out"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="nav-text">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('client'); ?>"><span class="nav-feather" data-feather="home"></span> <?php echo $this->lang->line('home'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('contact'); ?>"><span class="nav-feather" data-feather="mail"></span> <?php echo $this->lang->line('contact'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('password/editMyPassword'); ?>"><span class="nav-feather" data-feather="key"></span> <?php echo $this->lang->line('edit_password'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('login/logout'); ?>"><span class="nav-feather" data-feather="log-out"></span> <?php echo $this->lang->line('logout'); ?></a></li>
                        </ul>
                    </div>

                </div>
            </nav>
            <?php elseif($auth === 'admin') : ?>
            <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                <div class="container">
                <a class="navbar-brand pt-3 pb-3" href="#"><img class="img-fluid" src="<?php echo base_url('assets/images/logo_horizontal.png'); ?>" alt="Logo Fotografie Sandy"></a>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#nav-text"><span class="sr-only"><?php echo $this->lang->line('toggle'); ?></span><span class="navbar-toggler-icon"></span></button>
                    <div id="nav-feather" role="group" aria-label="Group main buttons for a Client">
                        <a href="<?php echo base_url('overview'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('home'); ?>">
                            <span class="nav-feather" data-feather="home"></span>
                        </a>
                        <a href="<?php echo base_url('calendar'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('calendar'); ?>">
                            <span class="nav-feather" data-feather="calendar"></span>
                        </a>
                        <a href="<?php echo base_url('register'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('register'); ?>">
                            <span class="nav-feather" data-feather="user-plus"></span>
                        </a>
                        <a href="<?php echo base_url('password/editMyPassword'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('edit_password'); ?>">
                            <span class="nav-feather" data-feather="key"></span>
                        </a>
						<a href="<?php echo base_url('overview/exportLogs'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('logs'); ?>">
                            <span class="nav-feather" data-feather="file-text"></span>
                        </a>
						<a href="<?php echo base_url('setting'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('settings'); ?>">
                            <span class="nav-feather" data-feather="settings"></span>
                        </a>
                        <a href="<?php echo base_url('login/logout'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('logout'); ?>">
                            <span class="nav-feather" data-feather="log-out"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="nav-text">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('overview'); ?>"><span class="nav-feather" data-feather="home"></span> <?php echo $this->lang->line('home'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('calendar'); ?>"><span class="nav-feather" data-feather="calendar"></span> <?php echo $this->lang->line('calendar'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('register'); ?>"><span class="nav-feather" data-feather="user-plus"></span> <?php echo $this->lang->line('create_user'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('password/editMyPassword'); ?>"><span class="nav-feather" data-feather="key"></span> <?php echo $this->lang->line('edit_password'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('login/logout'); ?>"><span class="nav-feather" data-feather="log-out"></span> <?php echo $this->lang->line('logout'); ?></a></li>
                        </ul>
                    </div>

                </div>
            </nav>
            <?php elseif($auth === 'visitor') : ?>
            <nav class="navbar navbar-light navbar-expand-md navigation-clean">
                <div class="container">
                <a class="navbar-brand pt-3 pb-3" href="#"><img class="img-fluid" src="<?php echo base_url('assets/images/logo_horizontal.png'); ?>" alt="Logo Fotografie Sandy"></a>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#nav-text"><span class="sr-only"><?php echo $this->lang->line('toggle'); ?></span><span class="navbar-toggler-icon"></span></button>
                    <div id="nav-feather" role="group" aria-label="Group main buttons for a Client">
                        <a href="<?php echo base_url('visitor/link'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('home'); ?>">
                            <span class="nav-feather" data-feather="home"></span>
                        </a>
                        <a href="#" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('info'); ?>">
                            <span class="nav-feather" data-feather="info"></span>
                        </a>
                        <a href="<?php echo base_url('contact'); ?>" class="btn btn-lg btn-outline-info" title="<?php echo $this->lang->line('contact'); ?>">
                            <span class="nav-feather" data-feather="mail"></span>
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="nav-text">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item" role="presentation"><a class="nav-link" href="#"> <?php echo $this->lang->line('info'); ?></a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo base_url('contact'); ?>"> <?php echo $this->lang->line('contact'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <?php endif ?>
</div>
        <!-- end navigation -->
