<?php
class Shopware_Plugins_Frontend_SfBlogListing_Bootstrap
    extends Shopware_Components_Plugin_Bootstrap
{

    /**
     * @return bool
     */
    public function install()
    {
        $this->subscribeEvents();

        return array('success' => true, 'invalidateCache' => array('backend'));
    }

    public function uninstall()
    {
        return true;
    }

    public function getInfo()
    {
        return array(
            'version'     => $this->getVersion(),
            'author'      => 'Shop Freelancer',
            'copyright'   => 'Copyright (c) 2016, Shop Freelancer',
            'label'       => $this->getLabel(),
            'description' => "Blog Kategorien im Listing im Backen anzeigen.",
            'support'     => 'http://www.shop-freelancer.de',
            'link'        => 'http://www.shop-freelancer.de',
            'revision'    => '1'
        );
    }

    public function getLabel()
    {
        return "Blog Listing";
    }

    public function getVersion()
    {
        return "1.0.0";
    }

    /**
     * Registers all necessary events and hooks.
     */
    private function subscribeEvents()
    {

        $this->subscribeEvent(
            'Shopware\Models\Blog\Repository::getBackendListQueryBuilder::after',
            'afterGetListQueryBuilder'
        );

        $this->subscribeEvent(
            'Enlight_Controller_Action_PostDispatch_Backend_Blog',
            'onBackendBlogPostDispatch'
        );
    }

    /**
     * Hooks the getListQueryBuilder method of the customer repository.
     * Additionally selects our own attributes
     *
     * @param Enlight_Hook_HookArgs $arguments
     */
    public function afterGetListQueryBuilder(Enlight_Hook_HookArgs $arguments)
    {
        // get original builder
        $builder = $arguments->getReturn();


        $builder->addSelect(array('category.name as categoryName'));
        $builder->leftJoin('Shopware\Models\Category\Category', 'category', \Doctrine\ORM\Query\Expr\Join::WITH, 'blog.categoryId = category.id');

        $arguments->setReturn($builder);
    }

    /**
     * Called when the BackendCustomerPostDispatch Event is triggered
     *
     * @param Enlight_Event_EventArgs $args
     */
    public function onBackendBlogPostDispatch(Enlight_Event_EventArgs $args)
    {

        /**@var $view Enlight_View_Default */
        $view = $args->getSubject()->View();

        // Add template directory
        $args->getSubject()->View()->addTemplateDir(
            $this->Path() . 'Views/'
        );


        //if the controller action name equals "load" we have to load all application components
        if ($args->getRequest()->getActionName() === 'load') {

            $view->extendsTemplate(
                'Backend/blog/view/blog/list.js'
            );
        }
    }
}