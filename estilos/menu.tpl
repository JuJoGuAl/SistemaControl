<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li><a href="./"><i class="fa fa-home fa-fw"></i> INICIO</a></li>
            <!-- START BLOCK : menu_principal -->
            <li>
              <a href="#"><i class="fa {ico_menu} fa-fw"></i> {nom_menu} {arrow}</a>
              {sub_menu1}
              <!-- START BLOCK : submodulo -->
              <li>
                <a href="{mod_url}">{mod_name} {arrow1}</a>
                {sub_menu3}
                <!-- START BLOCK : modulo -->
                <li>
                  <a href="?mod={mod_cont}&submod={mod_url}">{mod_name}</a>
                </li>
                <!-- END BLOCK : modulo -->
                {sub_menu4}
              </li>
              <!-- END BLOCK : submodulo -->
              {sub_menu2}
            </li>
            <!-- END BLOCK : menu_principal -->
        </ul>
    </div>
</div>
