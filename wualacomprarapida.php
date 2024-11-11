<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class wualaCompraRapida extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'wualacomprarapida';
        $this->tab = 'search_filter';
        $this->version = '1.0.0';
        $this->author = 'Wuala - Luciano Villarreal';
        $this->need_instance = 0;
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Compra Rápida');
        $this->description = $this->l('Realiza una compra rápida de varios productos rápidamente.');
        $this->confirmUninstall = $this->l('¿Estas seguro que deseas desinstalar?');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        Configuration::updateValue('WUALACOMPRARAPIDA_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('header') &&
            $this->registerHook('displayNav2') &&
            $this->registerHook('displayNavFastBuy');
    }

    public function uninstall()
    {
        Configuration::deleteByName('WUALACOMPRARAPIDA_LIVE_MODE');
        
        $this->unregisterHook('displayNavFastBuy');
        return parent::uninstall();
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
    }

    public function hookDisplayNav2()
    {
        $this->context->smarty->assign([
            'custom_url' => $this->context->link->getModuleLink('wualacomprarapida', 'searchcart')
        ]);

        return $this->display(__FILE__, 'views/templates/hook/nav-button.tpl');
    }

    public function hookDisplayNavFastBuy()
    {
        return $this->display(__FILE__, 'views/templates/hook/fast-buy.tpl');
    }
}
