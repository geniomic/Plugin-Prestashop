<?php 
if (!defined('_PS_VERSION_'))
exit;
 
class Addlink extends Module {

    protected static $contact_fields = array(
        'TITLE',
        'LINK',
        'LINK_NAME',
        'LINK1',
        'LINK_NAME1',

    );
 
    public function __construct() {
        $this->name = 'addlink'; // il nome del modulo (lo stesso del file principale)
        $this->tab = 'content_management'; // sezione in cui va inserito
        $this->version = 1.0;
        $this->author = 'Michael Genio';
        $this->need_instance = 0; 
        /*
         * need_instance specifica se un istanza del modulo deve essere caricata
         * quando viene visualizzata la lista dei moduli
           (di norma può essere lasciato a 0)   
         */
        $this->bootstrap = true;
        parent::__construct();
 
        $this->displayName = $this->l('Addlink');
        $this->description = $this->l('Modulo per aggiungere link personalizzati nel footer.');
        $this->confirmUninstall = $this->l('Are you sure you want to delete these details?');
    }
    public function install()
    {
        Configuration::updateValue('TITTLE', Configuration::get('Add Link'));
        Configuration::updateValue('LINK', Configuration::get('#'));
        Configuration::updateValue('LINK_NAME', Configuration::get(''));
        Configuration::updateValue('LINK1', Configuration::get('#'));
        Configuration::updateValue('LINK_NAME1', Configuration::get(''));
        $this->_clearCache('addlink.tpl');
        return (parent::install() && $this->registerHook('displayFooter'));
    }
 
    public function uninstall() {
        if (!parent::uninstall() || !$this->unregisterHook('displayFooter'))
            return false;
        return true;
    }

    public function getContent()
    {
        $html = '';
        if (Tools::isSubmit('submitModule'))
        {   
            foreach (Addlink::$contact_fields as $field)
                Configuration::updateValue($field, Tools::getValue($field));
            $this->_clearCache('addlink.tpl');
            $html = $this->displayConfirmation($this->l('Configuration updated'));
        }

        return $html.$this->renderForm();
    }

    public function hookDisplayFooter($params) {
    

if (!$this->isCached('addlink.tpl', $this->getCacheId()))
            foreach (Addlink::$contact_fields as $field)
                $this->smarty->assign(strtolower($field), Configuration::get($field));
        return $this->display(__FILE__, 'addlink.tpl', $this->getCacheId());

    }

    public function renderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(

                    array(
                        'type' => 'text',
                        'label' => $this->l('Footer Title'),
                        'name' => 'TITLE',
                    ),
                    
                    array(
                        'type' => 'text',
                        'label' => $this->l('URL link 1'),
                        'name' => 'LINK',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Link Name 1'),
                        'name' => 'LINK_NAME',
                    ),
                     array(
                        'type' => 'text',
                        'label' => $this->l('URL link 2'),
                        'name' => 'LINK1',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Link Name 2'),
                        'name' => 'LINK_NAME1',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save')
                )
            ),
        );
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table =  $this->table;
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );
        foreach (Addlink::$contact_fields as $field)
            $helper->tpl_vars['fields_value'][$field] = Tools::getValue($field, Configuration::get($field));
        return $helper->generateForm(array($fields_form));
    }

    
}